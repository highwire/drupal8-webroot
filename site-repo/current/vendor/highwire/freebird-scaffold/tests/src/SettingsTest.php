<?php

namespace FreebirdComposer\Tests;

use Composer\IO\NullIO;
use FreebirdComposer\Build\Settings;

class SettingsTest extends FileSystemTestBase {

  public function testSettingsProvision() {
    $settings = new Settings(new NullIO());

    $settingsPaths = [
      'settings' => $this->rootDir . '/defaults/settings',
      'services' => $this->rootDir . '/defaults/services',
    ];

    $settings->provision($this->tmpDir, $settingsPaths);

    $this->assertFileExists($this->tmpDir . '/production.services.yml');
    $this->assertFileExists($this->tmpDir . '/development.services.yml');
    $this->assertFileExists($this->tmpDir . '/production.settings.php');
    $this->assertFileExists($this->tmpDir . '/development.settings.php');
    $this->assertFileExists($this->tmpDir . '/settings.php');
  }

}