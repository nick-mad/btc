<?php

declare(strict_types=1);

namespace App\Domain\Rate;

use App\Domain\DomainException\DomainException;

class InvalidStatusException extends DomainException
{
    public $code = 400;
    public $message = 'Invalid status value';
}
