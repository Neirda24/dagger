<?php

declare(strict_types=1);

namespace Dagger\Tests\Unit\ValueObject;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\Deprecated;
use Dagger\Attribute\Doc;
use Dagger\ValueObject\DaggerField;
use Dagger\ValueObject\Type;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

#[Group('unit')]
#[CoversClass(DaggerField::class)]
class DaggerFieldTest extends TestCase
{
    #[Test]
    #[DataProvider('provideReflectionProperties')]
    public function itBuildsFromReflectionProperty(
        DaggerField $expected,
        ReflectionProperty $reflectionProperty,
    ): void {
        $actual = DaggerField::fromReflection($reflectionProperty);

        self::assertEquals($expected, $actual);
    }

    /** @return Generator<array{0: DaggerField, 1: ReflectionProperty}> */
    public static function provideReflectionProperties(): Generator
    {
        $class = new #[DaggerObject] class () {
            #[DaggerFunction]
            public string $simple = '';

            #[DaggerFunction]
            #[Doc('A documented field')]
            public int $documented = 0;

            #[DaggerFunction]
            #[Deprecated('Use newField instead')]
            public string $legacyField = 'old';

            #[DaggerFunction]
            #[Deprecated]
            public string $deprecatedNoReason = '';

            #[DaggerFunction]
            #[Doc('With description')]
            #[Deprecated('Obsolete')]
            public string $documentedAndDeprecated = '';
        };

        yield 'simple field' => [
            new DaggerField('simple', null, new Type('string')),
            new ReflectionProperty($class, 'simple'),
        ];

        yield 'documented field' => [
            new DaggerField('documented', 'A documented field', new Type('int')),
            new ReflectionProperty($class, 'documented'),
        ];

        yield 'deprecated field with reason' => [
            new DaggerField('legacyField', null, new Type('string'), 'Use newField instead'),
            new ReflectionProperty($class, 'legacyField'),
        ];

        yield 'deprecated field without reason' => [
            new DaggerField('deprecatedNoReason', null, new Type('string'), ''),
            new ReflectionProperty($class, 'deprecatedNoReason'),
        ];

        yield 'documented and deprecated field' => [
            new DaggerField('documentedAndDeprecated', 'With description', new Type('string'), 'Obsolete'),
            new ReflectionProperty($class, 'documentedAndDeprecated'),
        ];
    }
}
