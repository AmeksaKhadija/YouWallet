<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegistrationResquest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegistrationResquest $request)
    {

        $newuser = $request->validated();
        $newuser['password'] = Hash::make($newuser['password']);
        $newuser['id_role'] = 1;

        $user = User::create($newuser);

        $success['token'] = $user->createToken('user', ['app:all'])->plainTextToken;
        $success['name'] = $user->first_name;
        $success['id_role'] = $user->id_role;
        $success['success'] = true;

        return response()->json($success, 200);
    }


    public function login(LoginRequest $request)
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $user1 = new User();

            $user1->mapper($user);

            $user1->tokens()->delete();

            $success['token'] = $user1->createToken(request()->userAgent())->plainTextToken;
            $success['name'] = $user->first_name;
            $success['success'] = true;

            return response()->json($success, 200);
        } else {
            return response()->json(['error' => 'Unauthorized', 401]);
        }
    }
}
