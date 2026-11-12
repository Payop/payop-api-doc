* [Back to contents](../Readme.md#contents)

# What is IPN (Instant Payment Notification)?

An **IPN (Instant Payment Notification)** is a server-to-server callback sent by Payop to your configured IPN URL whenever a transaction changes its status.

IPNs allow your system to stay synchronized with the full lifecycle of:

* Checkout transactions
* Withdrawals
* Refunds


### What Changed in IPN v2?

In previous versions, IPNs were sent only for final statuses.


#### In IPN v2:

IPNs are sent for **transaction status changes**, including:

* Pre-approved
* Timeout
* Failed
* Accepted / Rejected

This ensures real-time lifecycle synchronization and guarantees that your system always reflects the latest transaction state.


### Transaction Status Reference

Below are the official statuses used in IPN v2.

**⚠️ Important:**<br>
In IPN payloads, statuses are always sent as **numeric values (integer)** in the field `transaction.state`.<br>
String values are provided for readability and mapping only.


#### 1. Checkout Transaction

<table>
  <tr>
   <th>Value</th>
   <th>State</th>
   <th>API Status</th>
   <th>Description</th>
   <th>Final Status</th>
  </tr>
  <tr>
   <td>9</td>
   <td>pre-approved</td>
   <td>awaiting_transfer</td>
   <td>Payment is pre-approved (e.g., bank transfer). Waiting for funds to be received or confirmed.</td>
   <td>No</td>
  </tr>
  <tr>
   <td>2</td>
   <td>accepted</td>
   <td>paid</td>
   <td>Payment completed successfully. Funds are received and the order is considered paid.</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>3</td>
   <td>rejected</td>
   <td>rejected</td>
   <td>Payment rejected, has not been paid for technical or financial reasons</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>5</td>
   <td>failed</td>
   <td>failed</td>
   <td>Payment did not complete (declined, error). No funds received.</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>15</td>
   <td>timeout</td>
   <td>timeout</td>
   <td>Payment was not completed within the allowed time. Treated as not paid.</td>
   <td>Yes</td>
  </tr>
</table>

**Example**

```shell
{
  "invoice": {
    "id": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "txid": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "status": 4,
    "metadata": {}
  },
  "transaction": {
    "id": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "state": 5,
    "order": {
      "id": "ORDER_ID"
    },
    "error": {
      "code": "",
      "message": "Transaction Failed"
    }
  }
}
```

**Integration Rule:**

Only **value = 2 (paid)** should trigger order fulfillment.


#### 2. Withdrawal Transaction

<table>
  <tr>
   <th>Value</th>
   <th>Code</th>
   <th>Description</th>
   <th>Final Status</th>
  </tr>
  <tr>
   <td>2</td>
   <td>accepted</td>
   <td>Withdrawal is approved and payout has been or will be sent. Funds are being transferred to the recipient.</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>3</td>
   <td>rejected</td>
   <td>Withdrawal was declined. No payout is made; commission may apply depending on your terms.</td>
   <td>Yes</td>
  </tr>
</table>

**Example**

```shell
{
  "transaction": {
    "withdrawalId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "state": 2,
    "amount": 100,
    "currency": "EUR",
    "comment": null,
    "metadata": {
      "actorIdentifier": 100000
    },
    "error": {
      "code": "",
      "message": ""
    }
  }
}
```

**Integration Rule:**

Only **value = 2 (accepted)** confirms successful payout.


#### 3. Refund Transaction

<table>
  <tr>
   <th>Value</th>
   <th>Status name</th>
   <th>Description</th>
   <th>Final Status</th>
  </tr>
  <tr>
   <td>1</td>
   <td>new</td>
   <td>Refund request was created and is awaiting review or processing.</td>
   <td>No</td>
  </tr>
  <tr>
   <td>2</td>
   <td>accepted</td>
   <td>Refund was approved. The money will be or has been returned.</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>3</td>
   <td>rejected</td>
   <td>Refund was declined. No return of funds; commission may apply depending on your terms.</td>
   <td>Yes</td>
  </tr>
</table>

**Example**

```shell
{
  "transaction": {
    "refundId": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "state": 2,
    "amount": 50,
    "currency": "EUR",
    "metadata": {
      "orderId": "ORDER_REFUND_ID"
    },
    "error": {
      "code": "",
      "message": ""
    }
  },
  "sourceTransaction": {
    "id": "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
    "state": 2
  }
}
```

**Integration Rule:**

Only **value = 2 (accepted)** confirms refund completion.


### When is an IPN Sent?

An IPN is sent:

* After the transaction is successfully created
* On every status update
* To the IPN URL configured in your merchant dashboard
* Repeated until your server responds with **HTTP 200 OK**


### Duplicate & Updated IPNs

Because IPNs are lifecycle-based, you may receive multiple notifications for the same operation.

<table>
  <tr>
   <th>Scenario</th>
   <th>Required Action</th>
  </tr>
  <tr>
   <td>Same status received twice</td>
   <td>Ignore duplicate</td>
  </tr>
  <tr>
   <td>Status changed</td>
   <td>Update your system</td>
  </tr>
  <tr>
   <td>Preliminary → Final</td>
   <td>Update to final</td>
  </tr>
  <tr>
   <td>Failed → Success (rare case)</td>
   <td>Update to latest state</td>
  </tr>
</table>

Your system must be idempotent.


### IPN Delivery Rules


#### HTTP Method

* Default: POST


#### Content Type

```shell
Content-Type: application/json
```


#### Success Criteria

IPN is considered delivered successfully only if your server responds with **HTTP 200 OK**.

Any other response will trigger automatic retry.


### Security Best Practices

Whitelist the following IP addresses:

```shell
18.199.249.46
35.158.36.143
3.125.109.58
3.127.103.117
```

Additionally, we recommend:

* Logging every IPN
* Validating payload structure
* Returning HTTP 200 immediately after processing


### Checkout IPN (v2)


#### When is it Sent?

Sent whenever an checkout transaction changes status.


#### Example Payload (Checkout)

```shell
POST https://your-server.com/ipn-endpoint
Content-Type: application/json
```

```shell
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
     "message": "Error message",
     "code": ""
   }
 }
}
```


### Withdrawal IPN (v2)


#### When is it Sent?

Sent on:

* Accepted
* Rejected
* Any further state change


#### Example Payload

```shell
POST https://{your_withdrawal_ipn_url}
Content-Type: application/json
{
 "transaction": {
   "withdrawalId": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
   "state": 2,
   "amount": 100,
   "currency": "USD",
   "comment": "Manager's comment",
   "metadata": {
     "key1": "Metadata information.",
     "key2": "Metadata information."
   },
   "error": {
     "message": "Error Message",
     "code": ""
   }
 }
}
```


### Refund IPN (v2)


#### When is it Sent?

Sent on:

* New
* Accepted
* Rejected
* Any status change


#### Example Payload

```shell
POST https://{your_refund_ipn_url}
Content-Type: application/json
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
     "message": "Error Message",
     "code": ""
   }
 },
 "sourceTransaction": {
   "id": "999999-ba2d-456f-910e-4d7fdfd338dd",
   "state": 1
 }
}
```


### Lifecycle Status Handling

Because IPN v2 sends lifecycle updates, your system should:

* Store latest known state
* Compare incoming state with stored state
* Update only if different
* Always return HTTP 200


#### Example Idempotent Processing Logic

```shell
if operation does not exist:
    create record
else:
    if newState != storedState:
        update record
    else:
        ignore duplicate
return HTTP 200
```


### Retry Policy

* If an IPN cannot be delivered successfully, Payop automatically retries the notification according to the following schedule.

<table>
  <tr>
   <th>Attempt</th>
   <th>Timing</th>
   <th>Description</th>
  </tr>
  <tr>
   <td>1</td>
   <td>Immediately</td>
   <td>The first notification is sent after the transaction status change.</td>
  </tr>
  <tr>
   <td>2</td>
   <td>Approximately 1 minute later</td>
   <td>If the first attempt does not receive HTTP 200 OK, the notification is retried.</td>
  </tr>
  <tr>
   <td>3</td>
   <td>Approximately 1 hour later</td>
   <td>If the second attempt also fails, a final delivery attempt is made.</td>
  </tr>
</table>


### Migration Notes (v1 → v2)

<table>
  <tr>
   <th>Feature</th>
   <th>v1</th>
   <th>v2</th>
  </tr>
  <tr>
   <td>Only final statuses sent</td>
   <td>Yes</td>
   <td>No</td>
  </tr>
  <tr>
   <td>Lifecycle updates</td>
   <td>No</td>
   <td>Yes</td>
  </tr>
  <tr>
   <td>Real-time synchronization</td>
   <td>Partial</td>
   <td>Full</td>
  </tr>
  <tr>
   <td>Duplicate prevention required</td>
   <td>Recommended</td>
   <td>Mandatory</td>
  </tr>
</table>


### Important Integration Recommendation

Because IPN v2 sends preliminary statuses:

Trigger business logic only when final success status is received (e.g., `rejected`, `accepted`), not on preliminary states such as `awaiting_transfer`.
