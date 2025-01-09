<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $req){
        $user = User::create([
            'username'  => $req->username,
            'password'  => $req->password,
        ]);

        $token = $user->createToken($req->username);

        return [
            'user'  => $user,
            'token' => $token->plainTextToken
        ];
    }
    public function login(Request $req){
        $credentials = $req->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $req->username)->first();
        if($user){
            if(Hash::check($req->password,$user->password)){
                $token = $user->createToken($user->username)->plainTextToken;
                return response()->json([
                    'statusCode'    => 200,
                    'message'       => 'Login berhasil',
                    'token'         => $token
                ]);
            }else{
                return response()->json([
                    'statusCode'    => 404,
                    'message'       => 'Username atau password salah',
                ]);
            }
        }else{
            return response()->json([
                'statusCode'    => 404,
                'message'       => 'Akun tidak ditemukan',
            ]);
        }
    }
}
