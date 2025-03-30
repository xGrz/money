<?php


namespace XGrz\Money\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MoneyHelperMethodsTest extends TestCase
{

    public function test_is_negative_on_positive_value_return_false()
    {
        $this->assertFalse(money('120')->isNegative());
    }

    public function test_is_negative_on_negative_value_return_true()
    {
        $this->assertTrue(money('-120')->isNegative());
    }

    public function test_is_negative_on_zero_return_false()
    {
        $this->assertFalse(money(0)->isNegative());
    }

    public function test_is_negative_or_zero_on_positive_value_return_false()
    {
        $this->assertFalse(money('120')->isNegativeOrZero());
    }

    public function test_is_negative_or_zero_on_negative_value_return_true()
    {
        $this->assertTrue(money('-120')->isNegativeOrZero());
    }

    public function test_is_negative_or_zero_on_zero_return_true()
    {
        $this->assertTrue(money(0)->isNegativeOrZero());
    }

}
