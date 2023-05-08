<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Database\Factories\UserFactory;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserFactory $userFactory
    ) {}

    public function create(UserDTO $userDTO): ?User
    {
        $user = $this->userFactory->createUserByDTO($userDTO);

        if (!$user->save()) {
            return null;
        }

        return $user;
    }
}
