* [Back to contents](../Readme.md#contents)

# Get available payment methods

* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)
* [Error response example](#error-response-example)
* [Required fields description](#required-fields-description)

## Endpoint description

> **Important!** This endpoint requires [authentication](../Authentication/bearerAuthentication.md).

Get payment methods list available for merchant per application/project.

Please note that you can only use payment methods available for your application when creating an invoice.

**Endpoint:**

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/instrument-settings/payment-methods/available-for-application/{ID}
```

**Parameters:**

Parameter   |  Type  |           Description           |  Required |
------------|--------|---------------------------------|-----------|
ID          | string | Application/Project identifier  |     *     |

<br>

> **Note:** You can find out the project ID in the merchant's admin panel in the **Projects -> Project List -> Details section**
> 
> ![APP ID](../images/application-id.png)

<br>

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)
```shell
Content-Type: application/json
Authorization: Bearer YOUR_JWT_TOKEN
```

## Request example

```shell script
curl -X GET \
  https://api.payop.com/v1/instrument-settings/payment-methods/available-for-application/YOUR_PROJECT_ID \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer YOUR_JWT_TOKEN'
```

## Successful response example

![HEADERS](https://img.shields.io/badge/200-ok-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

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

## Error response example

![401](https://img.shields.io/badge/401-Unauthorized-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 401 Unauthorized
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Authorization token invalid"
}
```

## Required fields description

As you can see, the `config.fields` section of the response contains a description of required the fields.
For direct payments (payments without an intermediate checkout form) that rely on this payment method, you must fill out all the required fields when [create an invoice](createInvoice.md).
Otherwise, during the payment process, the payer will be redirected to the checkout form, where they will have to manually fill in the required fields.

> Note: Please note the way in which the fields are filled in: fields `email`, `phone`, `name` are contained in the `payer` object, and all other necessary fields are contained in a nested `extraFields` object.
> 
> Example:
```json
   {
       "payer": {
           "email": "PAYER_EMAIL@EMAIL.COM",
           "phone": "PAYER_PHONE",
           "name": "PAYER_NAME",
           "extraFields": {
               "nationalid": "GB-123456798",
               "other": "SOME_DATA"
            }
       }
   }
```
 
