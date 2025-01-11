<?php

use xGrz\Money\Money;
use xGrz\Money\MoneyValidationException;

if (!function_exists('money')) {
    /**
     * @throws MoneyValidationException
     */
    function money(string|int|float|null $amount, bool $shouldDisplayZero = true, int $precision = 2): Money
    {
        return Money::from($amount, $shouldDisplayZero, $precision);
    }

}
