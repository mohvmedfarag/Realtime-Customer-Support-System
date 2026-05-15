<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

Trait AuthUser
{
    public function getAuthUser(){
        return Auth::user();
    }
}
