# Role Module

## Features
- Full Crud For Roles (Show , Create , Update , Delete)

## How To Install

- Follow instructions to install laravel modules [HERE](https://nwidart.com/laravel-modules/v6/installation-and-setup)
- Run that command to sync needed packages
```shell
php artisan module:update Role
```

- Publish Module Resources
```shell
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

```shell
php artisan vendor:publish --provider=Modules\Role\Providers\RoleServiceProvider
```

```shell
php artisan migrate
```

```shell
php artisan module:seed Role
```

- Open `Role\Routes\api.php` route file and protect roles with 
and protect endpoints with permission and auth guard for example
```php
Route::get('' , [RoleController::class , 'index'])->middleware(['permission:show_roles' , 'auth:api']);
```
note that middleware names are listed in config file


## Other files that module depends on , NOTE: only important content put not whole file contents

- `User` model

```php
<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Casts\Attribute;
 use Illuminate\Database\Eloquent\Relations\MorphMany;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Support\Facades\Hash;
 use Spatie\Permission\Traits\HasRoles;

 class User extends Authenticatable
{
    use HasRoles;

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
}
```
