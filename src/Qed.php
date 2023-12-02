<?php

declare(strict_types=1);

namespace Whateverthing\Qed;

/**
 * Provides a chainable wrapper for BCMath operations
 *
 * Usage:
 *
 *      $num = new Qed('123');
 *      $newNum = $num->add('27')->mul('4');
 *
 *      echo $newNum->value; // 600
 *
 */
class Qed
{
    /**
     * @param string $value     An initial value
     * @param int|null $scale   The scale to use for operations (default: null / zero)
     */
    public function __construct(public readonly string $value, public readonly ?int $scale = null) {}

    protected function calc(string $action, string|Qed $rightOperand): static
    {
        if (is_string($rightOperand)) {
            $rightOperand = new static($rightOperand);
        }

        return new Qed(
            $action($this->value, $rightOperand->value, $this->scale)
        );
    }

    public function add(string|Qed $rightOperand): static
    {
        return $this->calc('bcadd', $rightOperand);
    }

    public function sub(string|Qed $rightOperand): static
    {
        return $this->calc('bcsub', $rightOperand);
    }

    public function div(string|Qed $divisor): static
    {
        return $this->calc('bcdiv', $divisor);
    }

    public function mul(string|Qed $rightOperand): static
    {
        return $this->calc('bcmul', $rightOperand);
    }

    /**
     * Compares two numbers
     *
     * Left smaller than Right: returns new Qed('-1')
     * Left equal to Right: returns new Qed('0')
     * Left smaller than Right: returns new Qed('1')
     *
     * NOTE: May have some odd thoughts on whether -0 is less than 0
     *
     * @param string|Qed $rightOperand
     * @return static
     */
    public function comp(string|Qed $rightOperand): static
    {
        $rightOperand = is_string($rightOperand) ? new Qed($rightOperand) : $rightOperand;

        return new static((string) bccomp($this->value, $rightOperand->value, $this->scale));
    }

    public function mod(string|Qed $rightOperand): static
    {
        return $this->calc('bcmod', $rightOperand);
    }

    public function pow(string|Qed $exponent): static
    {
        return $this->calc('bcpow', $exponent);
    }

    /**
     * Equivalent of calling $num->pow($exponent)->mod($modulus)
     *
     * @param string|Qed $exponent
     * @param string|Qed $modulus
     * @return static
     */
    public function powmod(string|Qed $exponent, string|Qed $modulus): static
    {
        $exponent = is_string($exponent) ? new Qed($exponent) : $exponent;
        $modulus = is_string($modulus) ? new Qed($modulus) : $modulus;

        return new static(bcpowmod($this->value, $exponent->value, $modulus->value, $this->scale));
    }

    public function sqrt(): static
    {
        return new static(bcsqrt($this->value, $this->scale));
    }

    /**
     * Sets the scale used by the returned Qed object
     *
     * Or, when called with null, or with no parameters, returns the current scale as a Qed object
     *
     * NOTE: This *DOES NOT* call the bcscale() method to set system-wide scale value.
     *
     * @param int|string|Qed|null $scale    Set the scale factor; Null returns current scale factor as Qed object
     * @return static
     */
    public function scale(null|int|string|Qed $scale = null): static
    {
        if (!isset($scale)) {
            return new static((string) ($this->scale ?? bcscale()));
        }

        if (is_string($scale)) {
            $scale = (int) $scale;
        }

        if ($scale instanceof static) {
            $scale = (int) $scale->value;
        }

        return new static($this->value, $scale);
    }

    public function floor(): static
    {
        return new Qed(bcfloor($this->value), $this->scale);
    }

    public function ceil(): static
    {
        return new Qed(bcceil($this->value), $this->scale);
    }

    /**
     * Rounds a Qed number to the provided position, possibly using the provided PHP RoundHalf setting
     *
     * NOTE: Current polyfill implementation ignores PHP RoundHalf setting unless system bcround() is present
     *
     * @param int $precision    Digits of precision after the decimal point
     * @param int $roundHalf    Directionality of rounding processes
     * @return static
     */
    public function round(int $precision = 0, int $roundHalf = PHP_ROUND_HALF_UP): static
    {
        return new Qed(bcround($this->value, $precision, $roundHalf), $this->scale);
    }
}