<?php

namespace Andser\BitfinexBundle\Service;

use Andser\BitfinexBundle\Client\HttpClient;
use Andser\BitfinexBundle\Model\Ticker;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TickerService
 */
class TickerService
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * TickerService constructor.
     *
     * @param HttpClient          $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct(HttpClient $httpClient, SerializerInterface $serializer)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $ticker
     *
     * @return Ticker
     */
    public function get(string $ticker)
    {
        return $this->serializer->deserialize($this->httpClient->get($ticker), Ticker::class, 'json');
    }
}
