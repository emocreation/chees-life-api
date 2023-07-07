<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;

class AuthController extends Controller
{
    public function base()
    {
        return $this->error(code: 401);
    }

    #[Group("CMS API")]
    #[Subgroup("Auth")]
    #[Endpoint('Login', 'Login')]
    #[Unauthenticated]
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $remember = Arr::exists($validated, 'remember') ? $validated['remember'] : false;
        Arr::forget($validated, 'remember');
        //Validate login
        if (!Auth::attempt($validated, $remember)) {
            return $this->error(__('auth.unauthorized'), 422);
        }
        //Generate token
        $user = $request->user();
        $abilities = $user->getPermissionsViaRoles()->pluck('name')->map(function ($row) {
            [$action, $subject] = explode('#', $row);
            return ['action' => $action, 'subject' => $subject];
        })->toArray();
        $token = $user->createToken('token', ['cms'])->plainTextToken;
        $user->unsetRelations();
        $user->role = $user->role_name;
        $user->makeHidden(['role_name']);
        $data = [
            'token_type' => 'Bearer', 'accessToken' => $token,
            'userData' => $user,
            'userAbilities' => $abilities
        ];
        return $this->success(data: $data);
    }

    #[Group("CMS API")]
    #[Subgroup("Auth")]
    #[Endpoint('User', 'Retrieve user info')]
    public function user(Request $request)
    {
        $user = $request->user();
        $user->role = $user->role_name;
        $user->makeHidden(['role_name']);
        //Retrieve user info
        return $this->success(data: $user);
    }

    #[Group("CMS API")]
    #[Subgroup("Auth")]
    #[Endpoint('Logout', 'Logout')]
    public function logout(Request $request)
    {
        //Delete token
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('auth.logout'));
    }

    #[Group("CMS API")]
    #[Subgroup("Auth")]
    #[Endpoint('Update Password', 'Update password')]
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        if ($user === null || !Hash::check($validated['currentPassword'], $user['password'])) {
            return $this->error(__('auth.incorrect_current_password'), 401);
        }
        $user->update(['password' => $validated['newPassword']]);
        return $this->success(__('base.update_success'));
    }
}
