<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function stock(Request $request)
    {
        $filters = $request->only(['category_id', 'status']);
        $products = $this->reportService->stockReport($filters);
        $categories = Category::orderBy('name')->get();

        $summary = [
            'total' => $products->count(),
            'value' => $products->sum(fn ($p) => $p->stock * $p->selling_price),
        ];

        return view('reports.stock', compact('products', 'categories', 'filters', 'summary'));
    }

    public function transactions(Request $request)
    {
        $filters = $request->only(['type', 'product_id', 'from', 'to']);
        $transactions = $this->reportService->transactionReport($filters);
        $summary = $this->reportService->summary($filters);
        $products = \App\Models\Product::orderBy('name')->get();

        return view('reports.transactions', compact('transactions', 'filters', 'summary', 'products'));
    }

    public function activities(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'from', 'to']);
        $logs = $this->reportService->activityReport($filters);
        $users = User::orderBy('name')->get();

        return view('reports.activities', compact('logs', 'filters', 'users'));
    }

    public function exportStock(Request $request)
    {
        $products = $this->reportService->stockReport($request->only(['category_id', 'status']));

        return $this->csvDownload('laporan-stok-'.date('Ymd-His').'.csv', function ($write) use ($products) {
            $write(['SKU', 'Nama Produk', 'Kategori', 'Supplier', 'Stok', 'Min. Stok', 'Harga Beli', 'Harga Jual', 'Total Nilai', 'Status']);
            foreach ($products as $p) {
                $write([
                    $p->sku,
                    $p->name,
                    $p->category->name ?? '-',
                    $p->supplier->name ?? '-',
                    $p->stock,
                    $p->min_stock,
                    (float) $p->purchase_price,
                    (float) $p->selling_price,
                    (float) ($p->stock * $p->selling_price),
                    $p->stock_status === 'low' ? 'Menipis' : ($p->stock_status === 'out' ? 'Habis' : 'Aman'),
                ]);
            }
        });
    }

    public function exportTransactions(Request $request)
    {
        $filters = $request->only(['type', 'product_id', 'from', 'to']);
        $transactions = $this->reportService->transactionReport($filters);

        return $this->csvDownload('laporan-transaksi-'.date('Ymd-His').'.csv', function ($write) use ($transactions) {
            $write(['Tanggal', 'Jenis', 'Produk', 'SKU', 'Qty', 'Harga', 'Supplier', 'Petugas', 'Catatan']);
            foreach ($transactions as $t) {
                $write([
                    $t->transaction_date->format('d-m-Y H:i'),
                    $t->type === 'in' ? 'Masuk' : 'Keluar',
                    $t->product->name ?? '-',
                    $t->product->sku ?? '-',
                    $t->quantity,
                    (float) $t->price,
                    $t->supplier->name ?? '-',
                    $t->user->name ?? '-',
                    $t->note ?? '',
                ]);
            }
        });
    }

    public function exportActivities(Request $request)
    {
        $filters = $request->only(['user_id', 'action', 'from', 'to']);
        $logs = $this->reportService->activityReport($filters);

        return $this->csvDownload('laporan-aktivitas-'.date('Ymd-His').'.csv', function ($write) use ($logs) {
            $write(['Waktu', 'Pengguna', 'Aksi', 'Keterangan']);
            foreach ($logs as $log) {
                $write([
                    $log->created_at->format('d-m-Y H:i'),
                    $log->user->name ?? 'Sistem',
                    $log->action,
                    $log->description,
                ]);
            }
        });
    }

    protected function csvDownload(string $filename, callable $rows): StreamedResponse
    {
        $response = response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            $rows(fn ($row) => fputcsv($out, $row));
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);

        return $response;
    }
}
