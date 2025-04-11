* [Back to contents](../Readme.md#contents)

# Get invoice info

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response example](#error-response-example)
* [Possible invoice statuses](#possible-invoice-statuses)

## Endpoint description

**Endpoint:**

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/invoices/{invoiceID}
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

**Parameters:**

Parameter   |  Type  |  Required |
------------|--------|-----------|
invoiceID   | string |     *     |

## Request example

```shell
curl -X GET \
    https://api.payop.com/v1/invoices/81962ed0-a65c-4d1a-851b-b3dbf9750399 \
    -H 'Content-Type: application/json'
```

## Successful response example

![HEADERS](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

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

## Error response example

Error when the invoice is not found or does not exist

![422](https://img.shields.io/badge/422-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Invoice (Invoice_ID) not found"
}
```

## Possible invoice statuses
> **Important!** The following statuses are invoice statuses, **transaction** statuses are described in the following section [Get transaction info](../Checkout/getTransaction.md) (If you are using [IPN](../Checkout/ipn.md) you will get final **transaction** statuses)  

Status      |  Type    |  Description                        |
------------|----------|-------------------------------------|
0           | new      |  New invoice, the invoice was created, no actions were taken                        |
1           | paid |  Invoice was paid successfully      |
2           | overdue  |  Invoice was not paid on time. By default, the deadline for invoice payment is 24 hours.      |
4           | pending  |  Invoice pending, a transaction has been created on the basis of the invoice, which has not yet been paid and is expected to be paid                    |
5           | failed   |  Invoice failed, invoice has not been paid for technical reasons (the server does not respond, etc.) or financial reasons (insufficient funds on the account, etc.)                     |
