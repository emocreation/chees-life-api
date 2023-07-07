<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\DestroyRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Customers')]
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#customer')->only('index', 'show');
        $this->middleware('permission:create#customer')->only('store');
        $this->middleware('permission:update#customer')->only('update');
        $this->middleware('permission:delete#customer')->only('destroy');
    }
    #[Endpoint('Customer List', 'Customer list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept id,name,gender,email,address,tel,hkid,birthday', example: '-id')]
    #[QueryParam('filter[name]', 'string', 'Filter by name')]
    #[QueryParam('filter[gender]', 'string', 'Filter by gender')]
    #[QueryParam('filter[email]', 'string', 'Filter by email')]
    #[QueryParam('filter[address]', 'string', 'Filter by address')]
    #[QueryParam('filter[tel]', 'string', 'Filter by tel')]
    #[QueryParam('filter[hkid]', 'string', 'Filter by hkid')]
    #[QueryParam('filter[birthday]', 'string', 'Filter by birthday')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Customer::class)
            ->search($request->s)
            ->defaultSort('id')
            ->allowedSorts(['name', 'gender', 'email', 'address', 'tel', 'hkid', 'birthday'])
            ->allowedFilters(['name', 'gender', 'email', 'address', 'tel', 'hkid', 'birthday'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Customer Detail', 'Customer detail')]
    public function show(Customer $customer)
    {
        return $this->success(data: $customer);
    }

    #[Endpoint('Customer Create', 'Customer create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        if ($validated['is_verified']) {
            $validated['email_verified_at'] = now();
        }
        return $this->success(data: Customer::create($validated));
    }

    #[Endpoint('Customer Update', 'Customer update')]
    public function update(UpdateRequest $request, Customer $customer)
    {
        $validated = $request->validated();
        $validated = $validated['password'] ? $validated : Arr::except($validated, 'password');
        $customer->update($validated);

        if ($validated['is_verified'] && $customer['email_verified_at'] === null) {
            $customer->update(['email_verified_at' => now()]);
        } else if ($validated['is_verified'] === false) {
            $customer->update(['email_verified_at' => null]);
        }
        return $this->success(data: $customer->refresh());
    }

    #[Endpoint('Customer Destroy', 'Customer destroy')]
    public function destroy(DestroyRequest $request, Customer $customer)
    {
        $customer->delete();
        return $this->success();
    }
}
