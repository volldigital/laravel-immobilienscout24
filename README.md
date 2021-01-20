# Laravel Immobilienscout24 Wrapper

Access the Immobilienscout24 API within Laravel.

## Installation

```
composer require volldigital/laravel-immobilienscout24
```

Publish config file or use .ENV Variables:

```
IMMOSCOUT_IDENTIFIER=
IMMOSCOUT_SECRET=
IMMOSCOUT_CALLBACK_URI=http://YOUR_DOMAIN/immobilienscout/callback
IMMOSCOUT_SANDBOX=true
```

Set your callback url to this route:

```
http://YOUR_DOMAIN/immobilienscout/callback
```

## Fetching data

First visit `http://YOUR_DOMAIN/immobilienscout/authorize` to generate access tokens.

After that it is possible to fetch data from the api:

```php
$server->fetchData('offer/v1.0/user/me/realestate');
```