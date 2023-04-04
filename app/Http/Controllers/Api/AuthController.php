<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validasi->fails()) {
            return Response::error($validasi->errors()->all());
        } 

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return Response::error("User tidak ditemukan!");
        }

        if (!Hash::check($request->password, $user->password)) {
            return Response::error("Password anda salah!");
        }

        return Response::success($user);
    }

    function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users|numeric',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            return Response::error($validasi->errors()->first());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return Response::success($user);
        } else {
            return Response::error("Terjadi kesalahan");
        }
    }
}
