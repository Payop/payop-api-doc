* [Back to contents](../Readme.md#contents)

# Integration API Types

Payop offers two primary integration options for merchants to process payments: **Hosted Page Integration** and **Direct Integration**. Each method has its own benefits, depending on your business needs, technical capabilities, and the level of control you want over the payment process.

## **1. Hosted Page Integration**


### **Overview**

The **Hosted Page Integration** is the **simplest** and **most convenient** method for merchants who prefer a **quick and easy** way to accept payments without extensive development efforts. Payop handles most of the payment flow, including security, compliance, and user experience.


### **How It Works**

**🔹1. Create an Invoice** – A request is sent to generate a payment invoice. `POST https://api.payop.com/v1/invoices/create`  
*( [See the Invoice section for more details](../1.Invoice/invoice.md) )*  
**🔹2. Redirect the Payer** – The payer is redirected to the **invoice preprocessing page**. (`https://checkout.payop.com/{{locale}}/payment/invoice-preprocessing/{{invoiceId}}`)  
**🔹3. Payer Enters Required Data:** On the Payop checkout page, the payer fills in necessary details (e.g., name, date of birth, email, etc.).  
**🔹4. Automatic Processing** – Payop determines the next steps, such as selecting the appropriate payment method or requiring additional details.  
**🔹5. Payment Confirmation** – If the payment is successful, the payer is redirected to the `resultUrl`. If the payment fails, the payer is redirected to the `failPath`.  
**🔹6. Receive IPN (Instant Payment Notification)** If IPNs are configured, Payop will automatically notify your server when the transaction status changes. This ensures your backend is updated even if the user does not return to your site.   \

 *[See Checkout->IPN for more details](../2.Checkout/checkout.md#4-ipn)*


### **Checkout Flow**

1. **Create Invoice** `POST https://api.payop.com/v1/invoices/create`   
*( [See the Invoice section for more details](../1.Invoice/invoice.md) )*
2. **Redirect payer to invoice preprocessing page:**
3. `https://checkout.payop.com/{{locale}}/payment/invoice-preprocessing/{{invoiceId}}`
    * `{{locale}}` → Language of the invoice (`en`, `ru`, etc.).
    * `{{invoiceId}}` → Unique invoice identifier.
4. If all required fields are correctly filled, the system attempts to process the payment.
5. If required fields are missing, the payer is redirected to **checkout form completion**.
6. If additional authentication is needed the payer is redirected to the appropriate forms.
7. **Redirection:**
    * On **successful payment**, the payer is redirected to the `resultUrl` specified when creating the invoice.
    * On **failed payment**, the payer is redirected to `failPath`.

### **Advantages of Hosted Page Integration**

✅ **Quick Setup:** No development expertise required.  
✅ **Minimal Effort:** Payop handles the checkout process, reducing merchant workload.  
✅ **Secure and Compliant:** Payment processing fully complies with industry regulations and security standards without requiring additional efforts from merchants.  
✅ **Global Payment Methods:** The payer sees only available methods based on their country and IP.  


### **Best Use Cases**



* Businesses that want **a fast and hassle-free** integration.
* Merchants who **do not want to build** their own checkout page.
* Companies needing **multi-language** and **multi-currency** payment solutions with minimal setup.


## **2. Direct Integration**

The Direct Integration option provides a more advanced and customizable way for merchants to accept payments by bypassing the Payop-hosted checkout page. Instead, merchants integrate specific payment methods directly into their own system, giving them full control over the user experience, design, and data collection flow.

This method is ideal for businesses with development resources who want to embed payments directly into their own UI and reduce friction during the checkout process.


### **How It Works**

**🔹1. Create invoice**

Use your **public key and signature** to create invoice via the API:

*You can generate signature using the script ( [See signature generation instruction section for more details](signatureGenerator.md) )*

```shell
curl -X POST "https://api.payop.com/v1/invoices/create" \
  -H "Content-Type: application/json" \
  -d '{
    "publicKey": "application-xxx",
    "order": {
      "id": "12345",
      "amount": "3",
      "currency": "EUR",
      "description": "Test payment",
      "items": []
    },
    "signature": "GENERATED_SIGNATURE",
    "payer": {
      "email": "test.user@payop.com"
    },
    "language": "en",
    "resultUrl": "https://your.site/result",
    "failPath": "https://your.site/fail"
  }'
 
```


**🔹2. Retrieve Available Payment Methods**

Use your **application ID** to retrieve a list of available payment methods via the API:

```shell
curl -X GET "https://api.payop.com/v1/instrument-settings/payment-methods/available-for-application/{APPLICATION_ID}" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"

```

The response includes method identifiers and the required payer fields for each method. Example response for "Pay by bank":

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
 "data": [
   {
     "identifier": 30000018,
     "type": "bank_transfer",
     "title": "Pay by bank",
     "currencies": ["EUR", "GBP"],
     "countries": ["AT", "ES", "IT", "PT", "FR", "DE", "FI", "NL", "EE", "LT"],
     "config": {
       "fields": [
         { "name": "email", "type": "email", "required": true },
         { "name": "name", "type": "text", "required": true },
         { "name": "date_of_birth", "type": "text", "required": true },
         { "name": "bank_code", "type": "bank_code", "required": true },
         { "name": "bank_type", "type": "bank_type", "required": true },
         { "name": "bank_country", "type": "bank_country", "required": true }
       ]
     }
   }
 ]
}

```


**🔹3. Collect Payer Data** 

Based on the payment method selected, request the required fields from the payer.  

For the example above, the required fields are:  
  * `email`
  * `name`
  * `date_of_birth`
  * `bank_code`
  * `bank_type`
  * `bank_country`

**🔹4. Create Checkout Transaction** 

Once all required data is collected, send a POST request to create the transaction:


```shell
curl -X POST "https://api.payop.com/v1/checkout/create" \
 -H "Content-Type: application/json" \
 -d '{
   "invoiceIdentifier": "YOUR_INVOICE_ID",
   "customer": {
     "email": "test.user@payop.com",
     "name": "John Doe",
     "ip": "192.168.1.1",
     "extraFields": {
       "date_of_birth": "01.01.1990",
       "bank_code": "DEUTDEFF",
       "bank_type": "SEPA",
       "bank_country": "DE"
     }
   },
   "paymentMethod": 30000018,
   "checkStatusUrl": "https://your.site/check-status/{{txid}}"
 }'

```

![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)


```json
{
 "invoiceIdentifier": "YOUR_INVOICE_ID",
 "customer": {
   "email": "test.user@payop.com",
   "name": "John Doe",
   "ip": "192.168.1.1",
   "extraFields": {
     "date_of_birth": "01.01.1990",
     "bank_code": "DEUTDEFF",
     "bank_type": "SEPA",
     "bank_country": "DE"
   }
 },
 "paymentMethod": 30000018,
 "checkStatusUrl": "https://your.site/check-status/{{txid}}"
}
```


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


**🔹5. Check Invoice Status (Polling)**  

Use long-polling to check the status of the transaction using:

```shell
curl -X GET "https://api.payop.com/v1/checkout/check-invoice-status/{invoiceID}" \
 -H "Content-Type: application/json" \
```

![response](https://img.shields.io/badge/possible-response-blue?style=for-the-badge)


```json
{
 "data": {
   "isSuccess": true,
   "status": "success",
   "form": {
     "method": "GET",
     "url": "https://checkout.payop.com/en/payment/success-page/",
     "fields": []
   },
   "url": "https://checkout.payop.com/en/payment/success-page/"
 },
 "status": 1
}

```

**Status-Based Actions**


* **If** `status = success` → Redirect the user to the URL provided in `data.form.url` (e.g., the success page).
* **If** `status = fail` → Redirect the user to the URL provided in `data.form.url`, which will lead to the fail page.
* **If** `status = pending`→ Redirect the user to the URL in `data.form.url`, which points to the payment provider's page. 

Use the `method` and `fields` returned in the `data.form` object to construct a form and submit it from the browser. After the user completes the payment on the provider's side, they will be redirected back to either the success or fail page based on the final result.

**🔹6. Receive IPN (Instant Payment Notification)** 

If IPNs are configured, Payop will automatically notify your server when the transaction status changes. This ensures your backend is updated even if the user does not return to your site. \

*[See Checkout->IPN for more details](../2.Checkout/checkout.md#4-ipn)*


---
### **Checkout Flow Summary**

1. **Call payment methods list endpoint** to get available methods with required payer fields.
2. **Collect required data** from the payer depending on the selected method.
3. **Create a transaction** using the `/checkout/create` endpoint.
4. **Check invoice status** by polling `/checkout/check-invoice-status/{invoiceID}` or wait for an IPN.
5. **Redirect the user** based on the status:
    * `success` → Success page
    * `fail` → Fail page
    * `pending` → Payment provider page → then redirected to final status page

---
### **Advantages of Direct Integration**

✅ **Optimized User Flow**  
By embedding required fields and handling logic on the merchant’s side, the number of steps for the payer is reduced—resulting in a smoother, more streamlined experience.

✅ **Faster Checkout Process**  
No need to navigate through intermediate Payop-hosted method selection pages. The payer is taken directly to the payment form, speeding up the process.

✅ **Ideal for Complex Checkout Use Cases**
Perfect for platforms and systems that require tighter integration or more advanced UX control, such as e-commerce platforms, SaaS tools, or mobile apps.

---
### **Best Use Cases**



* Businesses that **already have** a own custom checkout system.
* Merchants who need **control** over the payment process.
* Companies looking to **minimize the payment steps** for a seamless user experience.


## **Comparison: Hosted Page vs. Direct Integration**

<table>
  <tr>
   <td><strong>Feature</strong>
   </td>
   <td><strong>Hosted Page</strong>
   </td>
   <td><strong>Direct Integration</strong>
   </td>
  </tr>
  <tr>
   <td>Ease of Setup
   </td>
   <td>✅ Quick & Simple – No deep tech setup needed
   </td>
   <td>❌ Requires technical development
   </td>
  </tr>
  <tr>
   <td>Checkout Experience
   </td>
   <td>✅ Managed entirely by Payop
   </td>
   <td>✅ Merchant-managed before redirection
   </td>
  </tr>
  <tr>
   <td>Compliance & Security
   </td>
   <td>✅ Handled by Payop
   </td>
   <td>✅ Handled by Payop
   </td>
  </tr>
  <tr>
   <td>Redirection to Payop
   </td>
   <td>✅ Yes – full checkout via Payop
   </td>
   <td>✅ Yes – final step redirects to Payop for bank/payment
   </td>
  </tr>
  <tr>
   <td>Required Input From Payer
   </td>
   <td>✅ Dynamically requested by Payop
   </td>
   <td>✅ Merchant must collect required fields based on payment method
   </td>
  </tr>
  <tr>
   <td>Speed to Go Live
   </td>
   <td>✅ Instant – Create invoice & redirect
   </td>
   <td>❌ Slower – Requires front/backend implementation
   </td>
  </tr>
  <tr>
   <td>IPN/Webhook Support
   </td>
   <td>✅ Available
   </td>
   <td>✅ Available
   </td>
  </tr>
  <tr>
   <td>Error Handling
   </td>
   <td>✅ Managed by Payop interface
   </td>
   <td>✅ Must handle on frontend/backend
   </td>
  </tr>
  <tr>
   <td>Use Case
   </td>
   <td>✅ Ideal for SMBs, MVPs, quick start
   </td>
   <td>✅ Best for enterprises needing full control over your checkout flow and optimize the payment process
   </td>
  </tr>
</table>



### **Which Integration Should You Choose?**


* **Choose Hosted Page** if you want a **simple, fast, and secure** way to accept payments with minimal development effort.
* **Choose Direct Integration** if you **want full control** over your checkout flow and **optimize** the payment process for better conversion rates.