<?php

namespace xGrz\Money;

use Illuminate\Support\Number;
use xGrz\Money\Exceptions\MoneyValidationException;

class Money
{
    private int|float $amount = 0;
    private bool $shouldDisplayZero = true;
    private string $decimalSeparator = ',';
    private string $thousandsSeparator = '';
    private ?string $currency = null;
    private bool $currencyBeforeAmount = false;
    private bool $currencySeparation = true;


    /**
     * @throws MoneyValidationException
     */
    private function __construct(int|float|string|null $amount, private int $precision = 2)
    {
        if (empty($amount)) $amount = 0;
        $amount = is_numeric($amount) ? $amount : $this->toNumeric($amount);
        if (!is_numeric($amount)) throw new MoneyValidationException('Amount is not a number');
        $this->amount = (int)round($amount * (10 ** $this->precision));
    }

    private function toNumeric($amount): string
    {
        return str($amount)
            ->replace(' ', '')
            ->replace(',', '.')
            ->toString();
    }

    public function decimalSeparator(string $separator): static
    {
        if ($separator === '@') throw new \ParseError('[@] cannot be a decimal separator.');
        $this->decimalSeparator = $separator;
        return $this;
    }

    public function thousandsSeparator(string $separator = ' '): static
    {
        if ($separator === '@') throw new \ParseError('[@] cannot be a thousands separator.');
        if ($separator === ' ') $separator = self::getSpacer();

        $this->thousandsSeparator = $separator;
        return $this;
    }

    public function currency(string|null $currency, bool $beforeAmount = false, bool $withSpace = true): static
    {
        $this->currency = $currency;
        $this->currencyBeforeAmount = $beforeAmount;
        $this->currencySeparation = $withSpace;
        return $this;
    }

    public function toNumber(): float|int
    {
        return round($this->amount / (10 ** $this->precision), $this->precision);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function format(): ?string
    {
        if (!$this->shouldDisplayZero && $this->amount == 0) return null;

        return str(Number::format($this->amount / (10 ** $this->precision), $this->precision))
            ->replace('.', '@')
            ->replace(',', $this->thousandsSeparator)
            ->replace('@', $this->decimalSeparator)
            ->when(
                $this->currency && $this->currencyBeforeAmount,
                fn($amount) => str($amount)
                    ->when($this->currencySeparation, fn($amount) => str($amount)->prepend(self::getSpacer()))
                    ->prepend($this->currency)
            )
            ->when(
                $this->currency && !$this->currencyBeforeAmount,
                fn($amount) => str($amount)
                    ->when($this->currencySeparation, fn($amount) => str($amount)->append(self::getSpacer()))
                    ->append($this->currency)
            )
            ->toString();
    }

    public function shouldDisplayZero(bool $shouldDisplayZero = true): static
    {
        $this->shouldDisplayZero = $shouldDisplayZero;
        return $this;
    }

    public function multiply(int|float $multiplier): static
    {
        $this->amount = round($this->amount * $multiplier);
        return $this;
    }

    public function addPercent(int|float $percent): static
    {
        $this->amount += round($this->amount / 100 * $percent);
        return $this;
    }

    protected function evaluate(int|float|string|Money $value): int
    {
        if ($value instanceof Money) $value = $value->toNumber();
        return round($value * (10 ** $this->precision), 0);
    }

    public function add(int|float|Money $value): static
    {
        $this->amount += $this->evaluate($value);
        return $this;
    }

    public function minus(int|float|Money $value): static
    {
        $this->amount -= $this->evaluate($value);
        return $this;
    }

    public function divide(int|float $by): static
    {
        if ($by == 0) return $this;
        $this->amount = round($this->amount / $by, $this->precision);
        return $this;
    }

    public function subtractPercent(int|float $percent): static
    {
        $this->amount = ($this->amount / (100 + $percent)) * 100;
        return $this;
    }

    public function __toString(): string
    {
        return $this->format() ?? '';
    }

    public static function isValid($amount): bool
    {
        try {
            new self($amount);
            return true;
        } catch (MoneyValidationException $e) {
        }
        return false;
    }

    public static function getSpacer(): string
    {
        return ' ';
    }

    public function toDatabase(): int
    {
        return $this->amount;
    }

    /**
     * @throws MoneyValidationException
     */
    public static function from(int|float|string|null $amount, bool $shouldDisplayZero = true, int $precision = 2): static
    {
        return (new self($amount, $precision))->shouldDisplayZero($shouldDisplayZero);
    }

    /**
     * @throws MoneyValidationException
     */
    public static function fromDatabase(int $amount, bool $shouldDisplayZero = true, int $precision = 2): static
    {
        return (new self($amount / (10 ** $precision), $precision))->shouldDisplayZero($shouldDisplayZero);
    }
}
