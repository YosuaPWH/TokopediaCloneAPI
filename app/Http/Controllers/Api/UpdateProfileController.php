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
            return Response::error("User tidak ditemukan");
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


    public function uploadImage(Request $request, $id) {
        $user = User::where('id', $id)->first();

        if ($user) {
            // Jika ada image
            $filename = "";
            if ($request->image) {
                $image = $request->image->getClientOriginalName();
                $image = str_replace(' ', '', $image);
                $image = date('Hs').rand(1,9999) . "_" . $image;
                $filename = $image;
                $request->image->storeAs('public/user', $image);
            } else {
                return Response::error("Image wajib dikirim");
            }

            $user->update([
                'image' => $filename
            ]);
            return Response::success($user);
        }


        return Response::error("User tidak ditemukan");
    }
}
