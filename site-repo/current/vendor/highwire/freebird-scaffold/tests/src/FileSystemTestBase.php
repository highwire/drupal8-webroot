<?php

namespace FreebirdComposer\Tests;

use Composer\Util\Filesystem;
use PHPUnit\Framework\TestCase;

class FileSystemTestBase extends TestCase {

  /**
   * @var \Composer\Util\Filesystem
   */
  protected $fs;

  /**
   * @var string
   */
  protected $tmpDir;

  /**
   * @var string
   */
  protected $rootDir;

  /**
   * @var string
   */
  protected $tmpReleaseTag;

  /**
   * SetUp test.
   */
  public function setUp() {
    $this->rootDir = realpath(realpath(__DIR__ . '/../..'));

    // Prepare temp directory.
    $this->fs = new Filesystem();
    $this->tmpDir = realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'freebird-scaffold';
    $this->ensureDirectoryExistsAndClear($this->tmpDir);

    chdir($this->tmpDir);
  }

  /**
   * Makes sure the given directory exists and has no content.
   *
   * @param string $directory
   */
  protected function ensureDirectoryExistsAndClear($directory) {
    if (is_dir($directory)) {
      $this->fs->removeDirectory($directory);
    }
    mkdir($directory, 0777, TRUE);
  }

}