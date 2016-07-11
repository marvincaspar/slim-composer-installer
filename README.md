# Slim Composer Installer

This module registered all your Slim 3 modules and load the dependencies.

## Usage

### Slim 3 project

Add the new composer package to your `composer.json` file

```bash
composer require mc388/slim-composer-installer
```

Create an App class `src/App.php` to your Slim 3 project

```php
<?php

namespace SlimTest;

use Mc388\SlimComposerInstaller\Autoloader;

/**
 * Class App initialize the slim app
 * @package App
 */
class App extends \Slim\App
{

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct([]);
        $this->loadModules();
    }

    /**
     * Load all installed slim-modules
     */
    private function loadModules()
    {
        $autoloader = new Autoloader();

        // Get all modules
        foreach ($autoloader->getModules() as $module) {
            // Iterate over each module namespace
            foreach ($module['namespaces'] as $namespace) {
                // Build class name
                $moduleClass = $namespace . 'App';

                // Instantiate module class
                new $moduleClass($this);
            }
        }
    }
}

```

The `public/index.php` file should looks like this:

```php
<?php

use SlimTest\App;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App();
$app->run();
```

### Slim Module

There are a few requirements to your Slim 3 module to use this package.
The composer package type is `slim-module` and the `autoloader/psr-4` attribute is set.


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
    "mc388/slim-composer-installer": "v1.0.0"
  }
}
```


Put all your dependencies to a `src/App.php` file, for example:

```php
<?php

namespace Mc388\SlimTestModule;

use Interop\Container\ContainerInterface;
use Slim\App as SlimApp;

class App
{
    /**
     * App constructor.
     * @param SlimApp $app
     */
    public function __construct(SlimApp $app)
    {
        /** @var ContainerInterface $container */
        $container = $app->getContainer();

        $container['view'] = function (ContainerInterface $container) {
            // Init twig
        };

        $app->get('/test', function ($request, $response) {
            return 'Hello World!';
        });
    }
}
```

 After installing the module, the route `/test` should be available
