* [Back to content](../Readme.md)

# Get transaction info

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response examples](#error-response-examples)
* [Transaction states](#transaction-states)
* [Transaction types](#transaction-types)
* [Payer information types](#payer-information-types)

## Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v2/transactions/{transactionID}
```

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

**Parameters:**

Parameter | Type   | Required |
----------|--------|----------|
id        | string | *        |

## Request example

```shell
curl -X GET \
  https://api.payop.com/v2/transactions/{id} \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```

## Successful response example

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "data": {
    "identifier": "81962ed0-a65c-4d1a-851b-b3dbf9750399",
    "walletIdentifier": "1607",
    "type": 7,
    "amount": 100,
    "currency": "USD",
    "state": 5,
    "error": "3DS authorization error or 3DS canceled by payer",
    "errorCode": "",
    "cardMetadata": {
      "bin": "555555",
      "lastDigits": "4444",
      "paymentSystem": "mastercard",
      "country": "BR",
      "holderName": "Snatiago Delcaptcha"
    },
    "commission": [],
    "exchange": [],
    "createdAt": 1567402240,
    "updatedAt": null,
    "orderId": "134666",
    "description": "",
    "productAmount": 100,
    "productCurrency": "USD",
    "pageDetails": [],
    "language": "en",
    "paymentMethodIdentifier": "381",
    "payerInformation": [
      {
        "type": 1,
        "value": "test.user@payop.com"
      },
      {
        "type": 3,
        "value": "test.user"
      }
    ],
    "geoInformation": {
      "ip": "127.0.0.1",
      "city": {
        "name": "Baguio City"
      },
      "region": {
        "iso": "PH-15",
        "name": "Cordillera"
      },
      "country": {
        "iso": "PH",
        "name": "Philippines"
      },
      "continent": {
        "code": "AS"
      }
    },
    "resultUrl": "https://your.site/result",
    "failUrl": "https://your.site/fail",
    "pid": "23234523525",
    "strategy": 3,
    "chosenAmount": 100,
    "chosenCurrency": "USD",
    "application": {
      "identifier": "3",
      "name": "Project name",
      "info": "For sales"
    }
  }
}
```

## Error response examples

### Authentication required

![401](https://img.shields.io/badge/401-Unauthorized-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 401 Unauthorized
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Full authentication is required to access this resource."
}
```

## Transaction not found

![422](https://img.shields.io/badge/422-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Transaction not found"
}
```


## Transaction states

Status | Type         | Description                                                                                                              |
-------|--------------|--------------------------------------------------------------------------------------------------------------------------|
1      | new          | New transaction, no actions were taken                                                                                   |
2      | accepted     | Transaction was paid successfully                                                                                        |
4      | pending      | Transaction pending, has not yet been paid and is expected to be paid                                                    |
3, 5  | failed       | Transaction failed, has not been paid for technical or financial reasons                                                 |
9     | pre-approved | Transaction has been submitted through the bank, however, we are still awaiting the funds to be credited to our account* |
15    | timeout      | Transaction timed out due to lack of final confirmation from the payer after initiation                                  |

> **Important!** *"Pre-approved" status may change to "Accepted" status or "Failed" status, in case funds are not received or the payer has canceled the transaction. While it is quite a rare scenario, in some cases it is still possible to cancel the payment on the payer's side, **please use "Pre-approved" for goods/service delivery at your own risk. Only the final "Accepted" status is guaranteed**


## Transaction types

Type | Type     | Description          |
-----|----------|----------------------|
7    | checkout | Checkout transaction |

## Payer information types

Type | Description  |
-----|--------------|
1    | Email        |
2    | Phone        |
3    | Name         |
4    | National id  |
5    | IBAN         |
6    | Address      |
7    | Zip code     |
8    | IP           |
9    | Country code |
