<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Lend
 */
class Lend
{
    /**
     * Average rate of total funding received at fixed rates, ie past Flash Return Rate annualized
     *
     * @var float
     */
    private $rate;

    /**
     * Total amount of open margin funding in the given currency
     *
     * @var float
     */
    private $amountLent;

    /**
     * Total amount of open margin funding used in a margin position in the given currency
     *
     * @var float
     */
    private $amountUsed;

    /**
     * The timestamp at which this information was valid
     *
     * @var \DateTime
     */
    private $timestamp;

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
     * @return Lend
     */
    public function setRate(float $rate): Lend
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmountLent(): float
    {
        return $this->amountLent;
    }

    /**
     * @param float $amountLent
     *
     * @return Lend
     */
    public function setAmountLent(float $amountLent): Lend
    {
        $this->amountLent = $amountLent;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmountUsed(): float
    {
        return $this->amountUsed;
    }

    /**
     * @param float $amountUsed
     *
     * @return Lend
     */
    public function setAmountUsed(float $amountUsed): Lend
    {
        $this->amountUsed = $amountUsed;

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
     * @return Lend
     */
    public function setTimestamp(string $timestamp): Lend
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);

        return $this;
    }
}
