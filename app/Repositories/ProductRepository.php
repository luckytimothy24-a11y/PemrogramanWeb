<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::latest()->get();
    }

    public function getAllWithRelations()
    {
        return Product::with(['category', 'supplier', 'attributes'])->latest()->get();
    }

    public function findById($id)
    {
        return Product::with(['category', 'supplier', 'attributes'])->findOrFail($id);
    }

    public function findBySku($sku)
    {
        return Product::where('sku', $sku)->first();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        return $product->delete();
    }

    public function search($keyword)
    {
        return Product::with(['category', 'supplier'])
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('sku', 'like', "%{$keyword}%")
            ->latest()
            ->get();
    }

    public function count()
    {
        return Product::count();
    }

    public function lowStock()
    {
        return Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->get();
    }

    public function outOfStock()
    {
        return Product::where('stock', '<=', 0)->get();
    }
}
