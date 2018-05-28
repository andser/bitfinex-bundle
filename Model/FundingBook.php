<?php

namespace Andser\BitfinexBundle\Model;

use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class FundingBook
 */
class FundingBook
{
    /**
     * Array of funding bids
     *
     * @var Fund[]
     */
    private $bids = [];

    /**
     * Array of funding offers
     *
     * @var Fund[]
     */
    private $asks = [];

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * FundingBook constructor.
     */
    public function __construct()
    {
        $this->serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()]);
    }

    /**
     * @return Fund[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    /**
     * @param Fund[] $bidsArray
     */
    public function setBids(array $bidsArray)
    {
        $this->bids = array_map(function ($fundArray) {
            return $this->serializer->denormalize($fundArray, Fund::class);
        }, $bidsArray);
    }

    /**
     * @return Fund[]
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
        $this->asks = array_map(function ($fundArray) {
            return $this->serializer->denormalize($fundArray, Fund::class);
        }, $asksArray);
    }
}
