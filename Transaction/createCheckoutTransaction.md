* [Create transaction](#create-transaction)
    * [URL for requests](#url-for-requests)
    * [Request example](#request-example)
    * [Successful response example](#successful-response-example)
    * [Errors and failed responses](#errors-and-failed-responses)
    * [Using the anti-fraud system](#using-the-anti-fraud-system)

# Intro

**Transaction** - An entity that reflects the money transferring.

----
**Note:** Checkout transaction can be created only in case of successful request to acquirer. 
This mean that tries to pay invoice is not limited, if invoice doesn't have transaction and invoice is not overdue.

----

## Create transaction

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/checkout/create`

**Parameters**

Parameter             |        Type      |                 Description                                                                             |  Required |
----------------------|------------------|---------------------------------------------------------------------------------------------------------|-----------| 
invoiceIdentifier     | string           | Invoice identifier                                                                                      |     *     |
**customer**          | **JSON object**  | Payer/Customer info                                                                                     |     *     |
&emsp;customer.email  | string           | Email                                                                                                   |     *     |
&emsp;customer.ip     | string           | IP adress. We highly recommend adding this parameter to the request for more complete identification of the customer                                                                                               |           |
&emsp;customer.session_id  | string      | Session ID is a custom, unique ID that links a user’s device data with transactions. It should be based on the user’s current browsing session, by tracking cookies for example. Required if you want to [use the anti-fraud system](#using-the-anti-fraud-system)       |           |
&emsp; ...            | string           | Any data related to the payer/customer                                                                  |           |
checkStatusUrl        | string           | [URL to check payment status][status]                                                                   |     *     |
payCurrency           | string           | Currency code. Should be passed in case of the payment currency is different from the order currency    |           |
paymentMethod         | string           | Payment method id. Required if invoice doesn't have payment method                                      |           |
cardToken             | string           | [Bank card token][token]. Required if paymentMethod.formType equal "cards" (bank card payment method)   |           |
authOnly              | bool             | If this parameter is equal to `true`, then authorization places the funds on hold with the customer's bank. When the transaction is [captured](../Checkout/captureVoid.md#capture-transaction), the funds transfer process will occur.    |           |

[token]: ../Checkout/createCardToken.md
[status]: ../Checkout/checkInvoiceStatus.md


### Request example

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


### Successful response example
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

### Errors and failed responses

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```

**404 Not Found**
```json
{
    "message": "Invoice not found"
}
```

**422 Unprocessable Entity**

Error from acquirer
```json
{
    "message": {
        "isSuccess": false,
        "message": "Invalid amount, less than allowed minumum"
    }
}
```

Validation error
```json
{
    "message": {
        "checkStatusUrl": [
            "This value should not be blank."
        ]
    }
}
```

### Using the anti-fraud system

You can integrate an optional device fingerprinting module directly into a web app, by using JavaScript agent. Please, always use CDN hosted script to ensure you always load the latest available version.

1. Include the JavaScript Agent inside the <head> tags of your website or web app.
2. Set a unique session_id for your the client using the seon.config() function.
3. Call the seon.saveSession() function to save session for the device.
4. Add session_id to [Create transaction](#create-transaction) request.
	
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
**Note:** Don’t forget to replace **[session_id]** with your unique session identifier. We recommend to use UUID, but you can use your own implementation as well.

----
```js
seon.config({
	public_key: '2d1888404acc3faaa6797bd3',
        session_id: '[session_id]',
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
