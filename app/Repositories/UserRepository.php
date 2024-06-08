<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository {

    public function getAllUsers()
    {
        return User::with('infouser')
        ->where('id', auth()->user()->id)
        ->first();
    }

    public function getUserbyId($id) {
        
        return User::findOrFail($id);
    }

    public function findByEmailOrUsername(string $emailOrUsername)
    {
        return User::where('email', $emailOrUsername)
                   ->first();
    }

    // public function create(array $data): ?User
    // {
    //     $data['password'] = Hash::make($data['password']);
    //     return User::create($data);
    // }

    public function registerAcc(string $username, string $email, string $password) : User {
        DB::beginTransaction();
        try {
            $account = User::create([
                'username'    => $username,
                'email'       => $email,
                'password'    => $password
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $account;
    }

    public function deleteAcc()
    {
        DB::beginTransaction();
        try {
            $user = $this->getAllUsers();
            $user->delete();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $user;
    }
}
