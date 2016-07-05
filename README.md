# Slim Composer Installer

This module registered all your Slim 3 modules and load the dependencies.

## Usage

### Slim 3 project

Add the new composer package to your `composer.json` file

```
composer require mc388/slim-composer-installer
```

Next add the autoloader class to your Slim 3 project

```php
<?php

use Mc388\SlimComposerInstaller\Autoloader;

session_start();

require __DIR__ . '/../vendor/autoload.php';

// Create Slim app
$app = new \Slim\App();
// Fetch DI Container
$container = $app->getContainer();


// Get autoloader instance
$autoloader = new Autoloader();
// Require all Slim 3 modules
foreach ($autoloader->getModules() as $module) {
    echo __DIR__ . '/../vendor/' . $module['name'] . '/' . Autoloader::REQUIRE_FILE;
}


$app->run();
```

### Slim Module

There are a few requirements to your Slim 3 module to use this package.
The composer package type must be `slim-module` and the `autoloader/psr-4` attribute is available.


Here is an example for the `composer.json` file

```
{
  "name": "mc388/slim-test",
  "type": "slim-module",
  "autoload": {
    "psr-4": {
      "Mc388\\SlimTest\\": "src"
    }
  },
  "require": {
    "mc388/slim-composer-installer": "dev-master"
  }
}
```


Put all your routes and containers to a `src/app.php` file.


Next an example for the `src/app.php` file

```php
<?php

// no definition of $app or $container required

$app->get('/test', function ($request, $response) {
  return 'test';
});

```
