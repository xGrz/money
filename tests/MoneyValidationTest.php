<?php


use PHPUnit\Framework\TestCase;
use XGrz\Money\Money;

class MoneyValidationTest extends TestCase
{
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


}
