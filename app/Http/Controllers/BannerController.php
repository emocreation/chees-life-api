<?php

namespace App\Http\Controllers;

use App\Http\Requests\Banner\DestroyRequest;
use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Banners')]
class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#banner')->only('index', 'show');
        $this->middleware('permission:create#banner')->only('store');
        $this->middleware('permission:update#banner')->only('update');
        $this->middleware('permission:delete#banner')->only('destroy');
    }

    #[Endpoint('Banner List', 'Banner list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept id,sequence,enable', example: '-id')]
    #[QueryParam('filter[id]', 'string', 'Filter by id')]
    #[QueryParam('filter[sequence]', 'int', 'Filter by sequence')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Banner::class)
            ->search($request->s)
            ->defaultSort('sequence')
            ->allowedSorts(['id', 'sequence', 'enable'])
            ->allowedFilters(['id', 'sequence', 'enable'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Banner Detail', 'Banner detail')]
    public function show(Banner $banner)
    {
        return $this->success(data: $banner->unsetRelation('media'));
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    #[Endpoint('Banner Create', 'Banner create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $banner = Banner::create($validated);
        $banner->addMediaFromRequest('image_web_en')->toMediaCollection('web_banner_en');
        $banner->addMediaFromRequest('image_web_tc')->toMediaCollection('web_banner_tc');
        $banner->addMediaFromRequest('image_mobile_en')->toMediaCollection('mobile_banner_en');
        $banner->addMediaFromRequest('image_mobile_tc')->toMediaCollection('mobile_banner_tc');
        return $this->success(data: $banner);
    }

    #[Endpoint('Banner Update', 'Banner update')]
    public function update(UpdateRequest $request, Banner $banner)
    {
        $validated = $request->validated();
        $validated['enable'] = $validated['enable'] ?? false;
        $banner->update($validated);
        if ($request->hasFile('image_web_en')) {
            $banner->clearMediaCollection('web_banner_en');
            $banner->addMediaFromRequest('image_web_en')->toMediaCollection('web_banner_en');
        }
        if ($request->hasFile('image_web_tc')) {
            $banner->clearMediaCollection('web_banner_tc');
            $banner->addMediaFromRequest('image_web_tc')->toMediaCollection('web_banner_tc');
        }
        if ($request->hasFile('image_mobile_en')) {
            $banner->clearMediaCollection('mobile_banner_en');
            $banner->addMediaFromRequest('image_mobile_en')->toMediaCollection('mobile_banner_en');
        }
        if ($request->hasFile('image_mobile_tc')) {
            $banner->clearMediaCollection('mobile_banner_tc');
            $banner->addMediaFromRequest('image_mobile_tc')->toMediaCollection('mobile_banner_tc');
        }
        return $this->success(data: $banner);
    }

    #[Endpoint('Banner Delete', 'Banner delete')]
    public function destroy(DestroyRequest $request, Banner $banner)
    {
        $banner->delete();
        return $this->success();
    }
}
