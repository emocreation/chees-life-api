<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\BulkUpdateRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\District;
use App\Models\LatestNews;
use App\Models\Review;
use App\Models\Service;
use App\Models\SocialMedia;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("CMS API")]
class BulkUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:update#banner')->only('banner');
        $this->middleware('permission:update#category')->only('category');
        $this->middleware('permission:update#district')->only('district');
        $this->middleware('permission:update#service')->only('service');
        $this->middleware('permission:update#review')->only('review');
        $this->middleware('permission:update#social_media')->only('socialMedia');
    }

    #[Subgroup("Banners")]
    #[Endpoint('Bulk Update Banner', 'Bulk update banners param or delete on listing page')]
    public function banner(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, Banner::class);
        $this->success();
    }

    private function bulkUpdate($validated, $model)
    {
        $ids = $validated->ids ?? [];
        if (count($ids) > 0) {
            $record = $model::whereIn('id', $ids);
            if (Arr::exists($validated, 'delete')) {
                $record->delete();
            } else {
                $params = Arr::except($validated, ['ids', 'delete']);
                if (count($params)) {
                    $record->update($params);
                }
            }
        }
    }

    #[Subgroup("Categories")]
    #[Endpoint('Bulk Update Category', 'Bulk update categories param or delete on listing page')]
    public function category(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, Category::class);
        $this->success();
    }

    #[Subgroup("Districts")]
    #[Endpoint('Bulk Update District', 'Bulk update districts param or delete on listing page')]
    public function district(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, District::class);
        $this->success();
    }

    #[Subgroup("Reviews")]
    #[Endpoint('Bulk Update Review', 'Bulk update reviews param or delete on listing page')]
    public function review(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, Review::class);
        $this->success();
    }

    #[Subgroup("Services")]
    #[Endpoint('Bulk Update Service', 'Bulk update services param or delete on listing page')]
    public function service(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, Service::class);
        $this->success();
    }

    #[Subgroup("Social Media")]
    #[Endpoint('Bulk Update Social Media', 'Bulk update social media param or delete on listing page')]
    public function socialMedia(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, SocialMedia::class);
        $this->success();
    }

    #[Subgroup("Latest News")]
    #[Endpoint('Bulk Update Latest News', 'Bulk update Latest News param or delete on listing page')]
    public function latestNews(BulkUpdateRequest $request)
    {
        $validated = $request->validated();
        $this->bulkUpdate($validated, LatestNews::class);
        $this->success();
    }
}
