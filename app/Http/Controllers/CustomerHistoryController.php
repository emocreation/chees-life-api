<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerHistory\DestroyRequest;
use App\Http\Requests\CustomerHistory\StoreRequest;
use App\Http\Requests\CustomerHistory\UpdateRequest;
use App\Models\CustomerHistory;
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
#[Subgroup('Customer History')]
class CustomerHistoryController extends Controller
{
    #[Endpoint('Customer History List', 'Customer history list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept customer_id,name,gender,birthday,hkid,tel,email,address,amount,created_at,translation.district#en,translation.district#tc,translation.title#en,translation.title#tc', example: '-created_at')]
    #[QueryParam('filter[customer_id]', 'string', 'Filter by customer_id')]
    #[QueryParam('filter[name]', 'string', 'Filter by name')]
    #[QueryParam('filter[gender]', 'string', 'Filter by gender')]
    #[QueryParam('filter[birthday]', 'string', 'Filter by birthday')]
    #[QueryParam('filter[hkid]', 'string', 'Filter by hkid')]
    #[QueryParam('filter[tel]', 'string', 'Filter by tel')]
    #[QueryParam('filter[email]', 'string', 'Filter by email')]
    #[QueryParam('filter[address]', 'string', 'Filter by address')]
    #[QueryParam('filter[amount]', 'string', 'Filter by amount')]
    #[QueryParam('filter[created_at]', 'string', 'Filter by created_at')]
    #[QueryParam('filter[translations.district]', 'string', 'Filter by district')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(CustomerHistory::class)
            ->search($request->s)
            ->defaultSort('-created_at')
            ->allowedSorts(['customer_id', 'name', 'gender', 'birthday', 'hkid', 'tel', 'email', 'address', 'amount', 'created_at',
                AllowedSort::custom('translations.district#en', new SortByTranslation()),
                AllowedSort::custom('translations.district#tc', new SortByTranslation()),
            ])
            ->allowedFilters(['customer_id', 'name', 'gender', 'birthday', 'hkid', 'tel', 'email', 'address', 'amount', 'created_at',
                AllowedFilter::partial('translations.district'),])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Customer History Detail', 'Customer history detail')]
    public function show(CustomerHistory $customerHistory)
    {
        return $this->success(data: $customerHistory->load('customer', 'customer_history_details'));
    }

    #[Endpoint('Customer History Store', 'Customer history store')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $customerHistory = CustomerHistory::create($validated);
        //Store file
        if ($request->hasFile('report_pdf')) {
            $customerHistory->addMediaFromRequest('report')->toMediaCollection();
        }
        $customerHistory->customer_history_details()->createMany($validated['customer_history_details'] ?? []);
        return $this->success(data: $customerHistory->load('customer_history_details'));
    }

    #[Endpoint('Customer History Update', 'Customer history update')]
    public function update(UpdateRequest $request, CustomerHistory $customerHistory)
    {
        $validated = $request->validated();
        $customerHistory->update($validated);
        //Update Image
        if ($request->hasFile('report_pdf')) {
            $customerHistory->clearMediaCollection();
            $customerHistory->addMediaFromRequest('report')->toMediaCollection();
        }
        $this->updateRelation($customerHistory, 'customer_history_details', $validated['customer_history_details'] ?? []);
        return $this->success(data: $customerHistory->load('customer_history_details'));
    }

    private function updateRelation(CustomerHistory $model, string $relation, array $validated)
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

    #[Endpoint('Customer History Delete', 'Customer history delete')]
    public function destroy(DestroyRequest $request, CustomerHistory $customerHistory)
    {
        $customerHistory->delete();
        return $this->success();
    }
}
