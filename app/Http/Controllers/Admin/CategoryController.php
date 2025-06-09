<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Contracts\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->authorizeResource(Category::class,'category');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepositoryInterface->indexCategory();
        return Inertia::render('admin/Category/Index', [
            'categories' => $categories
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datas = $this->categoryRepositoryInterface->createCategory();
        return Inertia::render('admin/Category/Form', $datas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $this->categoryRepositoryInterface->storeCategory($request);
        return to_route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {

        return Inertia::render('admin/Category/Show', ['category' => $this->categoryRepositoryInterface->showCategory($category)]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $datas=$this->categoryRepositoryInterface->editCategory($category);
        return Inertia::render('admin/Category/Form', $datas);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->categoryRepositoryInterface->updateCategory($request, $category);
        return to_route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
       $this->categoryRepositoryInterface->destroyCategory($category);
        return to_route('categories.index');

    }

    public function updateStatus(Request $request, Category $category)
    {
        $inputData = $request->validate([
            'active' => 'sometimes|boolean',
            'featured' => 'sometimes|boolean',
        ]);
        $category->update($inputData);
        return back();
    }
}
