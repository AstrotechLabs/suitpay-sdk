<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge;

use JsonSerializable;

final class PixData implements JsonSerializable
{
    public function __construct(
        public readonly string $dueDate,
        public readonly float $amount,
        public readonly string $callbackUrl,
        public readonly SuitPayCustomer $clientData,
        public readonly string $requestNumber = ''
    ) {
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
