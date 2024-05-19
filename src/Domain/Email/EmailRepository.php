<?php

declare(strict_types=1);

namespace App\Domain\Email;

interface EmailRepository
{
    /**
     * @return Email[]
     */
    public function findAll(): array;

    /**
     * @param Email $email
     * @return Email
     * @throws EmailExistException
     */
    public function store(Email $email): Email;
}
