<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use App\Models\Company;

interface UserServiceInterface
{
    public function __construct(User $user);

    public function create(UserDTO $userDTO): ?User;

    public function setPassword(User $user, string $password): ?User;
}
