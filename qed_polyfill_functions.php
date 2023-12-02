<?php

if (!function_exists('bcround')) {
    function bcround(string $num1, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): string
    {
        $sign = substr($num1, 0, 1) === '-' ? '-' : '';
        $num1 = ltrim($num1, '-');

        $decimalPos = strpos($num1, '.');
        $remainderAdjustment = 1;

        if (false === $decimalPos && $precision >= 0) {
            return $sign . $num1;
        }

        // Special accommodation for negative precision
        if ($precision < 0) {
            $remainderAdjustment = 0;

            if (false === $decimalPos) {
                $decimalPos = strlen($num1);
            }
        }

        $number = substr($num1, 0, $decimalPos);
        $decimal = substr($num1, $decimalPos + 1, $precision);
        $remainder = substr($num1, $decimalPos + $precision + $remainderAdjustment, 1);

        if ($precision == 0) {
            if ((int) $remainder > 5) {
                return $sign . bcadd($number, '1');
            }

            if ((int) $remainder == 5) {
                if (PHP_ROUND_HALF_UP == $mode) {
                    return $sign . bcadd($number, '1');
                }

                $digit = substr($number, -1);
                $even = ((int) $digit) % 2 == 0;

                if (PHP_ROUND_HALF_EVEN == $mode && !$even ) {
                    return $sign . bcadd($number, '1');
                }

                if (PHP_ROUND_HALF_ODD == $mode && $even ) {
                    return $sign . bcadd($number, '1');
                }
            }

            return $sign . ($number ?: '0');
        }

        if ($precision < 0) {
            $slicedNumber = substr($number, 0, strlen($number) + $precision);
            $paddingLength = strlen($number);

            if ((int) $remainder >= 5) {
                $slicedNumber = bcadd($slicedNumber,'1');

                if ($paddingLength == abs($precision)) {
                    $paddingLength++;
                }
            }

            $paddedNumber = str_pad($slicedNumber, $paddingLength, '0', STR_PAD_RIGHT);

            // add a 0 so that a paddedNumber like '000' will come out as '0'
            return $sign . bcadd($paddedNumber, '0');
        }

        $length = strlen($decimal);

        if ((int) $remainder > 5) {
            $decimal = bcadd($decimal, '1');
        }

        if ((int) $remainder == 5) {
            if (PHP_ROUND_HALF_UP == $mode) {
                $decimal = bcadd($decimal, '1');
            }

            $digit = substr($decimal, -1);

            if ($digit == '') {
                $digit = substr($number, -1);
            }

            $even = ((int) $digit) % 2 == 0;

            if (PHP_ROUND_HALF_EVEN == $mode && !$even ) {
                $decimal = bcadd($decimal, '1');
            }

            if (PHP_ROUND_HALF_ODD == $mode && $even ) {
                $decimal = bcadd($decimal, '1');
            }
        }

        // re-add leading zeroes, if any
        $decimal = str_pad($decimal, $length, '0', STR_PAD_LEFT);

        return $sign . ($number ?: 0) . '.' . $decimal;
    }
}

if (!function_exists('bcceil')) {
    function bcceil(string $num): string
    {
        $sign = substr($num, 0, 1) === '-' ? '-' : '';
        $num = ltrim($num, '-');

        $decimalPos = strpos($num, '.');

        if (false === $decimalPos) {
            return $sign . $num;
        }

        $output = substr($num, 0, $decimalPos) ?: '0';

        if ($sign === '-') {
            return $sign . $output;
        }

        $remainder = substr($num, $decimalPos);
        if (bccomp($remainder, '0', 100) === '1') {
            return $sign . $output;
        }

        return bcadd($output, '1');
    }
}

if (!function_exists('bcfloor')) {
    function bcfloor(string $num): string
    {
        $sign = substr($num, 0, 1) === '-' ? '-' : '';
        $num = ltrim($num, '-');

        $decimalPos = strpos($num, '.');

        if (false === $decimalPos) {
            return $sign . $num;
        }

        $output = substr($num, 0, $decimalPos) ?: '0';

        return $sign . $output;
    }
}