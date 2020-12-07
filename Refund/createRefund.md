 * [Back to contents](../Readme.md#contents)

# Create Refund

* [Endpoint description](#endpoint-description)
    * [Refund type](#refund-type)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

### Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

    POST https://payop.com/v1/refunds/create

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Parameters:**

Parameter             | Type   | Description                                                                                              | Required |
----------------------|--------|----------------------------------------------------------------------------------------------------------|----------|
transactionIdentifier | string | your checkout transaction identifier                                                                     | *        |
refundType            | number | [Refund type](#refund-type)                                                                               | *        |
amount                | number | refund amount in the currency of the parent transaction                                                  | *        |
metadata              | number | Arbitrary structure object to store any additional merchant data. Result JSON should be less than 800 kB | *        |

#### Refund type

There are 2 variants possible: 
    
    1 - all transaction amount
    2 - partial amount
          
## Request example

```json
{
    "transactionIdentifier": "d839c714-7743-47cf-8f9d-73592597c6e1",
    "refundType": 2,
    "amount": "10",
    "metadata": {
        "internal merchant id": "example"
    }
}
```

### Successful response example

In case of successful response you can get refund identifier from header `identifier`

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

Body
```json
{
    "data": "",
    "status": 1
}
```
