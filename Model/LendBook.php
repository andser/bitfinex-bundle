<?php

namespace Andser\BitfinexBundle\Model;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class LendBook
 */
class LendBook
{
    /**
     * Array of funding bids
     *
     * @var Lend[]
     */
    private $bids = [];

    /**
     * Array of funding offers
     *
     * @var Lend[]
     */
    private $asks = [];

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * LendBook constructor.
     */
    public function __construct()
    {
        $this->serializer = new Serializer([new ArrayDenormalizer(), new ObjectNormalizer()]);
    }

    /**
     * @return Lend[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    /**
     * @param Lend[] $bidsArray
     */
    public function setBids(array $bidsArray)
    {
        $this->bids = array_map(function ($lendArray) {
            return $this->serializer->denormalize($lendArray, Lend::class);
        }, $bidsArray);
    }

    /**
     * @return Lend[]
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
        $this->asks = array_map(function ($lendArray) {
            return $this->serializer->denormalize($lendArray, Lend::class);
        }, $asksArray);
    }
}
