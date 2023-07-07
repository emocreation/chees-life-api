<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\DestroyRequest;
use App\Http\Requests\Review\StoreRequest;
use App\Http\Requests\Review\UpdateRequest;
use App\Models\Review;
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
#[Subgroup('Reviews')]
class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#review')->only('index', 'show');
        $this->middleware('permission:create#review')->only('store');
        $this->middleware('permission:update#review')->only('update');
        $this->middleware('permission:delete#review')->only('destroy');
    }

    #[Endpoint('Review List', 'Review list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept review_date,rating,enable,translations.customer_name#en,translations.customer_name#tc', example: '-id')]
    #[QueryParam('filter[review_date]', 'string', 'Filter by review_date')]
    #[QueryParam('filter[rating]', 'int', 'Filter by rating')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    #[QueryParam('filter[translations.customer_name]', 'string', 'Filter by translations.customer_name')]
    #[QueryParam('filter[translations.content]', 'string', 'Filter by translations.content')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Review::class)
            ->search($request->s)
            ->defaultSort('-review_date')
            ->allowedSorts(['review_date', 'rating', 'enable',
                AllowedSort::custom('translations.customer_name#en', new SortByTranslation()),
                AllowedSort::custom('translations.customer_name#tc', new SortByTranslation()),
                AllowedSort::custom('translations.content#en', new SortByTranslation()),
                AllowedSort::custom('translations.content#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['review_date', 'rating', 'enable',
                AllowedFilter::partial('translations.customer_name'),
                AllowedFilter::partial('translations.content')])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Review Detail', 'Review detail')]
    public function show(Review $review)
    {
        return $this->success(data: $review);
    }

    #[Endpoint('Review Create', 'Review create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        return $this->success(data: Review::create($validated));
    }

    #[Endpoint('Review Update', 'Review update')]
    public function update(UpdateRequest $request, Review $review)
    {
        $validated = $request->validated();
        return $this->success(data: tap($review)->update($validated));
    }

    #[Endpoint('Review Delete', 'Review delete')]
    public function destroy(DestroyRequest $request, Review $review)
    {
        $review->delete();
        return $this->success();
    }
}
