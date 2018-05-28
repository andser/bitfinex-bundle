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
     */
    public function setRate(float $rate)
    {
        $this->rate = $rate;
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
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;
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
     */
    public function setPeriod(int $period)
    {
        $this->period = $period;
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
     */
    public function setTimestamp(string $timestamp)
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);
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
     */
    public function setFlashReturnRate(bool $flashReturnRate)
    {
        $this->flashReturnRate = $flashReturnRate;
    }

    /**
     * Alias for serializer
     *
     * @param string $frr
     */
    public function setFrr(string $frr)
    {
        $this->setFlashReturnRate($frr === 'Yes');
    }
}
