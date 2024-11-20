# ESA HELPER

- For common code in UK and NL ESA Applications.

#### Register Provider

add below line in config/app.php

```
\Esa\Helper\Provider\PackageServiceProvider::class,
```

#### Run below commands

```
php artisan migrate
```

```
php artisan vendor:publish --tag=esa-helper
```
