<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Requests\v1\Auth\RegisterRequest;
use App\Http\Requests\v1\Auth\ResendVerifyRequest;
use App\Http\Requests\v1\Auth\ResetPasswordMailRequest;
use App\Http\Requests\v1\Auth\ResetPasswordRequest;
use App\Models\Customer;
use App\Models\PasswordResetToken;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Frontend API')]
#[Subgroup('Auth')]
class AuthController extends Controller
{
    #[Endpoint('Register')]
    #[Unauthenticated]
    public function register(RegisterRequest $request)
    {
        $validation = $request->validated();
        $validation['token'] = Str::random(64);
        $customer = Customer::create($validation);
        event(new Registered($customer));
        return $this->success(__('auth.register_success'));
    }

    #[Endpoint('Verify Email')]
    #[Unauthenticated]
    #[UrlParam('token', 'string')]
    public function verify(string $token)
    {
        $customer = Customer::where('token', $token)->first();
        if (!$customer) {
            return $this->error(__('auth.invalid_token'));
        }
        $customer->update(['email_verified_at' => now(), 'token' => null]);
        return $this->success();
    }

    #[Endpoint('Resend Verification Email')]
    #[Unauthenticated]
    public function resendVerificationEmail(ResendVerifyRequest $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return $this->error(__('auth.invalid_email'));
        }

        if ($customer['email_verified_at']) {
            return $this->error(__('auth.email_verified'));
        }
        $customer->sendEmailVerificationNotification();
        return $this->success();
    }

    #[Endpoint('Login')]
    #[Unauthenticated]
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        //Validate login
        $customer = Customer::where('email', $validated['email'])->first();
        clock($customer);
        if (!$customer || !Hash::check($validated['password'], $customer->password)) {
            return $this->error(__('auth.unauthorized'), 422);
        }
        //Generate token
        if (empty($customer->email_verified_at)) {
            return $this->error(__('auth.verify_email_notification'), 422);
        }
        $token = $customer->createToken('token', ['app'])->plainTextToken;
        $data = [
            'token_type' => 'Bearer',
            'accessToken' => $token,
            'userData' => $customer,
        ];
        return $this->success(data: $data);
    }

    #[Endpoint('Logout')]
    public function logout(Request $request)
    {
        //Delete token
        $request->user()->currentAccessToken()->delete();
        return $this->success(__('auth.logout'));
    }

    #[Endpoint('Send Reset Password Email')]
    #[Unauthenticated]
    public function resetPasswordMail(ResetPasswordMailRequest $request)
    {
        $validated = $request->validated();
        $customer = Customer::where('email', $validated['email'])->first();
        if (!$customer) {
            return $this->error(__('auth.invalid_email'));
        }
        $token = Str::random(64);
        PasswordResetToken::where('email', $validated['email'])->delete();
        DB::table('password_reset_tokens')->insert(['email' => $validated['email'], 'token' => $token, 'created_at' => now()]);
        $customer->sendPasswordResetNotification($token);
        return $this->success();
    }

    #[Endpoint('Reset Password')]
    #[Unauthenticated]
    public function resetPassword(ResetPasswordRequest $request, string $token)
    {
        $validated = $request->validated();
        $record = PasswordResetToken::where('token', $token)->whereBetween('created_at', [now()->subHour(), now()])->first();
        if (!$record) {
            return $this->error(__('auth.invalid_token'));
        }
        $record->update($validated);
        $record->delete();
        return $this->success();
    }
}
