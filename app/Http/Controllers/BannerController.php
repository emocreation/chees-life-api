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
            ->defaultSort('-sequence')
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
        $banner->addMediaFromRequest('image')->toMediaCollection();
        return $this->success(data: $banner);
    }

    #[Endpoint('Banner Update', 'Banner update')]
    public function update(UpdateRequest $request, Banner $banner)
    {
        $validated = $request->validated();
        $validated['enable'] = $validated['enable'] ?? false;
        $banner->update($validated);
        if ($request->hasFile('image')) {
            $banner->clearMediaCollection();
            $banner->addMediaFromRequest('image')->toMediaCollection();
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
