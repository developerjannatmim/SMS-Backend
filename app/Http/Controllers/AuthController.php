<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|max:191',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        }
        else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($user->email.'_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Register successful.'
            ]);
        }
    }

    public function login(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email|max:191',
        'password' => 'required|min:6',
      ]);
      if ($validator->fails()) {
        return response()->json([
          'validation_errors' => $validator->messages(),
        ]);
      } else {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
          return response()->json([
            'status' => 401,
            'message' => 'Invalid Credentials.'
          ]);
        } else {
          $token = $user->createToken($user->email . '_Token')->plainTextToken;
          $user_info = User::where('id', $user->id)->value('user_information');
          $gender = json_decode($user_info)->gender;
          $birthday = json_decode($user_info)->birthday;
          $phone = json_decode($user_info)->phone;
          $photo = json_decode($user_info)->photo;
          $blood_group = json_decode($user_info)->blood_group;
          $address = json_decode($user_info)->address;
          return response()->json([
            'status' => 200,
            'token' => $token,
            'auth_name' => $user->name,
            'auth_email' => $user->email,
            'gender' => $gender,
            'phone' => $phone,
            'address' => $address,
            'birthday' => $birthday,
            'photo' => $photo,
            'blood_group' => $blood_group,
            'message' => 'Logged In successful.'
          ]);
        }
      }
    }
}
