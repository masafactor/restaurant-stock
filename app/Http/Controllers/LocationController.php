<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * 一覧
     */
public function index(Request $request)
    {
        $this->authorize('viewAny', Location::class);

        $kw = $request->string('keyword')->trim();

        $locations = Location::query()
            ->when($kw, fn($q) => $q->where('name', 'like', "%{$kw}%"))
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->through(function (Location $l) use ($request) {
                return [
                    'id'        => $l->id,
                    'name'      => $l->name,
                    'is_active' => (bool)$l->is_active,
                    'can' => [
                        'update' => $request->user()->can('update', $l),
                        'delete' => $request->user()->can('delete', $l),
                    ],
                ];
            });

        return Inertia::render('Locations/Index', [
            'locations' => $locations,
            'filters'   => ['keyword' => (string)$kw],
            'can'       => [
                'create' => $request->user()->can('create', Location::class),
            ],
        ]);
    }


    /**
     * 新規作成フォーム
     */
    public function create()
    {
        $this->authorize('create', Location::class);
        return Inertia::render('Locations/Create');
    }

    /**
     * 登録
     */
    public function store(StoreLocationRequest $request)
    {
        Location::create($request->validated());
        return redirect()->route('locations.index')->with('success', 'ロケーションを登録しました');
    }

    /**
     * 詳細
     */
    public function show(Location $location)
    {
        $this->authorize('view', $location);

        return Inertia::render('Locations/Show', [
            'location' => [
                'id'        => $location->id,
                'name'      => $location->name,
                'is_active' => (bool)$location->is_active,
                'can' => [
                    'update' => request()->user()->can('update', $location),
                    'delete' => request()->user()->can('delete', $location),
                ],
            ],
        ]);
    }
    /**
     * 編集フォーム
     */
    public function edit(Location $location)
    {
        $this->authorize('update', $location);

        return Inertia::render('Locations/Edit', [
            'location' => [
                'id'        => $location->id,
                'name'      => $location->name,
                'is_active' => (bool)$location->is_active,
            ],
        ]);
    }

    /**
     * 更新
     */
    public function update(StoreLocationRequest $request, Location $location)
    {
        $location->update($request->validated());
        return redirect()->route('locations.index')->with('success', 'ロケーションを更新しました');
    }

    /**
     * 削除
     */
    public function destroy(Location $location)
    {
        $this->authorize('delete', $location);

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'ロケーションを削除しました');
    }
}
