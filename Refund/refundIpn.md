# IPN

After finish the accepting or rejecting processes the payment gateway sends to the Merchant's server an Instant Payment Notification (IPN).

IPN request will be sent to ipn url which you setup for selected project.

For greater security, we highly recommend that you accept IPN only from our IP address:
* 52.49.204.201
* 54.229.170.212

----
**Note:** Sometimes IPN that is related to a particular refund may be sent multiple times. If you are receiving multiple IPNs with the same status and data (refund id, source transaction data etc.) that refer to refund, **please make sure the payment has been processed only once at your side**.

So, what do you need to check: if you received IPNs with the same status and data (refund ID, source transactions data, status etc.) related to a refund, you have to accept only the first notification and ignore the others.

However, several IPN notifications should be accepted for a particular refund if they carry different statuses. In this case, you don't need to ignore them, but update the current refund state.

For instance:

case 1. You received several IPNs with the same data and "success" state for refund ID 1111;
case 2. You received IPN for refund ID 1111 with status 'failed'; later on you received the other IPN notification with the same transaction data for the refund ID 1111 but with the other state "success".

Solution:

case 1. You need to accept only the first notification and ignore the others;
case 2. You shouldn't ignore notification with a different state to let the transaction status become updated.

----
**Note:** Please note! A notification is sending to the merchant's server within 24 hours
and until the Payop server, upon this request, receives the HTTP status code "200 OK".

----

### IPN Request example

`Content-Type: application/json`

`POST https://url from your project`

```json
{
    "transaction": {
        "refundId": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
        "state": 1,
        "amount": 100,
        "currency": "USD",
        "metadata": {
          "key1": "Metadata information.",
          "key2": "Metadata information."
        },
        "error": {
            "message": "3DS authorization error or 3DS canceled by payer",
            "code": ""
        }
    },
    "sourceTransaction": {
        "id": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
        "state": 1
    }  
}
```

**Parameters**

Parameter                       |  Type   |                 Description     |
--------------------------------|---------|---------------------------------| 
transaction.refundId            | string  | Refund identifier               |
transaction.state               | number  | Refund state                    |
transaction.amount              | number  | Currency                        |
transaction.currency            | number  | Amount                          |
transaction.error.message       | string  | Transaction error message       |
transaction.error.code          | string  | Always empty string             |
sourceTransaction.id            | string  | Source transaction identifier   |
sourceTransaction.state         | number  | Source transaction state        |

Using refund id (refundId) you [can get refund info](getRefund.md)
and continue processing on your side.