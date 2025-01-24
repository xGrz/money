<?php

namespace xGrz\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use xGrz\Money\Money;

class MoneyCast implements CastsAttributes
{

    public function __construct(public int $precision = 2)
    {
    }

    public function get(?Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return Money::fromDatabase($value, precision: $this->precision)->toNumber();
    }

    public function set(?Model $model, string $key, mixed $value, array $attributes): int
    {
        return ($value instanceof Money)
            ? $value->toDatabase()
            : Money::from($value, precision: $this->precision)->toDatabase();
    }
}
