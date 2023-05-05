<?php

namespace App\DTO;

class CompanyDTO
{
    public function __construct(
        public $title,
        public $phone,
        public $description
    ) {}

    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'phone'       => $this->phone,
            'description' => $this->description
        ];
    }
}
