<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Sorts\SortByRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Users')]
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#user')->only('index', 'show');
        $this->middleware('permission:create#user')->only('store');
        $this->middleware('permission:update#user')->only('update');
        $this->middleware('permission:delete#user')->only('destroy');
    }
    #[Endpoint('User List', 'User list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept id,name,email,role.name', example: 'id')]
    #[QueryParam('filter[id]', 'string', 'Filter by id')]
    #[QueryParam('filter[name]', 'int', 'Filter by name')]
    #[QueryParam('filter[email]', 'string', 'Filter by email')]
    #[QueryParam('filter[role.name]', 'string', 'Filter by role name')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(User::class)
            ->search($request->s)
            ->defaultSort('id')
            ->allowedSorts(['id', 'name', 'email',
                AllowedSort::custom('role.name', new SortByRelation()),
            ])
            ->allowedFilters(['id', 'name', 'email', 'role.name'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('User Detail', 'User detail')]
    public function show(User $user)
    {
        return $this->success(data: $user);
    }

    #[Endpoint('User Create', 'User create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        $user->syncRoles($validated['role_name']);
        return $this->success(data: $user);
    }

    #[Endpoint('User Update', 'User update')]
    public function update(UpdateRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated = $validated['password'] ? $validated : Arr::except($validated, 'password');
        $user->update($validated);
        $user->syncRoles($validated['role_name']);
        return $this->success(data: $user->refresh());
    }

    #[Endpoint('User Destroy', 'User destroy')]
    public function destroy(DestroyRequest $request, User $user)
    {
        $user->delete();
        return $this->success();
    }
}
