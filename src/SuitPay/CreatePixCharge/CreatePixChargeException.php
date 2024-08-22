<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge;

use Exception;

final class CreatePixChargeException extends Exception
{
    private string $description;
    private array $requestPayload;
    private array $responsePayload;

    public function __construct(
        int $code,
        string $description,
        array $requestPayload,
        array $responsePayload
    ) {
        $this->code = $code;
        $this->description = $description;
        $this->requestPayload = $requestPayload;
        $this->responsePayload = $responsePayload;
        parent::__construct("$description - [{$code}]");
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRequestPayload(): array
    {
        return $this->requestPayload;
    }

    public function getResponsePayload(): array
    {
        return $this->responsePayload;
    }
}
