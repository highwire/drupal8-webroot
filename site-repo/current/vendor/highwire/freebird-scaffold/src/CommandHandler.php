<?php

namespace FreebirdComposer;

use Composer\Script\Event;
use FreebirdComposer\Build\Settings;
use FreebirdComposer\Theme\Compiler;

class CommandHandler
{

    protected static function autoload(Event $event)
    {
        require_once $event
        ->getComposer()
        ->getConfig()
        ->get('vendor-dir') . '/autoload.php';
    }

    public static function provisionSettings(Event $event)
    {
        static::autoload($event);

        $settings = new Settings($event->getIO());

        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $siteDirectory = $vendorDir . '/../web/sites/default/';
        $settingsPaths = [
        'settings' => $vendorDir . '/highwire/freebird-scaffold/defaults/settings',
        'services' => $vendorDir . '/highwire/freebird-scaffold/defaults/services',
        ];

        $settings->provision($siteDirectory, $settingsPaths);
    }

    public static function compileThemes(Event $event)
    {
        static::autoload($event);

        $compiler = new Compiler($event->getIO());

        $themesDirectories = $event->getComposer()->getConfig()->get('theme-directories');

        foreach ($themesDirectories as $key => $themesDirectory) {
            $themesDirectories[$key] = $themesDirectory . '/compile.yml';
        }

        $compiler->execute($themesDirectories);
    }
}
