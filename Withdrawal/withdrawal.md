* [Back to contents](../Readme.md#contents)

# Withdrawal

**Important!** To create a withdrawal request, you need to create a certificate and to [encrypt request payload](#request-payload-encryptdecrypt).
  You can **download** your personal certificate in [your account](https://payop.com/en/profile/settings/certificate).
  
  ![Payop API Certificate page](../images/api-certificate.jpg)

## Withdrawal flow

**Important!** Withdrawal is asynchronous operation, i.e. you will not get final status immediately after a withdrawal creation.

 1. [Create withdrawal request](massWithdrawal.md)
 2. Save withdrawal id from response (`id` property from response object related to concrete withdrawal)
 3. [Check withdrawal status by getting withdrawal details](getWithdrawal.md)
  every 10 minutes (available statuses: 1, 4 - Pending; 2 - Accepted; 3 - Rejected).

----
**Note:** Your application have to change withdrawal status on your side only in case you get final status from Payop (2 or 3). 
As example, if you are getting 500 http error code (or something like this) when you make request to get withdrawal details,
you don't need to change withdrawal status. Just leave it as pending and repeat request later.

----

## Request payload encrypt/decrypt

When creating a request for withdrawal, you need to encrypt request payload with a personal certificate.
We are using popular encryption library to decrypt request payload - [Sodium](https://libsodium.gitbook.io/doc/).
In short, before sending a withdrawal request you have to make next steps:
 
* Encrypt request payload with [Sodium Sealed boxes](https://libsodium.gitbook.io/doc/public-key_cryptography/sealed_boxes#usage)
(
    [Python](https://libnacl.readthedocs.io/en/latest/topics/raw_sealed.html),
    [PHP](https://www.php.net/manual/en/function.sodium-crypto-box-seal.php)
).
* Encode encrypted binary string with Base64.
 
Below you can see PHP example, how to encrypt request payload before sending a withdrawal request:

```php
// Original certificate file that was downloaded from the site (payop.com). it's contains a binary string.
$certFilePath = '/project/x25519.pub';
// Certificate must be encoded as base64 string.
// You can use below example to encode it or use linux console command: cat /project/x25519.pub | base64 
$certificate = base64_encode(file_get_contents($certFilePath));
$data = [
    [
            'method' => 8,
            'type' => 1,
            'amount' => 34,
            'currency' => 'USD',
            'additionalData' => [
                'direction' => 'direction one',
                'email' => 'my.email@address.com'
            ]
    ]
];

$encryptedPayload = sodium_crypto_box_seal(json_encode($data), $certificate);
// $encryptedPayload - it's a binary string
$base64Payload = base64_encode($encryptedPayload);
// $base64Payload - looks like a next string 9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g=
```

See more examples [here](../examples/apiCertificates).
