* [Get invoice info](#get-invoice-info)
    * [URL for requests](#url-for-requests)
    * [Request example](#request-example)
    * [Successful response example](#successful-response-example)
    * [Errors and failed responses](#errors-and-failed-responses)
    * [Invoice statuses](#invoice-statuses)

# Get invoice info

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/invoices/{{invoiceId}}`

**Parameters**

Parameter   |  Type  |  Required |
------------|--------|-----------| 
invoiceId   | string |     *     |

### Request example

```shell script
curl -X GET \
    https://payop.com/v1/invoices/81962ed0-a65c-4d1a-851b-b3dbf9750399 \
    -H 'Content-Type: application/json'
```

### Successful response example

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
        "resultUrl": "https://test.com/result",
        "failUrl": "https://test.com/fail",
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
        "updatedAt": null
    },
    "status": 1
}
```

### Errors and failed responses

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```

**404 Not Found**
```json
{
   "message": "Invoice not found"
}
```


### Invoice statuses

Status      |  Type    |  Description                |
------------|----------|-----------------------------| 
0           | new      |  Invoice can be paid        |
1           | paid     |  Invoice already paid       |
2           | overdue  |  Overdue invoice can't paid |
