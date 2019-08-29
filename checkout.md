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
  *  **items** (required) - order items. **Can be empty: []**  
  *  **description** - order description.
* **payer** (required) - object with payer information
  *  **email** - payer email. **[Required]** always. 
  *  **phone** - payer phone. **[Required]** depending on the selected payment method.
  *  **name** - payer name. **[Required]** depending on the selected payment method.
  *  **extraFields** - extra payer fields. **[Optional]** depending on the selected payment method.
* **language** (required) - language code. Available codes: en|ru.
* **resultUrl** (required) - Link to the page of successful payment.
* **failPath** (required) - Link to unsuccessful payment page.
* **signature** (required) - Signature of payment.
* **paymentMethod**  [Selected payment method id]. If not selected, the standard Payop payment page will be displayed.

Important! You can create a signature as shown in the "Signature" section. 

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

Actually, you can create an example request in the personal merchants account in the section Projects > REST. 

Successful response contains invoice id.

**Response example**

```json
{
    "data": "b10e8f26-7af4-4a83-9bd1-ad3224511b6e",
    "status": 1
}
```

**Valid payment methods**

It should be noted that when creating an invoice you can only use payment methods available to you.

You can get a list of available payment methods using such an endpoint: https://payop.com/v1/instrument-settings/payment-methods/available-for-user

The request must be executed by GET REQUEST using the token in the header of the http request. You can get token for your account as described in the "Login" section.

**Response example**

```json
{
    "data":[
        {
            "identifier":336,
            "type":"cards_local",
            "formType":"standard",
            "title":"Argencard",
            "logo":"https://payop.com/assets/images/payment_methods/argencard.jpg",
            "parentIdentifier":null,
            "pmIdentifier":"a728eb60-f8ac-11e8-afc4-65c7f5e909d5",
            "currencies":[
                "USD"
            ],
            "countries":[
                "AR"
            ],
            "config":{
                "fields":[
                    {
                        "name":"email",
                        "type":"email",
                        "required":true
                    },
                    {
                        "name":"name",
                        "type":"text",
                        "required":true
                    },
                    {
                        "name":"nationalid",
                        "type":"text",
                        "title":"Consumer`s national id",
                        "required":true
                    }
                ]
            }
        }
    ],
    "status":1
}
```

As you can see, the config > fields section contains the required fields. For direct payment (payment without an intermediate checkout form), you must fill out all the required fields. Otherwise, during the payment process, you will be redirected to the checkout form, where you will have to manually fill in the required fields.

It is important to note the order in which the fields are filled in when creating the invoice.

Fields such as email, phone, name are filled in the payer array.

```json
{
    ...
    "payer": {
        "email": "user+1@payop.com",
        "phone": "",
        "name": ""
    },
    ...
}
```

Complete example you can see above in the Create Invoice > Request example.

If it is necessary to fill in other required fields, they are placed in a nested extraFields array.

```json
{
    ...
    "payer": {
        "email": "user+1@payop.com",
        "phone": "",
        "name": "",
        "extraFields": {
          "nationalid": "GB-123456798"
        }
    },
    ...
}
```

### View created invoice

After creating an invoice you have the opportunity to view it.

**Endpoint**: https://payop.com/v1/invoices/{{invoiceId}}

**Url Parameters**:
* **{{invoiceId}}** - invoice id

### Redirect user to invoice preprocessing page

After getting invoice id you can redirect user to the invoice preprocessing page.
This page has builtin functionality to make decision about next processes.

**Invoice preprocessing page url**:  https://payop.com//{{locale}}/payment/invoice-preprocessing/{{invoiceId}}

**Url Parameters**:
* **{{language}}** - invoice language
* **{{invoiceId}}** - invoice id

If all the required fields are completed, an attempt will be made to pay by the payment method specified when creating the account.
If you missed any of the required fields specified in the properties of the payment method, or did not specify a payment method, then you will be redirected to checkout form.

Also, if you need to enter a card number or fill out a 3DS form to confirm cards payment, you will be redirected to the appropriate forms.

In case of successful payment, you will be redirected to the URL specified in **resultUrl** when creating the invoice. In case of error or inability to make payment to **failPath** accordingly.

### Receive IPN

IPN will be send only in case of successfully created transaction.
Internal Payop transaction created only after successful request to Acquirer.
IPN request will be send to ipn url which you setup for selected application.

**IPN url**: selected from application

**Method**: POST

**Content-Type**: application/json

**Request example**

```json
{
    "invoice": {
        "id": "54b8f367-8c4c-4495-969d-bf4cb46bceb2",
        "txid": null
    }
}
```

Using invoice id from notification you can get invoice info (as indicated in the example above), find there your order id and process this order in your system.
If transaction was created for invoice parameter **txid** will contains transaction identifier.
Using this transaction id (txid) you can get transaction (please see related docs) info.



