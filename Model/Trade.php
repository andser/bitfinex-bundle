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
     *
     * @return Trade
     */
    public function setTimestamp(string $timestamp): Trade
    {
        $this->timestamp = (new \DateTime())->setTimestamp($timestamp);

        return $this;
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
     *
     * @return Trade
     */
    public function setTid(int $tid): Trade
    {
        $this->tid = $tid;

        return $this;
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
     *
     * @return Trade
     */
    public function setPrice(float $price): Trade
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
     * @return Trade
     */
    public function setAmount(float $amount): Trade
    {
        $this->amount = $amount;

        return $this;
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
     *
     * @return Trade
     */
    public function setExchange(string $exchange): Trade
    {
        $this->exchange = $exchange;

        return $this;
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
     *
     * @return Trade
     */
    public function setType(string $type): Trade
    {
        $this->type = $type;

        return $this;
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
