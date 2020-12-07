
# IPN

After finish the accepting or rejecting processes the payment gateway sends to the Merchant's server an Instant Payment Notification (IPN).

IPN request will be send to ipn url which you provide at user settings.

For greater security, we highly recommend that you accept IPN only from our IP address:
* 52.49.204.201 
* 54.229.170.212

----
**Note:** Sometimes IPN that is related to a particular order may be sent multiple times. If you are receiving multiple IPNs with the same status and data (withdraw id, status etc.) that refer to withdraw, **please make sure the payment has been processed only once at your side**.

So, what do you need to check: if you received IPNs with the same status and data (withdraw ID, status etc.) related to a transaction, you have to accept only the first notification and ignore the others.

However, several IPN notifications should be accepted for a particular transaction if they carry different statuses. In this case, you don't need to ignore them, but update the current transaction state.

For instance: 

case 1. You received several IPNs with the same data and "success" state for withdraw ID 1111; 
case 2. You received IPN for withdraw ID 1111 with status 'failed'; later on you received the other IPN notification with the same transaction data for the withdraw ID 1111 but with the other state "success".

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
        "withdrawId": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
        "state": 1,
        "amount": 100,
        "currency": "USD",
        "error": {
            "message": "3DS authorization error or 3DS canceled by payer",
            "code": ""
        }
    }
}
```

**Parameters**

Parameter                       |  Type   |                 Description     |
--------------------------------|---------|---------------------------------| 
transaction.withdrawId         | string  | Withdraw identifier             |
transaction.state               | number  | Withdraw state                  |
transaction.amount              | number  | Currency                        |
transaction.currency            | number  | Amount                          |
transaction.error.message       | string  | Transaction error message       |
transaction.error.code          | string  | Always empty string             |

Using withdraw id (withdrawId) you [can get withdraw info](#get-concrete-withdrawal-details) 
and continue processing on your side.
