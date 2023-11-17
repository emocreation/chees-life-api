<?php

namespace App\Http\Controllers;

use App\Http\Requests\Coupon\DestroyRequest;
use App\Http\Requests\Coupon\StoreRequest;
use App\Http\Requests\Coupon\UpdateRequest;
use App\Models\Coupon;
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
#[Subgroup('Coupons')]
class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#coupon')->only('index', 'show');
        $this->middleware('permission:create#coupon')->only('store');
        $this->middleware('permission:update#coupon')->only('update');
        $this->middleware('permission:delete#coupon')->only('destroy');
    }

    #[Endpoint('Coupon List', 'Coupon list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept type,limitation,code,valid_from,valid_to,quota,used', example: '-id')]
    #[QueryParam('filter[translations.name]', 'string', 'Filter by translations.name')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Coupon::class)
            ->search($request->s)
            ->defaultSort('-valid_from', '-valid_to')
            ->allowedSorts(['type', 'limitation', 'code', 'valid_from', 'valid_to', 'quota', 'used',
                AllowedSort::custom('translations.name#en', new SortByTranslation()),
                AllowedSort::custom('translations.name#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['type', 'limitation', 'code', 'valid_from', 'valid_to', 'quota', 'used', AllowedFilter::partial('translations.name')])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Coupon Detail', 'Coupon detail')]
    public function show(Coupon $coupon)
    {
        return $this->success(data: $coupon->load('coupon_services'));
    }

    #[Endpoint('Coupon Create', 'Coupon create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $coupon = Coupon::create($validated);
        //handle services
        if (!empty($validated['coupon_services'])) {
            foreach ($validated['coupon_services'] as $service_id) {
                $coupon->coupon_services()->firstOrCreate(['service_id' => $service_id]);
            }
        }
        return $this->success(data: $coupon->load('coupon_services'));
    }

    #[Endpoint('Coupon Update', 'Coupon update')]
    public function update(UpdateRequest $request, Coupon $coupon)
    {
        $validated = $request->validated();
        //handle model_case_type
        $coupon->update($validated);
        $delete_list = $coupon->coupon_services()->whereNotIn('service_id', $validated['coupon_services'] ?? [])->delete();
        //Create new model_case_type
        if (!empty($validated['coupon_services'])) {
            foreach ($validated['coupon_services'] as $service_id) {
                $coupon->coupon_services()->firstOrCreate(['service_id' => $service_id]);
            }
        }
        return $this->success(data: $coupon->load('coupon_services'));
    }

    #[Endpoint('Coupon Delete', 'Coupon delete')]
    public function destroy(DestroyRequest $request, Coupon $coupon)
    {
        $coupon->delete();
        return $this->success();
    }
}
