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

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
