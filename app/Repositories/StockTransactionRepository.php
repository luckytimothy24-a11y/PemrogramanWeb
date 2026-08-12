<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Repositories\Contracts\StockTransactionRepositoryInterface;
use Illuminate\Support\Carbon;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = StockTransaction::with(['product', 'supplier', 'user'])->latest('transaction_date');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('transaction_date', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('transaction_date', '<=', $filters['to']);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return StockTransaction::with(['product', 'supplier', 'user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, array $data)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->update($data);

        return $transaction;
    }

    public function delete($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        return $transaction->delete();
    }

    public function totalIn(array $filters = [])
    {
        return $this->scoped($filters)->where('type', 'in')->sum('quantity');
    }

    public function totalOut(array $filters = [])
    {
        return $this->scoped($filters)->where('type', 'out')->sum('quantity');
    }

    public function todayIn()
    {
        return StockTransaction::where('type', 'in')
            ->whereDate('transaction_date', Carbon::today())
            ->sum('quantity');
    }

    public function todayOut()
    {
        return StockTransaction::where('type', 'out')
            ->whereDate('transaction_date', Carbon::today())
            ->sum('quantity');
    }

    private function scoped(array $filters = [])
    {
        $query = StockTransaction::query();

        if (! empty($filters['from'])) {
            $query->whereDate('transaction_date', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('transaction_date', '<=', $filters['to']);
        }

        return $query;
    }
}
