<?php

namespace XGrz\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use XGrz\Money\Money;

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
        $dbValue = ($value instanceof Money)
            ? $value->toDatabase()
            : Money::from($value, precision: $this->precision)->toDatabase();

        return $this->formatDatabaseOutput($dbValue);
    }

    public function formatDatabaseOutput(int $value): ?int
    {
        if ($value !== 0) return $value;
        return $this->nullable ? null : 0;
    }

}
