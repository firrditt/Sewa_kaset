<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $attributes)
    {
        User::create([
            'username' => $request -> username,
            'email' => $request -> email,
            'password' => Hash::make($request->password),

        ]);

        return ['message' => 'Register successfully'];
    }

    public function login(array $credentials)
    {

        $user = $this->userRepository->findByEmail($credentials['email']);
        if(!$user){
            return ['message' => 'Akun Tidak Ditemukan !'];
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function Logout()  {
        auth()->user()->tokens()->delete();
        return (object) [
            "statusCode"    => Response::HTTP_OK,
            "message"       => "Berhasil Logout!",
        ];
    }
}
