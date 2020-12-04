* [Back to contents](../Readme.md#contents)

# Bearer Authentication

Some requests to Payop API require authentication.

Payop API Authentication is based on JWT tokens and uses [Bearer Authentication](https://swagger.io/docs/specification/authentication/bearer-authentication/).
Client must send this token in the `Authorization` header when making requests to protected resources.

Headers
```
Content-Type: application/json
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwMDAyIiwiYWNjZXNzVG9rZW4iOm51bGwsInRpbWUiOjE1NjY5MTk4NDJ9.jebGttoGUOGQORsPyr5smSbE01fEGDjFgUkBCF342sc
``` 

----
**Note:** Authentication using `token` header is **deprecated**. 
This functionality may be removed in next major api version.

----     

## Get authentication token

You can **create** and **view** your API keys in [your account](https://payop.com/en/profile/settings/jwt-token).

![Payop JWT Tokens page](../images/payop-jwt-page.png)

----
**Note:** After creating token you will see it only once, right after creation.
But you can create as many tokens as you need.

----

After you **delete** your token you can't authenticate with this token anymore.

You can set token **expiration date**. After this date you can't authenticate with this token anymore.
If expiration date is not set, the token can be used until it is deleted. 

