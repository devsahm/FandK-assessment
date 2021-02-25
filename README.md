

## PROJECT DESCRIPTION

The following are the process to execute this project

### SETUP
```
composer install
```

### Migrate and Seed Database

```
php artisan migrate
php artisan db:seed
```
### Task

**GET /api/v1/register** - register a new user

Available query parameters:\
`username` -a unique username \
`email` - a valid email \
`password`- password \
`password_confirmation` - repeat password \

`--url` /api/v1/register \
`--header` 'Accept: application/json' \
`--header` 'Content-Type: application/json' \
 `--data` '{"username":samuel ,"email":samuel@email.com, "password":pass123, "password_confirmation":pass123}' \


Sample response (HTTP 201)
```
{
    "status": true,
    "message": "User successfully registered",
    "user": {
        "username": "samuel",
        "email": "fagbenrosamuel@gmail.com",
        "updated_at": "2021-02-25T18:11:26.000000Z",
        "created_at": "2021-02-25T18:11:26.000000Z",
        "id": 2
    }
}
```



