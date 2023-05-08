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

    public function create(UserDTO $userDTO): ?User
    {
        $this->user->fill($userDTO->toArray());

        return $this->setPassword($this->user, $userDTO->password);
    }

    public function setPassword(User $user, string $password): ?User
    {
        $user->password = Hash::make($password);

        if (!$user->save()) {
            return null;
        }

        return $user;
    }
}
