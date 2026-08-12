<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getAll()
    {
        return Supplier::latest()->get();
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update($id, array $data)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);

        return $supplier;
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);

        return $supplier->delete();
    }

    public function count()
    {
        return Supplier::count();
    }
}
