* [Back to contents](../Readme.md#contents)

# Create bank card token

* [Intro](#intro)
* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Errors and fail responses](#errors-and-fail-responses)

## Intro

Payop provides you the opportunity to independently 
initiate the debiting of money from payment cards of payers
and takes care of certification and compliance with PCS-DSS standards.
The standard declares a ban on the processing and storage of cardholder data (DDC) on the merchant's side.

----
**Note:** Access to card token generation is available only upon request.
 Please contact [Payop support](https://payop.com/en/contact-us) if you want use card tokenization. 

----

## Endpoint description

**Endpoint:**

    POST https://payop.com/v1/payment-tools/card-token/create

**Headers:**

    Content-Type: application/json

**Parameters:**

Parameter         | Type   | Description                           | Required |
------------------|--------|---------------------------------------|----------|
invoiceIdentifier | string | Invoice identifier                    | *        |
pan               | string | Bank card number                      | *        |
expirationDate    | string | Expiration date. Format mm/yy (12/20) | *        |
cvv               | string | CVV                                   | *        |
holderName        | string | Cardholder name                       | *        |

## Request example

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

## Successful response example

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

## Errors and fail responses

**503 Service Unavailable**
```json
{
    "message": "Card tokenization is temporarily unavailable. Please contact support"
}
```

**403 Forbidden**

In case of you get this error, you should contact [Payop support](https://payop.com/en/contact-us).

```json
{
    "message": "Card tokenization is not available for your application. Please contact support"
}
```
