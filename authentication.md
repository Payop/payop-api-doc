* [Authentication](#authentication)
* [Get authentication token](#authenticate-get-authentication-token)
    * [URL for requests](#url-for-requests)
    * [Successful response](#successful-response)
    * [Errors and failed responses](#errors-and-failed-responses)

# Authentication

Several requests to Payop API require authentication.

Payop API Authentication based on JWT tokens and using custom http header with name **token** for transferring.

The token has a limited "time to live" (TTL). Default token TTL is 30 days.

----
***Note:** We recommend to get new token each 15-20 days, to avoid authentication fails*

----     

```
HTTP-Headers
Content-Type: application/json
token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwMDAyIiwiYWNjZXNzVG9rZW4iOm51bGwsInRpbWUiOjE1NjY5MTk4NDJ9.jebGttoGUOGQORsPyr5smSbE01fEGDjFgUkBCF342sc
```   

## Authenticate (Get authentication token)

### URL for requests

`Content-Type: application/json`

`POST https://payop.com/v1/users/login`

**Parameters**

Parameter | Type   | Description    | Required  |
----------|--------|----------------|-----------| 
email     | string | Email address  |     *     |
password  | string | Password       |     *     |


**Request example**
```shell script
curl -X POST  https://payop.com/v1/users/login \
    -H 'Content-Type: application/json' \
    -d '{"email": "John.McLein@payop.com", "password": "John.McLein PASSWORD"}'
```

### Successful response

Headers
```
HTTP/1.1 200 OK
Content-Type: application/json
token: eyJ0eXAiO...
```
Body
```json
{
    "data": {
        "id": "423131",
        "personalInformation": {
            "email": "John.McLein@payop.com"
        },
        "systemInformation": {
            "role": 1,
            "accountType": 0,
            "approvedStatus": 1
        },
        "status": 1,
        "dateTime": {
            "createdAt": 1566543694,
            "updatedAt": null
        }
    },
    "status": 1
}
```

### Errors and failed responses

**404 Not Found**
```json
{
   "message": "User not found"
}
```

**415 Unsupported Media Type**
```json
{
  "message": "Unsupported media type. Only json allowed"
}
```

**422 Unprocessable Entity**
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