<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateProfileController extends Controller
{
    public function updateProfile(Request $request, $id) {

        $user = User::where('id', $id)->first();

        if (!$user) {
            return Response::error("Tidak ada user");
        }

        $rules = [
            'email' => [
                'required',
                'email',
                Rule::unique((new User)->getTable())->ignore($id),
            ],
            'phone' => [
                'required',
                Rule::unique((new User)->getTable())->ignore($id),
            ],
        ];

        $validasi = Validator::make($request->all(), $rules);
        
        if ($validasi->fails()) {
            return Response::error($validasi->errors()->first());
        }

        $user->update($request->all());
        return Response::success($user);
        
    }
}
