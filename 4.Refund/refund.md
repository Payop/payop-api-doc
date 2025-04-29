* [Back to contents](../Readme.md#contents)

# Refund

**The Refund API allows merchants to process full or partial refunds for previously completed transactions. This functionality is essential for handling customer returns, failed deliveries, duplicate payments, or dispute resolutions.**

**All refund-related operations require authentication and are tied to the original transaction.**


| # | Endpoint                                                                 | Method                                                                 | Purpose                                                                                      |
|---|--------------------------------------------------------------------------|------------------------------------------------------------------------|----------------------------------------------------------------------------------------------|
| 1 | [`/v1/refunds/create`](#1-create-refund)                      | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Initiate a refund request for a completed transaction (full or partial).                    |
| 2 | [`/v1/refunds/user-refunds?query[identifier]={refundId}`](#2-get-refund-list) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)   | Retrieve detailed information about a specific refund using its identifier.                |
| 3 | [`/v1/refunds/user-refunds`](#3-get-refund-list)                      | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | Fetch the list of all refunds initiated by the merchant.                                    |
| 4 | [`{Refund IPN URL from your project settings}`](#4-refund-ipn-instant-payment-notification) | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Receive an Instant Payment Notification (IPN) when a refund is accepted or rejected.       |



### **1. Create Refund**


### **Purpose:**
**Initiate a refund by sending a request with the required transaction and amount details.**

### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://api.payop.com/v1/refunds/create
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
```




![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

<table>
  <tr>
   <td>Parameter
   </td>
   <td>Type
   </td>
   <td>Description
   </td>
   <td>Required
   </td>
  </tr>
  <tr>
   <td><strong><code>transactionIdentifier</code></strong>
   </td>
   <td><strong><code>string</code></strong>
   </td>
   <td><strong>Identifier of the original (parent) transaction</strong>
   </td>
   <td><strong>✅ Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>refundType</code></strong>
   </td>
   <td><strong><code>number</code></strong>
   </td>
   <td><strong>Type of refund: <code>1</code> for full, <code>2</code> for partial</strong>
   </td>
   <td><strong>✅ Yes</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>amount</code></strong>
   </td>
   <td><strong><code>number</code></strong>
   </td>
   <td><strong>Amount to refund (same currency as original transaction)</strong>
   </td>
   <td><strong>✅ Yes (if partial)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>metadata</code></strong>
   </td>
   <td><strong><code>object</code></strong>
   </td>
   <td><strong>Optional key-value object to attach merchant data</strong>
   </td>
   <td><strong>❌ No</strong>
   </td>
  </tr>
</table>



### **Refund Type Options:**


<table>
  <tr>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td><strong><code>1</code></strong>
   </td>
   <td><strong>Full refund</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>2</code></strong>
   </td>
   <td><strong>Partial refund</strong>
   </td>
  </tr>
</table>



### **Request Example (Partial Refund):**


```shell
curl -X POST "https://api.payop.com/v1/refunds/create" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN" \
 -d '{
   "transactionIdentifier": "d839c714-7743-47cf-8f9d-73592597c6e1",
   "refundType": 2,
   "amount": 10,
   "metadata": {
     "internal merchant id": "example"
   }
 }'
```



![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
 "data": "",
 "status": 1
}
```


**📌 The refund identifier is returned in the response header:**


```shell
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```



### **Error Response Examples:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)
```json
{   "message": "Refund amount is bigger than transaction amount" }
```


** **

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)
```json
{   "message": "Transaction not found" }
```


** **

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)
```json
{   "message": "Full authentication is required to access this resource." }
```


** **


### **2. Get Refund Details**


### **Purpose:**

**Fetch information about a specific refund using its identifier.**


### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://api.payop.com/v1/refunds/user-refunds?query[identifier]={payopRefundId}
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
```


** **


![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/refunds/user-refunds?query[identifier]=7ede204f-0d2a-55c4-9b84-8877720c9f6" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```


** **


![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

**Includes refund metadata, amount, status, and original transaction info.**


```json
{
 "data": [
   {
     "identifier": "8888888-7725-56d5-b75d-1d54382d1e46",
     "status": 2,
     "amount": 10,
     "currency": "USD",
     "sourceTransaction": {
       "identifier": "999999-9d55-5fcc-aa56-35cc093e434e",
       "amount": 440.65,
       "currency": "USD"
     },
     "metadata": {
       "internal merchant id": "example",
       "isAutoRefund": true
     }
   }
 ],
 "status": 1
}
```


** **


### **3. Get Refunds List**


### **Purpose:**

**Returns a list of all refunds associated with the authenticated merchant.**


### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)


```shell
https://api.payop.com/v1/refunds/user-refunds
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
```



![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/refunds/user-refunds" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```



![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

**List of refund entries with associated details.**


```json
{
 "data": [
   {
     "identifier": "38c92c25-33fd-4979-ac50-f73d7dbbf660",
     "status": 1,
     "amount": 10,
     "currency": "USD",
     "sourceTransaction": {
       "identifier": "d839c714-7743-47cf-8f9d-73592597c6e1"
     },
     "metadata": {
       "internal merchant id": "example"
     }
   }
 ],
 "status": 1
}
```



### **Refund Statuses**


<table>
  <tr>
   <td>Status
   </td>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td><strong><code>1</code></strong>
   </td>
   <td><strong><code>new</code></strong>
   </td>
   <td><strong>New refund request</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>2</code></strong>
   </td>
   <td><strong><code>accepted</code></strong>
   </td>
   <td><strong>Successfully processed refund</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>3,4</code></strong>
   </td>
   <td><strong><code>rejected</code></strong>
   </td>
   <td><strong>Refund was rejected</strong>
   </td>
  </tr>
</table>



---


### **4. Refund IPN (Instant Payment Notification)**


### **Purpose:**

**Notify the merchant server when a refund is accepted or rejected.**


### **Delivery URL:**

**Sent to the IPN URL configured for your project.**


### **Security:**

**Only accept IPNs from Payop IP addresses:**


```shell
18.199.249.46  
35.158.36.143   
3.125.109.58   
3.127.103.117
```



### **Behavior:**



* **If the IPN has the same status and refund ID, accept only the first notification.**
* **If the status changes, accept the new IPN to update the refund status accordingly.**


### **Example IPN Payload:**


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
     "message": "Error message",
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
   <td><strong><code>string</code></strong>
   </td>
   <td><strong>Refund identifier</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.state</code></strong>
   </td>
   <td><strong><code>number</code></strong>
   </td>
   <td><strong>Refund status</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.amount</code></strong>
   </td>
   <td><strong><code>number</code></strong>
   </td>
   <td><strong>Refunded amount</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.currency</code></strong>
   </td>
   <td><strong><code>string</code></strong>
   </td>
   <td><strong>Currency code</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.metadata</code></strong>
   </td>
   <td><strong><code>object</code></strong>
   </td>
   <td><strong>Merchant metadata</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>transaction.error.message</code></strong>
   </td>
   <td><strong><code>string</code></strong>
   </td>
   <td><strong>Error message (if any)</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>sourceTransaction.id</code></strong>
   </td>
   <td><strong><code>string</code></strong>
   </td>
   <td><strong>Identifier of the original transaction</strong>
   </td>
  </tr>
  <tr>
   <td><strong><code>sourceTransaction.state</code></strong>
   </td>
   <td><strong><code>number</code></strong>
   </td>
   <td><strong>Status of the original transaction</strong>
   </td>
  </tr>
</table>