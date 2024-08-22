<?php

declare(strict_types=1);

namespace Astrotech\Primebets\Shared\Infra\SuitPay\CreatePixCharge;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Ramsey\Uuid\Uuid;

final class CreatePixChargeGateway
{
    private GuzzleClient $httpClient;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly bool $isProduction = false
    ) {
        $baseUrl = $this->isProduction
            ? 'https://ws.suitpay.app'
            : 'https://sandbox.ws.suitpay.app';

        $this->httpClient = new GuzzleClient([
            'base_uri' => $baseUrl,
            'timeout' => 10
        ]);
    }

    public function createCharge(PixData $pixData): CreatePixChargeOutput
    {
        try {
            $requestNumber = !empty($pixData->requestNumber)
                ? $pixData->requestNumber
                : Uuid::uuid4()->toString();

            $response = $this->httpClient->post("api/v1/gateway/request-qrcode", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'ci' => $this->clientId,
                    'cs' => $this->clientSecret,
                ],
                'json' => [
                    'requestNumber' => $requestNumber,
                    'dueDate' => $pixData->dueDate,
                    'amount' => $pixData->amount,
                    'callbackUrl' => $pixData->callbackUrl,
                    'client' => [
                        'name' => $pixData->clientData->name,
                        'document' => $pixData->clientData->document,
                        'email' => $pixData->clientData->email,
                    ]
                ]
            ]);
        } catch (ClientException $e) {
            $responsePayload = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw new CreatePixChargeException(
                1001,
                $responsePayload['message'],
                $pixData->toArray(),
                $responsePayload
            );
        }

        $responsePayload = json_decode($response->getBody()->getContents(), true);

        $options = new QROptions([
            'version' => QRCode::VERSION_AUTO,
            'imageTransparent' => false,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG
        ]);
        $qrCode = new QRCode($options);

        return new CreatePixChargeOutput(
            gatewayId: $responsePayload['idTransaction'],
            copyPasteUrl: $responsePayload['paymentCode'],
            qrCode: $qrCode->render($responsePayload['paymentCode']),
            details: $responsePayload
        );
    }
}
