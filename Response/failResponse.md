* [Back to contents](../Readme.md#contents)

# Fail responses examples

* [Invalid requests](#invalid-requests)
* [Validation fails](#validation-fails)
* [Requested resource not found](#requested-resource-not-found)
* [Payment method not enabled](#payment-method-not-enabled)
* [Server error](#server-error)

## Invalid requests
    
Headers
```
HTTP/1.1 415 Unsupported Media Type
Content-Type: application/json
```
Body
```json
{
    "message": "Unsupported media type. Only json allowed"
}
```

## Validation fails

Headers
```
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```
Body
```json
{
    "message": {
        "email": [
            "This value should not be blank."
        ],
        "password": [
            "This value should not be blank."
        ]
    }
}
```

## Requested resource not found

Headers
```
HTTP/1.1 404 Not Found
Content-Type: application/json
```
Body
```json
{
    "message": "Invoice not found"
}
```

## Authentication is required

Headers
```
HTTP/1.1 401 Unauthorized
Content-Type: application/json
```
Body
```json
{
    "message":"Full authentication is required to access this resource."
}
```

## Payment method not enabled

Headers
```
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```
Body
```
{
    "message": "Method must be enabled to use it"
}
```

Please contact [Payop support](https://payop.com/en/contact-us) if you want to enable additional payment methods.

## Server error

Headers
```
HTTP/1.1 500 Internal Server Error
Content-Type: application/json
```
Body
```
{
    "message": "Something went wrong, try again or contact support."
}
```

Please contact [Payop support](https://payop.com/en/contact-us) if you faced such cases.