<?php

namespace App\Repositories;

use App\Models\Admin\Category;
use App\Contracts\CategoryRepositoryInterface;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    // Category Index
    public function indexCategory()
    {
        $categories = Cache::has('categories') ? Cache::get('categories') : Cache::rememberForever('categories', function () {
            return Category::with('parent')->latest()->get();
        });

        return $categories;
    }

    // Category Create
    public function createCategory()
    {
        $categories = Category::get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            })->values()->toArray();
        $moduleOptions = getAllUniqueModelNames();

        return ['categories' => $categories, 'models' => $moduleOptions];
    }

    // Category Store
    public function storeCategory(CategoryRequest $request)
    {
        Category::create($request->validated());
    }

    // Category Show
    public function showCategory(Category $category)
    {
        return $category;
    }

    // Category Edit
    public function editCategory(Category $category)
    {
        $categories = Category::get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            })->values()->toArray();
        $moduleOptions = getAllUniqueModelNames();
        return ['category' => $category, 'categories' => $categories, 'models' => $moduleOptions];
    }

    // Category Update
    public function updateCategory(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
    }

    // Category Destroy
    public function destroyCategory(Category $category)
    {
        $category->delete();
    }
}
