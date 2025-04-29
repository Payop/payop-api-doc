* [Back to contents](../Readme.md#contents)

# What is IPN (Instant Payment Notification)?

**An IPN is a server-to-server callback sent by Payop to your configured IPN URL after a transaction reaches a final status.**

**It allows your system to be updated in real-time when a transaction is either successful or failed.**


## **When is an IPN Sent?**

* **An IPN is sent only if a transaction is successfully created on Payop’s side.**
* **It is sent to the IPN URL configured for your project.**
* **Retries happen until your server responds with HTTP 200 OK.**
* **You may receive multiple IPNs for the same transaction with:**
    * **The same status (duplicate)**
    * **Updated status (e.g., from <code>failed</code> → <code>success</code>) — must be handled**


## **Best Practices for IPNs**



* **Whitelist IPs for security**:
```shell
18.199.249.46,
35.158.36.143,
3.125.109.58,
3.127.103.117
```
* <strong>Always return HTTP 200 after successful processing.</strong>
* <strong>Log every IPN you receive.</strong>
* <strong>Ignore duplicates with same data.</strong>
* <strong>Update transaction status if new status is different.</strong>

<strong> </strong>


## **Checkout IPN**

### **What is Checkout IPN?**

A **Checkout IPN (Instant Payment Notification)** is a server-to-server message sent by Payop when the status of a checkout transaction is updated—particularly when it reaches a final state such as **Success** or **Fail**.


### **When is Checkout IPN Sent?**


*  **Sent after a final status is determined** for a transaction (e.g., payment succeeded or failed). \
*  **Delivered to the IPN URL** configured in your project settings under the *Checkout* section. \
*  **Repeated automatically** until your server responds with an HTTP `200 OK`, confirming successful receipt.


## **IPN Payload (Example)**


```shell
POST https://{your_withdrawal_ipn_url}
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



## **Important Payload Fields**


<table>
  <tr>
   <td>Field
   </td>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td><strong><code>invoice.id</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Unique ID of the invoice</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>invoice.status</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Status of the invoice (1 - paid, etc.)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>invoice.txid</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Related transaction ID</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.id</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Transaction identifier</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.state</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Transaction state (2 = success, 3/5 = failed, etc.)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.error.message</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Error details (if any)</strong>
   </td>
  </tr>
</table>



## **cURL Example: Simulate IPN (for testing)**


```shell
curl -X POST https://your-server.com/ipn-endpoint \
 -H "Content-Type: application/json" \
 -d '{
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
       "message": "Error Message",
       "code": ""
     }
   }
 }'
```


**Replace <code>https://your-server.com/ipn-endpoint</code> with the actual IPN URL you configured in your Payop merchant dashboard.**


## **IPN for Refunds**


### **What is Refund IPN?**

**An Instant Payment Notification (IPN) for a refund is a server-to-server callback sent by Payop to your server once a refund is either accepted or rejected.**


### **When is Refund IPN Sent?**



* **Sent after a refund is accepted or rejected.**
* **Sent to your project’s configured Refund IPN URL.**
* **Sent within 24 hours and repeated until your server responds with <code>HTTP 200 OK</code>.**


### **Duplicate & Updated IPNs**



* **You may receive multiple IPNs with the same data → Process only the first one.**
* **If status changes later (e.g., from <code>failed</code> to <code>success</code>), process the new one and update your system.**
* **Received multiple IPNs with same refundId and <code>success</code> → process the first only.**
* **First IPN had <code>failed</code> status, later IPN has <code>success</code> → update refund state.**


### **IPN Payload Example for Refund**


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


** **


### **Payload Parameters for Refund**

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

<table>
  <tr>
   <td>Field
   </td>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.refundId</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Refund ID</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.state</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Refund status (1 - new, 2 - accepted, 3/4 - rejected)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.amount</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Refunded amount</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.currency</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Currency of refund</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.metadata</code></strong>
   </td>
   <td><strong>object</strong>
   </td>
   <td><strong>Metadata passed during refund</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.error.message</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Error message, if any</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>sourceTransaction.id</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Original transaction ID</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>sourceTransaction.state</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>State of the original transaction</strong>
   </td>
  </tr>
</table>



## **IPN for Withdrawals**


### **What is Withdrawal IPN?**

**An IPN for withdrawal is a server-to-server notification sent by Payop when a withdrawal request reaches its final status: Accepted or Rejected.**


### **When is Withdrawal IPN Sent?**



* **Sent after a final decision is made on a withdrawal.**
* **Sent to the configured Withdrawal IPN URL in your project settings.**
* **Sent repeatedly until your server replies with <code>HTTP 200 OK</code>.**


### **Handling Duplicates and Updates**


<table>
  <tr>
   <td>Scenario
   </td>
   <td>Action
   </td>
  </tr>
  <tr>
   <td>Multiple IPNs with <strong>same status</strong>
   </td>
   <td><strong>Accept only the first</strong>
   </td>
  </tr>
  <tr>
   <td><strong>IPNs with different status</strong>
   </td>
   <td><strong>Accept and update your system state accordingly</strong>
   </td>
  </tr>
</table>



### **IPN Payload Example for Withdrawal**


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


** **


### **Payload Parameters for Withdrawal** 

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

<table>
  <tr>
   <td>Field
   </td>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.withdrawalId</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Withdrawal ID</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.state</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Status (1/4 - pending, 2 - accepted, 3 - rejected)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.amount</code></strong>
   </td>
   <td><strong>number</strong>
   </td>
   <td><strong>Amount withdrawn</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.currency</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Currency</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.comment</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Optional manager comment</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.metadata</code></strong>
   </td>
   <td><strong>object</strong>
   </td>
   <td><strong>Custom metadata sent with withdrawal</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.error.message</code></strong>
   </td>
   <td><strong>string</strong>
   </td>
   <td><strong>Error message, if any</strong>
   </td>
  </tr>
</table>

