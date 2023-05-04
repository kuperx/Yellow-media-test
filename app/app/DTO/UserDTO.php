<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public $firstName,
        public $lastName,
        public $email,
        public $phone,
        public $password
    ) {}

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'password'   => $this->password
        ];
    }
}
