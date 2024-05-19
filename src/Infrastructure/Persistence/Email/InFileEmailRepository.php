<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Email;

use App\Domain\Email\EmailRepository;
use App\Domain\Email\Email;
use App\Domain\Email\EmailExistException;
use Generator;

class InFileEmailRepository implements EmailRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../../../var/storage/emails.txt';
    }

    public function findAll(): array
    {
        $emails = [];
        foreach ($this->readFile() as $existEmail) {
            $emails[] = new Email($existEmail);
        }

        return $emails;
    }

    public function store(Email $email): Email
    {
        // Перевірити, чи існує вже email у файловій базі даних
        foreach ($this->readFile() as $existEmail) {
            if ($existEmail === $email->getEmail()) {
                throw new EmailExistException();
            }
        }

        file_put_contents($this->file, $email->getEmail() . PHP_EOL, FILE_APPEND);

        return $email;
    }

    private function readFile(): Generator
    {
        if (is_file($this->file)) {
            $handle = fopen($this->file, 'rb');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $email = trim($line);
                    if ($email) {
                        yield $email;
                    }
                }
                fclose($handle);
            }
        }
    }
}
