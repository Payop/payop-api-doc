* [Back to contents](../Readme.md#contents)

# Checkout

### **Bearer Authentication**


#### **Purpose:**

* **This section explains how authentication works in the Payop API using JWT-based Bearer Authentication.**
* **Authentication is required for accessing protected API endpoints.**

#### **How It Works:**

* **Clients must send a JWT token in the <code>Authorization</code> header with each API request.**

![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)

```
Content-Type: application/json  
Authorization: Bearer YOUR_JWT_TOKEN
```

* <strong>Tokens can be generated in the Payop dashboard and should be securely stored.</strong> 

**📺 Watch the video "How to create a JWT token?":**  

[![Watch the video "How to create a JWT token?](https://img.youtube.com/vi/Q7vlfEpJMRA/0.jpg)](https://www.youtube.com/watch?v=Q7vlfEpJMRA)

> **Deprecated: Using tokens in the header directly is discouraged, and this method may be removed in future API versions.**


---

| # | Endpoint                                                                     | Method                                                                 | Auth Required | Purpose                                                                 |
|---|------------------------------------------------------------------------------|------------------------------------------------------------------------|----------------|-------------------------------------------------------------------------|
| 1 | [`/v1/checkout/create`](#1-create-checkout)                                  | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | ❌ No          | Create a new checkout transaction using invoice details.               |
| 2 | [`/v1/checkout/check-invoice-status/{invoiceID}`](#2-check-invoice-status)  | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | ✅ Yes         | Check the current status of a specific invoice.                        |
| 3 | [`/v2/transactions/{transactionID}`](#3-get-transaction-details)            | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | ✅ Yes         | Retrieve detailed information about a specific transaction.           |
| 4 | [`{Checkout IPN URL configured in project settings}`](#4-ipn)      | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | ❌ No          | Receive IPNs for transaction status updates (e.g., success, fail).     |
| 5 | [`/v1/checkout/void`](#5-void-transaction)                                      | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | ❌ No          | Void (cancel) a previously created but not completed checkout transaction. |


### **1. Create Checkout**


#### **Purpose:**



* **This endpoint creates a checkout transaction linked to an invoice.**
* **Required when a customer wants to make a payment.**


#### **How It Works:**



* **A <code>POST</code> request is sent with the invoice details, payment method, and customer information.**
* **The response returns a unique transaction ID.**


#### **Example Usage:**

![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)


```shell
curl -X POST \
  https://api.payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "INVOICE_IDENTIFIER",
	"customer": {"email": "test@email.com", "name":"CUSTOMER_NAME"},
	"checkStatusUrl": "https://your.site/check-status/{{txid}}",
	"paymentMethod": 935
}'
```


** **

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

* **<code>invoiceIdentifier</code>: The invoice ID.**
* **<code>customer</code>: Customer details (name, email, IP address).**
* **<code>paymentMethod</code>: The payment method ID.**
* **<code>checkStatusUrl</code>: A URL of a page that will be displayed when the user is redirected to the payment provider's page.**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
 "data": {
   "isSuccess": true,
   "message": "",
   "txid": "transaction_unique_id"
 },
 "status": 1
}
```


---


### **2. Check invoice status**


#### **Purpose:**

* **This endpoint allows users to check the current status of an invoice after initiating a payment.**
* **Useful for tracking payments and determining if further action is required.**

#### **How It Works:**


* **A <code>GET</code> request is sent with the <code>invoiceID</code> parameter.**
* **The response contains one of the following statuses:**
    * **Pending: The transaction is still being processed.**
    * **Success: The transaction is completed successfully.**
    * **Fail: The payment attempt failed.**


#### **Endpoint:**

![GET](https://img.shields.io/badge/-get-darkgreen?style=for-the-badge)


```shell
https://api.payop.com/v1/checkout/check-invoice-status/{invoiceID}
```


** **

**Response Examples:**

**Pending (Waiting for further action):**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
 "data": {
   "isSuccess": true,
   "status": "pending",
   "url": ""
 },
 "status": 1
}
```


** **

**Redirect  (POST request required):**


```json
{
 "data": {
   "isSuccess": true,
   "status": "pending",
   "form": {
     "method": "POST",
     "url": "https://acs.anybank.com/",
     "fields": {
       "PaReq": "encrypted_data",
       "MD": "unique_id",
       "TermUrl": "https://payop.com/v1/url"
     }
   }
 },

 "status": 1
}
```


** **

### **3. Get Transaction Details**


#### **Purpose:**



* **Retrieves detailed information about a specific transaction.**
* **Can be used for monitoring payments and identifying errors.**


#### **How It Works:**



* **A <code>GET</code> request is sent with the transaction ID.**
* **The response includes transaction status, amount, error messages (if applicable), and payment method details.**


#### **Endpoint:**

![GET](https://img.shields.io/badge/-get-darkgreen?style=for-the-badge)


```shell
 https://api.payop.com/v2/transactions/{transactionID}
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
```


![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
 "data": {
   "identifier": "transaction_id",
   "amount": 100,
   "currency": "USD",
   "state": 5,
   "error": "error message",
   "createdAt": 1567402240,
   "orderId": "134666",
   "resultUrl": "https://your.site/result"
 }

}
```

### **Transaction states**

Status | Type         | Description                                                                                                              |
-------|--------------|--------------------------------------------------------------------------------------------------------------------------|
1      | new          | New transaction, no actions were taken                                                                                   |
2      | accepted     | Transaction was paid successfully                                                                                        |
4      | pending      | Transaction pending, has not yet been paid and is expected to be paid                                                    |
3, 5  | failed       | Transaction failed, has not been paid for technical or financial reasons                                                 |
9     | pre-approved | Transaction has been submitted through the bank, however, we are still awaiting the funds to be credited to our account* |
15    | timeout      | Transaction timed out due to lack of final confirmation from the payer after initiation                                  |

> **🧾Please Note:** *"Pre-approved" status may change to "Accepted" status or "Failed" status, in case funds are not received or the payer has canceled the transaction. While it is quite a rare scenario, in some cases it is still possible to cancel the payment on the payer's side, **please use "Pre-approved" for goods/service delivery at your own risk. Only the final "Accepted" status is guaranteed**

---


### **4. IPN**


#### **Purpose:**

* **Describes how Instant Payment Notification (IPN) works.**
* **IPNs inform merchants in real-time about payment updates.**


#### **How It Works:**

* **Once a transaction is completed, Payop sends an IPN request to the merchant’s pre-configured IPN URL.**
* **Merchants should validate the IPN and update their order statuses accordingly.**
* **IPNs may be sent multiple times until a confirmation (HTTP 200) is received.**


#### **Endpoint:**

**IPN Request Format:**


```json
{
 "invoice": {
   "id": "invoice_id",
   "status": 1,
   "txid": "transaction_id"

 },

 "transaction": {
   "id": "transaction_id",
   "state": 2,
   "order": { "id": "ORDER_ID" },
   "error": {
     "message": "Error message",
     "code": ""
   }
 }
}
```



#### **Handling IPNs:**



* **Validate the IPN request source (only accept from Payop IPs).**
* **Ensure transactions are updated based on received status.**
* **Prevent duplicate processing if the same IPN is received multiple times.**


---


### **5. Void Transaction**


#### **Purpose:**

* **Cancels (voids) a transaction  in status “Pending”.**
* **Used when the merchant wants to cancel an ongoing transaction and initiate a new one with a fresh invoice**

#### **How It Works:**


* **A <code>POST</code> request is sent with the invoice identifier.**
* **The response confirms whether the void was successful.**

#### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://api.payop.com/v1/checkout/void
```


**Key Parameter:**



* **<code>invoiceIdentifier</code>: The unique invoice ID.**


![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
 "data": {
   "isSuccess": true,
   "message": "",
   "txid": "canceled_transaction_id"
 },
 "status": 1
}
```

