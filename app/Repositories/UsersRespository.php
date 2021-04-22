<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserById($userId)
    {
        return $this->user
            ->where('id', $userId)
            ->first();
    }

    public function updateUserById($userId, $updateData)
    {
        if(isset($updateData['password']))
        {
            $updateData['password'] = Hash::make($updateData['password']);
        }

        return $this->getUserById($userId)->update($updateData);
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->user->create($data);
    }
}