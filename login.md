
# Login

1. Send POST request to [https://payop.com/v1/users/login](https://app.prod.payop.com/v1/users/login) with data:
```
{
	"email": "YOUR_EMAIL@payop.com",
	"password": "YOUR PASS"
}
```

2. Depends on your data you can receive next responses:
- Http status: 415 Unsupported Media Type

  Body: 
  ```
    {
      "message": "Unsupported media type. Only json allowed"
    }
  ```
  
  Explanation: Invalid json

- Http status: 404 Not Found

  Body: 
  ```
    {
       "message": "User not found"
    }
  ```
  
  Explanation: You entered wrong email or password

- Http status: 200 OK

  Body: 
  
  ```
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
Explanation: You successfully entered to the system
3. If on the step 2 You successfully entered to the system.
You can get system token from header with title `token`, that could be for example like this: `eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEwMDAyIiwiYWNjZXNzVG9rZW4iOm51bGwsInRpbWUiOjE1NjY5MTk4NDJ9.jebGttoGUOGQORsPyr5smSbE01fEGDjFgUkBCF342sc`