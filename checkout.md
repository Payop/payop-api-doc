# Checkout

### Minimal Checkout Flow

1. Create Invoice
2. Redirect user to invoice preprocessing page
3. Receive IPN (instant payment notification) - in case of transaction was successfully created.

### Create Invoice

**Endpoint**: https://payop.com/v1/invoices/create
**Content-Type**: application/json

**Parameters**
* **publicKey** (required) - application public key. Example: application-0000-0000-0001
* **order** (required) - object with order information
  *  **id** (required) - order id. Example: testOrder
  *  **amount** (required) - order amount. Example: 50.21
  *  **currency** (required) - order currency. Example: USD
  *  **description** - order description.
* **payer** (required) - object with payer information
  *  **email** (required) - payer email.
  *  **phone** - payer phone. **[Required]** depending on the selected payment method.
  *  **name** - payer name. **[Required]** depending on the selected payment method.
* **language** (required) - language code. Available codes: en|ru.
* **resultUrl** (required) - Link to the page of successful payment.
* **failPath** (required) - Link to unsuccessful payment page.
* **signature** (required) - Signature of payment.
* **paymentMethod**  [Selected payment method id]. If not selected, the standard Payop payment page will be displayed.

**Request example**
```json
{
    "publicKey": "application-3b60feb1-eeb8-4215-a494-2382427ffe88",
    "order": {
        "id": "testOrder",
        "amount": "50",
        "currency": "USD",
        "items": [
            {
                "id": "487",
                "name": "Item 1",
                "price": "15"
            },
            {
                "id": "358",
                "name": "Item 2",
                "price": "35"
            }
        ],
        "description": ""
    },
    "signature": "b2f93517c341872c39f79384d9456a0d93350da651e615820d39a6aa626462b3",
    "payer": {
        "email": "user+1@payop.com",
        "phone": "",
        "name": ""
    },
    "paymentMethod": "261",
    "language": "en",
    "resultUrl": "https://test.com/result",
    "failPath": "https://test.com/fail"
}
```

Successful response contains invoice id.

**Response example**

```json
{
    "data": "b10e8f26-7af4-4a83-9bd1-ad3224511b6e",
    "status": 1
}
```

### Redirect user to invoice preprocessing page

After getting invoice id you can redirect user to the invoice preprocessing page.
This page has builtin functionality to make decision about next processes.

**Invoice preprocessing page url**:  https://payop.com//{{locale}}/payment/invoice-preprocessing/{{invoiceId}}

**Url Parameters**:
* **{{language}}** - invoice language
* **{{invoiceId}}** - invoice id

### Receive IPN

IPN will be send only in case of successfully created transaction.
Internal Payop transaction created only after successful request to Acquirer.
IPN request will be send to ipn url which you setup for selected application.

**IPN url**: selected from application
**Method**: POST
**Content-Type**: application/json

**Request example**

```json
{"txid": "b6c9f501-5517-4bc5-89e2-a516fcd7e117"}
```
Using this transaction id (txid) you can get transaction (please see related docs) info,
find there your order id and process this order in your system.



