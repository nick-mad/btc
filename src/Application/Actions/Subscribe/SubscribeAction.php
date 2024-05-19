<?php

declare(strict_types=1);

namespace App\Application\Actions\Subscribe;

use App\Application\Actions\Action;
use App\Domain\Email\Email;
use App\Domain\Email\EmailExistException;
use App\Domain\Email\EmailNoValidException;
use App\Domain\Email\EmailRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class SubscribeAction extends Action
{
    protected EmailRepository $emailRepository;

    public function __construct(LoggerInterface $logger, EmailRepository $emailRepository)
    {
        parent::__construct($logger);
        $this->emailRepository = $emailRepository;
    }

    /**
     * @throws EmailNoValidException
     * @throws EmailExistException
     */
    protected function action(): Response
    {
        $post = $this->getFormData();
        $email = $post['email'] ?: null;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailNoValidException();
        }

        $this->emailRepository->store(new Email($email));

        return $this->respondWithData('email added');
    }
}
