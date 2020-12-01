* [Back to contents](../Readme.md#contents)

# Create checkout transaction

* [Intro](#intro)
* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Using the anti-fraud system](#using-the-anti-fraud-system)

## Intro

**Transaction** - An entity that reflects the money transferring.

----
**Note:** Checkout transaction can be created only in case of successful request to acquirer. 
This means that tries to pay invoice is not limited, if invoice doesn't have transaction yet and is not overdue.

----

----
***Note:** The longest period between creating invoice and making checkout is 24 hours. After that invoice will be marked as overdue.*

----

## Endpoint description

**Endpoint:**

    POST https://payop.com/v1/checkout/create
    
**Headers:**

    Content-Type: application/json

**Parameters:**

Parameter                       | Type            | Description                                                                                                                                                                                                                                                        | Required |
--------------------------------|-----------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------|
invoiceIdentifier               | string          | Invoice identifier                                                                                                                                                                                                                                                 | *        |
**customer**                    | **JSON object** | Payer/Customer info                                                                                                                                                                                                                                                | *        |
&emsp;&emsp;customer.email      | string          | Email                                                                                                                                                                                                                                                              | *        |
&emsp;&emsp;customer.ip         | string          | IP adress. We highly recommend adding this parameter to the request for more complete identification of the customer                                                                                                                                               |          |
&emsp;&emsp;customer.session_id | string          | Session ID is a custom, unique ID that links a user’s device data with transactions. It should be based on the user’s current browsing session, by tracking cookies for example. Required if you want to [use the anti-fraud system](#using-the-anti-fraud-system) |          |
&emsp;&emsp; ...                | string          | Any data related to the payer/customer                                                                                                                                                                                                                             |          |
checkStatusUrl                  | string          | [URL to check payment status](checkInvoiceStatus.md))                                                                                                                                                                                                              | *        |
payCurrency                     | string          | Currency code. Should be passed in case of the payment currency is different from the order currency                                                                                                                                                               |          |
paymentMethod                   | string          | Payment method id. Required if invoice doesn't have payment method                                                                                                                                                                                                 |          |
cardToken                       | string          | [Bank card token](createCardToken.md). Required if paymentMethod.formType equal "cards" (bank card payment method)                                                                                                                                                 |          |
authOnly                        | bool            | If this parameter is equal to `true`, then authorization places the funds on hold with the customer's bank. When the transaction is [captured](captureTransaction.md), the funds transfer process will occur.                                                      |          |


## Request example

```shell script
curl -X POST \
  https://payop.com/v1/checkout/create \
  -H 'Content-Type: application/json' \
  -d '{
	"invoiceIdentifier": "e61dfa44-4987-400a-b58e-cd550aae9613",
	"customer": {"email": "test@email.com", "ip": "127.0.0.1"},
	"checkStatusUrl": "https://your.site/check-status/{{txid}}",
	"payCurrency": "EUR",
	"paymentMethod": 381,
	"cardToken": "sdffsdfsdf"
}'
```

## Successful response example
Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
identifier: 81962ed0-a65c-4d1a-851b-b3dbf9750399
```

Body
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

## Using the anti-fraud system

You can integrate an optional device fingerprinting module directly into a web app, by using JavaScript agent. Please, always use CDN hosted script to ensure you always load the latest available version.

1. Include the JavaScript Agent inside the <head> tags of your website or web app.
2. Set a unique session_id for your the client using the seon.config() function.
3. Call the seon.saveSession() function to save session for the device.
4. Add session_id to [Create transaction](createCheckoutTransaction.md#endpoint-description) request.
	
```html
<html>
	<head>
    		...
    		<script src="https://cdn.seon.io/v3.0/js/agent.js"></script>
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
	public_key: '2d1888404acc3faaa6797bd3',
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
seon.saveSession(function (success) {
        if (success) {
            	console.log('Session data has been saved!', success)
        } else {
            	console.log('Failed to save session data.')
        }
})

```
