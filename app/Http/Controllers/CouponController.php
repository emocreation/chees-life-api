<?php

namespace App\Http\Controllers;

use App\Http\Requests\Coupon\DestroyRequest;
use App\Http\Requests\Coupon\StoreRequest;
use App\Http\Requests\Coupon\UpdateRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
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
    #[QueryParam('filter[coupon_date]', 'string', 'Filter by coupon_date')]
    #[QueryParam('filter[rating]', 'int', 'Filter by rating')]
    #[QueryParam('filter[enable]', 'int', 'Filter by enable')]
    #[QueryParam('filter[translations.customer_name]', 'string', 'Filter by translations.customer_name')]
    #[QueryParam('filter[translations.content]', 'string', 'Filter by translations.content')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Coupon::class)
            ->search($request->s)
            ->defaultSort('-valid_from', '-valid_to')
            ->allowedSorts(['type', 'limitation', 'code', 'valid_from', 'valid_to', 'quota', 'used'])
            ->allowedFilters(['type', 'limitation', 'code', 'valid_from', 'valid_to', 'quota', 'used'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Coupon Detail', 'Coupon detail')]
    public function show(Coupon $coupon)
    {
        return $this->success(data: $coupon);
    }

    #[Endpoint('Coupon Create', 'Coupon create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        return $this->success(data: Coupon::create($validated));
    }

    #[Endpoint('Coupon Update', 'Coupon update')]
    public function update(UpdateRequest $request, Coupon $coupon)
    {
        $validated = $request->validated();
        return $this->success(data: tap($coupon)->update($validated));
    }

    #[Endpoint('Coupon Delete', 'Coupon delete')]
    public function destroy(DestroyRequest $request, Coupon $coupon)
    {
        $coupon->delete();
        return $this->success();
    }
}
