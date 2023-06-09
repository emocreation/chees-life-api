<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\DragUpdateRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\District;
use App\Models\Service;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

class DragController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update#banner')->only('banner');
        $this->middleware('permission:update#category')->only('category');
        $this->middleware('permission:update#district')->only('district');
        $this->middleware('permission:update#service')->only('service');
    }

    private function drag(array $data, $model)
    {
        foreach ($data as $row) {
            $model::find($row['id'])->update([
                'sequence' => $row['sequence']
            ]);
        }
    }

    #[Group("CMS API")]
    #[Subgroup("Banners")]
    #[Endpoint('Drag Update Banner', 'Datatable drag update banner sequence')]
    public function banner(DragUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->drag($validated['rows'] ?? [], Banner::class);
        $this->success();
    }

    #[Group("CMS API")]
    #[Subgroup("Categories")]
    #[Endpoint('Drag Update Category', 'Datatable drag update category sequence')]
    public function category(DragUpdateRequest $request)
    {
        $this->drag($validated['rows'] ?? [], Category::class);
        $this->success();
    }

    #[Group("CMS API")]
    #[Subgroup("Districts")]
    #[Endpoint('Drag Update District', 'Datatable drag update district sequence')]
    public function district(DragUpdateRequest $request)
    {
        $this->drag($validated['rows'] ?? [], District::class);
        $this->success();
    }

    #[Group("CMS API")]
    #[Subgroup("Services")]
    #[Endpoint('Drag Update Service', 'Datatable drag update service sequence')]
    public function service(DragUpdateRequest $request)
    {
        $this->drag($validated['rows'] ?? [], Service::class);
        $this->success();
    }

}
