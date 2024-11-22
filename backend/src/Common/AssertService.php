<?php

declare(strict_types=1);

namespace App\Common;

use Exception;

final readonly class AssertService
{
    public static function notEmpty(array $array, Exception $exception): void
    {
        if (count($array) > 0) {
            return;
        }

        throw $exception;
    }

    public static function arrayHasKey(string $key, array $array, Exception $exception): void
    {
        if (array_key_exists($key, $array)) {
            return;
        }

        throw $exception;
    }

    /** @psalm-assert array $array */
    public static function isArray(mixed $array, Exception $exception): void
    {
        if (is_array($array)) {
            return;
        }

        throw $exception;
    }

    /** @psalm-assert string $var */
    public static function isString(mixed $var, Exception $exception): void
    {
        if (is_string($var)) {
            return;
        }

        throw $exception;
    }

    public static function notEquals(mixed $firstVar, mixed $secondVar, Exception $exception): void
    {
        if ($firstVar !== $secondVar) {
            return;
        }

        throw $exception;
    }

    /** @psalm-assert !null $expression */
    public static function notNull(mixed $expression, Exception $exception): void
    {
        if ($expression !== null) {
            return;
        }

        throw $exception;
    }

    /**
     * @template T
     *
     * @psalm-assert T $expression
     *
     * @param class-string<T> $classString
     */
    public static function instanceOf(mixed $object, string $classString, Exception $exception): void
    {
        if ($object instanceof $classString) {
            return;
        }

        throw $exception;
    }
}
