<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;

interface UserServiceInterface
{
    public function create(UserDTO $userDTO): ?User;
}
