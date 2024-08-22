<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge;

final class SuitPayCustomer
{
    public function __construct(
        public readonly string $name,
        public readonly string $document,
        public readonly string $email,
        public readonly string $phoneNumber = '',
    ) {
    }
}
