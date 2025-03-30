<?php

namespace XGrz\Money\Tests\Unit;

use PHPUnit\Framework\TestCase;
use XGrz\Money\Casts\MoneyCast;
use XGrz\Money\Money;

class MoneyCastTest extends TestCase
{

    public function test_convert_from_database_integer_value()
    {
        $money = Money::fromDatabase('30030')->format();
        $this->assertEquals('300,30', $money);
    }

    public function test_convert_from_database_null_value()
    {
        $money = Money::fromDatabase(null)->format();
        $this->assertEquals('0,00', $money);

    }

    public function test_convert_to_database_with_custom_precision()
    {
        $money = money('300,3011', precision: 3)->toDatabase();
        $this->assertEquals(300301, $money);
    }

    public function test_convert_from_database_with_custom_precision()
    {
        $money = Money::fromDatabase('300301', precision: 3)->format();
        $this->assertEquals('300,301', $money);
    }

    public function test_get_cast_money_with_default_precision()
    {
        $cast = new MoneyCast(2);
        $this->assertEquals('999.99', $cast->get(null, 'key', 99999, []));

    }

    public function test_set_cast_money_with_default_precision()
    {
        $cast = new MoneyCast(2);
        $this->assertEquals(99999, $cast->set(null, 'key', '999,99', []));
    }

    public function test_get_cast_money_with_custom_precision()
    {
        $cast = new MoneyCast(3);
        $this->assertEquals('99.999', $cast->get(null, 'key', 99999, []));

    }

    public function test_set_cast_money_with_custom_precision()
    {
        $cast = new MoneyCast(3);
        $this->assertEquals(999990, $cast->set(null, 'key', '999,99', []));
    }


    public function test_set_money_object_instead_value()
    {
        $cast = new MoneyCast();
        $amount = Money::from(100);
        $this->assertEquals(10000, $cast->set(null, 'key', $amount, []));
    }

    public function test_cast_money_object_zero_value_on_non_nullable()
    {
        $cast = new MoneyCast(nullable: false);
        $amount = Money::from(0);
        $this->assertEquals(0, $cast->set(null, 'key', $amount, []));
    }

    public function test_cast_money_object_zero_value_on_nullable()
    {
        $cast = new MoneyCast(nullable: true);
        $amount = Money::from(0);
        $this->assertNull($cast->set(null, 'key', $amount, []));
    }


    public function test_cast_money_set_null_value_when_not_nullable()
    {
        $cast = new MoneyCast(2, false);
        $this->assertEquals(0, $cast->set(null, 'key', null, []));
    }

    public function test_cast_model_set_null_value_when_nullable()
    {
        $cast = new MoneyCast(2, true);
        $this->assertNull($cast->set(null, 'key', null, []));
    }

    public function test_cast_storing_null_when_zero_value_on_nullable()
    {
        $cast = new MoneyCast(2, true);
        $this->assertNull($cast->set(null, 'key', 0, []));
    }

    public function test_cast_storing_zero_when_zero_value_on_non_nullable()
    {
        $cast = new MoneyCast(2, false);
        $this->assertEquals(0, $cast->set(null, 'key', 0, []));
    }


}
