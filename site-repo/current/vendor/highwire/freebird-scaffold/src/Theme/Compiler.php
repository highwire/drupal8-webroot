<?php

namespace FreebirdComposer\Theme;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Compiler
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

    public function execute(array $compileFilePaths)
    {

        if (empty($compileFilePaths)) {
            return 0;
        }

        foreach ($compileFilePaths as $compileFilePath) {
            $compileFileDirectory = dirname($compileFilePath);
            if ($this->fs->exists($compileFileDirectory)) {
                $currentDirectory = getcwd();
                $this->io->write('Compiling theme: ' . $compileFileDirectory);

                $commands = Yaml::parseFile($compileFilePath);
                chdir($compileFileDirectory);
                foreach ($commands['commands'] as $command) {
                    $return = 0;
                    passthru($command, $return);
                    $themeName = basename($compileFileDirectory);
                    if ($return != 0) {
                        throw new \Exception("$command failed on theme $themeName");
                    }
                }
                chdir($currentDirectory);
            }
        }
    }
}
