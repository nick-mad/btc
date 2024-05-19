<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Email;

use App\Domain\Email\EmailRepository;
use App\Domain\Email\Email;
use App\Domain\Email\EmailExistException;
use PDO;
use Psr\Container\ContainerInterface;
use App\Application\Settings\SettingsInterface;

class InDbEmailRepository implements EmailRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function store(Email $email): Email
    {
        $statement = $this->connection->prepare('SELECT email FROM emails WHERE email = :email');
        $statement->bindValue(':email', $email->getEmail());
        $statement->execute();
        $rowsCount = $statement->rowCount();
        if ($rowsCount) {
            throw new EmailExistException();
        }

        $statement = $this->connection->prepare('INSERT INTO emails SET email = :email');
        $statement->bindValue(':email', $email->getEmail());
        $statement->execute();

        return $email;
    }

    public function findAll(): array
    {
        $emails = [];
        $statement = $this->connection->query('SELECT email FROM emails');
        $rowsCount = $statement->rowCount();
        if ($rowsCount) {
            $result = $statement->fetchAll();
            foreach ($result as $item) {
                $emails[] = new Email($item['email']);
            }
        }

        return $emails;
    }
}
