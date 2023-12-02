<?php

if (!function_exists('bcround')) {
    function bcround(string $num1, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): string
    {
        $symbol = substr($num1, 0, 1) === '-' ? '-' : '';
        $num1 = ltrim($num1, '-');

        $decimalPos = strpos($num1, '.');

        if (false === $decimalPos) {
            return $symbol . $num1;
        }

        $number = substr($num1, 0, $decimalPos);
        $decimal = substr($num1, $decimalPos + 1, $precision);
        $remainder = substr($num1, $decimalPos + $precision + 1, 1);

        if ($precision == 0) {
            if ((int) $remainder >= 5) {
                return $symbol . bcadd($number, '1');
            }

            return $symbol . ($number ?: '0');
        }

        if ((int) $remainder >= 5) {
            $decimal = bcadd($decimal, '1');
        }

        return $symbol . ($number ?: 0) . '.' . $decimal;
    }
}

if (!function_exists('bcceil')) {
    function bcceil(string $num): string
    {
        $decimalPos = strpos($num, '.');

        if (false === $decimalPos) {
            return $num;
        }

        $output = substr($num, 0, $decimalPos) ?: '0';

        if ($output == '-') {
            return '-0';
        }

        if (strpos($output, '-') === 0) {
            return $output;
        }

        $remainder = substr($num, $decimalPos);
        if (bccomp($remainder, '0', 100) === '1') {
            return $output;
        }

        return bcadd($output, '1');
    }
}

if (!function_exists('bcfloor')) {
    function bcfloor(string $num): string
    {
        $decimalPos = strpos($num, '.');

        if (false === $decimalPos) {
            return $num;
        }

        $output = substr($num, 0, $decimalPos) ?: '0';

        if ($output == '-') {
            return '-0';
        }

        return $output;
    }
}