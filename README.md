# Slim Composer Installer

This module registered all your Slim 3 modules and load the dependencies.

## Usage

### Slim 3 project

Add the new composer package to your `composer.json` file

```
composer require mc388/slim-composer-installer
```

Next add the autoloader class to your Slim 3 project

```
<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

// Create Slim app
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

// Fetch DI Container
$container = $app->getContainer();

// Get autoloader instance
$autoloader = new \Mc388\SlimComposerInstaller\Autoloader();
// Require all Slim 3 modules
foreach ($autoloader->getModules() as $module) {
    require __DIR__ . '/../vendor/' . $module . '/bootstrap.php';
}

$app->run();
```

### Slim Module

There are a few requirements to your Slim 3 module to use this package.
The composer package type must be `slim-module` and there must be a `bootstrap.php` file in the root directory of your package.
The bootstrap file contains all routes and contains from your module.

Heres an example for the `composer.json` file

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


Next an example for the `bootstrap.php` file

```
<?php

$app->get('/test', function ($request, $response) {
  return 'test';
});

```
