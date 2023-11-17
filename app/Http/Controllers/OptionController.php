<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\QueryBuilder;

#[Group("CMS API")]
class OptionController extends Controller
{
    #[Subgroup('Roles')]
    #[Endpoint('Options', 'List out all records for select options')]
    public function role()
    {
        return $this->success(data: Role::all()->pluck('name'));
    }

    #[Subgroup('Permissions')]
    #[Endpoint('Options', 'List out all records for select options')]
    public function permission()
    {
        return $this->success(data: Permission::all()->pluck('name'));
    }

    #[Subgroup('Categories')]
    #[Endpoint('Options', 'List out all records for select options')]
    public function category()
    {
        $data = Category::all()->map(function ($row) {
            return ['id' => $row->id, 'name' => $row->name];
        });
        return $this->success(data: $data);
    }

    #[Subgroup('Customers')]
    #[Endpoint('Options', 'List out all records for select options')]
    #[QueryParam('filter[id]', 'int', 'Filter by customer id')]
    #[QueryParam('filter[name]', 'string', 'Filter by customer name')]
    #[QueryParam('filter[tel]', 'string', 'Filter by customer tel')]
    #[QueryParam('filter[email]', 'string', 'Filter by customer email')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept id,name,tel,email', example: '-id')]
    public function customer(Request $request)
    {
        $data = QueryBuilder::for(Customer::class)
            ->defaultSort('id')
            ->allowedSorts(['id', 'name', 'tel', 'email'])
            ->allowedFilters(['id', 'name', 'tel', 'email'])
            ->cursorPaginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Subgroup('Services')]
    #[Endpoint('Options', 'List out all records for select options')]
    public function service()
    {
        $data = Service::get();
        return $this->success(data: $data);
    }
}
