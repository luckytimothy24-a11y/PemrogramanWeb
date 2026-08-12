<?php

namespace App\Repositories\Contracts;

interface StockOpnameRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById($id);
    public function create(array $data);
    public function delete($id);
    public function count();
}
