<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\DestroyRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\ModelHasRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Subgroup;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('CMS API')]
#[Subgroup('Roles')]
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view#role')->only('index', 'show');
        $this->middleware('permission:create#role')->only('store');
        $this->middleware('permission:update#role')->only('update');
        $this->middleware('permission:delete#role')->only('destroy');
    }
    #[Endpoint('Role List', 'Role list')]
    #[QueryParam('s', 'string', 'Search keyword')]
    #[QueryParam('p', 'int', 'Page number, default=20')]
    #[QueryParam('sort', 'string', 'Sort by column name, `-` equal to descending. Accept id,sequence', example: 'id')]
    #[QueryParam('filter[id]', 'string', 'Filter by id')]
    #[QueryParam('filter[sequence]', 'int', 'Filter by sequence')]
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Role::class)
            ->search($request->s)
            ->defaultSort('id')
            ->allowedSorts(['id', 'name'])
            ->allowedFilters(['id', 'name'])
            ->paginate($request->p ?? 20)
            ->appends($request->query());
        return $this->success(data: $data);
    }

    #[Endpoint('Role Detail', 'Role detail')]
    public function show(Role $role)
    {
        return $this->success(data: $role);
    }

    #[Endpoint('Role Create', 'Role create')]
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $role = Role::create($validated);
        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }
        return $this->success(data: $role);
    }

    #[Endpoint('Role Update', 'Role update')]
    public function update(UpdateRequest $request, Role $role)
    {
        $validated = $request->validated();
        $role->syncPermissions($validated['permissions']);
        return $this->success(data: tap($role)->update($validated));
    }

    #[Endpoint('Role Delete', 'Role delete')]
    public function destroy(DestroyRequest $request, Role $role)
    {
        if (ModelHasRole::where('role_id', $role->id)->count() > 0) {
            return $this->error(__('auth.not_allow_delete_role'), 403);
        }
        return $this->success(data: $role->delete());
    }
}
