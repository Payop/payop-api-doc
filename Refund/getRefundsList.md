 * [Back to contents](../Readme.md#contents)

# Merchants refund transactions

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Refund statuses](#refund-statuses)

## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

    GET https://payop.com/v1/refunds/user-refunds

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

## Request example

```shell script
curl -X GET \
  https://payop.com/v1/refunds/user-refunds \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```

## Successful response example

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
                        "transactionIdentifier": "d839c714-7743-47cf-8f9d-73592597c6e1"
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
                    "ip": "31.223.231.161",
                    "city": {
                        "id": 703448,
                        "lat": 50.45466,
                        "lon": 30.5238,
                        "name_en": "Kiev",
                        "name_ru": "Киев"
                    },
                    "region": {
                        "id": 703447,
                        "iso": "UA-30",
                        "name_en": "Kyiv",
                        "name_ru": "Киев"
                    },
                    "country": {
                        "id": 222,
                        "iso": "UA",
                        "lat": 49,
                        "lon": 32,
                        "name_en": "Ukraine",
                        "name_ru": "Украина"
                    }
                },
                "resultUrl": "https://example.com/result_url",
                "failUrl": "https://example.com/error_url",
                "pid": "498218467",
                "error": "",
                "application": {
                    "identifier": "9027682f-5a80-4f21-abda-3688e5d35554",
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
