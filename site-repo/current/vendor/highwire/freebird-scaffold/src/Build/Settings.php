<?php

namespace FreebirdComposer\Build;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;

class Settings
{

    protected $io;

    protected $fs;

  /**
   * Settings constructor.
   * @param $io
   * @param $fs
   */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->fs = new Filesystem();
    }


    public function provision($siteDirectory, array $settingsPaths)
    {
        if ($this->fs->exists($siteDirectory)) {
            foreach ($settingsPaths as $type => $settingsPath) {
                $this->io->write('Coping default ' . $type . ' files.');
                $this->fs->mirror($settingsPath, $siteDirectory);
            }
        }
    }
}
