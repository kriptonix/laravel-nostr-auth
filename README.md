# laravel-nostr-auth

![GitHub contributors](https://img.shields.io/github/contributors/kriptonix/laravel-nostr-auth)
![GitHub issues](https://img.shields.io/github/issues/kriptonix/laravel-nostr-auth)
![GitHub last commit (branch)](https://img.shields.io/github/last-commit/kriptonix/laravel-nostr-auth/main)

This package allows users to log in to Laravel application using Nostr browser key management extensions (e.g., Alby). It enables users to authenticate by signing a message with their private key and sending the signature and public key for verification. If the verification is successful and the user is not registered in the Laravel application, the package will register the user, adding them to the users table with the corresponding public key. If the user with given public key is already registered in will be logged in.

More info about Nostr: https://github.com/nostr-protocol/nostr.

## Features
- Log in or register using Nostr browser key management extensions
- Automatically register new users with their public key
- Default email set to '<pubkey>@nostr.io'
- Default name set to 'Nostr User'
- Default password set to a hash of a random integer (not cryptographically secure)
- You can set where you want to redirect users after successful login in config/nostr-auth.php
- You can customize user registration details in the NostrAuthController
- You can disable default authentication via email/password if desired

## Installation

To use the package in your Laravel project it is recommended to install authentication starter kits as they provide many additional features. Allthough the package will work with Laravel's built-in authentication services without starter pack you might need to tweek it a bit to adopt it to your needs.

Install the package with composer:

```console
$ composer require kriptonix/laravel-nostr-auth
```

Install dependencies if you would like to test / code some things out for yourself. 

```console
$ composer install
```

Publish the configuration file:
```console
$ php artisan vendor:publish --provider="Kriptonix\LaravelNostrAuth\LaravelNostrAuthServiceProvider" --tag="config"
```

Publish the migration file:
```console
$ php artisan vendor:publish --provider="Kriptonix\LaravelNostrAuth\LaravelNostrAuthServiceProvider" --tag="migrations"
```

Run the migrations:
```console
$ php artisan migrate
```

Add 'pubkey' to $fillable property of User model:
```php
protected $fillable = [
        // ... other fields
        'pubkey'
    ];
```

Add `@stack('scripts')` to layout views or static HTML pages where you want to show the "Login with Nostr" button/link. 

**Note**: For the script to work, csrf_token should be used. When implementing the login button on static HTML pages, include the following in the <head> section:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

Add the login button:
```html
<x-nostrauth::nostr-login-button />
```
When user clicks this button Nostr browser extension (i.e. Alby) will pop up so that user can confirm and sign a simple event message. After signing, if user with public key that signed the message exists the package will authenticate and log in the user. If user doesn't exist the package will create and log in the user.

## Customization

If you would like to change the HTML of the login button, you can modify the view component located at:
```console
vendor/kriptonix/laravel-nostr-auth/resources/views/components/nostr-login-button.blade.php
```

## Usage

### Logging In
Users can log in by clicking the "Login with Nostr" button, which will use their Nostr browser key management extension to sign a message and send the signature and public key for verification.

### Registration
If the user is not already registered:
- They will be registered with their public key
- The email will be set to <pubkey>@nostr.io
- The name will be set to 'Nostr User'
- The password will be set to a hash of a random integer (not cryptographically secure)
- You can handle password security and other default user creation details by modifying the NostrAuthController

## Disabling Default Authentication
If you prefer to use only the Nostr authentication method, you can disable the default email/password authentication in your Laravel application.

## Contributing
Contributions are welcome! Please feel free to submit a pull request.

## License
This package is open-sourced software licensed under the MIT license.


## Community

If you need any help, please contact me via Nostr npub1ap9fscjl92zclacmaa0nneurr5js85ymuem6ew0ftu8f6eztvpds8qchga

## Funding

If you would like to support this project with a donation, you could send some lightning sats to `plebeian@getalby.com`. 

## Maintainers
 
* [@kriptonix](https://github.com/kriptonix)  `npub1ap9fscjl92zclacmaa0nneurr5js85ymuem6ew0ftu8f6eztvpds8qchga` (original author)