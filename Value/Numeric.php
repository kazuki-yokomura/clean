<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Foundation;
use Clean\Rule\Method;

/**
 * numeric value object.
 */
class Numeric extends Foundation
{
    const ROUND_TYPE_UP = 1;
    const ROUND_TYPE_DOWN = 2;
    const ROUND_TYPE_DEFAULT = 4;

    /** @var int minimam value */
    protected $minValue = 0;

    /** @var int maximam value */
    protected $maxValue = 0;

    /** @var int numeric precision */
    protected $precision = 2;

    /** @var int round type */
    protected $roundType = self::ROUND_TYPE_DEFAULT;

    /** @var int round type */
    protected $roundHalf = PHP_ROUND_HALF_UP;

    /**
     * set value.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        parent::__construct($value);

        if ($this->validate($this->round($value))) {
            $this->value = $this->round($value);
        }
    }

    /**
     * round numeric
     *
     * @param  mixed $value numeric
     * @return mixed
     */
    protected function round($value)
    {
        if (!Method::isNumeric($value)) {
            return $value;
        }

        if ($this->roundType === self::ROUND_TYPE_DEFAULT) {
            return round($value, $this->precision, $this->roundHalf);
        }
        if ($this->roundType === self::ROUND_TYPE_UP) {
            return $this->roundUp($value, $this->precision);
        }
        if ($this->roundType === self::ROUND_TYPE_DOWN) {
            return $this->roundDown($value, $this->precision);
        }
    }

    /**
     * round up
     *
     * @param  int|float $num       numeric
     * @param  int       $precision precision
     * @return float
     */
    protected function roundUp($num, int $precision):float
    {
        return round($num + 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_DOWN);
    }

    /**
     * round down
     *
     * @param  int|float $num       numeric
     * @param  int       $precision precision
     * @return float
     */
    protected function roundDown($num, int $precision):float
    {
        return round($num - 0.5 * pow(0.1, $precision), $precision, PHP_ROUND_HALF_UP);
    }

    /**
     * parse string this value
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->$value;
    }

    /**
     * return this value
     *
     * @return int|float
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * set default rule.
     */
    protected function setRule(): void
    {
        $this->rules
            ->add('invalid', [
                'final'   => true,
                'method'  => 'isNumeric',
                'message' => 'Not numeric value.'
            ])
            ->add('minValue', [
                'method'  => 'minValue',
                'vars'    => ['min' => $this->minValue],
                'message' => function ($value) {
                    $format = "Minimam value is %f. Can't input %f.";

                    return sprintf($format, $this->minValue, $value);
                }
            ])
            ->add('maxValue', [
                'method'  => 'maxValue',
                'vars'    => ['max' => $this->maxValue],
                'message' => function ($value) {
                    $format = "Maximam value is %f. Can't input %f.";

                    return sprintf($format, $this->maxValue, $value);
                }
            ]);
    }
}
