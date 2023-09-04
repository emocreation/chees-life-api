<?php

namespace App\Http\Controllers;

use App\Http\Requests\LatestNew\DestroyRequest;
use App\Http\Requests\LatestNew\StoreRequest;
use App\Http\Requests\LatestNew\UpdateRequest;
use App\Models\LatestNews;
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
#[Subgroup('Latest News')]
class LatestNewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#latest_new')->only('index', 'show');
        $this->middleware('permission:create#latest_new')->only('store');
        $this->middleware('permission:update#latest_new')->only('update');
        $this->middleware('permission:delete#latest_new')->only('destroy');
    }

    #[Endpoint('Latest News List', 'Latest News list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept sequence,slug,price,hot,enable,translations.title#en,translations.title#tc', example: '-news_date')]
    #[QueryParam('filter[sequence]', 'string', 'Filter by sequence')]
    #[QueryParam('filter[slug]', 'string', 'Filter by slug')]
    #[QueryParam('filter[news_date]', 'string', 'Filter by news_date')]
    #[QueryParam('filter[enable]', 'string', 'Filter by enable')]
    #[QueryParam('filter[translations.title]', 'string', 'Filter by title')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(LatestNews::class)
            ->search($request->s)
            ->defaultSort('news_date')
            ->allowedSorts(['sequence', 'news_date', 'slug', 'enable',
                AllowedSort::custom('translations.title#en', new SortByTranslation()),
                AllowedSort::custom('translations.title#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['sequence', 'slug', 'news_date', 'enable',
                AllowedFilter::partial('translations.title')])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Latest News Detail', 'Latest News detail')]
    public function show(LatestNews $latest_news)
    {
        return $this->success(data: $latest_news);
    }

    #[Endpoint('Latest News Create', 'Latest News create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $latest_news = LatestNews::create($validated);
        //Store file
        if ($request->hasFile('image')) {
            $latest_news->addMediaFromRequest('image')->toMediaCollection();
        }
        return $this->success(data: $latest_news);
    }

    #[Endpoint('Latest News Update', 'Latest News update')]
    public function update(UpdateRequest $request, LatestNews $latest_news)
    {
        $validated = $request->validated();
        $latest_news->update($validated);
        //Update Image
        if ($request->hasFile('image')) {
            $latest_news->clearMediaCollection();
            $latest_news->addMediaFromRequest('image')->toMediaCollection();
        }
        return $this->success(data: $latest_news);
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

    #[Endpoint('Latest News Delete', 'Latest News delete')]
    public function destroy(DestroyRequest $request, LatestNews $latest_news)
    {
        $latest_news->delete();
        return $this->success();
    }
}

