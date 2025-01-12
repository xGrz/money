[![Laravel 10.x](https://github.com/xGrz/money/actions/workflows/laravel_10_test.yml/badge.svg)](https://github.com/xGrz/money/actions/workflows/laravel_10_test.yml)
[![Laravel 11.x](https://github.com/xGrz/money/actions/workflows/laravel_11_test.yml/badge.svg)](https://github.com/xGrz/money/actions/workflows/laravel_11_test.yml)

# Money formatter/calculator for Laravel

Money is small package for money format for Laravel.
It works with Laravel 10.x and Laravel 11.x.

## Installation

```
composer require xgrz/money
```

## Example usage

```
use xGrz\Money\Money;

try {
    $money = Money::from(1234.567, precision: 2)
        ->currency('€', beforeAmount: true)
        ->decimalSeparator('.')
        ->thousandsSeparator(',')
        ->shouldDisplayZero(false);

        echo $money->format(); // €1,234.57
    } catch (MoneyValidationException $e) {
        echo "Error: " . $e->getMessage();
    }
```
