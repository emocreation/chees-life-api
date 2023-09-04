<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\RowUpdateRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\District;
use App\Models\LatestNews;
use App\Models\Service;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("CMS API")]
class RowUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:update#banner')->only('banner');
        $this->middleware('permission:update#category')->only('category');
        $this->middleware('permission:update#district')->only('district');
        $this->middleware('permission:update#service')->only('service');
    }

    #[Subgroup("Banners")]
    #[Endpoint('Row Update Banner', 'Datatable row update banner sequence')]
    public function banner(RowUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->rowUpdate($validated['id'], $validated['sequence'], Banner::class);
        $this->success();
    }

    private function rowUpdate($id, $sequence, $model)
    {
        $record = $model::find($id);
        $old_sequence = $record->sequence;
        $new_sequence = $sequence;
        $max = $model::max('sequence');

        if ($new_sequence < 1) {
            $new_sequence = 1;
        } elseif ($new_sequence > $max) {
            $new_sequence = $max;
        }

        if ($new_sequence !== $old_sequence) {
            if ($new_sequence > $old_sequence) {
                $model::where('sequence', '>=', $old_sequence)->where('sequence', '<=',
                    $new_sequence)->decrement('sequence');
            } elseif ($new_sequence < $old_sequence) {
                $model::where('sequence', '>=', $new_sequence)->where('sequence', '<=',
                    $old_sequence)->increment('sequence');
            }
            $record->update(['sequence' => $new_sequence]);
        }
    }

    #[Subgroup("Categories")]
    #[Endpoint('Row Update Category', 'Datatable row update category sequence')]
    public function category(RowUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->rowUpdate($validated['id'], $validated['sequence'], Category::class);
        $this->success();
    }

    #[Subgroup("Districts")]
    #[Endpoint('Row Update District', 'Datatable row update district sequence')]
    public function district(RowUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->rowUpdate($validated['id'], $validated['sequence'], District::class);
        $this->success();
    }

    #[Subgroup("Services")]
    #[Endpoint('Row Update Service', 'Datatable row update service sequence')]
    public function service(RowUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->rowUpdate($validated['id'], $validated['sequence'], Service::class);
        $this->success();
    }

    #[Subgroup("Latest News")]
    #[Endpoint('Row Update Latest News', 'Datatable row update Latest News sequence')]
    public function latestNews(RowUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->rowUpdate($validated['id'], $validated['sequence'], LatestNews::class);
        $this->success();
    }
}
