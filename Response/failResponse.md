* [Back to contents](../Readme.md#contents)

# Fail responses examples

* [Requested resource not found](#requested-resource-not-found)
* [HTTP Forbidden](#http-forbidden)
* [Payment method not enabled](#payment-method-not-enabled)
* [Validation fails](#validation-fails)
* [Server error](#server-error)


## Authentication required

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

## HTTP Forbidden

Headers
```
HTTP/1.1 403 HTTP Forbidden
Content-Type: application/json
```
Body
```json
{
    "message": "Access denied."
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