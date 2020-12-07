
 * [Back to contents](../Readme.md#contents)

# IPN

* [Intro](#intro)
* [IPN Request description](#ipn-request-description)

## Intro

After finishing the payment and assigning a transaction
one of the [final statuses](getTransaction.md#transaction-states),
the payment gateway sends to the Merchant's server an Instant Payment Notification (IPN).

IPN will be send only in case of successfully created transaction.
Internal Payop transaction is created only after successful request to Acquirer.
IPN request will be send to **ipn url** which you setup for selected project.

For greater security, we highly recommend that you accept IPN only from our IP addresses:
* 52.49.204.201 
* 54.229.170.212

----
**Note:** Sometimes IPN that is related to a particular order may be sent multiple times. If you are receiving multiple IPNs with the same status and data (order ID, transaction ID etc.) that refer to the order, **please make sure the payment has been credited only once**.

So, what do you need to check: if you received IPNs with the same status and data (order ID, transaction ID etc.) related to a transaction, you have to accept only the first notification and ignore all others.

However, several IPN notifications should be accepted for a particular transaction if they carry different statuses. In this case, you don't need to ignore them, but update the current transaction state.

**For instance:** 

**case 1.** You received several IPNs with the same data and "success" state for the order ID 1111; 

**case 2.** You received IPN for the order ID 1111 with status 'failed'; later on you received the other IPN notification with the same transaction data for the order ID 1111 but with the other state "success".

**Solution:** 

**case 1.** You need to accept only the first notification and ignore the others;

**case 2.** You shouldn't ignore notification with a different state to let the transaction status become updated.

----
**Note:** Please note! A notification is sent to the merchant's server within 24 hours
 and until the Payop server, upon this request, receives the HTTP status code "200 OK".

----

## IPN Request description

**URL:**

    POST https://IPN-url-from-your-project
    
**Headers:**

    Content-Type: application/json

**Payload:**

```json
{
    "invoice": {
        "id": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
        "status": 1,
        "txid": "dca59ca5-be19-470d-9494-9b76944e0241",
        "metadata": {
            "internal merchant id": "example",
            "any other merchant data which were passed to invoice on create it": {
                "orderId": "test",
                "amount": 3,
                "customerId": 15487            
            }
        }
    }, 
    "transaction": {
        "id": "dca59ca5-be19-470d-9494-9b76944e0241",
        "state": 2,
        "order": {
            "id": "ANY_ORDER_ID"        
        },
        "error": {
            "message": "3DS authorization error or 3DS canceled by payer",
            "code": ""
        }
    }
}
```

**Payload fields:**

Parameter                 | Type   | Description               |
--------------------------|--------|---------------------------|
invoice.id                | string | Invoice identifier        |
invoice.status            | number | Invoice status            |
invoice.txid              | string | Transaction identifier    |
transaction.id            | string | Transaction identifier    |
transaction.state         | number | Transaction state         |
transaction.error.message | string | Transaction error message |
transaction.error.code    | string | Always empty string       |

Using transaction id (invoice.txid) you [can get transaction](getTransaction.md),
find there your order id and process this order in your system.

Invoice status is one of [Invoice statuses](../Invoice/getInvoice.md).
