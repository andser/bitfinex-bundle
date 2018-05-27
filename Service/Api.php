<?php

namespace Andser\BitfinexBundle\Service;

use Andser\BitfinexBundle\Model\LendBook;
use Andser\BitfinexBundle\Model\Stats;
use Andser\BitfinexBundle\Model\Ticker;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class Api
 */
class Api
{
    /**
     * Guzzle client instance
     *
     * @var Client
     */
    protected $client;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Guzzle client options
     *
     * @var array
     */
    protected $options = [
        'base_uri' => 'https://api.bitfinex.com/v1/',
    ];

    /**
     * Api constructor.
     *
     * @param SerializerInterface  $serializer
     * @param ClientInterface|null $client
     */
    public function __construct(SerializerInterface $serializer, ClientInterface $client = null)
    {
        $this->client = $client ?: new Client($this->options);
        $this->serializer = $serializer;
    }

    /**
     * @param string $symbol
     *
     * @return Ticker
     */
    public function getTicker(string $symbol)
    {
        $response = $this->client->get(sprintf('pubticker/%s', $symbol));

        return $this->deserialize($response->getBody()->getContents(), Ticker::class);
    }

    /**
     * @param string $symbol
     *
     * @return object
     */
    public function getStats(string $symbol)
    {
        $response = $this->client->get(sprintf('stats/%s', $symbol));

        return $this->deserialize($response->getBody()->getContents(), Stats::class.'[]');
    }

    /**
     * @param string $currency
     * @param int    $limitBids
     * @param int    $limitAsks
     *
     * @return LendBook
     */
    public function getLendBook(string $currency, int $limitBids = 50, int $limitAsks = 50)
    {
        $query = http_build_query([
            'limit_bids' => $limitBids,
            'limit_asks' => $limitAsks,
        ]);
        $response = $this->client->get(sprintf('lendbook/%s?%s', $currency, $query));

        return $this->deserialize($response->getBody()->getContents(), LendBook::class);
    }

    /**
     * @param string $response
     * @param string $model
     *
     * @return object
     */
    protected function deserialize(string $response, string $model)
    {
        return $this->serializer->deserialize($response, $model, 'json');
    }
}
