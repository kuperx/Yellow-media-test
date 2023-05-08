<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\DTO\CompanyDTO;
use App\Models\User;
use App\Models\Company;

interface UserServiceInterface
{
    public function __construct(User $user, Company $company);

    public function create(UserDTO $userDTO): ?User;

    public function createCompany(User $user, CompanyDTO $company): ?Company;

    public function setPassword(User $user, string $password): ?User;
}
