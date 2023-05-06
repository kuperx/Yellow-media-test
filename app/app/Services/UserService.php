<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\DTO\CompanyDTO;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(
        private User $user,
        private Company $company
    ) {}

    public function create(UserDTO $userDTO): ?User
    {
        $this->user->fill($userDTO->toArray());

        return $this->updatePassword($this->user, $userDTO->password);
    }

    public function createCompany(User $user, CompanyDTO $company): ?Company
    {
        $this->company->fill($company->toArray());

        if (!$user->companies()->save($this->company)) {
            return null;
        }

        return $this->company;
    }

    public function updatePassword(User $user, string $password): ?User
    {
        $user->password = Hash::make($password);

        if (!$user->save()) {
            return null;
        }

        return $user;
    }
}
