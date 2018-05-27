<?php

namespace Andser\BitfinexBundle\Model;

/**
 * Class Stats
 */
class Stats
{

    /**
     * Period covered in days
     *
     * @var int
     */
    private $period;

    /**
     * Volume
     *
     * @var float
     */
    private $volume;

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
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
    }

    /**
     * @param float $volume
     */
    public function setVolume(float $volume)
    {
        $this->volume = $volume;
    }
}
