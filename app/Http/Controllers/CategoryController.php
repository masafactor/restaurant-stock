<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
 public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $kw = $request->string('keyword')->trim();

        $categories = Category::query()
            ->when($kw, fn($q) => $q->where('name', 'like', "%{$kw}%"))
            ->orderByDesc('id')
            ->paginate(20)
            ->through(function (Category $c) use ($request) {
                return [
                    'id'        => $c->id,
                    'name'      => $c->name,
                    'is_active' => $c->is_active,
                    'can' => [
                        'update' => $request->user()->can('update', $c),
                        'delete' => $request->user()->can('delete', $c),
                    ],
                ];
            });

           

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'filters'    => ['keyword' => (string) $kw],
            'can'        => [
                'create' => $request->user()->can('create', Category::class),
            ],
        ]);
    }


    public function create()
    {
        $this->authorize('create', Category::class);

        return Inertia::render('Categories/Create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'カテゴリーを登録しました');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
       
        return Inertia::render('Categories/Edit', [
            'category' => $category->only('id','name','is_active'), // ← is_active を含める
        ]);
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'カテゴリーを更新しました');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'カテゴリーを削除しました');
    }

    // app/Http/Controllers/CategoryController.php
public function show(Category $category)
    {
        $this->authorize('view', $category);

        return Inertia::render('Categories/Show', [
            'category' => [
                'id'        => $category->id,
                'name'      => $category->name,
                'is_active' => (bool) $category->is_active, // ここでboolean化（モデルでcastsしてれば不要）
                'can' => [
                    'update' => request()->user()->can('update', $category),
                    'delete' => request()->user()->can('delete', $category),
                ],
            ],
        ]);
    }

}
