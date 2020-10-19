# Payop REST-like API Reference

The Payop API is organized around [REST](http://en.wikipedia.org/wiki/Representational_State_Transfer).

Payop API has predictable resource-oriented URLs, accepts [JSON](http://www.json.org/) request bodies,
 returns [JSON](http://www.json.org/) responses, and uses standard HTTP response codes.

Each request to Payop API should have **Content-Type HTTP header** with `application/json` value.

1. [API Response examples](#api-response-examples)
    * [Successful response](#successful-response)
    * [Failed responses](#failed-responses)
1. [Authentication](authentication.md)
1. [Checkout](Checkout/checkout.md)
    * [Integration types](Checkout/checkout.md#integration-types)
        * [Hosted page](Checkout/hostedPage.md)
        * [Server-To-Server](Checkout/serverToServer.md)
    * [IPN (instant payment notification)](Checkout/checkout.md#ipn)
        * [IPN Request example](Checkout/checkout.md#ipn-request-example)
    * [Card tokenization](Checkout/createCardToken.md)
    * [Check payment status](Checkout/checkTransactionStatus.md)
    * [Merchant payment methods](Checkout/getMerchantPaymentMethods.md)
1. [Invoice](Invoice/getInvoice.md)
    * [Create invoice](Invoice/createInvoice.md)
    * [Get invoice](Invoice/getInvoice.md)
1. [Transaction](Transaction/getTransaction.md)
    * [Create checkout transaction](Transaction/createCheckoutTransaction.md)
    * [Get transaction](Transaction/getTransaction.md)
1. [Withdrawal](withdrawal.md)
1. [Refund](refund.md)



## API Response examples

### Successful response

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
Authorization: Bearer eyJ0eXAiO...
```
Body
```json
{
    "data": {
        "id": "423131",
        "status": 1,
        "dateTime": {
            "createdAt": 1566543694,
            "updatedAt": null
        }
    },
    "status": 1
}
```
Each successful response this is JSON object with keys:   

Key       | Type                              | Description                                                        |
----------|-----------------------------------|--------------------------------------------------------------------| 
data      | **JSON object** OR **string**     | Response data. Arbitrary structure object.                         |
status    | **Number**                        | Don't care about it. It's required for internal technical purposes |

----
***Note:** "string" type for **data** this is not a feature, this is a bug.
  We are unable to change this in current api version, because of some integrations rely on it.
  But we will fix this in next API versions.*

----

### Failed responses

* **Invalid requests**
    
    Headers
    ```
    HTTP/1.1 415 Unsupported Media Type
    Content-Type: application/json
    ```
    Body
    ```json
    {
        "message": "Unsupported media type. Only json allowed"
    }
    ```

* **Validation fails**

    Headers
    ```
    HTTP/1.1 422 Unprocessable Entity
    Content-Type: application/json
    ```
    Body
    ```json
    {
        "message": {
            "email": [
                "This value should not be blank."
            ],
            "password": [
                "This value should not be blank."
            ]
        }
    }
    ```

