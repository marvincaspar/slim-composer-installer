<?php
/**
 * Slim Composer Installer
 *
 * @link      https://github.com/mc388/slim-composer-installer
 * @copyright Copyright (c) Marvin Caspar
 * @license   https://github.com/mc388/slim-composer-installer/blob/master/LICENSE (MIT License)
 */
namespace Mc388\SlimComposerInstaller;

/**
 * Class Autoloader
 * @package Mc388\SlimComposerInstaller
 */
class Autoloader
{
    const CONFIG_FILE_PATH = 'config/';
    const CONFIG_FILE_NAME = 'modules.json';

    /**
     * @return array
     */
    public function getModules()
    {
        $installedModules = [];
        $moduleNames = [];
        $configPath = $_SERVER['DOCUMENT_ROOT'] . '/../' . self::CONFIG_FILE_PATH . self::CONFIG_FILE_NAME;

        if (file_exists($configPath)) {
            $configFileContent = file_get_contents($configPath);
            $installedModules = json_decode($configFileContent, true);
        }

        foreach ($installedModules as $name => $namespace) {
            array_push($moduleNames, $name);
        }

        return $installedModules;
    }
}
