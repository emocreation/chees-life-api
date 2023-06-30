<?php

namespace App\Http\Controllers;

use App\Http\Requests\District\DestroyRequest;
use App\Http\Requests\District\StoreRequest;
use App\Http\Requests\District\UpdateRequest;
use App\Models\District;
use App\Sorts\SortByTranslation;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Districts')]
class DistrictController extends Controller
{
    #[Endpoint('District List', 'District list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept sequence,name,enable,translations.name#en,translations.name#tc', example: '-id')]
    #[QueryParam('filter[sequence]', 'int', 'Filter by sequence')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    #[QueryParam('filter[translations.name]', 'string', 'Filter by translations.name')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(District::class)
            ->search($request->s)
            ->defaultSort('-sequence')
            ->allowedSorts(['sequence', 'enable',
                AllowedSort::custom('translations.name#en', new SortByTranslation()),
                AllowedSort::custom('translations.name#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['sequence', 'enable', AllowedFilter::partial('translations.name')])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('District Detail', 'District detail')]
    public function show(District $district)
    {
        return $this->success(data: $district);
    }

    #[Endpoint('District Create', 'District create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        return $this->success(data: District::create($validated));
    }

    #[Endpoint('District Update', 'District update')]
    public function update(UpdateRequest $request, District $district)
    {
        $validated = $request->validated();
        return $this->success(data: tap($district)->update($validated));
    }

    #[Endpoint('District Delete', 'District delete')]
    public function destroy(DestroyRequest $request, District $district)
    {
        $district->delete();
        return $this->success();
    }
}
