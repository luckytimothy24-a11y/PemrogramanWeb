<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Repositories\Contracts\StockOpnameRepositoryInterface;

class StockOpnameRepository implements StockOpnameRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = StockOpname::with(['product', 'user'])->latest('opname_date');

        if (! empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('opname_date', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('opname_date', '<=', $filters['to']);
        }

        return $query->get();
    }

    public function findById($id)
    {
        return StockOpname::findOrFail($id);
    }

    public function create(array $data)
    {
        return StockOpname::create($data);
    }

    public function delete($id)
    {
        $opname = StockOpname::findOrFail($id);

        return $opname->delete();
    }

    public function count()
    {
        return StockOpname::count();
    }
}
