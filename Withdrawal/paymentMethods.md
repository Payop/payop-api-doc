* [Back to contents](../Readme.md#contents)

# Get available withdrawal methods

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response example](#error-response-example)

## Endpoint description

You can get a list of available withdrawal payout methods.

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint**:

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```
https://api.payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

## Request example:

```shell
curl -X GET \
  https://api.payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer YOUR_JWT_TOKEN'
```    

## Successful response example:

![HEADERS](https://img.shields.io/badge/200-ok-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "data": [
        {
            "type": 14,
            "name": "Advcash",
            "currencies": [
                "EUR",
                "USD"
            ]
        },
        {
            "type": 15,
            "name": "PayDo",
            "currencies": [
                "EUR",
                "USD"
            ]
        },
        {
            "type": 16,
            "name": "PerfectMoney",
            "currencies": [
                "EUR",
                "USD"
            ]
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

You can create a withdrawal request, using the type of withdrawal method from this page.
