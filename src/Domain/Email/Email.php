<?php

namespace App\Domain\Email;

class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = strtolower(trim($email));
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
