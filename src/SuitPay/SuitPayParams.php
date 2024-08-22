<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay;

final class SuitPayParams
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly bool $isProduction = false,
    ) {
    }
}
