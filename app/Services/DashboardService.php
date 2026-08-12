<?php

namespace App\Services;

use App\Models\Category;
use App\Models\StockTransaction;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Carbon;

class DashboardService
{
    protected $productRepository;
    protected $supplierRepository;
    protected $transactionRepository;
    protected $userRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SupplierRepositoryInterface $supplierRepository,
        StockTransactionRepositoryInterface $transactionRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->productRepository = $productRepository;
        $this->supplierRepository = $supplierRepository;
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    public function getSummary()
    {
        return [
            'total_products' => $this->productRepository->count(),
            'total_categories' => Category::count(),
            'total_suppliers' => $this->supplierRepository->count(),
            'total_users' => $this->userRepository->count(),
            'low_stock' => $this->productRepository->lowStock()->count(),
            'out_of_stock' => $this->productRepository->outOfStock()->count(),
            'today_in' => $this->transactionRepository->todayIn(),
            'today_out' => $this->transactionRepository->todayOut(),
        ];
    }

    public function getLowStockProducts()
    {
        return $this->productRepository->lowStock();
    }

    public function getOutOfStockProducts()
    {
        return $this->productRepository->outOfStock();
    }

    public function getTransactionsLastDays(int $days = 14)
    {
        $labels = [];
        $inData = [];
        $outData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $inData[] = StockTransaction::where('type', 'in')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');

            $outData[] = StockTransaction::where('type', 'out')
                ->whereDate('transaction_date', $date)
                ->sum('quantity');
        }

        return compact('labels', 'inData', 'outData');
    }

    public function getStockByCategory()
    {
        $categories = Category::with('products')->get();

        return [
            'labels' => $categories->pluck('name')->map(fn ($n) => $n ?? 'Tanpa Kategori')->values(),
            'values' => $categories->map(fn ($c) => (int) $c->products->sum('stock'))->values(),
        ];
    }
}
