<?php

declare(strict_types=1);

namespace App\Traits;

use BackedEnum;

trait EnumHelper
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    /** Get an array of case values. */
    public static function values(): array
    {
        $cases = static::cases();

        return isset($cases[0]) && $cases[0] instanceof BackedEnum
            ? array_column($cases, 'value')
            : array_column($cases, 'name');
    }

    /** Get an associative array of [case name => case value]. */
    public static function options(): array
    {
        $cases = static::cases();

        return isset($cases[0]) && $cases[0] instanceof BackedEnum
            ? array_column($cases, 'value', 'name')
            : array_column($cases, 'name');
    }

    /** Get an array of values except one or more */
    public static function except(string|array $value): array
    {
        $values = static::values();

        if (is_string($value)) {
            $value = [$value];
        }

        return array_values(array_diff($values, $value));
    }

    /** Get an array of values only one or more */
    public static function only(string|array $value): array
    {
        $values = static::values();

        if (is_string($value)) {
            $value = [$value];
        }

        return array_values(array_intersect($values, $value));
    }

    /** Check if match */
    public function is(string | self $value): bool
    {
        if ($value instanceof self) {
            $value = $value->value;
        }

        return $this->value === $value;
    }

    /** Check if exists */
    public function in(array $value): bool
    {
        return in_array($this->value, $value);
    }
}
