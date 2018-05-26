<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Ticker
 */
class Ticker
{
    /**
     * (bid + ask) / 2
     *
     * @var float
     */
    private $mid;

    /**
     * Innermost bid
     *
     * @var float
     */
    private $bid;

    /**
     * Innermost ask
     *
     * @var float
     */
    private $ask;

    /**
     * The price at which the last order executed
     *
     * @var float
     */
    private $lastPrice;

    /**
     * Lowest trade price of the last 24 hours
     *
     * @var float
     */
    private $low;

    /**
     * Highest trade price of the last 24 hours
     *
     * @var float
     */
    private $high;

    /**
     * Trading volume of the last 24 hours
     *
     * @var float
     */
    private $volume;

    /**
     * The timestamp at which this information was valid
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @param float $lastPrice
     *
     * @return $this
     */
    public function setLastPrice(float $lastPrice)
    {
        $this->lastPrice = $lastPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getLastPrice(): float
    {
        return $this->lastPrice;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     *
     * @return $this
     */
    public function setTimestamp(string $timestamp)
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);

        return $this;
    }

    /**
     * @return float
     */
    public function getMid(): float
    {
        return $this->mid;
    }

    /**
     * @param float $mid
     */
    public function setMid(float $mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return float
     */
    public function getBid(): float
    {
        return $this->bid;
    }

    /**
     * @param float $bid
     *
     * @return $this
     */
    public function setBid(float $bid)
    {
        $this->bid = $bid;

        return $this;
    }

    /**
     * @return float
     */
    public function getAsk(): float
    {
        return $this->ask;
    }

    /**
     * @param float $ask
     *
     * @return $this
     */
    public function setAsk(float $ask)
    {
        $this->ask = $ask;

        return $this;
    }

    /**
     * @return float
     */
    public function getLow(): float
    {
        return $this->low;
    }

    /**
     * @param float $low
     *
     * @return $this
     */
    public function setLow(float $low)
    {
        $this->low = $low;

        return $this;
    }

    /**
     * @return float
     */
    public function getHigh(): float
    {
        return $this->high;
    }

    /**
     * @param float $high
     *
     * @return $this
     */
    public function setHigh(float $high)
    {
        $this->high = $high;

        return $this;
    }

    /**
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
    }

    /**
     * @param float $volume
     *
     * @return $this
     */
    public function setVolume(float $volume)
    {
        $this->volume = $volume;

        return $this;
    }
}
