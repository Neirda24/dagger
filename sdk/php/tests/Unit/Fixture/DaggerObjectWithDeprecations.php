<?php

declare(strict_types=1);

namespace Dagger\Tests\Unit\Fixture;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultAddress;
use Dagger\Attribute\Deprecated;
use Dagger\Attribute\Doc;
use Dagger\Container;
use Dagger\Json;
use Dagger\ValueObject;

#[DaggerObject]
final class DaggerObjectWithDeprecations
{
    #[DaggerFunction]
    #[Deprecated('Use newMethod instead')]
    public function oldMethod(): string
    {
        return 'old';
    }

    #[DaggerFunction]
    public function newMethod(): string
    {
        return 'new';
    }

    #[DaggerFunction]
    public function methodWithDeprecatedArg(
        #[Deprecated('Use name instead')]
        ?string $label = null,
        string $name = 'default',
    ): string {
        return $name ?? $label ?? '';
    }

    #[DaggerFunction]
    #[Deprecated]
    public function deprecatedWithoutReason(): void
    {
    }

    #[DaggerFunction]
    #[Deprecated('No longer needed')]
    #[Doc('Does something old')]
    public string $legacyField = 'old';

    #[DaggerFunction]
    public function containerWithDefaultAddress(
        #[DefaultAddress('alpine:latest')]
        Container $ctr,
    ): Container {
        return $ctr;
    }

    public static function getValueObjectEquivalent(): ValueObject\DaggerObject
    {
        return new ValueObject\DaggerObject(
            DaggerObjectWithDeprecations::class,
            '',
            [
                new ValueObject\DaggerField(
                    'legacyField',
                    'Does something old',
                    new ValueObject\Type('string'),
                    'No longer needed',
                ),
            ],
            [
                new ValueObject\DaggerFunction(
                    'oldMethod',
                    null,
                    [],
                    new ValueObject\Type('string'),
                    'Use newMethod instead',
                ),
                new ValueObject\DaggerFunction(
                    'newMethod',
                    null,
                    [],
                    new ValueObject\Type('string'),
                ),
                new ValueObject\DaggerFunction(
                    'methodWithDeprecatedArg',
                    null,
                    [
                        new ValueObject\Argument(
                            'label',
                            '',
                            new ValueObject\Type('string', true),
                            new Json('null'),
                            null,
                            null,
                            'Use name instead',
                        ),
                        new ValueObject\Argument(
                            'name',
                            '',
                            new ValueObject\Type('string'),
                            new Json('"default"'),
                        ),
                    ],
                    new ValueObject\Type('string'),
                ),
                new ValueObject\DaggerFunction(
                    'deprecatedWithoutReason',
                    null,
                    [],
                    new ValueObject\Type('void'),
                    '',
                ),
                new ValueObject\DaggerFunction(
                    'containerWithDefaultAddress',
                    null,
                    [
                        new ValueObject\Argument(
                            'ctr',
                            '',
                            new ValueObject\Type(Container::class),
                            null,
                            null,
                            null,
                            null,
                            'alpine:latest',
                        ),
                    ],
                    new ValueObject\Type(Container::class),
                ),
            ]
        );
    }
}
