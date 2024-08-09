* [Back to contents](../Readme.md#contents)

# IPN - Checkout

* [Intro](#intro)
* [IPN Request description](#ipn-request-description)

## Intro

After finishing the payment and assigning one of the
[final statuses](getTransaction.md#transaction-states)
to the transaction, the payment gateway sends an Instant Payment Notification
(IPN) to the Merchant's server.

An IPN will be sent only in cases when a transaction is created successfully. On the Payop side, a transaction is
created only after a successful request to the Acquirer. IPN requests will be sent to the **IPN URL** set up for your
selected project.

For greater security, we highly recommend that you accept IPNs from our IP addresses only:

* 18.199.249.46
* 35.158.36.143
* 3.125.109.58
* 3.127.103.117

----
**Note:** Sometimes IPNs related to a particular order may be sent multiple times. If you are receiving multiple IPNs
with the same status and data (order ID, transaction ID etc.)
that refer to the order, please make sure the payment has been credited only once. Otherwise, just accept the first
notification only and ignore all others.

Still, several IPN notifications should be accepted for a particular transaction if they carry different statuses. In
this case, proceed with updating the current transaction state.

**For instance:**

**CASE 1.** You received several IPNs with the same data and the `success` state for the order ID 1111;

**CASE 2.** You received an IPN for the order ID 1111 with the `failed` status; later on you received another IPN
notification with the same transaction data for the order ID 1111 but with the `success` state.

**Solution:**

**CASE 1.** You only need to accept the first notification and ignore the others;

**CASE 2.** You shouldn't ignore notifications with a different state to let the transaction status become updated.

----

**Note:** Please note! A notification is sent to the merchant's server within 24 hours and until the Payop server, upon
this request, receives the HTTP status code `200 OK`.

----

## IPN message description

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://{IPN_URL_from_your_project}
```

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

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

Using transaction id (invoice.txid) you [can get transaction](getTransaction.md), find there your order id and process
this order in your system.

Invoice status is one of [invoice statuses](../Invoice/getInvoice.md).
