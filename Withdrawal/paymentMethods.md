 * [Back to contents](../Readme.md#contents)

# Payment methods supporting withdrawal.

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint**:

    GET https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user

**Headers**:
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...
    
## Request example:

```shell script
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiOiJKV...'
```    

## Successful response example:

```json
{
    "data": [
        {
            "identifier": 1000008,
            "type": 4,
            "name": "manual_visa_ru_cards",
            "title": "Visa/MasterCard (Russian cards)",
            "currencies": [
                "RUB"
            ]
        },
        {
            "identifier": 1000005,
            "type": 1,
            "name": "manual_bank_transfer",
            "title": "Bank Transfer",
            "currencies": [
                "CAD",
                "CHF",
                "DKK",
                "EUR",
                "GBP",
                "JPY",
                "NZD",
                "SEK",
                "USD"
            ]
        },
        {
            "identifier": 1000003,
            "type": 6,
            "name": "manual_qiwi",
            "title": "Qiwi",
            "currencies": [
                "RUB"
            ]
        }
    ],
    "status": 1
}
```