<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    public function createCategory(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->categoryRepository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }
}