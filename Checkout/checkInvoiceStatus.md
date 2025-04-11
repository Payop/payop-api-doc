* [Back to contents](../Readme.md#contents)

# Check invoice status

* [Intro](#intro)
* [How to use data from response](#how-to-use-data-from-response)
* [Endpoint description](#endpoint-description)
* [Request example](#request-example)
* [Successful response example](#successful-response-example)

## Intro

This API endpoint provides invoice lifecycle states at the moment of request.

We use it for the following cases:

* After creating a checkout transaction - to make a decision on how to proceed with the payment
* When the payment is finished, but an acquirer doesn’t send a notification for some time (repeat the request and wait
  until the invoice status is changed)
* When the invoice transaction state is changed to FINAL (accepted, failed)

## How to use data from response

Based on the responses below, you can choose from several flows to proceed:

----

**Note:** The list is sorted by importance of checks.

----

1. The `data.form` response field is not empty - redirect user (GET/POST) to the URL specified in field `data.form.uri`.
   Usually, a POST request is a payer bank’s 3DS page. So, you have to send a form with
   an `enctype="application/x-www-form-urlencoded"` attribute. This request should be InBrowser (Normal POST
   request: [https://stackoverflow.com/a/15262442/2090853](https://stackoverflow.com/a/15262442/2090853))

   `data.form` can have the following
   structure: `['url' => url where make request, 'method' => 'http method GET|POST', 'fields' => [array with formFieldName => formFieldValue]]`

    * Example for GET:
      ```php
      [
         'url' => 'https://pay.skrill.com/app/?sid=9345093478', 
         'method' => 'GET', 'fields' => []
      ]
      ```
    * Example for POST:
      ```php
      [
         'url' => 'https://acs.anybank.com/',
         'method' => 'POST',
         'fields' => [
            'PaReq' => 'fmn3o8usfjlils', 
            'MD' => '8ec777d6-685d-4e06-b356-d7673acb47ba', 
            'TermUrl' => 'https://api.payop.com/v1/url'
         ]
      ]
      ```
2. A `data.status` response field is `pending` and a `data.url` field is empty - repeat  the invoice status request after 5-10 seconds.
3. If you don't receive the final status of the transaction (Accepted or Failed), we recommend 
   executing the request within an hour at intervals of 1 minute, 5 minutes, 10 minutes, and so on. 
   A `data.status` response field is `success` - redirect to the URL specified in field `data.url`.
4. A `data.status` responsive field is `fail` - redirect to the URL specified in the `data.url` field.
5. Exceptional case: Something went wrong on the Payop side. Contact [Payop support](https://payop.com/en/contact-us).

## Endpoint description

**Endpoint:**

![GET](https://img.shields.io/badge/-GET-blue?style=for-the-badge)

```shell
https://api.payop.com/v1/checkout/check-invoice-status/{invoiceID}
```

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
Content-Type: application/json
```

**Parameters:**

Parameter | Type   | Required |
----------|--------|----------|
invoiceID | string | *        |

## Request example

```shell
curl -X GET \
  https://api.payop.com/v1/checkout/check-invoice-status/{invoiceID}
    -H 'Content-Type: application/json'
```

## Successful response example

![200](https://img.shields.io/badge/200-OK-blue?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-Headers-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 200 OK
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-Body-blueviolet?style=for-the-badge)

* GET Redirect

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

* POST Redirect (send form)

```json
{
  "data": {
    "isSuccess": true,
    "status": "pending",
    "form": {
      "method": "POST",
      "url": "https://pay.skrill.com/app/?sid=468",
      "fields": {
        "PaReq": "fmn3o8usfjlils",
        "MD": "81962ed0-a65c",
        "TermUrl": "https://payop.com/3ds-result"
      }
    },
    "url": "https://pay.skrill.com/app/?sid=468"
  },
  "status": 1
}
```

* Repeat request

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

* Redirect to Success Page

```json
{
  "data": {
    "isSuccess": true,
    "status": "success",
    "message": "",
    "form": {
      "url": "https://your_result_page_url"
    },
    "url": "https://your_result_page_url"
  },
  "status": 1
}
```

* Redirect to Fail Page

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