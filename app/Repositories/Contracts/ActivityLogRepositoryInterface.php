<?php

namespace App\Repositories\Contracts;

interface ActivityLogRepositoryInterface
{
    public function getAll(array $filters = []);
    public function create(array $data);
    public function latest(int $limit);
    public function count();
}
