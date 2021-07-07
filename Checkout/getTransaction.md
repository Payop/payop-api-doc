 * [Back to content](../Readme.md)

# Get transaction info

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Transaction states](#transaction-states)
* [Transaction types](#transaction-types)
* [Payer information types](#payer-information-types)

## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

    GET https://payop.com/v1/transactions/{id}

**Headers:**
    
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiOiJKV...

**Parameters:**

Parameter | Type   | Required |
----------|--------|----------|
id        | string | *        |

## Request example

```shell script
curl -X GET \
  https://payop.com/v1/transactions/81962ed0-a65c-4d1a-851b-b3dbf9750399 \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```

## Successful response example

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
token: eyJ0eXAiOiJKV...
```

Body
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
        "application": {
            "identifier": "3",
            "name": "Project name",
            "info": "For sales"
        }
    }
}
```

## Transaction states

Status | Type     | Description                       |
-------|----------|-----------------------------------|
1      | new      | New transaction                   |
2      | accepted | Transaction was paid successfully |
4      | pending  | Transaction pending               |
3, 5   | failed   | Transaction failed                |


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
