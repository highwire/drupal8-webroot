<?php

namespace FreebirdComposer\Tests;

use Composer\IO\NullIO;
use FreebirdComposer\Build\Settings;
use FreebirdComposer\Theme\Compiler;

class ThemeCompilerTest extends FileSystemTestBase {

  protected $compiler;

  public function setUp()
  {
    parent::setUp();

    $this->compiler = $compiler = new Compiler(new NullIO());
  }

  public function testSuccessfulCompile() {
    $this->compiler->execute([$this->rootDir . '/tests/data/pass.compile.yml']);

    $this->addToAssertionCount(1);
  }

  public function testFailedCompile() {
    $this->expectException(\Exception::class);

    $this->compiler->execute([$this->rootDir . '/tests/data/fail.compile.yml']);
  }

}