<?php

declare(strict_types=1);

namespace App\Application\Console;

use App\Application\Settings\SettingsInterface;
use App\Domain\Email\EmailRepository;
use App\Domain\HttpClient\ClientInterface;
use App\Domain\Rate\InvalidStatusException;
use App\Domain\Rate\Rate;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailsCommand extends Command
{
    private EmailRepository $emailRepository;
    private MailerInterface $mailer;
    private array $mailer_settings;

    private ClientInterface $client;

    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        EmailRepository $emailRepository,
        MailerInterface $mailer,
        ContainerInterface $container,
        ClientInterface $httpClient
    ) {
        parent::__construct();
        $this->logger = $logger;
        $this->emailRepository = $emailRepository;
        $this->mailer = $mailer;
        $settings = $container->get(SettingsInterface::class);
        $this->mailer_settings = $settings->get('mailer');
        $this->client = $httpClient;
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('send_emails');
        $this->setDescription('розсилка актуального курса');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rateService = new Rate($this->client);
        $rate = $rateService->get();
        $emails = $this->emailRepository->findAll();
        foreach ($emails as $email) {
            $this->sendEmail($email->getEmail(), $rate);
        }

        return 0;
    }

    /**
     * Відправити Email на електронну адресу з актуальним курсом
     * @param $email
     * @param $rate
     * @return void
     */
    private function sendEmail($email, $rate): void
    {
        $fromEmail = $this->mailer_settings['email'] ?? '';
        if ($fromEmail) {
            $emailMailer = (new Email())
                ->from($fromEmail)
                ->to($email)
                ->subject('Курс BTC до UAH')
                ->text('Курс BTC до UAH становить: ' . $rate);
            try {
                $this->mailer->send($emailMailer);
            } catch (\Exception | TransportExceptionInterface $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}
