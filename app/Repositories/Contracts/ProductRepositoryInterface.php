<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getAllWithRelations();
    public function findById($id);
    public function findBySku($sku);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function search($keyword);
    public function count();
    public function lowStock();
    public function outOfStock();
}
