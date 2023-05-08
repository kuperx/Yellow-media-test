<?php

namespace App\DTO;

class CompanyDTO
{
    public function __construct(
        public $title,
        public $phone,
        public $description
    ) {}

    public function getTitle()
    {
        return $this->title;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
