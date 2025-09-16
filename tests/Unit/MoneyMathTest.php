<?php


namespace XGrz\Money\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MoneyMathTest extends TestCase
{

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

    public function test_discount_percent()
    {
        $money = money(85)->discount(25)->toNumber();

        $this->assertEquals(63.75, $money);
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

    public function test_divide_by_3()
    {
        $money = money(100)->divide(3)->toNumber();
        $this->assertEquals(33.33, $money);
    }

    public function test_divide_by_float_number()
    {
        $money = money(100, precision: 0)->divide(3.33)->toNumber();
        $this->assertEquals(30, $money);
    }

    public function test_add_money_object()
    {
        $amountToAdd = money(10);
        $money = money("36,59")->add($amountToAdd)->toNumber();
        $this->assertEquals(46.59, $money);
    }

    public function test_add_subtract_object()
    {
        $amountToAdd = money(10);
        $money = money("36,59")->minus($amountToAdd)->toNumber();
        $this->assertEquals(26.59, $money);
    }


}
