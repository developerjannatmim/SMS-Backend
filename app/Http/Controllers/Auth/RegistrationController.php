<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;

class RegistrationController extends Controller
{
    public function register()
    {
        $credentials = request(['name','email', 'password']);
        $credentials['password'] = bcrypt($credentials['password']);
        User::create($credentials);

        return response()->json('success');
    }
}
