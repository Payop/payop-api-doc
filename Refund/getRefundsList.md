* [Back to contents](../Readme.md#contents)

# Merchant's refund transactions

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Refund statuses](#refund-statuses)

## Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).


![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/refunds/user-refunds
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

## Request example

```shell
curl -X GET \
  https://api.payop.com/v1/refunds/user-refunds \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer YOUR_JWT_TOKEN'
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
      "identifier": "38c92c25-33fd-4979-ac50-f73d7dbbf660",
      "status": 1,
      "type": 2,
      "userIdentifier": 10043,
      "amount": 10,
      "currency": "USD",
      "createdAt": 1568105638,
      "updatedAt": null,
      "sourceTransaction": {
        "identifier": "d839c714-7743-47cf-8f9d-73592597c6e1",
        "walletIdentifier": "2196",
        "type": 7,
        "amount": 100,
        "currency": "USD",
        "payAmount": 98.836951875227,
        "payCurrency": "EUR",
        "state": 2,
        "commission": [
          {
            "identifier": "3591",
            "type": 1,
            "percent": 7,
            "amount": 0.4441,
            "totalValue": 0,
            "strategy": 1,
            "merchantPercent": 0,
            "payerPercent": 0,
            "merchantAmount": 0,
            "payerAmount": 0,
            "transactionIdentifier": "999999-7743-47cf-8f9d-73592597c6e1"
          }
        ],
        "exchange": [],
        "createdAt": 1566996929,
        "updatedAt": null,
        "orderId": "2",
        "description": "Description",
        "productAmount": 100,
        "productCurrency": "USD",
        "pageDetails": {
          "bg": {},
          "text": {},
          "logoSrc": ""
        },
        "language": "en",
        "paymentMethodIdentifier": "374",
        "payerInformation": [
          {
            "type": 1,
            "value": "payer@example.com"
          },
          {
            "type": 3,
            "value": "Payer"
          }
        ],
        "geoInformation": {
          "ip": "31.223.121.234",
          "city": {
            "id": 703448,
            "lat": 44.45466,
            "lon": 23.2234,
            "name_en": "Test",
            "name_ru": "Тест"
          },
          "region": {
            "id": 703447,
            "iso": "FR-30",
            "name_en": "Test",
            "name_ru": "Тест"
          },
          "country": {
            "id": 222,
            "iso": "EG",
            "lat": 49,
            "lon": 32,
            "name_en": "Test",
            "name_ru": "Тест"
          }
        },
        "resultUrl": "https://example.com/result_url",
        "failUrl": "https://example.com/error_url",
        "pid": "498218467",
        "error": "",
        "application": {
          "identifier": "9999999-5a80-4f21-abda-3688e5d35554",
          "name": "Example Application",
          "info": "Example Application Description"
        }
      },
      "metadata": {
        "internal merchant id": "example"
      }
    }
  ],
  "status": 1
}
```

## Refund statuses

Status | Type     | Description                       |
-------|----------|-----------------------------------|
1      | new      | New refund                        |
2      | accepted | Accepted refund                   |
3, 4   | rejected | Rejected refund                   |