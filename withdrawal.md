# Withdrawal

**Important!** To create a withdrawal request, you must first receive a token for your user, as described in the login section. When creating a request for withdrawal, you must transfer a personal token in the header of the http request.

### Create Withdrawal Request

**Endpoint**: https://payop.com/v1/withdrawals/create

**Content-Type**: application/json

**Headers**:
 
    Content-Type: application/json
    token: eyJ0eXAiO...

**Parameters:**

* **method** -- withdrawal method (numeric field):

     1 - Bank transfer.
     
     4 - Visa/MasterCard (RU cards).
     
     6 - Qiwi
     
     8 - Paypal.
     
     11 - Bitcoin.

* **type** -- commission type: 1 - take commission from wallet or 2 - take commission from money.
* **amount** -- withdraw amount.
* **currency** -- withdraw currency.
* **additionalData** - The content of the block depends on the method.

We present the fields in accordance with different values of the method field:

 1. Bank transfer

        transferType
        beneficiary
        accountNumber
        bankName
        swiftCode
        address
        city
        country
        zipCode
        direction
 

4. Visa/MasterCard (RU cards).

        cardNumber - card number
        cardHolderName — card holder name

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

### Mass withdrawal requests

Endpoint: https://payop.com/v1/withdrawals/create-mass

You can create multiple withdrawal requests at the same time. The procedure is very similar to the previous one. You should use a slightly different endpoint and put several data sets to create queries in one array.

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
