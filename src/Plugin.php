<?php
/**
 * Slim Composer Installer
 *
 * @link      https://github.com/mc388/slim-composer-installer
 * @copyright Copyright (c) Marvin Caspar
 * @license   https://github.com/mc388/slim-composer-installer/blob/master/LICENSE (MIT License)
 */
namespace Mc388\SlimComposerInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Class Plugin
 * @package Mc388\SlimComposerInstaller
 */
class Plugin implements PluginInterface
{
    /**
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
