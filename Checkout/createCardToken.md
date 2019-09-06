* [Create bank card token](#create-bank-card-token)
    * [URL for requests](#url-for-requests)
    * [Request example](#request-example)
    * [Successful response example](#successful-response-example)
    * [Errors and failed responses](#errors-and-failed-responses)

# Intro

The platform provides you with the opportunity to independently 
initiate the debiting of money from payment cards of payers
and takes care of certification and compliance with PCS-DSS standards.
The standard declares a ban on the processing and storage of cardholder data (DDC) on the merchant's side.

----
**Note:** Access to card token generation is available only upon request.
 Please contact Payop support if want use card tokenization. 

----

## Create bank card token

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/payment-tools/card-token/create`

**Parameters**

Parameter             |  Type   |                 Description              |  Required |
----------------------|------------------|---------------------------------|-----------| 
invoiceIdentifier     | string  | Invoice identifier                       |     *     |
pan                   | string  | Bank card number                         |     *     |
expirationDate        | string  | Expiration date. Format mm/yy (12/20)    |     *     |
cvv                   | string  | CVV                                      |     *     |
holderName            | string  | Cardholder name                          |     *     |


### Request example
{"invoiceIdentifier":"92db5ff8-d2fd-402f-9a57-ad6eb4c921fd","pan":"5555555555554444","expirationDate":"12/20","cvv":"123","holderName":"DMYTRO"}
```shell script
curl -X POST \
  https://payop.com/v1/payment-tools/card-token/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "e61dfa44-4987-400a-b58e-cd550aae9613",
    "pan":"5555555555554444",
    "expirationDate":"12/20",
    "cvv":"123",
    "holderName":"HOLDER"
}'
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
    "data":{
        "token":"1ay4YEZXF75BTraFw\/sPJ9iMJVOr\/bR\/UeEPp",
        "expired_at":1567765561
    },
    "status":1
}
```

### Errors and failed responses

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```

**503 Service Unavailable**
```json
{
    "message": "Card tokenization is temporarily unavailable. Please contact support"
}
```

**403 Not Found**

In case of you get this error, you should contact Payop support.

```json
{
    "message": "Card tokenization is not available for your application. Please contact support"
}
```

**404 Not Found**
```json
{
    "message": "Invoice not found"
}
```

**422 Unprocessable Entity**

```json
{
    "message": {
        "pan": [
            "Invalid card number."
        ],
        "cvv": [
            "This value is too long. It should have 4 characters or less."
        ]
    }
}
```
