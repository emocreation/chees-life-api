<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMedia\DestroyRequest;
use App\Http\Requests\SocialMedia\UpdateRequest;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Social Media')]
class SocialMediaController extends Controller
{
    #[Endpoint('Social Media List', 'Social media list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept type,link,enable', example: 'type')]
    #[QueryParam('filter[type]', 'string', 'Filter by type')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    #[QueryParam('filter[link]', 'string', 'Filter by link')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(SocialMedia::class)
            ->search($request->s)
            ->defaultSort('type')
            ->allowedSorts(['type', 'link', 'enable'])
            ->allowedFilters(['type', 'link', 'enable'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Social Media Detail', 'Social media detail')]
    public function show(SocialMedia $socialMedia)
    {
        return $this->success(data: $socialMedia);
    }

    #[Endpoint('Social Media Create', 'Social media create')]
    public function update(UpdateRequest $request, SocialMedia $socialMedia)
    {
        $validated = $request->validated();
        return $this->success(data: tap($socialMedia)->update($validated));

    }

    #[Endpoint('Social Media Delete', 'Social media delete')]
    public function destroy(DestroyRequest $request, SocialMedia $socialMedia)
    {
        $socialMedia->delete();
        return $this->success();
    }
}
