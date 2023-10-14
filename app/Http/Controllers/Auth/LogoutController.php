<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
