<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ActivityLogService;
use App\Services\ProductService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $productService;
    protected $supplierService;
    protected $activityLogService;

    public function __construct(
        ProductService $productService,
        SupplierService $supplierService,
        ActivityLogService $activityLogService
    ) {
        $this->productService = $productService;
        $this->supplierService = $supplierService;
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        if ($request->filled('q')) {
            $products = $this->productService->search($request->input('q'));
        } else {
            $products = $this->productService->getAllProducts();
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        $this->productService->createProduct($request->all());

        $this->activityLogService->log('Tambah Produk', "Produk \"{$request->input('name')}\" ditambahkan.");

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productService->getProduct($id);
        $categories = \App\Models\Category::orderBy('name')->get();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($id)],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        $this->productService->updateProduct($id, $request->all());

        $this->activityLogService->log('Ubah Produk', "Produk \"{$request->input('name')}\" diperbarui.");

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;

        $this->productService->deleteProduct($id);

        $this->activityLogService->log('Hapus Produk', "Produk \"{$name}\" dihapus.");

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
