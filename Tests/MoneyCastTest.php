<?php


use PHPUnit\Framework\TestCase;
use xGrz\Money\Casts\MoneyCast;
use xGrz\Money\Money;

class MoneyCastTest extends TestCase
{

    public function test_convert_from_safe_database_integer()
    {
        $money = Money::fromDatabase('30030')->format();
        $this->assertEquals('300,30', $money);
    }

    public function test_convert_from_safe_database_null()
    {
        $money = Money::fromDatabase(null)->format();
        $this->assertEquals('0,00', $money);

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

    public function test_model_cast_money_set_null_when_not_nullable()
    {
        $cast = new MoneyCast(2, false);
        $this->assertEquals(0, $cast->set(null, 'key', null, []));
    }

    public function test_model_cast_money_set_null_when_is_nullable()
    {
        $cast = new MoneyCast(2, true);
        $this->assertNull($cast->set(null, 'key', null, []));
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


}
