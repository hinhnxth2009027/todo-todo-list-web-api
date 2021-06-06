<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SessionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dataCheckLogin = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::attempt($dataCheckLogin)) {
            $checkTokenExist = SessionUser::where('user_id', \auth()->id())->first();
            if (empty($checkTokenExist)) {
                $userSession = SessionUser::create([
                    'token' => Str::random(50),
                    'refresh_token' => Str::random(50),
                    'token_time' => date('Y-m-d H:i:s', strtotime('+30 day')),
                    'refresh_token_time' => date('Y-m-d H:i:s', strtotime('+100 day')),
                    'user_id'=> \auth()->id()
                ]);
            } else {
                $userSession = $checkTokenExist;
            }
            return response()->json([
                'code' => 200,
                'message'=>'đăng nhập thành công',
                'data' => $userSession
            ], 200);
        }
        else
        {
            return response()->json([
                'code' => 401,
                'message'=>'Email hoặc mật khẩu không đúng'
            ], 401);
        }
    }

    public function logout(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (!empty($checkTokenIsValid)){
            $checkTokenIsValid->delete();
        }
        return response()->json([
            'code'=>200,
            'message'=>'logout success'
        ],200);

    }



}
