<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {

    public function getAll()
    {
        return User::all();
    }

    public function findByEmailorUsername(string $email)
    {
        return User::where('email', $email)->orWhere('username', $email)->first();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function updateById($id, $attributes)
    {
        $user = User::findOrFail($id);
        $user->update($attributes);
        return $user;
    }

    public function updateByEmail($email,$attributes)
    {
        $user = User::findOrFail($id);
        $user->update($attributes);
        return $user;
    }

}
