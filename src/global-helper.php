<?php

use XGrz\Money\Exceptions\MoneyValidationException;
use XGrz\Money\Money;


if (!function_exists('money')) {
    /**
     * @throws MoneyValidationException
     */
    function money(string|int|float|null $amount, bool $shouldDisplayZero = true, int $precision = 2): Money
    {
        return Money::from($amount, $shouldDisplayZero, $precision);
    }

}
