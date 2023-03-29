<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuthServiceController extends Controller
{
    public function __construct(private AuthService $auth)
    {}

    public function login(LoginRequest $request)
    {
        list($user,$token) = $this->auth->login($request->safe()->all());
        return $this->responseSuccess(data:['token' => $token, 'user' => $user], message: 'Login successfull');
    }


    public function register(RegisterRequest $request)
    {
        $user = $this->auth->register($request->safe()->all());
        return $this->responseSuccess(message: 'Register successfull', data: $user);
    }

    public function forgotPassword(Request $request)
    {

        $payload = $request->validate(['email' => 'required|email']);

        $result =  $this->auth->forgot(Arr::get($payload, 'email'));
        return $this->responseSuccess(data: $result);
    }

    public function getProfile()
    {
        $result = $this->auth->profile();
        return $this->responseSuccess(data: $result->toArray());
    }

    public function verifyPasswordCode(Request $request)
    {

        $payload = $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $result = $this->auth->verifyAndChangePassword($payload);
        return $this->responseSuccess(data: $result, message: 'success to change password');
    }
}
