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
    const CONFIG_FILE_PATH = 'config/';
    const CONFIG_FILE_NAME = 'modules.json';

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
        $installedModules = [];

        // Create config dir if not exists
        if (!file_exists(self::CONFIG_FILE_PATH)) {
            mkdir(self::CONFIG_FILE_PATH, 0777, true);
        }

        $filePath = self::CONFIG_FILE_PATH . self::CONFIG_FILE_NAME;

        // Read modules file
        if (file_exists($filePath)) {
            $configFileContent = file_get_contents($filePath);
            $installedModules = json_decode($configFileContent, true);
        }

        // Get package information
        $name = $package->getPrettyName();
        $psr4 = key($package->getAutoload()['psr-4']);

        // Add package to modules file
        $installedModules[$name] = $psr4;
        $prettyJson = json_encode($installedModules, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
        file_put_contents($filePath, $prettyJson);

        // Add package to project
        parent::install($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        echo "update";
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        echo "uninstall";
    }
}
