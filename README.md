

## PROJECT DESCRIPTION

The following are the process to execute this project. For the purpose of explanation, I have added a `test paystack key` in the project. 

### SETUP
Clone the from github, `cd` into the project root folder and install or update composer

```
git clone https://github.com/devsahm/FandK-assessment.git

composer install
```

### Migrate and Seed Database

```
php artisan migrate
php artisan db:seed
```
### Task

**POST /api/v1/register** - register a new user and send a welcome email to user

Request parameters:\
`username` -a unique username \
`email` - a valid email \
`password`- password \
`password_confirmation` - repeat password 

`--url` /api/v1/register \
`--header` 'Accept: application/json' \
`--header` 'Content-Type: application/json' \
 `--data` '{"username":samuel ,"email":mail@email.com, "password":pass123, "password_confirmation":pass123}' 


Sample response (HTTP 201)
```
{
    "status": true,
    "message": "User successfully registered",
    "user": {
        "username": "samuel",
        "email": "mail@gmail.com",
        "updated_at": "2021-02-25T18:11:26.000000Z",
        "created_at": "2021-02-25T18:11:26.000000Z",
        "id": 2
    }
}
```

**POST /api/v1/login** - login an existing user to authenticate 

Request parameters:\
`email`- email of the already registered user
`password`- user password

Sample response (HTTP 200)
```
"access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE2MTQyNzcwNzAsImV4cCI6MTYxNDI4MDY3MCwibmJmIjoxNjE0Mjc3MDcwLCJqdGkiOiJtSTNhbWFIZlFoSHpTeEI3Iiwic3ViIjoyLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.iiyyzdyFuFziq2NyXp7pFkRRxJneGBvNMO4KRP7HC0s",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 2,
        "username": "samuel",
        "first_name": null,
        "last_name": null,
        "email": "mail@gmail.com",
        "email_verified_at": null,
        "created_at": "2021-02-25T18:11:26.000000Z",
        "updated_at": "2021-02-25T18:11:26.000000Z"
    }
}

```

**POST /api/v1/deposit** - This endpoint return paystack authorization_url for payment
**_TO access this endpoint, authentication with api token is required_**

Request Parameter \
`amount` -The  amount to be deposited \
`token` - Access token 

`--header` "Authorization: Bearer `ACCESS TOKEN`" \
`--header` "Content-Type: application/json" \
`-data` '{"amount: "20000" }' 

Sample Response (HTTP 200)
```
{
    "status": true,
    "message": "Authorization URL created",
    "data": {
        "authorization_url": "https://checkout.paystack.com/0qpug0hyft6qjjd",
        "access_code": "0qpug0hyft6qjjd",
        "reference": "16142897525672378J0b9"
    }
}

```


**GET /api/v1/payment/gen_callback** - This endpoint verifies the transaction and returns deposited amount
**_TO access this endpoint, authentication with api token is required_**

Request Parameter \
`reference` - The reference from paystack \
`token` - Access token 

`--header` "Authorization: Bearer `ACCESS TOKEN`" \
`--header` "Content-Type: application/json" \
`-data` '{"reference: "16142897525672378J0b9" }' 

Sample Response (HTTP 200)
```
{
    "data": {
        "id": 3,
        "paystack_reference": "16142897525672378J0b9",
        "deposited_amount": 20,
        "currency":NGN,
        "date": "25 Feb 2021"
    },
    "status": true,
    "message": "Deposit Successful"
}

```

**POST /api/v1/transfer** - This endpoint transfer funds from authenticated user to another user via username 
**_TO access this endpoint, authentication with api token is required_**

Request Parameter \
`amount` -The  amount to be transfered \
`username` -username to to recieve the transfer \
`token` - Access token 

`--header` "Authorization: Bearer `ACCESS TOKEN`" \
`--header` "Content-Type: application/json" \
`-data` '{"amount: "200", "username":"dammy"}'

Sample Response (HTTP 200)
```
{
    "data": {
        "id": 1,
        "amount": "9000",
        "transfer_from": "samuel",
        "transfer_to": "dammy",
        "date": "25 Feb 2021"
    },
    "status": true,
    "message": "Transfer completed successfully"
}

```




