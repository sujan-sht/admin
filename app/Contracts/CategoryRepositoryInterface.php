<?php

namespace App\Contracts;

use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;

interface CategoryRepositoryInterface
{
    public function indexCategory();

    public function createCategory();

    public function storeCategory(CategoryRequest $request);

    public function showCategory(Category $category);

    public function editCategory(Category $category);

    public function updateCategory(CategoryRequest $request, Category $category);

    public function destroyCategory(Category $category);
}
