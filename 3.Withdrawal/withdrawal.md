* [Back to contents](../Readme.md#contents)

# Withdrawal

**The Withdrawal API allows merchants to process fund withdrawals from their account to external recipients, such as customers, partners, or employees. Withdrawals can be created individually or in batches (mass payouts) and support multiple methods and currencies. All operations require authentication and encrypted payloads.**


| # | Endpoint                                                                                       | Method                                                                 | Auth Required | Purpose                                                                                          |
|---|------------------------------------------------------------------------------------------------|------------------------------------------------------------------------|----------------|--------------------------------------------------------------------------------------------------|
| 1 | [`/v1/withdrawals/user-withdrawals?query[identifier]={withdrawalId}`](#1-get-withdrawal-details) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | ✅ Yes         | Get detailed information about a specific withdrawal by ID.                                     |
| 2 | [`/v1/withdrawals/user-withdrawals`](#2-get-withdrawals-list)                                  | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | ✅ Yes         | Retrieve a list of all withdrawals initiated by the merchant.                                   |
| 3 | [`/v1/withdrawals/create-mass`](#3-create-mass-withdrawal)                                     | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | ✅ Yes         | Submit one or more withdrawal requests as a batch (mass withdrawal).                            |
| 4 | [`/v1/instrument-settings/payment-methods/available-withdrawal-for-user`](#4-get-available-withdrawal-methods) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)    | ✅ Yes         | Get all available withdrawal payment methods for the authenticated user.                        |
| 5 | [`{Withdraw IPN URL configured in project settings}`](#5-ipn--withdrawal-notifications)                        | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | ❌ No          | Receive asynchronous Instant Payment Notifications (IPNs) for status updates on withdrawals.    |




### **1. Get Withdrawal Details**


### **Purpose:**

**Fetch detailed information about a specific withdrawal using its identifier.**


### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://api.payop.com/v1/withdrawals/user-withdrawals?query[identifier]={withdrawalId}
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
```



![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/withdrawals/user-withdrawals?query[identifier]=WITHDRAWAL_ID" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```



### **Withdrawal Statuses:**


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
   <td>1, 4
   </td>
   <td>pending
   </td>
   <td>Withdrawal initiated or processing
   </td>
  </tr>
  <tr>
   <td>2
   </td>
   <td>accepted
   </td>
   <td>Withdrawal completed successfully
   </td>
  </tr>
  <tr>
   <td>3
   </td>
   <td>rejected
   </td>
   <td>Withdrawal rejected
   </td>
  </tr>
</table>



### **2. Get Withdrawals List**


### **Purpose:**

**Retrieve a list of all withdrawals initiated by the merchant.**


### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
 https://api.payop.com/v1/withdrawals/user-withdrawals
```



![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/withdrawals/user-withdrawals" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```



### **3. Create Mass Withdrawal**


### **Purpose:**

**Send multiple withdrawal requests in a single encrypted payload to automate payouts.**


### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://api.payop.com/v1/withdrawals/create-mass
```



![HEADERS](https://img.shields.io/badge/-headers-purple?style=for-the-badge)


```shell
Content-Type: application/
Authorization: Bearer YOUR_JWT_TOKEN
idempotency-key: YOUR_UNIQUE_UUID  (Optional, recommended)
```



### **Encryption Required:**



* **All withdrawal requests must be encrypted using Sodium Sealed Box encryption.**
* **The encrypted binary data should then be Base64 encoded before sending.**
* **Certificates can be generated in your merchant account.**

 *[See “Request Payload Encrypt/Decrypt” section for PHP examples.](withdrawalEncrypt.md)*



### **Withdrawal Method Examples:**


#### **🟦 Volet (method: 14)**


```json
{
 "direction": "Salary for September",
 "email": "recipient@example.com"
}
```


** **


#### **🟧 PayDo (method: 15)**


```json
{
 "direction": "Commission payout",
 "referenceId": "recipient@example.com",
 "recipientAccountType": 1
}
```



### **Commission Types:**


<table>
  <tr>
   <td>Type
   </td>
   <td>Description
   </td>
  </tr>
  <tr>
   <td>1
   </td>
   <td>Fee deducted from wallet
   </td>
  </tr>
  <tr>
   <td>2
   </td>
   <td>Fee deducted from transaction
   </td>
  </tr>
</table>



### **Sample Encrypted Payload Call:**


```shell
curl -X POST "https://api.payop.com/v1/withdrawals/create-mass" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN" \
 -H "idempotency-key: 5c2eeb7f-f7db-4b9f-ae1d-f1bc2e6e5694" \
 -d '{"data": "9kQ7v9nXLHjeOyIqi+hIJfEKuOCQZ2C5WWVcnmfPHUxh1EbK5g=="}'
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
  "data": [
    {
      "id": "eab40b05-805b-5dbb-8900-a634a9ecaf57",
      "metadata": []
    },
    {
      "id": "19b60564-e75e-5c51-988d-9b7bf69ae240",
      "metadata": []
    }
  ],
  "status": 1
}

```


** **


### **4. Get Available Withdrawal Methods**


### **Purpose:**

**Retrieve a list of withdrawal methods supported for the authenticated user and available for payouts via API.** 

**To check the methods available for settlements, please visit your merchant account and the “Withdrawals” section.**


### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
 https://api.payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user
```


** **


![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://payop.com/v1/instrument-settings/payment-methods/available-withdrawal-for-user" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```



![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
 "data": [
   {
     "type": 14,
     "name": "Volet",
     "currencies": ["EUR", "USD"]
   },
   {
     "type": 15,
     "name": "PayDo",
     "currencies": ["EUR", "USD"]
   }
 ],
 "status": 1
}
```



### **🏦 Withdrawal Flow Overview**



1. **Encrypt the withdrawal payload using your public certificate.**
2. **Create Mass Withdrawal request with encrypted Base64-encoded data.**
3. **Save the returned withdrawal ID.**
4. **Poll Status every 10 minutes using the <code>GET /withdrawals/user-withdrawals?query[identifier]</code> endpoint.**
5. **Update internal status only after receiving a final status (<code>accepted</code> or <code>rejected</code>).**

---

### **5. IPN – Withdrawal Notifications**


### **Purpose:**

**Automatically notify the merchant about withdrawal status updates.**


### **Delivery:**

**Sent to the <code>Withdraw IPN URL</code> configured in your merchant account.**


### **IP Whitelist:**

**Only accept IPNs from:**


```shell
18.199.249.46   
35.158.36.143   
3.125.109.58  
3.127.103.117
```



### **IPN Payload Example:**


```json
{
 "transaction": {
   "withdrawalId": "d024f697-ba2d-456f-910e-4d7fdfd338dd",
   "state": 1,
   "amount": 100,
   "currency": "USD",
   "comment": "Manager comment",
   "metadata": {
     "key1": "Value",
     "key2": "Value"
   },
   "error": {
     "message": "",
     "code": ""
   }
 }
}
```



### **IPN Status Handling:**



* **Accept only the first IPN if status and data are identical.**
* **Accept IPNs with different status updates (e.g., from <code>pending</code> → <code>accepted</code>).**
* **Retry IPN delivery occurs for 24h until your server returns HTTP 200.**
