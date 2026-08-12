<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\StockOpnameService;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    protected $opnameService;
    protected $productService;

    public function __construct(StockOpnameService $opnameService, ProductService $productService)
    {
        $this->opnameService = $opnameService;
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $opnames = $this->opnameService->getAll($request->all());
        $products = $this->productService->getAllProducts();

        return view('stock.opname.index', compact('opnames', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'actual_qty' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
            'opname_date' => ['nullable', 'date'],
        ]);

        $this->opnameService->create($request->all());

        return redirect()->route('stock.opname.index')->with('success', 'Stock opname berhasil dicatat dan stok disesuaikan.');
    }

    public function destroy($id)
    {
        $this->opnameService->delete($id);

        return redirect()->back()->with('success', 'Data stock opname berhasil dihapus.');
    }
}
