# Withdrawal

**Important!** To create a withdrawal request, you must first [receive a token for your user](authentication.md),
 as described in the [login section](authentication.md) and create a certificate to encrypt request payload.
 1. When creating a request for withdrawal, you must transfer a personal token in the header of the http request.
 2. When creating a request for withdrawal, you must encrypt request payload with a personal certificate.
 
#### Request payload encrypt/decrypt

We are using popular encryption library to decrypt request payload - [Sodium](https://libsodium.gitbook.io/doc/).
In short, before send withdrawal request you have to make next steps:
 
* Encrypt request payload with [Sodium Sealed boxes](https://libsodium.gitbook.io/doc/public-key_cryptography/sealed_boxes#usage)
* Encode encrypted binary string with Base64
 
Below you can see PHP example, how encrypt request payload before send withdrawal request:

```php
$certificate = file_get_contents('path_to_certificate');
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

### Mass (batch) withdrawal requests

Only batch withdrawal request available using API.

**Endpoint**: https://payop.com/v1/withdrawals/create-mass

**Content-Type**: application/json

**Headers**:
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Withdrawal RAW DATA Example (this data should be encrypted, see below)**

```json
[
    {
        "method": 8,
        "type": 1,
        "amount": 34,
        "currency":"USD",
        "additionalData": {
            "direction": "direction one",
            "email": "my.email@address.com"
        }
    },
    {
        "method": 6,
        "type": 1,
        "amount": 35,
        "currency":"USD",
        "additionalData": {
            "direction": "direction two",
            "walletNumber": "my wallet number",
            "country": "USA"
        }
    }
]
```

**Withdrawal Request with encrypted data Example**

```json
{"data":  "9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g="}
```

**Parameters:**

* **method** -- withdrawal method (numeric field):

     1 - Bank transfer.
     
     2 - International Cards.
     
     3 - Visa/MasterCard (UA cards).
     
     4 - Visa/MasterCard (RU cards).
     
     5 - Webmoney
     
     6 - Qiwi
     
     8 - Paypal.
     
     11 - Bitcoin.

* **type** -- commission type: 1 - take commission from wallet or 2 - take commission from money.
* **amount** -- withdraw amount.
* **currency** -- withdraw currency.
* **additionalData** -- The content of the block depends on the method.
* **metadata** [JSON object] -- Arbitrary structure object to store any additional merchant data. Result JSON should be less than 800 kB

We present the fields in accordance with different values of the method field:

 1. Bank transfer
 
        beneficiary - JSON object
            - account - Receiver's account (IBAN or local account number). [A-Za-z0-9]. Max. length: 34 
            - name - Receiver's name. [A-Za-z0-9]. Max. length: 34
            - country - Receiver's country of residence. ISO 3166-1 alpha-2 code
            - city - Receiver's city. [A-Za-z0-9]. Max. length: 34
            - address - Receiver's address. [A-Za-z0-9]. Max. length: 34
            - registrationNumber - Optional field. Registration number of the receiver. [A-Za-z0-9]. Max. length: 34
        beneficiaryBank - JSON object
            - name - Name of the Beneficiary Bank. [A-Za-z0-9]. Max. length: 34 
            - bic - SWIFT code of the Beneficiary Bank. [A-Za-z0-9]. Max. length: 11 
        direction - Description. [A-Za-z0-9].
        afsk - Optional field. Only for IN transfers. 
        routingNumber - Optional field. Only for US transfers. 
        
 2. International Cards

        cardNumber 
        expirationDate
        cardHolderName
        cardholderBirthDate
        firstAddressLine
        secondAddressLine
        city
        country 
        zipCode
        direction

3. Visa/MasterCard (UA cards).

        cardNumber - card number
        cardHolderName — card holder name
        
4. Visa/MasterCard (RU cards).

        cardNumber - card number
        cardHolderName — card holder name
        
5. Webmoney.

        direction - transfer description,
        walletNumber - wallet number
    
6. Qiwi.

        direction - transfer description,
        walletNumber - wallet number
        country - country
     
8. Paypal.

        direction - transfer description
        email - recipient email
     
11. Bitcoin.
     
        data -  bitcoin wallet


**Withdrawal RAW DATA Example (this data should be encrypted)**

Create request for withdraw to the Visa/MasterCard (RU cards).

```json
{
    "method": 4,
    "type": 1,
    "amount": 100,
    "currency":"RUB",
    "additionalData": {
        "cardNumber": "4444444444444444",
        "cardHolderName": "Ivan Ivanov"
    },
    "metadata": {
            "internal merchant id": "example"
    }
}
```

### List of payment methods available to the merchant

**Endpoint**: https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user

**Content-Type**: application/json

**Headers**:
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...
    
**Request example:**

```shell script
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user \
    -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...
```    

**Successful response example:**

```json
{
    "data": [
        {
            "identifier": 1000008,
            "type": 4,
            "name": "manual_visa_ru_cards",
            "title": "Visa/MasterCard (Russian cards)",
            "currencies": [
                "RUB"
            ]
        },
        {
            "identifier": 1000005,
            "type": 1,
            "name": "manual_bank_transfer",
            "title": "Bank Transfer",
            "currencies": [
                "CAD",
                "CHF",
                "DKK",
                "EUR",
                "GBP",
                "JPY",
                "NZD",
                "SEK",
                "USD"
            ]
        },
        {
            "identifier": 1000003,
            "type": 6,
            "name": "manual_qiwi",
            "title": "Qiwi",
            "currencies": [
                "RUB"
            ]
        }
    ],
    "status": 1
}
```

### Merchants withdraw transactions

**Endpoint:**

`GET https://payop.com/v1/withdrawals/user-withdrawals`

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Request example:**

```shell script
curl -X GET \
  https://payop.com/v1/withdrawals/user-withdrawals \
    -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...
```

**Successful response example:**

```json
{
    "data": [
        {
            "identifier": "6173e7a5-aaee-4eb3-9851-943c0b5c47d1",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 100,
            "transactionIdentifier": "05f6ce15-fb5b-4232-8a9c-acda3dc256a2",
            "status": 1,
            "method": 4,
            "createdAt": 1568112855,
            "updatedAt": null,
            "additionalData": {
                "cardNumber": "4444444444444444",
                "cardHolderName": "Ivan Ivanov"
            },
            "metadata": {
                "internal merchant id": "example"
            }
        }
    ],
    "status": 1
}
```
