<?php

namespace xGrz\Tests;


use PHPUnit\Framework\TestCase;
use xGrz\Money\Casts\MoneyCast;
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

    public function test_add_percent()
    {
        $money = money(36.58)->addPercent(23)->toNumber();

        $this->assertEquals(44.99, $money);

    }

    public function test_subtract_percent()
    {
        $money = money(45)->subtractPercent(23)->toNumber();

        $this->assertEquals(36.59, $money);
    }

    public function test_multiply_amount()
    {
        $money = money(100)->multiply(2.5);

        $this->assertEquals(250, $money->toNumber());
    }

    public function test_multiply_with_add_percent_combined()
    {
        $money = money("36,59")->multiply(10)->addPercent(23);

        $this->assertEquals(450.06, $money->toNumber());
    }

    public function test_add_percent_with_multiply_combined()
    {
        $money = money("36,59")->addPercent(23)->multiply(10);

        $this->assertEquals(450.1, $money->toNumber());
    }

    public function test_incorrect_amount_throws_exception()
    {
        $this->expectException(MoneyValidationException::class);
        $this->expectExceptionMessage('Amount is not a number');
        money('PLN 200');
    }

    public function test_amount_validation_success()
    {
        $this->assertTrue(Money::isValid('200.12'));
        $this->assertTrue(Money::isValid('200.12'));
        $this->assertTrue(Money::isValid(' 200,12'));
        $this->assertTrue(Money::isValid('200,12 '));
        $this->assertTrue(Money::isValid(' 200,12 '));
        $this->assertTrue(Money::isValid(200.122));
    }


    public function test_amount_validation_fails()
    {
        $this->assertFalse(Money::isValid('A200.12'));
        $this->assertFalse(Money::isValid('200.12PLN'));
        $this->assertFalse(Money::isValid('$200,12'));
        $this->assertFalse(Money::isValid('200,12$'));
        $this->assertFalse(Money::isValid('20-12'));
    }

    public function test_convert_to_safe_database_integer()
    {
        $money = money('300,3011')->toDatabase();
        $this->assertEquals(30030, $money);
    }


    public function test_convert_from_safe_database_integer()
    {
        $money = Money::fromDatabase('30030')->format();
        $this->assertEquals('300,30', $money);
    }

    public function test_convert_to_safe_database_integer_with_custom_precision()
    {
        $money = money('300,3011', precision: 3)->toDatabase();
        $this->assertEquals(300301, $money);
    }


    public function test_convert_from_safe_database_integer_with_custom_precision()
    {
        $money = Money::fromDatabase('300301', precision: 3)->format();
        $this->assertEquals('300,301', $money);
    }

    public function test_model_cast_money_get_value_with_default_precision()
    {
        $cast = new MoneyCast(2);
        $this->assertEquals('999.99', $cast->get(null, 'key', 99999, []));

    }

    public function test_model_cast_money_set_value_with_default_precision()
    {
        $cast = new MoneyCast(2);
        $this->assertEquals(99999, $cast->set(null, 'key', '999,99', []));
    }

    public function test_model_cast_money_get_value_with_custom_precision()
    {
        $cast = new MoneyCast(3);
        $this->assertEquals('99.999', $cast->get(null, 'key', 99999, []));

    }

    public function test_model_cast_money_set_value_with_custom_precision()
    {
        $cast = new MoneyCast(3);
        $this->assertEquals(999990, $cast->set(null, 'key', '999,99', []));
    }

    public function test_add_value()
    {
        $money = money("36,59")->add(20.02)->toNumber();
        $this->assertEquals(56.61, $money);
    }

    public function test_subtract_value()
    {
        $money = money("36,59")->minus(20.02)->toNumber();
        $this->assertEquals(16.57, $money);
    }

    public function test_divide_by_3()
    {
        $money = money(100)->divide(3)->toNumber();
        $this->assertEquals(33.33, $money);
    }


}
