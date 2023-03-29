<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Support\Str;


interface AuthService
{
    function login($credential);
    function register($userData);
    function profile(): User;
    function forgot($email): array;
    function verifyAndChangePassword($credential): array;
}

class AuthServiceImpl implements AuthService
{

    public function login($credential)
    {
        if (Auth::attempt($credential)) {
            $user = Auth::user();
            return [
                $user->toArray(), $user->createToken('token')->plainTextToken,
            ];
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

    public function forgot($email): array
    {
        $user = User::whereEmail($email)->first();

        if (isset($user)) {
            $token = Password::createToken($user);
            return [
                'code' => $token
            ];
        }


        throw new BadRequestHttpException(message: 'email or password not found!');
    }

    public function verifyAndChangePassword($credential): array
    {
        $user = User::whereEmail(Arr::get($credential, 'email'))->first();

        if (isset($user)) {
            /**
             * when user is exist then we need to check the token
             * if exist then we change old password with the new password
             */
            $isExistToken = Password::tokenExists($user, Arr::get($credential, 'token'));

            if ($isExistToken) {

                $user->update(
                    ['password' => Hash::make(Arr::get($credential, 'password'))]
                );

                return $user->toArray();
            }
        }

        throw new BadRequestHttpException(message: 'User not found!, please try again');

    }
}
