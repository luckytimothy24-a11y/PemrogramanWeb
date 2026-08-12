<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ActivityLogService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $activityLogService;

    public function __construct(CategoryService $categoryService, ActivityLogService $activityLogService)
    {
        $this->categoryService = $categoryService;
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $this->categoryService->createCategory($request->all());

        $this->activityLogService->log('Tambah Kategori', "Kategori \"{$request->input('name')}\" ditambahkan.");

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
        ]);

        $this->categoryService->updateCategory($id, $request->all());

        $this->activityLogService->log('Ubah Kategori', "Kategori \"{$request->input('name')}\" diperbarui.");

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $name = $category->name;

        $this->categoryService->deleteCategory($id);

        $this->activityLogService->log('Hapus Kategori', "Kategori \"{$name}\" dihapus.");

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}
