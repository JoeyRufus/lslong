<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller
{
    public function check(Request $request)
    {
        $data = $request->post('password');
        if ($data == '2b35d4b6bf212cf40a3da3ce79e3fee1') {
            session(['is_login' => 'Y']);
            return response()->json([
                'code' => '200',
                'msg' => '登录成功~',
            ]);
        } else {
            return response()->json([
                'code' => '500',
                'msg' => '登录信息错误~'
            ]);
        }
    }
}
