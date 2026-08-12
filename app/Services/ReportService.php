<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;

class ReportService
{
    protected $transactionRepository;
    protected $activityLogRepository;

    public function __construct(
        StockTransactionRepositoryInterface $transactionRepository,
        ActivityLogRepositoryInterface $activityLogRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->activityLogRepository = $activityLogRepository;
    }

    public function stockReport(array $filters = [])
    {
        $query = Product::with(['category', 'supplier']);

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['status'])) {
            if ($filters['status'] === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($filters['status'] === 'out') {
                $query->where('stock', '<=', 0);
            } else {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        return $query->orderBy('name')->get();
    }

    public function transactionReport(array $filters = [])
    {
        return $this->transactionRepository->getAll($filters);
    }

    public function activityReport(array $filters = [])
    {
        return $this->activityLogRepository->getAll($filters);
    }

    public function summary(array $filters = [])
    {
        return [
            'in' => $this->transactionRepository->totalIn($filters),
            'out' => $this->transactionRepository->totalOut($filters),
            'transaction_count' => count($this->transactionRepository->getAll($filters)),
        ];
    }
}
