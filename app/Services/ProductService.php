<?php

namespace App\Services;

use App\Models\Product;
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
