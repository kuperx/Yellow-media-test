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

        // encrypt password
        $this->user->password = Hash::make($userDTO->password);

        if (!$this->user->save()) {
            return null;
        }

        return $this->user;
    }

    public function createCompany(User $user, CompanyDTO $company): ?Company
    {
        $this->company->fill($company->toArray());

        if (!$user->companies()->save($this->company)) {
            return null;
        }

        return $this->company;
    }
}
