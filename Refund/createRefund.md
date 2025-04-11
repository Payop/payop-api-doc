* [Back to contents](../Readme.md#contents)

# Create Refund

* [Endpoint description](#endpoint-description)
    * [Refund type](#refund-type)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response example](#error-response-example)

### Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint:**

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://api.payop.com/v1/refunds/create
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

**Parameters:**

Parameter             | Type   | Description                                                                                              | Required |
----------------------|--------|----------------------------------------------------------------------------------------------------------|----------|
transactionIdentifier | string | Your checkout transaction identifier                                                                     | *        |
refundType            | number | [Refund type](#refund-type)                                                                               | *        |
amount                | number | Refund amount in the currency of the parent transaction                                                  | *        |
metadata              | number | Arbitrary structure object to store any additional merchant data. Result JSON should be less than 800 KB | *        |

#### Refund type

There are 2 variants possible:

```
1 - all transaction amount
2 - partial amount
``` 

## Request example

```shell
curl -X POST \
  https://api.payop.com/v1/refunds/create \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer YOUR_JWT_TOKEN' \
  -d '{
        "transactionIdentifier": "d839c714-7743-47cf-8f9d-73592597c6e1",
        "refundType": 2,
        "amount": 10,
        "metadata": {
            "internal merchant id": "example"
        }
      }'
```

### Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

In case of a successful response you can get a refund identifier from header `identifier`

```shell
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "data": "",
  "status": 1
}
```

## Error response example

![422](https://img.shields.io/badge/422-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Refund amount is bigger than transaction amount"
}
```

<br>

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

<br>

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

[Authentication](../Authentication/bearerAuthentication.md) required.