<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Customer\UpdateRequest;
use Illuminate\Support\Arr;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Frontend API')]
#[Subgroup('User')]
class UserController extends Controller
{
    #[Endpoint('User Info')]
    public function index()
    {
        return $this->success(data: request()->user());
    }

    #[Endpoint('User Order History')]
    public function show()
    {
        return $this->success(data: request()->user()->load('customer_histories'));
    }

    #[Endpoint('User Update')]
    public function update(UpdateRequest $request)
    {
        $validated = $request->validated();
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        Arr::forget($validated, 'remember');
        request()->user()->update($validated);
        return $this->success(data: request()->user());
    }
}