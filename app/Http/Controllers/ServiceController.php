<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\DestroyRequest;
use App\Http\Requests\Service\StoreRequest;
use App\Http\Requests\Service\UpdateRequest;
use App\Models\Service;
use App\Sorts\SortByTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Services')]
class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#service')->only('index', 'show');
        $this->middleware('permission:create#service')->only('store');
        $this->middleware('permission:update#service')->only('update');
        $this->middleware('permission:delete#service')->only('destroy');
    }
    #[Endpoint('Service List', 'Service list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept sequence,slug,price,hot,enable,translations.title#en,translations.title#tc,translations.subtitle#en,translations.subtitle#tc,category.translations.name#en,category.translations.name#tc', example: '-sequence')]
    #[QueryParam('filter[sequence]', 'string', 'Filter by sequence')]
    #[QueryParam('filter[slug]', 'string', 'Filter by slug')]
    #[QueryParam('filter[price]', 'string', 'Filter by price')]
    #[QueryParam('filter[hot]', 'string', 'Filter by hot')]
    #[QueryParam('filter[enable]', 'string', 'Filter by enable')]
    #[QueryParam('filter[translations.title]', 'string', 'Filter by title')]
    #[QueryParam('filter[translations.subtitle]', 'string', 'Filter by subtitle')]
    #[QueryParam('filter[category.translations.name]', 'string', 'Filter by category name')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Service::class)
            ->search($request->s)
            ->defaultSort('-sequence')
            ->allowedSorts(['sequence', 'slug', 'price', 'hot', 'enable',
                AllowedSort::custom('category.translations.name#en', new SortByTranslation()),
                AllowedSort::custom('category.translations.name#tc', new SortByTranslation()),
                AllowedSort::custom('translations.title#en', new SortByTranslation()),
                AllowedSort::custom('translations.title#tc', new SortByTranslation()),
                AllowedSort::custom('translations.subtitle#en', new SortByTranslation()),
                AllowedSort::custom('translations.subtitle#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['sequence', 'slug', 'price', 'hot', 'enable',
                AllowedFilter::partial('category.translations.name'),
                AllowedFilter::partial('translations.title'),
                AllowedFilter::partial('translations.subtitle')])
            ->with('category')
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Service Detail', 'Service detail')]
    public function show(Service $service)
    {
        return $this->success(data: $service->load('category', 'service_descriptions', 'service_details'));
    }

    #[Endpoint('Service Create', 'Service create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $service = Service::create($validated);
        //Create Relation
        //$service->service_descriptions()->createMany($validated['service_descriptions'] ?? []);
        //$service->service_details()->createMany($validated['service_details'] ?? []);
        //Store file
        if ($request->hasFile('image')) {
            $service->addMediaFromRequest('image')->toMediaCollection();
        }
        return $this->success(data: $service->load('service_descriptions', 'service_details'));
    }

    #[Endpoint('Service Update', 'Service update')]
    public function update(UpdateRequest $request, Service $service)
    {
        $validated = $request->validated();
        $service->update($validated);
        //Update service's relation
        //$this->updateRelation($service, 'service_descriptions', $validated['service_descriptions'] ?? []);
        //$this->updateRelation($service, 'service_details', $validated['service_details'] ?? []);
        //Update Image
        if ($request->hasFile('image')) {
            $service->clearMediaCollection();
            $service->addMediaFromRequest('image')->toMediaCollection();
        }
        return $this->success(data: $service->load('service_descriptions', 'service_details'));
    }

    private function updateRelation(Service $model, string $relation, array $validated)
    {
        if ($validated === []) {
            $model->$relation()->delete();
        } else {
            $ids = collect($validated)->pluck('id')->filter()->toArray();
            $model->$relation()->whereNotIn('id', $ids)->delete();
            foreach ($validated as $data) {
                $model->$relation()->updateOrCreate(['id' => $data['id'] ?? null], Arr::except($data, ['id']));
            }
        }
    }

    #[Endpoint('Service Delete', 'Service delete')]
    public function destroy(DestroyRequest $request, Service $service)
    {
        $service->delete();
        return $this->success();
    }
}

