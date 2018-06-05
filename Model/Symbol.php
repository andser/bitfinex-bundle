<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Symbol
 */
class Symbol
{
    /**
     * The pair code
     *
     * @var string
     */
    private $pair;

    /**
     * Maximum number of significant digits for price in this pair
     *
     * @var int
     */
    private $pricePrecision;

    /**
     * Initial margin required to open a position in this pair
     *
     * @var float
     */
    private $initialMargin;

    /**
     * Minimal margin to maintain (in %)
     *
     * @var float
     */
    private $minimumMargin;

    /**
     * Maximum order size of the pair
     *
     * @var float
     */
    private $maximumOrderSize;

    /**
     * Minimum order size of the pair
     *
     * @var float
     */
    private $minimumOrderSize;

    /**
     * Expiration date for limited contracts/pairs
     *
     * @var string|null
     */
    private $expiration;

    /**
     * Margin trading enabled for this pair
     *
     * @var bool
     */
    private $margin;

    /**
     * @return string
     */
    public function getPair(): string
    {
        return $this->pair;
    }

    /**
     * @param string $pair
     *
     * @return Symbol
     */
    public function setPair(string $pair): Symbol
    {
        $this->pair = $pair;

        return $this;
    }

    /**
     * @return int
     */
    public function getPricePrecision(): int
    {
        return $this->pricePrecision;
    }

    /**
     * @param int $pricePrecision
     *
     * @return Symbol
     */
    public function setPricePrecision(int $pricePrecision): Symbol
    {
        $this->pricePrecision = $pricePrecision;

        return $this;
    }

    /**
     * @return float
     */
    public function getInitialMargin(): float
    {
        return $this->initialMargin;
    }

    /**
     * @param float $initialMargin
     *
     * @return Symbol
     */
    public function setInitialMargin(float $initialMargin): Symbol
    {
        $this->initialMargin = $initialMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinimumMargin(): float
    {
        return $this->minimumMargin;
    }

    /**
     * @param float $minimumMargin
     *
     * @return Symbol
     */
    public function setMinimumMargin(float $minimumMargin): Symbol
    {
        $this->minimumMargin = $minimumMargin;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaximumOrderSize(): float
    {
        return $this->maximumOrderSize;
    }

    /**
     * @param float $maximumOrderSize
     *
     * @return Symbol
     */
    public function setMaximumOrderSize(float $maximumOrderSize): Symbol
    {
        $this->maximumOrderSize = $maximumOrderSize;

        return $this;
    }

    /**
     * @return float
     */
    public function getMinimumOrderSize(): float
    {
        return $this->minimumOrderSize;
    }

    /**
     * @param float $minimumOrderSize
     *
     * @return Symbol
     */
    public function setMinimumOrderSize(float $minimumOrderSize): Symbol
    {
        $this->minimumOrderSize = $minimumOrderSize;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    /**
     * @param null|string $expiration
     *
     * @return Symbol
     */
    public function setExpiration(string $expiration): Symbol
    {
        $this->expiration = $expiration === 'NA' ? null : $expiration;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMargin(): bool
    {
        return $this->margin;
    }

    /**
     * @param bool $margin
     *
     * @return Symbol
     */
    public function setMargin(bool $margin): Symbol
    {
        $this->margin = $margin;

        return $this;
    }
}
