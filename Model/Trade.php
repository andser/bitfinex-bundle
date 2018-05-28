<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Trade
 */
class Trade
{
    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @var int
     */
    private $tid;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var string
     */
    private $type;

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
     * @return int
     */
    public function getTid(): int
    {
        return $this->tid;
    }

    /**
     * @param int $tid
     */
    public function setTid(int $tid)
    {
        $this->tid = $tid;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
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
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @param string $exchange
     */
    public function setExchange(string $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isSell()
    {
        return $this->type === 'sell';
    }

    /**
     * @return bool
     */
    public function isBuy()
    {
        return $this->type === 'buy';
    }
}
