* [Back to contents](../Readme.md#contents)

### **Summary of Invoice API Endpoints**


| # | Endpoint                                                                 | Method | Purpose                                                |
|---|--------------------------------------------------------------------------|--------|--------------------------------------------------------|
| 1 | [`/v1/invoices/create`](#1-create-invoice)                                                   | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Create a new invoice.                                 |
| 2 | [`/v1/invoices/{invoiceID}`](#2-get-invoice-info)                                             | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)  | Fetch detailed info about a specific invoice.         |
| 3 | [`/v1/instrument-settings/payment-methods/available-for-application/{ID}`](#3-get-available-payment-methods) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)  | Get available payment methods for a specific project. |



## **1. Create Invoice**


#### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell

https://api.payop.com/v1/invoices/create

```



#### **Purpose:**



* **This API is used to create an invoice for processing payments.**
* **The payer will be redirected to either the checkout page or a specific payment method, based on the request parameters.**


#### **How It Works:**



* **A <code>POST</code> request is sent with order details, payer information, and payment preferences.**
* **Optionally, a specific payment method ID can be provided to redirect the payer directly to that method.**


 ![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter**         | **Type**         | **Description**                                                                 | **Required** |
|-----------------------|------------------|----------------------------------------------------------------------------------|--------------|
| `publicKey`           | `string`         | Public key issued in the project.                                               | ✅           |
| `order`               | `JSON object`    | Order details.                                                                  | ✅           |
| `order.id`            | `string`         | Payment ID.                                                                     | ✅           |
| `order.amount`        | `string`         | Payment amount.                                                                 | ✅           |
| `order.currency`      | `string`         | Payment currency.                                                               | ✅           |
| `order.description`   | `string`         | Description of payment.                                                         | ❌           |
| `order.items`         | `json array`     | List of products/services.                                                      | ❌           |
| `payer`               | `JSON object`    | Payer details.                                                                  | ✅           |
| `payer.email`         | `string`         | Payer email.                                                                    | ✅           |
| `language`            | `string`         | Language of the checkout page (e.g., `en`, `ru`).                               | ❌           |
| `resultUrl`           | `string`         | URL for successful payment redirection. Allowed to use [template expression](#template-expressions). | ❌ |
| `failPath`            | `string`         | URL for failed payment redirection. Allowed to use [template expression](#template-expressions).    | ❌ |
| `signature`           | `string`         | SHA-256 hash for security verification.                                         | ✅           |
| `paymentMethod`       | `string`         | Specific payment method ID.                                                     | ❌           |
| `metadata`            | `JSON object`    | Additional data for merchant use.                                               | ❌           |


![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)


```shell
curl -X POST "https://api.payop.com/v1/invoices/create" \
 -H "Content-Type: application/json" \
 -d '{
   "publicKey": "YOUR_PUBLIC_KEY",
   "order": {
       "id": "12345",
       "amount": "3",
       "currency": "EUR",
       "items": [
           {
               "id": "487",
               "name": "Item 1",
               "price": "0.90"
           },
           {
               "id": "358",
               "name": "Item 2",
               "price": "2.10"
           }
       ],
       "description": "Test Payment"
   },
   "signature": "GENERATED_SIGNATURE", // Signature generated by secret key, invoice amount, invoice currency, invoice id
   "payer": {
       "email": "test.user@payop.com"
   },
   "paymentMethod": 261,
   "language": "en",
   "resultUrl": "https://your.site/result",
   "failPath": "https://your.site/fail"
}'

```


![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
   "data": "ec2aa893-e7f5-4a0d-98c4-ef1a424eaf5d",
   "status": 1
}

```


> 🧾 **Please Note:**
>* **The invoice identifier is returned in the <code>identifier</code> header.**
>* **Do not use the identifier in the response body (it will be removed in future API versions).**


#### **Wrong Signature:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
 "message": "Wrong signature"
}

```

### Template expressions

Template expressions allow dynamic values to be inserted into strings. They are particularly useful on result pages, where you may want to redirect payers with specific transaction details. Currently, only the parameters listed below support template expressions.

Parameter      |        Patterns          |
---------------|--------------------------| 
resultUrl      | {{invoiceId}},  {{txid}} |
failPath       | {{invoiceId}},  {{txid}} |


Pattern        |        Replacement
---------------|-------------------------------------| 
{{invoiceId}}  | Replaced with Payop invoice ID      |
{{txid}}       | Replaced with Payop transaction ID  |

**Template expression examples:**
```shell
# Template
https://your.site/result-page/?invoiceId={{invoiceId}}&txid={{txid}}
# Result
https://your.site/result-page/?invoiceId=b8bf37ab-fc69-44df-bfeb-b9a879ce20b7&txid=1eeda2f2-d3e1-4edd-853e-3d897bc629b2

# Template
https://your.site/result-page/{{txid}}/
# Result
https://your.site/result-page/1eeda2f2-d3e1-4edd-853e-3d897bc629b2/
```


## **2. Get Invoice Info**


#### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://api.payop.com/v1/invoices/{invoiceID}
```


#### **Purpose:**


* **Retrieves detailed information about a specific invoice.**
* **Includes invoice status, payer details, items, and associated transaction.**


#### **How It Works:**



* **A <code>GET</code> request is sent with the invoice ID.**
* **The response contains invoice status, amount, payment method, and metadata.**


![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/invoices/{invoiceID}" \
 -H "Content-Type: application/json"

```


![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
   "data": {
       "identifier": "81962ed0-a65c-4d1a-851b-b3dbf9750399",
       "status": 0,
       "type": 1,
       "amount": 3,
       "currency": "EUR",
       "orderIdentifier": "test",
       "items": [
           {
               "id": "487",
               "name": "Item 1",
               "price": "0.90"
           },
           {
               "id": "358",
               "name": "Item 2",
               "price": "2.10"
           }
       ],
       "resultUrl": "https://your.site/result",
       "failUrl": "https://your.site/fail",
       "payer": {
           "email": "test.user@payop.com"
       },
       "paymentMethod": {
           "identifier": 261,
           "formType": "standard"
       },
       "createdAt": 1567754398
   },
   "status": 1
}

```


#### **Invoice Not Found:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
 "message": "Invoice (Invoice_ID) not found"
}

```


** **


#### **Possible Invoice Statuses:**


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
   <td>0
   </td>
   <td>New
   </td>
   <td>Invoice created but no action taken.
   </td>
  </tr>
  <tr>
   <td>1
   </td>
   <td>Paid
   </td>
   <td>Invoice was successfully paid.
   </td>
  </tr>
  <tr>
   <td>2
   </td>
   <td>Overdue
   </td>
   <td>Invoice expired (default expiration is 24 hours).
   </td>
  </tr>
  <tr>
   <td>4
   </td>
   <td>Pending
   </td>
   <td>Invoice is awaiting payment.
   </td>
  </tr>
  <tr>
   <td>5
   </td>
   <td>Failed
   </td>
   <td>Payment failed (technical or financial issues).
   </td>
  </tr>
</table>


## **3. Get Available Payment Methods**


#### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://api.payop.com/v1/instrument-settings/payment-methods/available-for-application/{ID}
```



#### **Purpose:**



* **Returns available payment methods for a given application.**
* **Only these payment methods can be used to create an invoice.**


#### **How It Works:**



* **A <code>GET</code> request is sent with the application ID.**
* **The response includes available payment methods, supported currencies, and country restrictions.**


![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)


```shell
curl -X GET "https://api.payop.com/v1/instrument-settings/payment-methods/available-for-application/{APPLICATION_ID}" \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer YOUR_JWT_TOKEN"
```




![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)


```json
{
   "data": [
       {
           "identifier": 336,
           "type": "cards_local",
           "title": "Argencard",
           "logo": "https://payop.com/assets/images/payment_methods/argencard.jpg",
           "currencies": ["USD"],
           "countries": ["AR"],
           "config": {
               "fields": [
                   {
                       "name": "email",
                       "type": "email",
                       "required": true
                   },
                   {
                       "name": "name",
                       "type": "text",
                       "required": true
                   },
                   {
                       "name": "nationalid",
                       "type": "text",
                       "required": true
                   }
               ]
           }
       }
   ],
   "status": 1
}

```
#### **Invalid Token:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge) 


```json
{
 "message": "Authorization token invalid"
}
```
