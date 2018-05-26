<?php

namespace Andser\BitfinexBundle\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Client
 */
class HttpClient extends Client
{
    /**
     * Guzzle client instance
     *
     * @var Client
     */
    protected $client;

    /**
     * Guzzle client options
     *
     * @var array
     */
    protected $options = [
        'base_uri' => 'https://api.bitfinex.com/v1/',
    ];

    /**
     * HttpClient constructor.
     *
     * @param array                $options
     * @param ClientInterface|null $client
     */
    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $this->options = array_merge($options, $this->options);
        $this->client = $client ?: new Client($this->options);
    }

    /**
     * @param UriInterface|string $url
     * @param array               $params
     *
     * @return ResponseInterface
     */
    public function get($url, array $params = []): ResponseInterface
    {
        return $this->client->get($url, $params);
    }
}
