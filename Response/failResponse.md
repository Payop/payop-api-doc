* [Back to contents](../Readme.md#contents)

# Error response examples

* [Requested resource not found](#requested-resource-not-found)
* [HTTP Forbidden](#http-forbidden)
* [Payment method not enabled](#payment-method-not-enabled)
* [Validation fails](#validation-fails)
* [Server error](#server-error)

## Authentication required

![401](https://img.shields.io/badge/401-Unauthorized-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 401 Unauthorized
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Full authentication is required to access this resource."
}
```

## HTTP Forbidden

![403](https://img.shields.io/badge/403-Forbidden-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 403 HTTP Forbidden
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Access denied."
}
```

## Requested resource not found

![404](https://img.shields.io/badge/404-Not%20Found-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 404 Not Found
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Invoice not found"
}
```

## Payment method not enabled

![422](https://img.shields.io/badge/422-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Method must be enabled to use it"
}
```

Please contact [Payop support](https://payop.com/en/contact-us) if you want to enable additional payment methods.

## Validation fails

![422](https://img.shields.io/badge/422-Unprocessable%20Entity-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 422 Unprocessable Entity
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

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

![500](https://img.shields.io/badge/500-Internal%20Server%20Error-red?style=for-the-badge)

![HEADERS](https://img.shields.io/badge/-HEADERS-yellowgreen?style=for-the-badge)

```shell
HTTP/1.1 500 Internal Server Error
Content-Type: application/json
```

![BODY](https://img.shields.io/badge/-BODY-blueviolet?style=for-the-badge)

```json
{
  "message": "Something went wrong, try again or contact support."
}
```

Please contact [Payop support](https://payop.com/en/contact-us)  if you face such cases.