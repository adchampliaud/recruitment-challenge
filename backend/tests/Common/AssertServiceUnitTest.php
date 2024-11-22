<?php

declare(strict_types=1);

namespace App\Tests\Common;

use App\Common\AssertService;
use DateTime;
use DomainException;
use PHPUnit\Framework\TestCase;
use stdClass;

/** @internal */
final class AssertServiceUnitTest extends TestCase
{
    /** @test */
    public function it_should_not_throw_exception_when_not_empty(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::notEmpty(['test'], new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_empty(): void
    {
        self::expectException(DomainException::class);
        AssertService::notEmpty([], new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_array_key_exist(): void
    {
        $key = 'testKey';
        self::expectNotToPerformAssertions();
        AssertService::arrayHasKey($key, [$key => 'test'], new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_array_key_not_exist(): void
    {
        self::expectException(DomainException::class);
        AssertService::arrayHasKey('keySearch', ['keyNotFound' => 'test'], new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_is_array(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::isArray([], new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_not_is_array(): void
    {
        self::expectException(DomainException::class);
        AssertService::isArray('test', new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_is_string(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::isString('string', new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_not_is_string(): void
    {
        self::expectException(DomainException::class);
        AssertService::isString(1, new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_not_equals(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::notEquals('test', 'different_test', new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_equals(): void
    {
        self::expectException(DomainException::class);
        AssertService::notEquals('test', 'test', new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_not_null(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::notNull('test', new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_is_null(): void
    {
        self::expectException(DomainException::class);
        AssertService::notNull(null, new DomainException());
    }

    /** @test */
    public function it_should_not_throw_exception_when_is_instance_of(): void
    {
        self::expectNotToPerformAssertions();
        AssertService::instanceOf(new DateTime(), DateTime::class, new DomainException());
    }

    /** @test */
    public function it_should_throw_exception_when_is_not_instance_of(): void
    {
        self::expectException(DomainException::class);
        AssertService::instanceOf(new stdClass(), DateTime::class, new DomainException());
    }
}
