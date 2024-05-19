<?php

declare(strict_types=1);

namespace Tests\Domain\Email;

use App\Domain\Email\Email;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testGetters()
    {
        $email = 'test@ukr.net';
        $emailEntity = new Email($email);
        $this->assertEquals($email, $emailEntity->getEmail());
    }
}
