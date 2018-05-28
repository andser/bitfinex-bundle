<?php

namespace Andser\BitfinexBundle\Model;

use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class OrderBook
 */
class OrderBook
{
    /**
     * Array of bids
     *
     * @var Order[]
     */
    private $bids = [];

    /**
     * Array of asks
     *
     * @var Order[]
     */
    private $asks = [];

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * OrderBook constructor.
     */
    public function __construct()
    {
        $this->serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()]);
    }

    /**
     * @return Order[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    /**
     * @param Order[] $bidsArray
     */
    public function setBids(array $bidsArray)
    {
        $this->bids = array_map(function ($orderArray) {
            return $this->serializer->denormalize($orderArray, Order::class);
        }, $bidsArray);
    }

    /**
     * @return Order[]
     */
    public function getAsks(): array
    {
        return $this->asks;
    }

    /**
     * @param array $asksArray
     */
    public function setAsks(array $asksArray)
    {
        $this->asks = array_map(function ($orderArray) {
            return $this->serializer->denormalize($orderArray, Order::class);
        }, $asksArray);
    }
}
