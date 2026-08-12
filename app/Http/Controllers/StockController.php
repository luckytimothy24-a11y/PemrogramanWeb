<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');

            if ($status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($status === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        return view('stock.index', compact('products', 'categories', 'lowStockCount', 'outOfStockCount'));
    }
}
