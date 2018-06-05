<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Fund
 */
class Fund
{
    /**
     * Rate in % per 365 days
     *
     * @var float
     */
    private $rate;

    /**
     * @var float
     */
    private $amount;

    /**
     * Minimum period for the margin funding contract
     *
     * @var int
     */
    private $period;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * True if the offer is at Flash Return Rate, False if the offer is at fixed rate
     *
     * @var bool
     */
    private $flashReturnRate;

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return Fund
     */
    public function setRate(float $rate): Fund
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Fund
     */
    public function setAmount(float $amount): Fund
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

    /**
     * @param int $period
     *
     * @return Fund
     */
    public function setPeriod(int $period): Fund
    {
        $this->period = $period;

        return $this;
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
     * @return Fund
     */
    public function setTimestamp(string $timestamp): Fund
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasFlashReturnRate(): bool
    {
        return $this->flashReturnRate;
    }

    /**
     * @param bool $flashReturnRate
     *
     * @return Fund
     */
    public function setFlashReturnRate(bool $flashReturnRate): Fund
    {
        $this->flashReturnRate = $flashReturnRate;

        return $this;
    }

    /**
     * Alias for serializer
     *
     * @param string $frr
     *
     * @return Fund
     */
    public function setFrr(string $frr): Fund
    {
        $this->setFlashReturnRate($frr === 'Yes');

        return $this;
    }
}
