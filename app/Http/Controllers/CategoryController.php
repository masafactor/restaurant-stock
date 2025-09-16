<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        return Category::orderBy('id')->get();
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201); // ← 201
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return $category;
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return $category;
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->noContent();
    }
}
