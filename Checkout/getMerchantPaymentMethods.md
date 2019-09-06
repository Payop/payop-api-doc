* [Merchant payment methods](#merchant-payment-methods)
    * [URL for requests](#url-for-requests)
    * [Request example](#request-example)
    * [Successful response example](#successful-response-example)

# Merchant payment methods

Get payment methods list available for merchant.

You should be noted that when creating an invoice you can only use payment methods available to you.

----
**Note:** This URL require [authentication](../authentication.md).

----

### URL for requests

`Content-Type: application/json`

`GET https://payop.com/v1/instrument-settings/payment-methods/available-for-user`

### Request example

```shell script
curl -X GET \
  https://payop.com/v1/instrument-settings/payment-methods/available-for-user \
    -H 'Content-Type: application/json' \
  -H 'token: eyJ0eXAiOiJKV...
```


### Successful response example

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
token: eyJ0eXAiOiJKV...
```

Body
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

As you can see, the config > fields section contains the required fields.
For direct payment (payment without an intermediate checkout form), you must fill out all the required fields.
Otherwise, during the payment process, you will be redirected to the checkout form, where you will have to manually fill in the required fields.

It is important to note the order in which the fields are filled in when creating the invoice.

Fields such as email, phone, name are filled in the payer array.

```json
{
    "payer": {
        "email": "user+1@payop.com",
        "phone": "",
        "name": ""
    }
}
```

Complete example you can see above in the Create Invoice > Request example.

If it is necessary to fill in other required fields, they are placed in a nested extraFields array.

```json
{
    "payer": {
        "email": "user+1@payop.com",
        "phone": "",
        "name": "",
        "extraFields": {
          "nationalid": "GB-123456798"
        }
    },
}
```
