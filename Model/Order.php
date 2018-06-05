<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Order
 */
class Order
{
    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return Order
     */
    public function setPrice(float $price): Order
    {
        $this->price = $price;

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
     * @return Order
     */
    public function setAmount(float $amount): Order
    {
        $this->amount = $amount;

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
     * @return Order
     */
    public function setTimestamp(string $timestamp): Order
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);

        return $this;
    }
}
