<?php

namespace App\Http\Services;

use App\Events\UserLoginEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

interface AuthService
{
    function login($credential);
    function register($userData);
    function profile(): User;
}

class AuthServiceImpl implements AuthService
{

    public function login($credential)
    {
        if (Auth::attempt($credential)) {
            $user = Auth::user();
            UserLoginEvent::dispatch($user);
            return $user->createToken('token')->plainTextToken;
        }

        throw new BadRequestHttpException(message: 'email or password not found!');
    }

    public function register($userData)
    {

        $userData['password'] = Hash::make($userData['password']);
        $data = User::create($userData);
        return $data->toArray();
    }

    public function profile(): User
    {
        return Auth::user();
    }
}
