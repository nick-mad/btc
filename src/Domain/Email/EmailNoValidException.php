<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\DomainException\DomainException;

class EmailNoValidException extends DomainException
{
    public $code = 422;
    public $message = 'Unprocessable Content';
}
