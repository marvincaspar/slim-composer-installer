<?php
/**
 * Slim Composer Installer
 *
 * @link      https://github.com/mc388/slim-composer-installer
 * @copyright Copyright (c) Marvin Caspar
 * @license   https://github.com/mc388/slim-composer-installer/blob/master/LICENSE (MIT License)
 */
namespace Mc388\SlimComposerInstaller;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;

/**
 * Class Installer
 * @package Mc388\SlimComposerInstaller
 */
class Installer extends LibraryInstaller
{
    const PACKAGE_TYPE = 'slim-module';

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return $packageType === self::PACKAGE_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $installedModules = $this->getInstalledModules();
        $filePath = $this->getConfigFilePath();

        // Get package information
        $name = $package->getPrettyName();
        $autoload = $package->getAutoload();

        // Check if psr-4 key exists
        if (array_key_exists('psr-4', $autoload)) {
            $module = array(
                'name' => $name,
                'namespaces' => array_keys($autoload['psr-4'])
            );

            // Add package to modules list
            array_push($installedModules, $module);

            // Remove duplicated entries
            $installedModules = array_unique($installedModules, SORT_REGULAR);

            // Write to file
            $prettyJson = json_encode($installedModules, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
            file_put_contents($filePath, $prettyJson);
        }

        // Add package to project
        parent::install($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        echo "update";

        parent::update($repo, $initial, $target);
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $installedModules = $this->getInstalledModules();
        $filePath = $this->getConfigFilePath();

        // Get package information
        $name = $package->getPrettyName();
        $autoload = $package->getAutoload();

        // Remove the module from the list
        $installedModules = $this->array_remove_object($installedModules, $name, 'name');

        // Remove duplicated entries
        $installedModules = array_unique($installedModules, SORT_REGULAR);

        // Write to file
        $prettyJson = json_encode($installedModules, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        file_put_contents($filePath, $prettyJson);

        parent::uninstall($repo, $package);
    }


    /**
     * Remove an object from an array
     * @param array $array
     * @param string $value
     * @param string $prop
     * @return array
     */
    protected function array_remove_object(&$array, $value, $prop)
    {
        return array_filter($array, function ($a) use ($value, $prop) {
            return $a[$prop] !== $value;
        });
    }

    /**
     * Get all installed slim modules
     *
     * @return array
     */
    protected function getInstalledModules()
    {
        $installedModules = [];

        // Create config dir if not exists
        if (!file_exists(Autoloader::CONFIG_FILE_PATH)) {
            mkdir(Autoloader::CONFIG_FILE_PATH, 0777, true);
        }

        $filePath = $this->getConfigFilePath();

        // Read modules file
        if (file_exists($filePath)) {
            $configFileContent = file_get_contents($filePath);
            $installedModules = json_decode($configFileContent, true);
        }

        return $installedModules;
    }

    /**
     * Get the config file path
     * @return string
     */
    protected function getConfigFilePath()
    {
        return Autoloader::CONFIG_FILE_PATH . Autoloader::CONFIG_FILE_NAME;
    }
}
