<?php

namespace App\Repositories\Contracts;

interface StockTransactionRepositoryInterface
{
    public function getAll(array $filters = []);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function totalIn(array $filters = []);
    public function totalOut(array $filters = []);
    public function todayIn();
    public function todayOut();
}
