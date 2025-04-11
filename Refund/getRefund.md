
* [Back to contents](../Readme.md#contents)

# Get refund details

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Refund statuses](#refund-statuses)

## Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/refunds/user-refunds?query[identifier]={payopRefundId}
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

**Parameters:**

`payopRefundId` - identifier of refund.

## Request example

```shell
curl -X GET \
  https://api.payop.com/v1/refunds/user-refunds?query[identifier]=7ede204f-0d2a-55c4-9b84-8877720c9f6 \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer YOUR_JWT_TOKEN
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
  "data": [
    {
      "identifier": "8888888-7725-56d5-b75d-1d54382d1e46",
      "status": 2,
      "type": 2,
      "userIdentifier": 46288,
      "amount": 10,
      "currency": "USD",
      "createdAt": 1644402901,
      "updatedAt": 1644402905,
      "sourceTransaction": {
        "identifier": "999999-9d55-5fcc-aa56-35cc093e434e",
        "walletIdentifier": "38421",
        "type": 7,
        "amount": 440.65,
        "currency": "USD",
        "state": 2,
        "commission": [],
        "exchange": [],
        "createdAt": 1644334067,
        "updatedAt": 1644334070,
        "orderId": "59",
        "description": "",
        "productAmount": 455,
        "productCurrency": "USD",
        "pageDetails": [],
        "language": "en",
        "paymentMethodIdentifier": "381",
        "payerInformation": [
          {
            "type": 3,
            "value": "test"
          },
          {
            "type": 1,
            "value": "test@test.co"
          },
          {
            "type": 8,
            "value": "183.106.148.26"
          },
          {
            "type": 9,
            "value": "FR"
          }
        ],
        "geoInformation": {
          "ip": "183.106.148.26",
          "city": {
            "name": "Paris"
          },
          "region": {
            "iso": "UA-30",
            "name": "Paris City"
          },
          "country": {
            "iso": "FR",
            "name": "France"
          },
          "continent": {
            "code": "EU"
          }
        },
        "resultUrl": "https://example.com/",
        "failUrl": "https://example.com/",
        "pid": "1apAa331WAS",
        "error": "",
        "errorCode": "",
        "cardMetadata": {
          "bin": "4444444444444444",
          "lastDigits": "4444",
          "paymentSystem": "visa",
          "country": "US",
          "holderName": "HOLDER NAME"
        },
        "strategy": 11,
        "chosenAmount": 483.46,
        "chosenCurrency": "USD",
        "application": {
          "identifier": "9999999-27f3-4c4c-9b96-27bc463f3e69",
          "name": "TEST",
          "info": "TEST"
        }
      },
      "error": null,
      "metadata": {
        "internal merchant id": "example",
        "isAutoRefund": true
      }
    }
  ],
  "status": 1
}
```

## Error response example

![401](https://img.shields.io/badge/401-Unauthorized-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 401 Unauthorized
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Authorization token invalid"
}
```