<?php

namespace App\Models;

use App\Models\Base\Customer as BaseCustomer;
use App\Traits\Searchable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends BaseCustomer
{
    use HasApiTokens, Searchable;

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'name',
        'gender',
        'birthday',
        'hkid',
        'tel',
        'email',
        'password',
        'address',
        'email_verified_at'
    ];

    public array $searchable = [
        'name', 'gender', 'email', 'address', 'tel', 'hkid', 'birthday'
    ];

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
