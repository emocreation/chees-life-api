<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DestroyRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
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
#[Subgroup('Categories')]
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#category')->only('index', 'show');
        $this->middleware('permission:create#category')->only('store');
        $this->middleware('permission:update#category')->only('update');
        $this->middleware('permission:delete#category')->only('destroy');
    }
    #[Endpoint('Category List', 'Category list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept sequence,slug,enable,translations.name#en,translations.name#tc,translations.description#en,translations.description#tc,translations.title#en,translations.title#tc', example: '-sequence')]
    #[QueryParam('filter[sequence]', 'int', 'Filter by sequence')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    #[QueryParam('filter[slug]', 'string', 'Filter by slug')]
    #[QueryParam('filter[translations.name]', 'string', 'Filter by name')]
    #[QueryParam('filter[translations.description]', 'string', 'Filter by description')]
    #[QueryParam('filter[translations.title]', 'string', 'Filter by title')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Category::class)
            ->search($request->s)
            ->defaultSort('sequence')
            ->allowedSorts(['sequence', 'slug', 'enable',
                AllowedSort::custom('translations.name#en', new SortByTranslation()),
                AllowedSort::custom('translations.name#tc', new SortByTranslation()),
                AllowedSort::custom('translations.description#en', new SortByTranslation()),
                AllowedSort::custom('translations.description#tc', new SortByTranslation()),
                AllowedSort::custom('translations.title#en', new SortByTranslation()),
                AllowedSort::custom('translations.title#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['sequence', 'slug', 'enable',
                AllowedFilter::partial('translations.name'),
                AllowedFilter::partial('translations.description'),
                AllowedFilter::partial('translations.title')])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Category Detail', 'Category detail')]
    public function show(Category $category)
    {
        return $this->success(data: $category);
    }


    #[Endpoint('Category Create', 'Category create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        return $this->success(data: Category::create($validated));
    }

    #[Endpoint('Category Update', 'Category update')]
    public function update(UpdateRequest $request, Category $category)
    {
        $validated = $request->validated();
        return $this->success(data: tap($category)->update($validated));
    }

    #[Endpoint('Category Detail', 'Category detail')]
    public function destroy(DestroyRequest $request, Category $category)
    {
        $category->delete();
        return $this->success();
    }
}
