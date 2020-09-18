# Check invoice status

This API endpoint provide invoice lifecycle states in moment of request.

We use it for below cases:
 * After create checkout transaction - to make decision, what to do next to continue payment
 * When payment is finished, but acquirer don't send notification for some time (repeat request and wait while invoice status changed)
 * When invoice transaction changes state to FINAL (accepted, failed)
 
### How to use data from response

Based on below responses can be chosen several ways what to do next:

----
**Note:** The list is sorted by importance of checks.

----

1. Response['form'] is not empty - redirect user (GET/POST) to Response['form']['url'].

    Usually POST request - this is payer bank 3DS page. So you have to send form 
    with enctype='application/x-www-form-urlencoded' attribute and this request 
    should be InBrowser (Normal POST request: https://stackoverflow.com/a/15262442/2090853)

    Response['form'] can has next structure:  `['url' => url where make request, 'method' => 'http method GET|POST', 'fields' => [array with formFieldName => formFieldValue]]`

    * Example for GET:
    
        `['url' => 'https://pay.skrill.com/app/?sid=9345093478', 'method' => 'GET', 'fields' => []]`
        
    * Example for POST:
        
        ```
         [
            'url' => 'https://acs.anybank.com/',
            'method' => 'POST',
            'fields' => ['PaReq' => 'fmn3o8usfjlils', 'MD' => '8ec777d6-685d-4e06-b356-d7673acb47ba', 'TermUrl' => 'https://payop.com/v1/url']
         ]
        ```
2. Response['status'] is "pending" and Response['url'] empty - repeat invoice status request after 5-10 seconds.
3. Response['status'] is "success" - redirect to Response['url']
4. Response['status'] is "fail" - redirect to Response['url']
5. Exceptional case. Something went wrong on the Payop side. Contact Payop support.

### URL for requests

`Content-Type: application/json`

`GET https://payop.com/v1/checkout/check-invoice-status/{invoiceID}"`

**Parameters**

Parameter   |  Type  |  Required |
------------|--------|-----------| 
invoiceID   | string |     *     |

### Request example

```shell script
curl -X GET \
  https://payop.com/v1/checkout/check-invoice-status/81962ed0-a65c-4d1a-851b-b3dbf9750399 \
    -H 'Content-Type: application/json'
```

### Successful response example

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
```

Body
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

* Redirect to Success/Fail page
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
 
 
