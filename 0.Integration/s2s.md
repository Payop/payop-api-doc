* [Back to contents](../Readme.md#contents)

---

# Server-To-Server (S2S) Card Integration

Server-To-Server (S2S) integration allows you to interact directly with the Payop API and control each step of the payment on your side — from collecting card data to handling 3DS authentication. Use this integration if you are collecting card details on your own checkout page and want full control over the payment flow.

> 🧾 **Please Note:**
> * You can see an example of Server-To-Server integration in the [Checkout Demo App repository](https://github.com/Payop/payop-api-doc/blob/master/0.Integration/s2s.md#payop-checkout-demo-application).
> * Server-To-Server integration can only be used for card payment methods with ID **381**, **480**, **481**. You can view the list of available methods for your project using the [Get available payment methods](#1-get-available-payment-methods) request.

---

### Summary of S2S Integration Steps

| # | Step | Method | Description |
|---|------|--------|-------------|
| 1 | [Get available payment methods](#1-get-available-payment-methods) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge) | Retrieve card payment methods available for your project. |
| 2 | [Create invoice](#2-create-invoice) | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Create a new invoice with the card payment method. |
| 3 | [Create card token](#3-create-bank-card-token) | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Tokenize payer's card details. |
| 4 | [Create checkout transaction](#4-create-checkout-transaction) | ![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge) | Submit the transaction using the invoice and card token. |
| 5 | [Check invoice status](#5-check-invoice-status) | ![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge) | Determine next action: handle 3DS or redirect to result page. |
| 6 | [Receive IPN](#6-receive-ipn) | — | Receive final payment result on your server. |


---

## **1. Get Available Payment Methods**

#### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://payop.com/v1/instrument-settings/payment-methods/available-for-application/{ID}
```

#### **Purpose:**

* **Returns available payment methods for your application.**
* **Only methods returned by this endpoint can be used when creating an invoice.**

#### **How It Works:**

* **A `GET` request is sent with the application ID.**
* **The response includes available payment methods, supported currencies, country restrictions, and `formType`.**

> 🧾 **Please Note:**
> * This endpoint requires authentication. Pass your JWT token in the `Authorization` header.
> * You can find your Project ID in the merchant admin panel under **Projects → Projects List → Details**.

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter** | **Type** | **Description** | **Required** |
|---------------|----------|-----------------|--------------|
| `ID` | `string` | Application / Project identifier | ✅ |

**HEADERS**

```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)

```shell
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-for-application/YOUR_PROJECT_ID \
  -H 'Content-Type: application/json' \
  -H 'Authorization: Bearer YOUR_JWT_TOKEN'
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": [
    {
      "identifier": 336,
      "type": "cards_local",
      "formType": "standard",
      "title": "Argencard",
      "logo": "https://payop.com/assets/images/payment_methods/argencard.jpg",
      "parentIdentifier": null,
      "pmIdentifier": "a728eb60-f8ac-11e8-afc4-65c7f5e909d5",
      "currencies": ["USD"],
      "countries": ["AR"],
      "config": {
        "fields": [
          { "name": "email", "type": "email", "required": true },
          { "name": "name", "type": "text", "required": true },
          { "name": "nationalid", "type": "text", "title": "Consumer's national id", "required": true }
        ]
      }
    }
  ],
  "status": 1
}
```

#### **Required fields description:**

The `config.fields` array lists the fields required for the selected payment method. For direct payments, all required fields must be provided when creating an invoice. If any required field is missing, the payer will be redirected to the Payop checkout form to fill in the missing data manually.

| Field in `config.fields` | Where to pass it in the invoice request |
|--------------------------|-----------------------------------------|
| `email` | `payer.email` |
| `phone` | `payer.phone` |
| `name` | `payer.name` |
| Any other field | `payer.extraFields.{fieldName}` |

#### **Invalid Token:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
  "message": "Authorization token invalid"
}
```

---

## **2. Create Invoice**

#### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://payop.com/v1/invoices/create
```

#### **Purpose:**

* **An invoice is the starting point of the payment flow.**
* **It defines the order details, payer information, and the payment method to be used.**

#### **How It Works:**

* **A `POST` request is sent with order details, payer information, and payment preferences.**
* **For S2S card integration, always specify the payment method ID.**

> 🧾 **Please Note:**
> * You can get your Public Key and Secret Key in the merchant admin panel under **Projects → Projects List → Details**.
> * To generate and test a sample invoice request, use the built-in **Test checkout** tool available at **Projects → Test checkout** in your merchant admin panel.
> * For S2S integration, payer data must be provided explicitly at both invoice creation and transaction creation steps. The values passed at transaction creation must match those provided at invoice creation.

<!-- SCREENSHOT: Projects → Projects List → Details page with Public Key and Secret Key fields highlighted -->

<!-- SCREENSHOT: Projects → Test checkout — empty form (Main settings, Payer settings, General settings) -->

<!-- SCREENSHOT: Projects → Test checkout — filled form with Generate config button highlighted -->

<!-- SCREENSHOT: Projects → Test checkout — Show payment page button highlighted -->

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter** | **Type** | **Description** | **Required** |
|---------------|----------|-----------------|--------------|
| `publicKey` | `string` | Public key issued in the project | ✅ |
| `order` | `JSON object` | Order info | ✅ |
| `order.id` | `string` | Payment ID in your system | ✅ |
| `order.amount` | `string` | Amount of the payment | ✅ |
| `order.currency` | `string` | Character code of the payment currency supported by the selected payment method | ✅ |
| `order.description` | `string` | Description of payment | ❌ |
| `order.items` | `json array` | Products or services included in the order. An array containing arbitrary data. Can be an empty array. | ✅ |
| `payer` | `JSON object` | Payer info | ✅ |
| `payer.email` | `string` | Payer email | ✅ |
| `payer.name` | `string` | Payer name | ❌ |
| `payer.phone` | `string` | Payer phone | ❌ |
| `payer.extraFields` | `JSON object` | All required payment method fields other than `email`, `name`, and `phone` | ❌ |
| `language` | `string` | Language (`en`, `ru`) | ✅ |
| `resultUrl` | `string` | Redirect URL on successful payment. Supports [template expressions](#template-expressions). | ✅ |
| `failPath` | `string` | Redirect URL on failed payment. Supports [template expressions](#template-expressions). | ✅ |
| `signature` | `string` | Request signature. See [Signature](#signature) for generation details. | ✅ |
| `paymentMethod` | `string` | Payment method ID. Required for S2S card integration — use one of: `381`, `480`, `481`. | ❌ |
| `metadata` | `JSON object` | Arbitrary data structure for additional merchant data. Result JSON must be less than 800 kB. | ❌ |

**Payer object example:**

```json
{
  "email": "test.email@address.com",
  "extraFields": {
    "nationalid": "123456789",
    "other": "SOME_DATA"
  }
}
```

### Template expressions

Template expressions allow dynamic values to be substituted into `resultUrl` and `failPath` at the time of payment completion.

| Parameter | Patterns |
|-----------|----------|
| `resultUrl` | `{{invoiceId}}`, `{{txid}}` |
| `failPath` | `{{invoiceId}}`, `{{txid}}` |

| Pattern | Replaced with |
|---------|---------------|
| `{{invoiceId}}` | Payop invoice ID |
| `{{txid}}` | Payop transaction ID |

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

### Signature

The request signature ensures the integrity of the data transmitted during the payment process. Signature is required only on invoice creation.

**Encryption method:** SHA-256

Parameters included in the signature **(order matters):**

| Parameter | Description | Type | Example |
|-----------|-------------|------|---------|
| `order.id` | Order ID in your system | string | `FF01`, `354` |
| `order.amount` | Amount of payment | string | `100.0000` |
| `order.currency` | Character code of payment currency | string | `USD`, `EUR` |
| `secretKey` | Project secret key | string | `rekrj1f8bc4wer` |

The signature is generated by creating a SHA-256 hash of the following string:

```
order.amount:order.currency:order.id:secretKey
```

Values are separated by `:`, and the order and case of values must match exactly.

**Signature generation example (PHP):**

```php
<?php
  $order = ['id' => 'FF01', 'amount' => '100.0000', 'currency' => 'USD'];
  ksort($order, SORT_STRING);
  $dataSet = array_values($order);
  $dataSet[] = $secretKey;
  hash('sha256', implode(':', $dataSet));
?>
```

To generate a signature interactively, copy the script below to your machine and run it with `php path/to/script.php`. Enter the values exactly as they appear in your request payload.

```php
<?php
$amount = readline('Enter order amount (integer or numeric string, exactly as in request payload, e.g 100.00, 99.99 etc): ');
$currency = strtoupper(readline('Enter order currency (e.g. USD): '));
$id = readline('Enter your system order ID (e.g. order12345, 12345): ');
$secretKey = readline('Enter secret key of your project: ');
$data = [$amount, $currency, $id, $secretKey];
echo 'Signature: ', PHP_EOL, hash('sha256', implode(':', $data)), PHP_EOL;
```

**Verified signature examples:**

| Amount | Currency | Order ID | Secret key | Result |
|--------|----------|----------|------------|--------|
| `1.2000` | `USD` | `Test-Order-354` | `secretkey1` | `3445000c1f55f447b853fe068529c23fc4188e36aa4984e37836538d95f8e015` |
| `0.4500` | `EUR` | `FK-288-SDC` | `secretkey2` | `15c4c6ee83285dd82e1d7d29984a718cc527f218b8a0bb7e9b951b08ea1f30cd` |

![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)

```shell
curl -X POST \
  https://payop.com/v1/invoices/create \
  -H 'Content-Type: application/json' \
  -d '{
    "publicKey": "YOUR_PUBLIC_KEY",
    "order": {
      "id": "12345",
      "amount": "3.00",
      "currency": "USD",
      "items": [
        { "id": "487", "name": "Item 1", "price": "1.00" },
        { "id": "358", "name": "Item 2", "price": "2.00" }
      ],
      "description": "Order description"
    },
    "signature": "YOUR_SIGNATURE",
    "payer": {
      "email": "payer@example.com",
      "name": "PAYER_NAME"
    },
    "paymentMethod": 381,
    "language": "en",
    "resultUrl": "https://your.site/result?invoiceId={{invoiceId}}&txid={{txid}}",
    "failPath": "https://your.site/fail?invoiceId={{invoiceId}}"
  }'
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

```json
{
  "data": "",
  "status": 1
}
```

> 🧾 **Please Note:**
> * The invoice identifier is returned in the `identifier` **header**, not in the body. Use the `identifier` header value as `invoiceIdentifier` in all subsequent requests.
> * Do not use the identifier from the response body — it will be removed in future API releases.

#### **Wrong Signature:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
  "message": "Wrong signature"
}
```

---

## **3. Create Bank Card Token**

#### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://payop.com/v1/payment-tools/card-token/create
```

#### **Purpose:**

* **Tokenizes the payer's card details collected on your page.**
* **Payop handles PCI-DSS certification and compliance, ensuring that cardholder data is not stored on the merchant's side.**

#### **How It Works:**

* **Collect card details from the payer on your checkout page.**
* **Send the card data to Payop API to receive a card token.**
* **Pass the token as `cardToken` in the [Create checkout transaction](#4-create-checkout-transaction) request.**

> 🧾 **Please Note:**
> Access to card tokenization is available upon request and requires a valid **PCI DSS certificate**. Please contact [Payop support](https://payop.com/en/contact-us) and provide your certificate to initiate the activation process.

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter** | **Type** | **Description** | **Required** |
|---------------|----------|-----------------|--------------|
| `invoiceIdentifier` | `string` | Invoice identifier | ✅ |
| `pan` | `string` | Bank card number | ✅ |
| `expirationDate` | `string` | Expiration date. Format `MM/YY` (e.g. `12/28`) | ✅ |
| `cvv` | `string` | CVV / CVC | ✅ |
| `holderName` | `string` | Cardholder name | ✅ |

![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)

```shell
curl -X POST \
  https://payop.com/v1/payment-tools/card-token/create \
  -H 'Content-Type: application/json' \
  -d '{
    "invoiceIdentifier": "INVOICE_IDENTIFIER",
    "pan": "5555555555554444",
    "expirationDate": "12/28",
    "cvv": "123",
    "holderName": "HOLDER NAME"
  }'
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": {
    "token": "1ay4YEZXF75BTraFw/sPJ9iMJVOr/bR/UeEPp",
    "expired_at": 1567765561
  },
  "status": 1
}
```

| Field | Description |
|-------|-------------|
| `token` | Card token. Pass this value as `cardToken` when creating a checkout transaction. |
| `expired_at` | Unix timestamp. The token is valid until this point in time. Proceed to create a checkout transaction before the token expires. |

#### **Service Unavailable:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
  "message": "Card tokenization is temporarily unavailable. Please contact support"
}
```

#### **Forbidden:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

Card tokenization is not enabled for your application. Contact [Payop support](https://payop.com/en/contact-us) to request access.

```json
{
  "message": "Card tokenization is not available for your application. Please contact support"
}
```

#### **Unprocessable Entity:**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

The card details you provided are invalid. Check the `message` field for specific validation errors.

```json
{
  "message": {
    "pan": ["Invalid card number."],
    "expirationDate": ["This value is not valid."]
  }
}
```

---

## **4. Create Checkout Transaction**

#### **Endpoint:**

![POST](https://img.shields.io/badge/-POST-yellow?style=for-the-badge)

```shell
https://payop.com/v1/checkout/create
```

#### **Purpose:**

* **Submits the transaction using the invoice identifier and the card token.**
* **Initiates the payment processing with the acquirer.**

#### **How It Works:**

* **A `POST` request is sent with the invoice identifier, customer details, and card token.**
* **After a successful response, proceed to [Check invoice status](#5-check-invoice-status) to handle 3DS or redirect the payer.**

> 🧾 **Please Note:**
> * Checkout transactions can be created only in case of a successful request to the acquirer. Attempts to pay an invoice are not limited if the invoice does not have a transaction yet and is not overdue.
> * The longest period between creating an invoice and making a checkout is **24 hours**. After that, the invoice will expire.

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter** | **Type** | **Description** | **Required** |
|---------------|----------|-----------------|--------------|
| `invoiceIdentifier` | `string` | Invoice identifier | ✅ |
| `customer` | `JSON object` | Payer / Customer info | ✅ |
| `customer.name` | `string` | Name | ✅ |
| `customer.email` | `string` | Email | ✅ |
| `customer.ip` | `string` | Payer's IP address. If not provided, Payop will use the IP of the server that sent the request, which may lead to incorrect identification of the payer. We strongly recommend passing this parameter explicitly. | ❌ |
| `...` | `string` | Any additional data related to the payer / customer | ❌ |
| `checkStatusUrl` | `string` | URL to check payment status | ✅ |
| `payCurrency` | `string` | Currency code. Should be passed in case the payment currency differs from the order currency. | ❌ |
| `paymentMethod` | `string` | Payment method ID. Required if the invoice does not have a payment method specified. | ❌ |
| `cardToken` | `string` | Bank card token. Required when `paymentMethod.formType` equals `cards`. | ❌ |

**If `paymentMethod.formType` is `cards`:**

Include `cardToken` with the value received from the [Create card token](#3-create-bank-card-token) request.

![POST](https://img.shields.io/badge/request-post-yellow?style=for-the-badge)

```shell
curl -X POST \
  https://payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
    "invoiceIdentifier": "INVOICE_IDENTIFIER",
    "customer": {"email": "test@email.com", "name": "CUSTOMER_NAME"},
    "checkStatusUrl": "https://your.site/check-status/{{txid}}",
    "payCurrency": "EUR",
    "paymentMethod": 381,
    "cardToken": "CARD_TOKEN"
  }'
```

**If `paymentMethod.formType` is not `cards`:**

Omit the `cardToken` field entirely.

```shell
curl -X POST \
  https://payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
    "invoiceIdentifier": "INVOICE_IDENTIFIER",
    "customer": {"email": "test@email.com", "name": "CUSTOMER_NAME"},
    "checkStatusUrl": "https://your.site/check-status/{{txid}}",
    "payCurrency": "EUR",
    "paymentMethod": 381
  }'
```

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

```json
{
  "data": {
    "isSuccess": true,
    "message": "",
    "txid": "e6c8ba69-b961-4e93-a083-2097f30dfbd9"
  },
  "status": 1
}
```

After receiving a successful response, proceed to [Check invoice status](#5-check-invoice-status) to determine the next step — the payer will be redirected to complete the payment, including any required authentication, before being returned to your result page.

---

## **5. Check Invoice Status**

#### **Endpoint:**

![GET](https://img.shields.io/badge/-GET-darkgreen?style=for-the-badge)

```shell
https://payop.com/v1/checkout/check-invoice-status/{invoiceID}
```

#### **Purpose:**

* **Provides invoice lifecycle states at the moment of request.**
* **Used to determine the next action after creating a checkout transaction.**

#### **How It Works:**

* **A `GET` request is sent with the invoice ID.**
* **The response tells you whether to redirect the payer to 3DS, wait for processing, or redirect to a result page.**

> 🧾 **Please Note:**
> This endpoint is intended for use during an active payment session only. For the final payment result, rely exclusively on the [IPN](#6-receive-ipn) sent to your server. Do not use invoice status polling as a substitute for IPN-based order fulfilment.

![Key Parameters](https://img.shields.io/badge/key_parameters-lightgray?style=for-the-badge)

| **Parameter** | **Type** | **Required** |
|---------------|----------|--------------|
| `invoiceID` | `string` | ✅ |

**HEADERS**

```shell
Content-Type: application/json
```

![GET](https://img.shields.io/badge/request-get-darkgreen?style=for-the-badge)

```shell
curl -X GET \
  https://payop.com/v1/checkout/check-invoice-status/{invoiceID} \
  -H 'Content-Type: application/json'
```

### Response handling

Check the response fields in the following order. The list is sorted by importance of checks.

---

**1. `data.form` is not empty → redirect the payer**

**Case A — 3DS required (`data.form.method` is `POST`)**

The payer must be redirected to their bank's ACS (3DS) page via an HTML form submitted with the `POST` method. This must happen in the payer's browser, not server-side.

```html
<form id="acs-form"
      action="{{ data.form.url }}"
      method="POST"
      enctype="application/x-www-form-urlencoded">
  <input type="hidden" name="PaReq"   value="{{ data.form.fields.PaReq }}" />
  <input type="hidden" name="MD"      value="{{ data.form.fields.MD }}" />
  <input type="hidden" name="TermUrl" value="{{ data.form.fields.TermUrl }}" />
</form>
<script>document.getElementById("acs-form").submit();</script>
```

Replace `{{ ... }}` placeholders with the actual values from the API response. After the payer completes 3DS, Payop processes the result and delivers the final payment status via IPN.

**Case B — No 3DS (`data.form.method` is `GET`)**

```javascript
window.location.href = data.form.url;
```

**2. `data.status` is `pending` and `data.url` is empty → repeat after 5–10 seconds**

**3. `data.status` is `success` → redirect to `data.url`**

**4. `data.status` is `fail` → redirect to `data.url`**

**5. Exceptional case → contact [Payop support](https://payop.com/en/contact-us)**

---

> 🧾 **Note on the transition page:**
> In some flows, `checkInvoiceStatus` may return a URL pointing to Payop's internal transition page. This page performs additional processing silently:
> * **If you use Payop's waiting page** — the system redirects the payer to the 3DS page or result page automatically. No additional action is required.
> * **If you use your own waiting page** — after the transition page completes, the payer is redirected back to your page. Call `checkInvoiceStatus` again to get the updated state and proceed accordingly.

---

### Successful response examples

**GET Redirect**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": {
    "isSuccess": true,
    "status": "pending",
    "form": {
      "method": "GET",
      "url": "https://pay.skrill.com/app/?sid=468",
      "fields": []
    },
    "url": "https://pay.skrill.com/app/?sid=468"
  },
  "status": 1
}
```

**POST Redirect (3DS)**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": {
    "isSuccess": true,
    "status": "pending",
    "form": {
      "method": "POST",
      "url": "https://acs.anybank.com/",
      "fields": {
        "PaReq": "fmn3o8usfjlils",
        "MD": "81962ed0-a65c",
        "TermUrl": "https://payop.com/3ds-result"
      }
    },
    "url": "https://acs.anybank.com/"
  },
  "status": 1
}
```

**Repeat request**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": {
    "isSuccess": true,
    "status": "pending",
    "form": [],
    "url": ""
  },
  "status": 1
}
```

**Redirect to Success Page**

![response](https://img.shields.io/badge/success-response-green?style=for-the-badge)

```json
{
  "data": {
    "isSuccess": true,
    "status": "success",
    "message": "",
    "form": { "url": "https://your_result_page_url" },
    "url": "https://your_result_page_url"
  },
  "status": 1
}
```

**Redirect to Fail Page**

![response](https://img.shields.io/badge/fail-response-red?style=for-the-badge)

```json
{
  "data": {
    "isSuccess": true,
    "status": "fail",
    "message": "",
    "form": [],
    "url": "https://your_result_page_url/fail",
    "invoiceIdentifier": {
      "value": "INVOICE_IDENTIFIER",
      "null": false
    }
  },
  "status": 1
}
```

---

## **6. Receive IPN**

Payop sends an Instant Payment Notification (IPN) to your server when the transaction reaches a final state. The IPN is the authoritative source of the final payment result. Base your order fulfilment logic on the IPN.

Implement an IPN handler on your server and configure its URL in your Payop project settings under **Projects → Projects List → Details → Callback / IPN URL**.

---

## **Payop Checkout Demo Application**

This demo application illustrates the S2S (Server-To-Server) card payment integration flow using the Payop API.

> 🧾 **Please Note:**
> This application is intended to demonstrate the integration flow only. Ensure your production implementation includes proper error handling and input validation.

#### **Preconditions:**

1. [Register an account at Payop.com](https://payop.com/en/auth/registration), complete pre-check verification, and get access to the merchant dashboard.
2. To use card payment methods via S2S integration, card tokenization must be enabled for your Payop application. This requires a valid **PCI DSS certificate**. Please contact [Payop support](https://payop.com/en/contact-us) and provide your certificate to initiate the activation process.

#### **Running on dev:**

1. Clone the repository:

```shell
git clone https://github.com/Payop/checkout-demo-app.git
```

2. Install [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/).

3. Navigate to the application directory and create the configuration file:

```shell
cp .env.dist .env
```

4. Open `.env` and fill in the configuration parameters according to your Payop project settings. The required parameters can be found in the Payop merchant admin panel under **Projects → Projects List → Details**.

5. Start the application:

```shell
make up
```

The application will start and become available on the port specified in the `NGINX_PORT` configuration parameter.
