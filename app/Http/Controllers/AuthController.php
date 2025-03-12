<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegistrationResquest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegistrationResquest $request)
    {

        $newuser = $request->validated();
        $newuser['password'] = Hash::make($newuser['password']);
        $newuser['id_role'] = 2;

        $user = User::create($newuser);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        $role = Role::find($user->id_role);

        $success['token'] = $user->createToken('user', ['app:all'])->plainTextToken;
        $success['name'] = $user->first_name;
        $success['id_role'] = $role->name;
        $success['compte'] = $wallet;
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
            $role = Role::find($user->id_role);
            $success['token'] = $user1->createToken(request()->userAgent())->plainTextToken;
            $success['name'] = $user->first_name;
            $success['id_role'] = $role->name;
            return response()->json($success, 200);
        } else {
            return response()->json(['error' => 'Unauthorized', 401]);
        }
    }



    public function logout()
    {
        try {
            Auth::user()->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception $e) {
            return ["message" => $e->getMessage()];
        }
    }

    public function infosUser()
    {
        $user = Auth::user();
        $wallet = $user->wallet->balance;
        return response()->json([
            'user' => $user,
            'wallet' => $wallet
        ]);
    }
}
