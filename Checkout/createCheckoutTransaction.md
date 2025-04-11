* [Back to contents](../Readme.md#contents)

# Create checkout transaction

* [Intro](#intro)
* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Using the anti-fraud system](#using-the-anti-fraud-system)

## Intro

**Transaction** - is an entity that reflects the money transfer.

----

**Note:** Checkout transactions can be created only in 
case of a successful request to the acquirer. This means 
that attempts to pay an invoice are not limited if the invoice 
doesn't have a transaction yet and is not overdue.

----

----

**Note:** The longest period between creating an 
invoice and making a checkout is 24 hours. 
After that, the invoice will expire.

----

## Endpoint description

**Endpoint:**

![POST](https://img.shields.io/badge/-POST-green?style=for-the-badge)

```shell
https://api.payop.com/v1/checkout/create
```    

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

**Parameters:**

Parameter                       | Type            | Description                                                                                                                                                                                                                                                        | Required |
--------------------------------|-----------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------|
invoiceIdentifier               | string          | Invoice identifier                                                                                                                                                                                                                                                 | *        |
**customer**                    | **JSON object** | Payer/Customer info                                                                                                                                                                                                                                                | *        |
&emsp;customer.name             | string          | Name                                                                                                                                                                                                                                                              | *        |
&emsp;customer.email            | string          | Email                                                                                                                                                                                                                                                              | *        |
&emsp;customer.ip               | string          | IP adress. We highly recommend adding this parameter to the request for more complete identification of the customer                                                                                                                                               |          |
&emsp; ...                      | string          | Any data related to the payer/customer                                                                                                                                                                                                                             |          |
checkStatusUrl                  | string          | [URL to check payment status](checkInvoiceStatus.md)                                                                                                                                                                                                              | *        |
payCurrency                     | string          | Currency code. Should be passed in case of the payment currency is different from the order currency                                                                                                                                                               |          |
paymentMethod                   | string          | Payment method id. Required if invoice doesn't have payment method                                                                                                                                                                                                 |          |   
authOnly                        | bool            | If this parameter is equal to `true`, then authorization places the funds on hold with the customer's bank. When the transaction is [captured](captureTransaction.md), the funds transfer process will occur.                                                      |          |


## Request example
<!--1. If `paymentMethod.formType` is **cards**:

```shell
curl -X POST \
  https://api.payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "INVOICE_IDENTIFIER",
	"customer": {"email": "test@email.com", "name":"CUSTOMER_NAME"},
	"checkStatusUrl": "https://your.site/check-status/{{txid}}",
	"payCurrency": "EUR",
	"paymentMethod": 381,
}'
```

2. If `paymentMethod.formType` is **not cards**:-->


```shell
curl -X POST \
  https://api.payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "INVOICE_IDENTIFIER",
	"customer": {"email": "test@email.com", "name":"CUSTOMER_NAME"},
	"checkStatusUrl": "https://your.site/check-status/{{txid}}",
	"payCurrency": "EUR",
	"paymentMethod": 381
}'
```

## Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

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

Add the generated transaction ID to the waiting 
page `https://checkout.payop.com/en/payment/wait-page/{{txid}}` and redirect the user to that page. 
You can then use the check invoice status 
query to track the transaction's progress.


## Using the anti-fraud system

You can integrate an optional device fingerprinting module 
directly into a web app by using a JavaScript agent. Please always use a
CDN hosted script to ensure you always load the latest available version.

1. Include the JavaScript Agent inside the tags of your website or web app.
2. Set a unique `session_id` for your client using the `seon.config()` function.
3. Call the `seon.getBase64Session()` function to get the encrypted payload for the device.
4. Add `seon_session` to  [create transaction](createCheckoutTransaction.md#endpoint-description) request.
	
```html
<html>
    <head>
        ...
        <script src="https://cdn.seon.io/js/v6/agent.js"></script>
    </head>
  	<body>
    	...
  	</body>
</html>
```

----

**Note:** Don’t forget to replace **{session_id}** with your unique session identifier. We recommend to use UUID, but you can use your own implementation as well.

----

```js
seon.config({
        session_id: '{session_id}',
        audio_fingerprint: true,
        canvas_fingerprint: true,
        webgl_fingerprint: true,
        onSuccess: function(message) {
		console.log("success", message);
        },
        onError: function(message) {
            	console.log("error", message);
        }
});
seon.getBase64Session(function(data) {
        if (data) {
                console.log("Session payload", data);
        } else {
                console.log("Failed to retrieve session data.");
        }
});
```
