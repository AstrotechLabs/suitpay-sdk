<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge;

use JsonSerializable;

final class CreatePixChargeOutput implements JsonSerializable
{
    public function __construct(
        public readonly string $gatewayId,
        public readonly string $copyPasteUrl,
        public readonly string $qrCode,
        public readonly array $details
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
