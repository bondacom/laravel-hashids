[![Build Status](https://travis-ci.org/bondacom/laravel-hashids.svg?branch=master)](https://travis-ci.org/bondacom/laravel-hashids)
[![Latest Stable Version](https://poser.pugx.org/bondacom/laravel-hashids/v/stable)](https://packagist.org/packages/bondacom/laravel-hashids)
[![Total Downloads](https://poser.pugx.org/bondacom/laravel-hashids/downloads)](https://packagist.org/packages/bondacom/laravel-hashids)
[![License](https://poser.pugx.org/bondacom/laravel-hashids/license)](https://packagist.org/packages/bondacom/laravel-hashids)

# Laravel Hash ids

###### [FAQ](#faq) | [Contributing](https://github.com/bondacom/laravel-hashids/blob/master/CONTRIBUTING.md)

> Laravel Hashids is a [Bondacom](https://bondacom.com) library which provides a clean and isolated way to have public ids in your API REST.

> This package will convert all ids from requests (responses, query and route parameters, and headers), 
so when a consumer request your API using the hash ids, this will decode it.

The advantages are: 
- API consumers will never be concern about the real ids 
- All your system have no idea of the concept of public ids because it change the routing layer. Everything remains the same. Amazing!!

## Getting Started

### Installation

> *Note: Laravel Hashids requires at least PHP v7.1.*

To use Laravel Hashids in your project, run:
```
composer require bondacom/laravel-hashids
```

> **Note**: For Laravel less than 5.5 remember to register manually the service provider!

### Configuration
Copy the config file into your project. For Laravel projects run:
```
php artisan vendor:publish --provider="Bondacom\LaravelHashids\Providers\LaravelHashidsServiceProvider"
```

Here you are able to change the default parameters:
- separators
- whitelist
- blacklist

If you need more customization, you can set specific parameters to each part of request or response (query parameters, route parameters and headers)

### Usage

It's really simple! 
Register the middleware (globally, to specific routes, in a group).

**IMPORTANT:** Always register it before Laravel bindings middleware.
>For more information about middleware check out: [Laravel Middleware](https://laravel.com/docs/5.5/middleware)

If you already have any endpoint which the response contains an id, request it and all your ids will now be hashed.

### Examples
- Create a new route that returns a list of entities.
    ```
    Route::get('users', function () {
        return User::all();
    })->middleware('publicids');
    ```

- Create a new route that returns an specified entity by an id. This example uses Route Model Binding.
    ```
    Route::get('users/{{user}}', function (App\User $user) {
      return $user;
    })->middleware('publicids');
    ```

- The same with a POST, DELETE, PUT, etc.. 

## Contributing to Laravel Hashids

Check out [contributing guide](https://github.com/bondacom/laravel-hashids/blob/master/CONTRIBUTING.md) to get an overview of Laravel Hashids development.

# FAQ

#### Q: Which PHP version does Laravel Hashids use?

Look for [composer.json](https://github.com/bondacom/laravel-hashids/blob/master/composer.json).