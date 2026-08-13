<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllWithRelations();
    }

    public function getProduct($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data)
    {
        $data['sku'] = ! empty($data['sku']) ? $data['sku'] : $this->generateSku($data['name']);

        if (! empty($data['image']) && is_object($data['image'])) {
            $data['image'] = $this->storeImage($data['image']);
        }

        $attributes = $data['attributes'] ?? [];
        unset($data['attributes']);

        $product = $this->productRepository->create($data);

        $this->syncAttributes($product, $attributes);

        return $product;
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->productRepository->findById($id);

        if (! empty($data['image']) && is_object($data['image'])) {
            $this->deleteImage($product);
            $data['image'] = $this->storeImage($data['image']);
        } else {
            unset($data['image']);
        }

        $attributes = $data['attributes'] ?? [];
        unset($data['attributes']);

        $product = $this->productRepository->update($id, $data);

        $this->syncAttributes($product, $attributes);

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->findById($id);
        $this->deleteImage($product);

        return $this->productRepository->delete($id);
    }

    public function search($keyword)
    {
        return $this->productRepository->search($keyword);
    }

    public function exportProducts()
    {
        return Product::with(['category', 'supplier'])->orderBy('name')->get();
    }

    public function importProducts(\Illuminate\Http\UploadedFile $file): array
    {
        $imported = 0;
        $skipped = 0;
        $categoriesCreated = 0;
        $suppliersCreated = 0;

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return compact('imported', 'skipped', 'categoriesCreated', 'suppliersCreated');
        }

        $headers = fgetcsv($handle);
        $map = $this->buildHeaderMap($headers ?: []);

        while (($row = fgetcsv($handle)) !== false) {
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $data = $this->mapRow($map, $row);
            $name = trim($data['name'] ?? '');

            if ($name === '') {
                $skipped++;
                continue;
            }

            $sku = trim($data['sku'] ?? '');
            if ($sku === '') {
                $sku = $this->generateSku($name);
            } elseif ($this->productRepository->findBySku($sku)) {
                $skipped++;
                continue;
            }

            $category = null;
            $categoryName = trim($data['category'] ?? '');
            if ($categoryName !== '') {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['slug' => Str::slug($categoryName)]
                );
                if ($category->wasRecentlyCreated) {
                    $categoriesCreated++;
                }
            }

            if (! $category) {
                $skipped++;
                continue;
            }

            $supplier = null;
            $supplierName = trim($data['supplier'] ?? '');
            if ($supplierName !== '') {
                $supplier = Supplier::firstOrCreate(
                    ['name' => $supplierName],
                    ['contact_person' => null, 'phone' => null, 'email' => null, 'address' => null]
                );
                if ($supplier->wasRecentlyCreated) {
                    $suppliersCreated++;
                }
            }

            $description = trim($data['description'] ?? '');

            $this->productRepository->create([
                'name' => $name,
                'sku' => $sku,
                'category_id' => $category->id,
                'supplier_id' => $supplier?->id,
                'purchase_price' => $this->normalizeNumber($data['purchase_price'] ?? 0),
                'selling_price' => $this->normalizeNumber($data['selling_price'] ?? 0),
                'stock' => (int) $this->normalizeNumber($data['stock'] ?? 0),
                'min_stock' => (int) $this->normalizeNumber($data['min_stock'] ?? 0),
                'description' => $description !== '' ? $description : null,
                'image' => null,
            ]);

            $imported++;
        }

        fclose($handle);

        return compact('imported', 'skipped', 'categoriesCreated', 'suppliersCreated');
    }

    protected function buildHeaderMap(array $headers): array
    {
        $labels = [
            'nama' => 'name',
            'name' => 'name',
            'sku' => 'sku',
            'kategori' => 'category',
            'category' => 'category',
            'supplier' => 'supplier',
            'harga beli' => 'purchase_price',
            'harga_beli' => 'purchase_price',
            'hargabeli' => 'purchase_price',
            'purchase_price' => 'purchase_price',
            'harga jual' => 'selling_price',
            'harga_jual' => 'selling_price',
            'hargajual' => 'selling_price',
            'selling_price' => 'selling_price',
            'stok' => 'stock',
            'stock' => 'stock',
            'stok minimum' => 'min_stock',
            'stok_minimum' => 'min_stock',
            'minstok' => 'min_stock',
            'min_stock' => 'min_stock',
            'deskripsi' => 'description',
            'description' => 'description',
        ];

        $map = [];
        foreach ($headers as $index => $header) {
            $key = mb_strtolower(trim((string) $header));
            $key = preg_replace('/^\xEF\xBB\xBF/', '', $key);

            if (isset($labels[$key])) {
                $map[$index] = $labels[$key];
            }
        }

        return $map;
    }

    protected function mapRow(array $map, array $row): array
    {
        $data = [];
        foreach ($map as $index => $field) {
            $data[$field] = trim($row[$index] ?? '');
        }

        return $data;
    }

    protected function normalizeNumber($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return 0;
        }

        $value = str_replace(['.', ' '], '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    public function generateSku($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 3));

        return $prefix.'-'.date('ymd').'-'.strtoupper(Str::random(4));
    }

    protected function storeImage($file)
    {
        return $file->store('products', 'public');
    }

    protected function deleteImage(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
    }

    protected function syncAttributes(Product $product, array $attributes)
    {
        $product->attributes()->delete();

        if (! empty($attributes)) {
            $rows = [];
            foreach ($attributes as $name => $value) {
                if (is_array($value)) {
                    // Format dari form: attributes[i][name] & attributes[i][value]
                    if (isset($value['name']) || isset($value['value'])) {
                        if (! empty($value['name']) || ! empty($value['value'])) {
                            $rows[] = [
                                'product_id' => $product->id,
                                'name' => $value['name'] ?? $name,
                                'value' => $value['value'] ?? '',
                            ];
                        }

                        continue;
                    }

                    // Format alternatif: daftar item { name, value }
                    foreach ($value as $item) {
                        if (! empty($item['name']) || ! empty($item['value'])) {
                            $rows[] = [
                                'product_id' => $product->id,
                                'name' => $item['name'] ?? $name,
                                'value' => $item['value'] ?? '',
                            ];
                        }
                    }
                } elseif (! empty($name) && $value !== '' && $value !== null) {
                    $rows[] = [
                        'product_id' => $product->id,
                        'name' => $name,
                        'value' => $value,
                    ];
                }
            }

            if (! empty($rows)) {
                $product->attributes()->createMany($rows);
            }
        }
    }
}
