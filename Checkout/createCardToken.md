* [Back to contents](../Readme.md#contents)

# Create bank card token

* [Intro](#intro)
* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response examples](#error-response-examples)

## Intro

PayOp provides you with an opportunity to 
independently initiate a money debit from payers’ 
payment cards and takes care of certification and compliance 
with the PCI-DSS standards. The standard declares a ban on 
the processing and storage of cardholder data (DDC) on the merchant's side.


----

**Note:** Access to card token generation is available only upon request. 
Please contact  [PayOp support](https://payop.com/en/contact-us) if you want to use card tokenization.

----

## Endpoint description

**Endpoint:**

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://payop.com/v1/payment-tools/card-token/create
```

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "invoiceIdentifier": "INVOICE_IDENTIFIER",
  "pan":"5555555555554444",
  "expirationDate":"12/28",
  "cvv":"123",
  "holderName":"HOLDER_NAME"
}
```

**Parameters:**

Parameter         | Type   | Description                           | Required |
------------------|--------|---------------------------------------|----------|
invoiceIdentifier | string | Invoice identifier                    | *        |
pan               | string | Bank card number                      | *        |
expirationDate    | string | Expiration date. Format mm/yy (12/20) | *        |
cvv               | string | CVV                                   | *        |
holderName        | string | Cardholder name                       | *        |

## Request example

```shell
curl -X POST \
  https://payop.com/v1/payment-tools/card-token/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "INVOICE_IDENTIFIER",
    "pan":"5555555555554444",
    "expirationDate":"12/28",
    "cvv":"123",
    "holderName":"HOLDER NAME"
}'
```

## Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "data":{
        "token":"1ay4YEZXF75BTraFw\/sPJ9iMJVOr\/bR\/UeEPp",
        "expired_at":1567765561
    },
    "status":1
}
```

## Error response examples

### Service Unavailable
![503](https://img.shields.io/badge/503-Service%20Unavailable-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 503 Service Unavailable
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "message": "Card tokenization is temporarily unavailable. Please contact support"
}
```
### Forbidden

![403](https://img.shields.io/badge/403-Forbidden-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 403 Forbidden
Content-Type: application/json
```

If you get this error, you should contact [PayOp support](https://payop.com/en/contact-us).

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "message": "Card tokenization is not available for your application. Please contact support"
}
```

### Unprocessable Entity

![422](https://img.shields.io/badge/403-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

If you get this error, you entered incorrect card details.

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": {
    "pan": [
      "Invalid card number."
    ],
    "expirationDate": [
      "This value is not valid."
    ]
  }
}
```