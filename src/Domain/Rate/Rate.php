<?php

namespace App\Domain\Rate;

use App\Domain\HttpClient\ClientInterface;

class Rate
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws InvalidStatusException
     */
    public function get(): string
    {
        $rate = $this->getRateFomCoingecko();
        if ($rate) {
            return $rate;
        }

        $rate = $this->getRateFomCoincap();
        if ($rate) {
            return $rate;
        }

        throw new InvalidStatusException();
    }

    private function getRateFomCoingecko(): ?string
    {
        $url = 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=uah';
        $response = $this->client->get($url);
        $data = $response->getBody()->getContents();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }

        if (!empty($data['bitcoin']['uah'])) {
            return (string)$data['bitcoin']['uah'];
        }

        return null;
    }

    private function getRateFomCoincap(): ?string
    {
        $response = $this->client->get('https://api.coincap.io/v2/rates/bitcoin');
        $data = $response->getBody()->getContents();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        $btc = $data['data']['rateUsd'] ?? 0;


        $response = $this->client->get('https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5');
        $data = $response->getBody()->getContents();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        $uah = $data[1]['buy'] ?? 0;
        $btc_uah = ceil((int)$btc * $uah);
        if ($btc_uah > 0) {
            return (string)$btc_uah;
        }

        return null;
    }
}
