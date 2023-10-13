<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    public function login() {
        $credentials = $this->validate();

        if ( auth()->attempt( [ 'email' => $this->email, 'password' => $this->password ], $this->remember_me ) ) {

            return response()->json( [ 'error' => 'Unauthorized' ], 401 );

        } else {
            return response()->json( 'success' );
        }
    }
}
