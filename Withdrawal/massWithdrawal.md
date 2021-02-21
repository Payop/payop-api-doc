 * [Back to contents](../Readme.md#contents)

# Mass (batch) withdrawal requests

Only batch withdrawal request is currently supported by API. You can create **1** or more withdrawal using batch request. 

* [Endpoint description](#endpoint-description)
    * [Withdrawal method](#withdrawal-method)
    * [Commission type](#commission-type)
    * [Additional data](#additional-data)
* [Raw request data example](#withdrawal-raw-request-data-example)
* [Full request example](#full-request-example)
* [Successful response example](#successful-response-example)

## Endpoint description

**Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

**Endpoint**: 

    POST https://payop.com/v1/withdrawals/create-mass

**Headers**:

    Content-Type: application/json
    Authorization: Bearer eyJ0eXAiO...

**Parameters:**

Parameter      | Type        | Description                                                                                              | Required |
---------------|-------------|----------------------------------------------------------------------------------------------------------|----------|
method         | number      | [Withdrawal method](#withdrawal-method)                                                                  | *        |
type           | number      | [Commission type](#commission-type)                                                                      | *        |
amount         | number      | Withdrawal amount                                                                                        | *        |
currency       | string      | Withdrawal currency                                                                                      | *        |
additionalData | JSON object | [Additional data](#additional-data), depends on the selected method.                                     | *        |
metadata       | JSON object | Arbitrary structure object to store any additional merchant data. Result JSON should be less than 800 kB |          |

### Withdrawal method

Possible values:

     1 - Bank transfer.   
     2 - International Cards.
     3 - Visa/MasterCard (UA cards).
     4 - Visa/MasterCard (RU cards).
     5 - Webmoney
     6 - Qiwi
     8 - Paypal.
     11 - Bitcoin.

### Commission type

Possible values:

    1 - take commission from wallet.  
    2 - take commission from money.

### Additional data

Additional data must be a JSON object with structrure depending on the withdrawal method.
Here is the list of possible methods with appropriate structure descriptions (all fields are required unless other is indicated):

1. Bank transfer
 ```
     beneficiary - JSON object
        - account - Receiver's account (IBAN or local account number). [A-Za-z0-9]. Max. length: 34 
        - name - Receiver's name. [A-Za-z0-9]. Max. length: 34
        - country - Receiver's country of residence. ISO 3166-1 alpha-2 code
        - city - Receiver's city. [A-Za-z0-9]. Max. length: 34
        - state - State or province. [A-Za-z0-9]. Required for US, CA beneficiary. Max. length: 34. 
        - address - Receiver's address. [A-Za-z0-9]. Max. length: 34
        - zipCode - Receiver's zip code. Max. length: 34
        - registrationNumber - Optional field. Registration number of the receiver. [A-Za-z0-9]. Max. length: 34
     beneficiaryBank - JSON object
        - name - Name of the Beneficiary Bank. [A-Za-z0-9]. Max. length: 34 
        - bic - SWIFT code of the Beneficiary Bank. [A-Za-z0-9]. Max. length: 11
        - address - Bank address. [A-Za-z0-9]. Max. length: 68
        - city - Optional field. Bank city. [A-Za-z0-9]. Max. length: 34
        - zipCode - Optional field. Bank zip code. Max. length: 34 
     direction - Description. [A-Za-z0-9].
     afsk - Optional field. Required only for IN transfers. 
     routingNumber - Optional field. Required only for US, CA transfers. 
     purposeCode - Optional field. Required only for AE transfers. 
```    
* For **CA** transfers **routingNumber** takes the format *0XXXYYYYY* and is made up of:
  * a leading 0
  * the 3 digit Bank Code (XXX)
  * the 5 digit Branch Code (YYYYY)

2 - International Cards:
```
     cardNumber - Example: 5555555555554444 
     expirationDate - format: MM/YYYY
     cardHolderName - [A-Za-z0-9]. Max. length: 50
     cardholderBirthDate - format: YYYY-MM-DD
     firstAddressLine - [A-Za-z0-9]. Max. length: 50
     secondAddressLine - [A-Za-z0-9]. Max. length: 50
     city - [A-Za-z0-9]. Max. length: 50. Example: Kyiv
     country - Country code. Must be in ISO 3166-1 alpha-2 format. Example: US
     zipCode - [A-Za-z0-9]. Max. length: 20
     direction - Description. [A-Za-z0-9].
```

3 - Visa/MasterCard (UA cards):
```
     cardNumber - card number. Example: 5555555555554444
     cardHolderName — [A-Za-z0-9]. Max. length: 50
     direction - Description. [A-Za-z0-9].
```
        
4 - Visa/MasterCard (RU cards):
```
     cardNumber - card number. Example: 5555555555554444
     cardHolderName — [A-Za-z0-9]. Max. length: 50
     direction - Description. [A-Za-z0-9].
```
        
5 - Webmoney:
```
     direction - Description. [A-Za-z0-9].
     walletNumber - wallet number. Example: Z432423894723947823
```
    
6 - Qiwi:
```
     direction - Description. [A-Za-z0-9].
     walletNumber - wallet number. Example: +7451684153189138
     country - Country code. Must be in ISO 3166-1 alpha-2 format. Example: RU
```
     
7 - Yandex money:
```
     direction - Description. [A-Za-z0-9].
     walletNumber - wallet number.
```

8 - Paypal:
```
     direction - Description. [A-Za-z0-9].
     email - recipient email
```
     
11 - Bitcoin:
```     
     direction - Description. [A-Za-z0-9].
     data -  bitcoin wallet
```

## Withdrawal Raw Request Data Example

**Important!** This data should be [encrypted](withdrawal.md#request-payload-encryptdecrypt) before sending a request.

**Raw data example 1:**
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

**Raw data example 2 - Create request for withdraw to the Visa/MasterCard (RU cards):**

```json
[
    {
        "method": 4,
        "type": 1,
        "amount": 100,
        "currency":"RUB",
        "additionalData": {
            "cardNumber": "4444444444444444",
            "cardHolderName": "Ivan Ivanov"
        },
        "metadata": {
            "internal merchant id": "example"
        }
    }
]
```

## Full request example

```shell script
curl -X POST \
  https://payop.com/v1/invoices/create \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer eyJ0eXAiO...' \
    -d '{"data":  "9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g="}'
```

## Successful Response Example

```json
{
    "data": [
        {
            "id": "eab40b05-805b-5dbb-8900-a634a9ecaf57",
            "metadata": {
                "description": "Test bank transfer payout"
            }
        },
        {
            "id": "19b60564-e75e-5c51-988d-9b7bf69ae240",
            "metadata": {
                "description": "Test international cards payout"
            }
        }
    ],
    "status": 1
}
```
