<?php

namespace xGrz\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use xGrz\Money\Money;

class MoneyCast implements CastsAttributes
{

    public function __construct(public int $precision = 2, public bool $nullable = false)
    {
    }

    public function get(?Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return Money::fromDatabase($value, precision: $this->precision)->toNumber();
    }

    public function set(?Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value instanceof Money) return $value->toDatabase();
        if (is_null($value)) {
            if ($this->nullable) return null;
            $value = 0;
        }

        return Money::from($value, precision: $this->precision)->toDatabase();
    }
}
