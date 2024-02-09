# Auth Module

## Features

- Login
- Register
- Email Verification
- Password Reset
- Profile
- Google Captcha With Login And Register
- Choose What Feature You Need To Enable

## How To Install

### Used Packages
- Follow instructions to install laravel modules [HERE](https://nwidart.com/laravel-modules/v6/installation-and-setup)
- Install Laravel Sanctum from [HERE](https://laravel.com/docs/10.x/sanctum) if not installed

Enable The Module

```php
php artisan module:enable Auth
```

Run that command to sync needed packages

```shell
php artisan module:update Auth
```

- Publish module resources

```shell
php artisan vendor:publish --provider=Modules\Auth\Providers\AuthModuleServiceProvider
```

### If you are planning to work with Web Browser (SPA) , make sure to make these configurations

- Open <b>app\config\cors.php</b> file and apply these settings

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    /*
     * paths
     * 
     * If you want to get requests only on specific routes update that variable
     * 
     * for example if you want to get requests from all requests that start with api/
     * make it like that => api/*
     * 
     * if the url is not in paths , you will get `NO_ACCESS_CONTROL_ALLOW_ORIGIN` error 
     * */
    'paths' => ['*'],

    'allowed_methods' => ['*'],// Allowed HTTP Methods

    'allowed_origins' => explode(',' , env('ALLOWED_ORIGINS')), // Allowed Origins

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,// This Value Is Very Important To Allow Sending Cookies In Api

];
```

- Open <b>app\config\sanctum.php</b> file and make sure that `stateful` like this

```php
<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Sanctum::currentApplicationUrlWithPort()
    ))),
];
```

#### The Most Important Part !

- open <b>app/config/session.php</b> file and make sure that `domain` isset

```php
<?php

use Illuminate\Support\Str;

return [
    /*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | Here you may change the domain of the cookie used to identify a session
    | in your application. This will determine which domains the cookie is
    | available to in your application. A sensible default has been set.
    |
    */

    'domain' => env('SESSION_DOMAIN'), // This Value is So Important To Be Able To Send Cookie To SPA
];
```

- Include <b>EnsureFrontendRequestsAreStateful<b> middleware to verify that request is coming from web browser

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
        'api' => [
            ...
            EnsureFrontendRequestsAreStateful::class,
        ],
    ];
}

```

- Add tests paths to <b>phpunit.xml</b> if you want to test module

```xml

<testsuites>
    <testsuite name="Unit">
        ...
        <directory suffix="Test.php">./Modules/Auth/Tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        ...
        <directory suffix="Test.php">./Modules/Auth/Tests/Feature</directory>
    </testsuite>
</testsuites>
```

- Make these configurations to authenticate

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
</head>

<!-- Note That ?onload=onloadCallback is the function that Google Captcha Will Search For it To Render Captcha -->
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<body>
<form id="loginForm" action="#">
    <input type="text" name="email" id="" value="text@example.com">
    <input type="password" name="password" id="" value="password">
    <input type="checkbox" checked name="remember_me">
    <div id="google_recaptcha"></div>
    <input type="submit" value="login">
</form>
<button class="loggedUser">Get Logged User Info</button>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="main.js"></script>
</body>

</html>
```

- `main.js` file

```javascript

// Render Google Captcha Box (I'm not a robot)
var onloadCallback = function () {
    grecaptcha.render('google_recaptcha', {
        'sitekey': 'YOUR_SITE_KEY'
    });
};


// Create Axios Object With Default Configurations
const api = axios.create({
    baseURL: 'http://api.sanctum.test',
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: 'application/json'
    },
    withCredentials: true,
})


api.get('/sanctum/csrf-cookie').then(function () {

    let form = document.getElementById('loginForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(form);

        formData = Object.fromEntries(formData.entries());
        formData.remember_me = formData.remember_me == 'on' ? true : false;

        /*
            Will Output That Array
            
            {
                "email": "test@example.com", 
                "password": "password", 
                "remember_me": true, 
                "g-recaptcha-response": "03AKH6MR......"
            }
        */
        console.log(formData);

        api.post('/public/login', formData)
            .then(res => console.log(res))
            .catch(err => console.log(err.response.data));
        grecaptcha.reset()
    })
})

// Get Logged User Info
document.querySelector('.loggedUser').addEventListener('click', function (e) {
    e.preventDefault();

    api.get('/auth-module/user')
        .then(res => console.log(res))
        .catch(err => console.log(err));
})
```

- Last is `.env` configurations

```shell
# Sanctum Authentication

APP_URL=http://api.sanctum.test

SESSION_DOMAIN=.sanctum.test # Send Cookies To All sub Domains of Domain

# Allowed Origins Used In app/cors.php
ALLOWED_ORIGINS="http://sanctum.test,http://localhost:3000,http://localhost:8080,http://localhost:8001,http://localhost"

# Sanctum Stateful Domains Used in config/sanctum.php if you are working with SPA
SANCTUM_STATEFUL_DOMAINS="sanctum.test,localhost:3000,localhost:8080,localhost:8001,localhost,,127.0.0.1,127.0.0.1:8000,::1"

# Enable it if in production
SESSION_SECURE_COOKIE=false

# Trusted Hosts
TRUSTED_HOSTS="sanctum.test"

# Google V3 Recaptcha
RECAPTCHA_ENABLED=false
RECAPTCHA_SITE_KEY=6Ldub1olAAAAAFnn6yp7qpH814yCOUh7JqHplGBO
RECAPTCHA_SECRET_KEY=6Ldub1olAAAAANbRU8aXYzTMSBG-8mJb-ymP0bEm

# Email Credentials
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=MAILTRAP_USER_NAME
MAIL_PASSWORD=MAILTRAP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="support@elattar.com"
MAIL_FROM_NAME="Mohamed Attar"

```

## Other files that module depends on , NOTE: only important content put not whole file contents

- `User` model

```php
<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Casts\Attribute;
 use Illuminate\Database\Eloquent\Relations\MorphMany;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Spatie\MediaLibrary\HasMedia;
 use Spatie\MediaLibrary\InteractsWithMedia;

 class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at'
    ];


    public function password(): Attribute
    {
        return Attribute::make(set: fn($val) =>  !Hash::check($val , $this->password) ? Hash::make($val) : $this->password);
    }

    public function avatar(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name' , 'users')
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
```
