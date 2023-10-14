<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller {
    
    public function login() {
        $credentials = $this->validate();

        if ( auth()->attempt( [ 'email' => $this->email, 'password' => $this->password ], $this->remember_me ) ) {

            return response()->json( [ 'error' => 'Unauthorized' ], 401 );

        } else {
            return response()->json( 'success' );
        }
    }
}
