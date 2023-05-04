<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(
        private User $user
    ) {}

    public function create(UserDTO $userDTO): User
    {
        $this->user->fill($userDTO->toArray());

        // encrypt password
        $this->user->password = Hash::make($userDTO->password);

        $this->user->save();
        return $this->user;
    }
}
