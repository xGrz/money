<?php

namespace xGrz\Tests;


use PHPUnit\Framework\TestCase;
use xGrz\Money\Exceptions\MoneyValidationException;
use xGrz\Money\Money;

class MoneyTest extends TestCase
{

    public function test_can_create_money_object()
    {
        $money = Money::from('300,30');

        $this->assertEquals('300,30', $money->format());
        $this->assertEquals(300.3, $money->toNumber());
    }

    public function test_can_create_money_object_with_helper()
    {
        $money = money('300,30');

        $this->assertEquals('300,30', $money->format());
        $this->assertEquals(300.3, $money->toNumber());
    }

    public function test_can_change_default_decimal_separator()
    {
        $money = money('300,30');

        $this->assertEquals('300,30', $money);
        $this->assertEquals('300-30', $money->decimalSeparator('-'));
    }

    public function test_can_change_default_thousands_separator()
    {
        $money = money(1123300.3);
        $spacer = Money::getSpacer();

        $this->assertEquals('1123300,30', $money->format());
        $this->assertEquals("1{$spacer}123{$spacer}300,30", $money->thousandsSeparator(' ')->format());
    }

    public function test_return_zero_values_in_default_configuration()
    {
        $money = money(0);

        $this->assertEquals('0,00', $money);
    }

    public function test_return_empty_string_when_display_zero_is_disabled()
    {
        $money = money(0, false);

        $this->assertEquals('', $money);
    }

    public function test_add_currency_before_value()
    {
        $money = money(1)->currency('PLN', true);
        $spacer = Money::getSpacer();

        $this->assertEquals("PLN{$spacer}1,00", $money->format());
    }

    public function test_add_currency_after_value()
    {
        $money = money(1)->currency('PLN', false);
        $spacer = Money::getSpacer();

        $this->assertEquals("1,00{$spacer}PLN", $money->format());
    }

    public function test_add_currency_before_value_without_space()
    {
        $money = money(1)->currency('PLN', true, false);

        $this->assertEquals('PLN1,00', $money->format());
    }

    public function test_add_currency_after_value_without_space()
    {
        $money = money(1)->currency('PLN', false, false);

        $this->assertEquals('1,00PLN', $money->format());
    }


    public function test_incorrect_amount_throws_exception()
    {
        $this->expectException(MoneyValidationException::class);
        $this->expectExceptionMessage('Amount [PLN200] is not a number');
        money('PLN 200');
    }


    public function test_convert_to_safe_database_integer()
    {
        $money = money('300,3011')->toDatabase();
        $this->assertEquals(30030, $money);
    }





}
