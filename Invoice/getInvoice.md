* [Back to contents](../Readme.md#contents)

# Get invoice info

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Possible invoice statuses](#possible-invoice-statuses)

## Endpoint description

**Endpoint:**

    GET https://payop.com/v1/invoices/{{invoiceId}}

**Headers:**

    Content-Type: application/json

**Parameters:**

Parameter   |  Type  |  Required |
------------|--------|-----------|
invoiceId   | string |     *     |

## Request example

```shell script
curl -X GET \
    https://payop.com/v1/invoices/81962ed0-a65c-4d1a-851b-b3dbf9750399 \
    -H 'Content-Type: application/json'
```

## Successful response example

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
```

Body
```json
{
    "data": {
        "identifier": "81962ed0-a65c-4d1a-851b-b3dbf9750399",
        "status": 0,
        "type": 1,
        "applicationIdentifier": "3b60feb1-eeb8-4215-a494-2382427ffe88",
        "amount": 3,
        "currency": "RUB",
        "orderIdentifier": "test",
        "items": [
            {
                "id": "487",
                "name": "Item 1",
                "price": "0.8999999999999999"
            },
            {
                "id": "358",
                "name": "Item 2",
                "price": "2.0999999999999996"
            }
        ],
        "description": "",
        "resultUrl": "https://your.site/result",
        "failUrl": "https://your.site/fail",
        "language": "en",
        "payer": {
            "email": "test.user@payop.com",
            "name": "",
            "phone": "",
            "extraFields": []
        },
        "paymentMethod": {
            "identifier": 261,
            "fields": [
                {
                    "name": "email",
                    "type": "email",
                    "required": true
                },
                {
                    "name": "phone",
                    "type": "string",
                    "title": "QIWI e-wallet PHONE number (ex. +12128322000)",
                    "regexp": "\\+\\d{1,15}",
                    "required": true
                }
            ],
            "formType": "standard"
        },
        "isSeen": true,
        "customization": [],
        "createdAt": 1567754398,
        "updatedAt": null,
        "transactionIdentifier": "3333feb1-eeb8-4215-a494-238242788888",
        "metadata": {
            "internal merchant id": "example",
            "any other merchant data": {
                "orderId": "test",
                "amount": 3,
                "customerId": 15487            
            }
        }
    },
    "status": 1
}
```

## Possible invoice statuses

Status      |  Type    |  Description                        |
------------|----------|-------------------------------------|
0           | new      |  New invoice                        |
1           | accepted |  Invoice was paid successfully      |
4           | pending  |  Invoice pending                    |
5           | failed   |  Invoice failed                     |