* [Back to contents](../Readme.md#contents)

# Get available withdrawal methods.

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

## Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint**:

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```
https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
Authorization: Bearer eyJ0eXAiO...
```

## Request example:

```shell
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```    

## Successful response example:

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
token: eyJ0eXAiOiJKV...
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "data": [
    {
      "identifier": 10010015,
      "type": 15,
      "name": "manual_paydo",
      "title": "PayDo",
      "currencies": [
        "EUR"
      ]
    },
    {
      "identifier": 1001005,
      "type": 1,
      "name": "manual_bank_transfer",
      "title": "Bank Transfer",
      "currencies": [
        "USD",
        "EUR",
        "AUD"
      ]
    },
    {
      "identifier": 1001006,
      "type": 8,
      "name": "manual_paypal",
      "title": "PayPal",
      "currencies": [
        "USD",
        "AUD"
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
token: eyJ0eXAiOiJKV...
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Authorization token invalid"
}
```

You can create a withdrawal request, using the type of withdrawal method from this page.