<?php

namespace PHLAK\Colorizer;

use PHLAK\Colorizer\Traits\Rangeable;
use InvalidArgumentException;
use OutOfRangeException;

class Colorize
{
    use Rangeable;

    /** @var int Minimum normalization value */
    protected $normalizeMin = 0;

    /** @var int Maximum normalization value */
    protected $normalizeMax = 255;

    /**
     * Colorize constructor, runs on object creation.
     *
     * @param int $normalizeMin Minimum normalized decimal value
     * @param int $normalizeMax Maximum normalized decimal value
     */
    public function __construct($normalizeMin = 0, $normalizeMax = 255)
    {
        foreach ([$normalizeMin, $normalizeMax] as $var) {
            if (! is_int($var)) {
                throw new InvalidArgumentException('Normalization values must be an integer');
            }
        }

        if (! $this->inRange([$normalizeMin, $normalizeMax], 0, 255)) {
            throw new OutOfRangeException('Normalization values must be between 0 and 255 (inclusive)');
        }

        $this->normalizeMin = $normalizeMin;
        $this->normalizeMax = $normalizeMax;
    }

    /**
     * Generate a new Color object from a string.
     *
     * @param string $string Input string
     *
     * @return Color Color object
     */
    public function text($string)
    {
        $hash = substr(hash('crc32', $string), 0, 6);

        $red = hexdec(substr($hash, 0, 2));
        $green = hexdec(substr($hash, 2, 2));
        $blue = hexdec(substr($hash, 4, 2));

        return (new Color($red, $green, $blue))->normalize($this->normalizeMin, $this->normalizeMax);
    }

    /**
     * Return a new static Colorize class with specified normalization values.
     *
     * @param int $value Minimum normalization value (0 - 255)
     * @param int $value Maximum normalization value (0 - 255)
     *
     * @return object This colorize object
     */
    public function normalize($min = 0, $max = 255)
    {
        return new static($min, $max);
    }
}
