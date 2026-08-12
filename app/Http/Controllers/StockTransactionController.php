<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\StockService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockTransactionController extends Controller
{
    protected $stockService;
    protected $productService;
    protected $supplierService;

    public function __construct(
        StockService $stockService,
        ProductService $productService,
        SupplierService $supplierService
    ) {
        $this->stockService = $stockService;
        $this->productService = $productService;
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $transactions = $this->stockService->getAllTransactions($request->all());
        $products = $this->productService->getAllProducts();

        return view('stock.transactions.index', compact('transactions', 'products'));
    }

    public function create($type)
    {
        abort_unless(in_array($type, ['in', 'out']), 404);

        $products = $this->productService->getAllProducts();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('stock.transactions.create', compact('type', 'products', 'suppliers'));
    }

    public function store(Request $request, $type)
    {
        abort_unless(in_array($type, ['in', 'out']), 404);

        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'supplier_id' => [$type === 'in' ? 'nullable' : 'prohibited', 'exists:suppliers,id'],
            'note' => ['nullable', 'string', 'max:500'],
            'transaction_date' => ['nullable', 'date'],
        ]);

        try {
            if ($type === 'in') {
                $this->stockService->recordStockIn($request->all());
                $message = 'Transaksi barang masuk berhasil dicatat!';
            } else {
                $this->stockService->recordStockOut($request->all());
                $message = 'Transaksi barang keluar berhasil dicatat!';
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('stock.transactions.index', ['type' => $type])->with('success', $message);
    }

    public function destroy($id)
    {
        $this->stockService->deleteTransaction($id);

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan stok disesuaikan kembali.');
    }
}
