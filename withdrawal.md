# Withdrawal

**Important!** To create a withdrawal request, you must first [receive a token for your user](authentication.md),
 as described in the [login section](authentication.md).
 When creating a request for withdrawal, you must transfer a personal token in the header of the http request.

### Create Withdrawal Request

**Endpoint**: https://payop.com/v1/withdrawals/create

**Content-Type**: application/json

**Headers**:
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Parameters:**

* **method** -- withdrawal method (numeric field):

     1 - Bank transfer.
     
     2 - International Cards.
     
     3 - Visa/MasterCard (UA cards).
     
     4 - Visa/MasterCard (RU cards).
     
     5 - Webmoney
     
     6 - Qiwi
     
     8 - Paypal.
     
     11 - Bitcoin.

* **type** -- commission type: 1 - take commission from wallet or 2 - take commission from money.
* **amount** -- withdraw amount.
* **currency** -- withdraw currency.
* **additionalData** - The content of the block depends on the method.

We present the fields in accordance with different values of the method field:

 1. Bank transfer

        transferType = "Personal" or "Business"
        beneficiary
        accountNumber
        bankName
        swiftCode
        address
        city
        country = 2 letters code of contry by ISO-3166
        zipCode
        direction
        
 2. International Cards

        cardNumber 
        expirationDate
        cardHolderName
        cardholderBirthDate
        firstAddressLine
        secondAddressLine
        city
        country 
        zipCode
        direction

3. Visa/MasterCard (UA cards).

        cardNumber - card number
        cardHolderName — card holder name
        
4. Visa/MasterCard (RU cards).

        cardNumber - card number
        cardHolderName — card holder name
        
5. Webmoney.

        direction - transfer description,
        walletNumber - wallet number
    
6. Qiwi.

        direction - transfer description,
        walletNumber - wallet number
        country - country
     
8. Paypal.

        direction - transfer description
        email - recipient email
     
11. Bitcoin.
     
        data -  bitcoin wallet


**Withdrawal Request Example**

Create request for withdraw to the Visa/MasterCard (RU cards).

```json
{
    "method": 4,
    "type": 1,
    "amount": 100,
    "currency":"RUB",
    "additionalData": {
        "cardNumber": "4444444444444444",
        "cardHolderName": "Ivan Ivanov"
    }
}
```

### List of payment methods available to the merchant

**Endpoint**: https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user

**Content-Type**: application/json

**Headers**:
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...
    
**Request example:**

```shell script
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user \
    -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...
```    

**Successful response example:**

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


### Mass withdrawal requests

Endpoint: https://payop.com/v1/withdrawals/create-mass

You can create multiple withdrawal requests at the same time.
The procedure is very similar to the previous one. You should use a slightly different endpoint and
put several data sets to create queries in one array.

```json
[
    {
        "method": 8,
        "type": 1,
        "amount": 34,
        "currency":"USD",
        "additionalData": {
            "direction": "direction one",
            "email": "my.email@address.com"
        }
    },
    {
        "method": 6,
        "type": 1,
        "amount": 35,
        "currency":"USD",
        "additionalData": {
            "direction": "direction two",
            "walletNumber": "my wallet number",
            "country": "USA"
        }
    }
]
```

### Merchants withdraw transactions

**Endpoint:**

`GET https://payop.com/v1/withdrawals/user-withdrawals`

**Headers:**
 
    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Request example:**

```shell script
curl -X GET \
  https://payop.com/v1/withdrawals/user-withdrawals \
    -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV...
```

**Successful response example:**

```json
{
    "data": {
            "identifier": "6173e7a5-aaee-4eb3-9851-943c0b5c47d1",
            "groupIdentifier": null,
            "userIdentifier": "10043",
            "type": 1,
            "currency": "RUB",
            "amount": 100,
            "transactionIdentifier": "05f6ce15-fb5b-4232-8a9c-acda3dc256a2",
            "status": 1,
            "method": 4,
            "createdAt": 1568112855,
            "updatedAt": null,
            "additionalData": {
                "cardNumber": "4444444444444444",
                "cardHolderName": "Ivan Ivanov"
            }
    },
    "status": 1
}
```
