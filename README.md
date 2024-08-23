# Suit Pay SDK para PHP

Este é um repositório que possui uma abstração a API do Suit Pay, facilitando a criação de PIX Copia e Cola como também
outros serviços oferecidos

## Installation

A forma mais recomendada de instalar este pacote é através do [composer](http://getcomposer.org/download/).

Para instalar, basta executar o comando abaixo

```bash
$ php composer.phar require vaironaegos/suitpay-sdk
```

ou adicionar esse linha

```
"astrotechlabs/suitpay-sdk": "^1.0"
```

na seção `require` do seu arquivo `composer.json`.

## Como Usar?

### Mínimo para usar

```php
$suitPay = new SuitPayGateway(new SuitPayParams(
    clientId: $_ENV['CLIENT_ID'],
    clientSecret: $_ENV['CLIENT_SECRET'],
    isProduction: $_ENV['IS_PRODUCTION']
));

return $suitPay->createPixCharge(new PixData(
    dueDate: date('Y-m-d'),
    amount: $order->amount,
    callbackUrl: $_ENV['PIX_WEBHOOK_URL'],
    clientData: new SuitPayCustomer(
        name: "Nome do Pagador",
        document: "CPF do Pagador",
        email: "E-mail do Pagador"
    )
));
```

Saída

```
[
    'txId' => '809d734b0d487097ad0c358d6ca78dd6',
    'copyPasteKey' => 'pix.example.com/qr/v2/9d36b84fc70b478fb95c12729b90ca25',
    'responsePayload' => [
        'txid' => '7978c0c97ea847e78e8849634473c1f1',
        'calendario' => [
            'criacao' => '2020-09-09T20:15:00.358Z'
            'expiracao' => 3600
        ],
        'revisao' => 0,
        ...........
    ],
    'qrCode' => 'imagem qrcode...'
]
```

## Contributing

Pull Request são bem-vindos. Para mudanças importantes, abra primeiro uma issue para discutir o que você gostaria de
mudar.

Certifique-se de atualizar os testes conforme apropriado.

## Licence

Este pacote é lançado sob a licença [MIT](https://choosealicense.com/licenses/mit/). Consulte o
pacote [LICENSE](./LICENSE) para obter detalhes.
