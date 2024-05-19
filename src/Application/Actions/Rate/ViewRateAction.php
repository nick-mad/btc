<?php

declare(strict_types=1);

namespace App\Application\Actions\Rate;

use App\Application\Actions\Action;
use App\Domain\Rate\InvalidStatusException;
use App\Domain\Rate\Rate;
use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\HttpClient\ClientInterface;
use Psr\Log\LoggerInterface;

class ViewRateAction extends Action
{
    private ClientInterface $client;

    public function __construct(LoggerInterface $logger, ClientInterface $httpClient)
    {
        parent::__construct($logger);
        $this->client = $httpClient;
    }

    /**
     * @throws InvalidStatusException
     */
    protected function action(): Response
    {

        $rate = new Rate($this->client);
        $this->response->getBody()->write($rate->get());

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
