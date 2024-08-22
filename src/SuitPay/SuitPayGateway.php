<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay;

use Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge\CreatePixChargeGateway;
use Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge\PixData;

final class SuitPayGateway
{
    public function __construct(
        private readonly SuitPayParams $params
    ) {
    }

    public function createPixCharge(PixData $pixData): array
    {
        $createPixChargeGateway = new CreatePixChargeGateway(
            clientId: $this->params->clientId,
            clientSecret: $this->params->clientSecret,
            isProduction: $this->params->isProduction
        );

        return $createPixChargeGateway->createCharge($pixData)->toArray();
    }
}
