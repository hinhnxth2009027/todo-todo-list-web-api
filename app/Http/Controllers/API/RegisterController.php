<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $userCreate = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email
        ]);
        return response()->json([
            'code'=> 201,
            'message'=>'Tạo tài khoản thành công',
            'data'=>$userCreate
        ],201);
    }
}
