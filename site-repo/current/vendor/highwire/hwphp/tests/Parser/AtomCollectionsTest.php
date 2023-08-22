<?php

use HighWire\Parser\AtomCollections\Membership;
use PHPUnit\Framework\TestCase;

class AtomCollectionsTest extends TestCase {

  public function testMembership() {
    $membership = new Membership(file_get_contents(__DIR__  . '/atom-collections/membership.atom'));    

    $this->assertNotEmpty($membership);
    $this->assertEquals(7, count($membership));
    $this->assertEquals(7, count($membership->getCategories()));
    $this->assertEquals('/sgrvv/31/6/1021.atom', $membership->apath());

    // Test Iterability
    $i = 0;
    foreach ($membership as $category) {
      if ($i == 0) $this->assertEquals('73da1bfd', $category->term());
      if ($i == 1) $this->assertEquals('91983b69', $category->term());
      if ($i == 2) $this->assertEquals('7b47e5fc', $category->term());
      $i++;
    }
    $this->assertEquals(7, $i);
  }

  public function testCategory() {
    $membership = new Membership(file_get_contents(__DIR__  . '/atom-collections/membership.atom'));    
    
    $categories = $membership->getCategories();
    $category = $categories[0];

    $this->assertNotEmpty($category);
    $this->assertEquals('73da1bfd', $category->term());
    $this->assertEquals('subject', $category->scheme());
    $this->assertEquals('Marriage and Family Counseling', $category->label());
    $this->assertEquals(1, $category->rank());
    $this->assertEquals('73da1bfd', $category->taxonomyNodeId());
    $this->assertEquals(3, $category->taxonomyNodeDepth());
    $this->assertEquals('/sgrvv/31/6/1021.atom', $category->apath());
    $this->assertEquals('<div>Description of the <em>Marriage and Family Counseling</em> node.</div>', trim($category->description()));
    
  }

}
