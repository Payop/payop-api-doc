# IPN - Refund

After finishing the accepting or rejecting processes, the payment gateway sends an Instant Payment Notification (IPN) to the Merchant's server.

IPN requests will be sent to the IPN URL which you set up for the selected project.

For greater security, we highly recommend that you accept IPN from our IP addresses only:

* 18.199.249.46
* 35.158.36.143
* 3.125.109.58
* 3.127.103.117

----

**Note:** Sometimes an IPN that is related to a particular refund may be sent multiple times. If you are receiving multiple IPNs with the same status and data (refund id, source transaction data etc.) that refer to a refund, **please make sure the payment has been processed only once at your side.**

---

In this way, you need to check the following: if you received IPNs with the same status and data (refund ID, source transactions data, status etc.) related to a refund, you have to accept only the first notification and ignore the others.

However, several IPN notifications should be accepted for a particular refund if they carry different statuses. In this case, you don't need to ignore them, but update the current refund state.

**For instance:**

**CASE 1:** You received several IPNs with the same data and `success` state for refund ID 1111;

**CASE 2:** You received an IPN for refund ID 1111 with the `failed` status; later on, you received the other IPN notification with the same transaction data for the refund ID 1111, but with the state "success".

**Solution:**

**CASE 1:** You only need to accept the first notification and ignore the others;

**CASE 2:** You shouldn't ignore notifications with a different state to let the transaction status become updated.

----

**Note:** Please note! A notification is sent to the merchant's server within 24 hours and until the Payop server, upon this request, receives the HTTP status code `200 OK`.

----

### IPN message example

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://{IPN_URL_from_your_project}
```

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json 
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
    "transaction": {
        "refundId": "8888888-ba2d-456f-910e-4d7fdfd338dd",
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
        "id": "999999-ba2d-456f-910e-4d7fdfd338dd",
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
transaction.metadata            | array   | Key-value array of metadata     |
transaction.error.message       | string  | Transaction error message       |
transaction.error.code          | string  | Always empty string             |
sourceTransaction.id            | string  | Source transaction identifier   |
sourceTransaction.state         | number  | Source transaction state        |

Using refund id (refundId) you [can get refund info](getRefund.md) and continue processing on your side.
