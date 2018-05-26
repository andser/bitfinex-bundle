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
    public $mid;

    /**
     * Innermost bid
     *
     * @var float
     */
    public $bid;

    /**
     * Innermost ask
     *
     * @var float
     */
    public $ask;

    /**
     * The price at which the last order executed
     *
     * @var float
     */
    public $lastPrice;

    /**
     * Lowest trade price of the last 24 hours
     *
     * @var float
     */
    public $low;

    /**
     * Highest trade price of the last 24 hours
     *
     * @var float
     */
    public $high;

    /**
     * Trading volume of the last 24 hours
     *
     * @var float
     */
    public $volume;

    /**
     * The timestamp at which this information was valid
     *
     * @var \DateTime
     */
    public $time;
}
