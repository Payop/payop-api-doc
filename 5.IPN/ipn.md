* [Back to contents](../Readme.md#contents)

# What is IPN (Instant Payment Notification)?

**An IPN (Instant Payment Notification) is a server-to-server callback sent by Payop to your configured IPN URL whenever a transaction changes its status.**

**IPNs allow your system to stay synchronized with the full lifecycle of:**

* **Checkout transactions**
* **Withdrawals**
* **Refunds**


## **What Changed in IPN v2?**

**In previous versions, IPNs were sent only for final statuses.**


### **In IPN v2:**

**IPNs are sent for transaction status changes, including:**

* **Pre-approved**
* **Timeout**
* **Failed**
* **Accepted / Rejected**

**This ensures real-time lifecycle synchronization and guarantees that your system always reflects the latest transaction state.**


## **Transaction Status Reference**

**Below are the official statuses used in IPN v2.**

> **⚠️ Important:**
> **In IPN payloads, statuses are always sent as numeric values (integer) in the field <code>transaction.state</code>.**
> **String values are provided for readability and mapping only.**


### **1. Checkout Transaction**

<table>
  <tr>
   <td>Value
   </td>
   <td>State
   </td>
   <td>API Status
   </td>
   <td>Description
   </td>
   <td>Final Status
   </td>
  </tr>
  <tr>
   <td><strong><code>9</code></strong>
   </td>
   <td><strong><code>pre-approved</code></strong>
   </td>
   <td><strong><code>awaiting_transfer</code></strong>
   </td>
   <td><strong>Payment is pre-approved (e.g., bank transfer). Waiting for funds to be received or confirmed.</strong>
   </td>
   <td><strong>No</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>2</code></strong>
   </td>
   <td><strong><code>accepted</code></strong>
   </td>
   <td><strong><code>paid</code></strong>
   </td>
   <td><strong>Payment completed successfully. Funds are received and the order is considered paid.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>3</code></strong>
   </td>
   <td><strong><code>rejected</code></strong>
   </td>
   <td><strong><code>rejected</code></strong>
   </td>
   <td><strong>Payment rejected, has not been paid for technical or financial reasons</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>5</code></strong>
   </td>
   <td><strong><code>failed</code></strong>
   </td>
   <td><strong><code>failed</code></strong>
   </td>
   <td><strong>Payment did not complete (declined, error). No funds received.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>15</code></strong>
   </td>
   <td><strong><code>timeout</code></strong>
   </td>
   <td><strong><code>timeout</code></strong>
   </td>
   <td><strong>Payment was not completed within the allowed time. Treated as not paid.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
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
**Only value = <code>2</code> (<code>paid</code>) should trigger order fulfillment.**


### **2. Withdrawal Transaction**

<table>
  <tr>
   <td>Value
   </td>
   <td>Code
   </td>
   <td>Description
   </td>
   <td>Final Status
   </td>
  </tr>
  <tr>
   <td><strong><code>2</code></strong>
   </td>
   <td><strong><code>accepted</code></strong>
   </td>
   <td><strong>Withdrawal is approved and payout has been or will be sent. Funds are being transferred to the recipient.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>3</code></strong>
   </td>
   <td><strong><code>rejected</code></strong>
   </td>
   <td><strong>Withdrawal was declined. No payout is made; commission may apply depending on your terms.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
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
**Only value = <code>2</code> (<code>accepted</code>) confirms successful payout.**


### **3. Refund Transaction**

<table>
  <tr>
   <td>Value
   </td>
   <td>Status name
   </td>
   <td>Description
   </td>
   <td>Final Status
   </td>
  </tr>
  <tr>
   <td><strong><code>1</code></strong>
   </td>
   <td><strong><code>new</code></strong>
   </td>
   <td><strong>Refund request was created and is awaiting review or processing.</strong>
   </td>
   <td><strong>No</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>2</code></strong>
   </td>
   <td><strong><code>accepted</code></strong>
   </td>
   <td><strong>Refund was approved. The money will be or has been returned.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>3</code></strong>
   </td>
   <td><strong><code>rejected</code></strong>
   </td>
   <td><strong>Refund was declined. No return of funds; commission may apply depending on your terms.</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
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
**Only value = <code>2</code> (<code>accepted</code>) confirms refund completion.**


## **When is an IPN Sent?**

**An IPN is sent:**

* **After the transaction is successfully created**
* **On every status update**
* **To the IPN URL configured in your merchant dashboard**
* **Repeated until your server responds with <code>HTTP 200 OK</code>**


## **Duplicate & Updated IPNs**

**Because IPNs are lifecycle-based, you may receive multiple notifications for the same operation.**

<table>
  <tr>
   <td>Scenario
   </td>
   <td>Required Action
   </td>
  </tr>
  <tr>
   <td><strong>Same status received twice</strong>
   </td>
   <td><strong>Ignore duplicate</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Status changed</strong>
   </td>
   <td><strong>Update your system</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Preliminary → Final</strong>
   </td>
   <td><strong>Update to final</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Failed → Success (rare case)</strong>
   </td>
   <td><strong>Update to latest state</strong>
   </td>
  </tr>
</table>

**Your system must be idempotent.**


## **IPN Delivery Rules**


### **HTTP Method**

**Default: <code>POST</code>**


### **Content Type**

```shell
Content-Type: application/json
```


### **Success Criteria**

**IPN is considered delivered successfully only if your server responds with <code>HTTP 200 OK</code>.**

**Any other response will trigger automatic retry.**


## **Security Best Practices**

* **Whitelist the following IP addresses**:
```shell
18.199.249.46
35.158.36.143
3.125.109.58
3.127.103.117
```

**Additionally, we recommend:**

* **Logging every IPN**
* **Validating payload structure**
* **Returning HTTP 200 immediately after processing**


## **Checkout IPN (v2)**


### **When is it Sent?**

**Sent whenever an checkout transaction changes status.**


### **Example Payload (Checkout)**

```shell
POST https://your-server.com/ipn-endpoint
Content-Type: application/json
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


## **Withdrawal IPN (v2)**


### **When is it Sent?**

**Sent on:**

* **Accepted**
* **Rejected**
* **Any further state change**


### **Example Payload**

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


## **Refund IPN (v2)**


### **When is it Sent?**

**Sent on:**

* **New**
* **Accepted**
* **Rejected**
* **Any status change**


### **Example Payload**

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


## **Lifecycle Status Handling**

**Because IPN v2 sends lifecycle updates, your system should:**

* **Store latest known state**
* **Compare incoming state with stored state**
* **Update only if different**
* **Always return HTTP 200**


### **Example Idempotent Processing Logic**

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


## **Retry Policy**

* **Failed delivery attempts are retried automatically**
* **Retry continues until success or retry limit is reached**
* **Timeouts are 5 seconds for connection and response**


## **Migration Notes (v1 → v2)**

<table>
  <tr>
   <td>Feature
   </td>
   <td>v1
   </td>
   <td>v2
   </td>
  </tr>
  <tr>
   <td><strong>Only final statuses sent</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
   <td><strong>No</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Lifecycle updates</strong>
   </td>
   <td><strong>No</strong>
   </td>
   <td><strong>Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Real-time synchronization</strong>
   </td>
   <td><strong>Partial</strong>
   </td>
   <td><strong>Full</strong>
   </td>
  </tr>
  <tr>
   <td><strong>Duplicate prevention required</strong>
   </td>
   <td><strong>Recommended</strong>
   </td>
   <td><strong>Mandatory</strong>
   </td>
  </tr>
</table>


## **Important Integration Recommendation**

**Because IPN v2 sends preliminary statuses:**

**Trigger business logic only when final success status is received (e.g., rejected, accepted), not on preliminary states such as <code>awaiting_transfer</code>.**
