<?php

declare(strict_types=1);

namespace Dagger\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final readonly class DefaultAddress
{
    public function __construct(
        public string $address,
    ) {
    }
}
