<?php

use HighWire\Parser\Taxonomy\TaxonomyTree;
use HighWire\Parser\Taxonomy\TaxonomyTreeList;
use PHPUnit\Framework\TestCase;

class TaxonomyTest extends TestCase {

  public function testTaxonomyTree() {
    $taxonomy = new TaxonomyTree(file_get_contents(__DIR__  . '/taxonomy/springer-subject.xml'));

    $this->assertNotEmpty($taxonomy);
    $this->assertEquals($taxonomy, $taxonomy->getData());
    $this->assertEquals(63, count($taxonomy));
    $this->assertEquals(62, count($taxonomy->getNodes()));
    $this->assertEquals(63, count($taxonomy->getNodeIds()));
    $this->assertEquals('springer', $taxonomy->publisher());
    $this->assertEquals('subject', $taxonomy->scheme());
    $this->assertEquals('content', $taxonomy->collection());
    $this->assertEquals('Subjects', $taxonomy->name());
    $this->assertEquals('springer content subjects taxonomy', $taxonomy->description());
    $this->assertEquals('e3b97379', $taxonomy->rootNode()->id());
    $this->assertEquals('', $taxonomy->rootNode()->parent());
    $this->assertNull($taxonomy->rootNode()->parentNode());
    $this->assertFalse($taxonomy->isStub());

    // Test Iterability
    $i = 0;
    foreach ($taxonomy as $node) {
      if ($i == 0) $this->assertEquals('e3b97379', $node->id());
      if ($i == 0) $this->assertEquals(0, $node->weight());
      if ($i == 1) $this->assertEquals('f1c129ee', $node->id());
      if ($i == 1) $this->assertEquals(1, $node->weight());
      if ($i == 2) $this->assertEquals('1188c5c7', $node->id());
      if ($i == 2) $this->assertEquals(2, $node->weight());
      if ($i == 3) $this->assertEquals('615adfa0', $node->id());
      if ($i == 3) $this->assertEquals(3, $node->weight());
      if ($i == 4) $this->assertEquals('f7f8736a', $node->id());
      if ($i == 4) $this->assertEquals(4, $node->weight());
      if ($i == 5) $this->assertEquals('8c657315', $node->id());
      if ($i == 5) $this->assertEquals(5, $node->weight());
      $i++;
    }
    $this->assertEquals(63, $i);

    // Test multi parent nodes.
    $parent_ids = $taxonomy->getParents('615adfa0');

    $this->assertEquals(count($parent_ids), 2);
    $this->assertEquals($parent_ids[0], '1188c5c7');
    $this->assertEquals($parent_ids[1], '439ba83a');

    // Test base nodes.
    $base_nodes = $taxonomy->getBaseNodes();
    $this->assertEquals(count($base_nodes), 5);

    // Test related nodes.
    $taxonomy = new TaxonomyTree(file_get_contents(__DIR__  . '/taxonomy/mheaeworks-eng_thesaurus.xml'));
    $this->assertNotEmpty($taxonomy);
    $this->assertEquals($taxonomy, $taxonomy->getData());
    
    $related_ids = $taxonomy->getRelatedTerms('0812d7ea');

    $this->assertEquals(count($related_ids), 2);
    $this->assertEquals($related_ids[0], '02be0bc0');
    $this->assertEquals($related_ids[1], '57d1f4d1');
  }

  public function testTaxonomyTreeList() {
    $taxonomies = new TaxonomyTreeList(file_get_contents(__DIR__  . '/taxonomy/springer-taxonomies.xml'));

    $this->assertNotEmpty($taxonomies);
    $this->assertEquals($taxonomies, $taxonomies->getData());
    $this->assertEquals(3, count($taxonomies));
    $this->assertEquals(3, count($taxonomies->getTaxonomyTrees()));

    // Test Iterability
    $i = 0;
    foreach ($taxonomies as $taxonomy) {
      if ($i == 0) {
        $this->assertEquals('springer', $taxonomy->publisher());
        $this->assertEquals('Subjects', $taxonomy->name());
        $this->assertEquals('content',  $taxonomy->collection());
        $this->assertEquals('subject',  $taxonomy->scheme());
        $this->assertTrue($taxonomy->isStub());
      }
      if ($i == 1) {
        $this->assertEquals('springer', $taxonomy->publisher());
        $this->assertEquals('Keywords', $taxonomy->name());
        $this->assertEquals('content',  $taxonomy->collection());
        $this->assertEquals('keyword',  $taxonomy->scheme());
        $this->assertTrue($taxonomy->isStub());
      }
      if ($i == 2) {
        $this->assertEquals('springer', $taxonomy->publisher());
        $this->assertEquals('GeoRef', $taxonomy->name());
        $this->assertEquals('georef',  $taxonomy->collection());
        $this->assertEquals('category',  $taxonomy->scheme());
        $this->assertTrue($taxonomy->isStub());
      }
      $i++;
    }
    $this->assertEquals(3, $i);
  }

  public function testTaxonomyNode() {
    $taxonomy = new TaxonomyTree(file_get_contents(__DIR__  . '/taxonomy/springer-subject.xml'));

    $node = $taxonomy->getNode('702fd6d9');
    $this->assertNotEmpty($node);
    $this->assertEquals('702fd6d9', $node->id());
    $this->assertEquals('702fd6d9', $node->ACSTermId());
    $this->assertEquals('Counseling', $node->term());
    $this->assertEquals('Counseling', $node->label());
    $this->assertEquals('counseling', $node->path());
    $this->assertEquals('ed671889', $node->parent());
    $this->assertEquals(2, $node->depth());
    $this->assertEquals(0, $node->position());
    $this->assertEquals(32, $node->weight());
    $this->assertEquals(['1f94cfc5','73da1bfd','c31f8e68','900b9cbe','e54a9d58', '2e836a8d'], $node->children());
    $this->assertEquals('<div>Description of the <em>Counseling</em> node.</div>', $node->description());
    $this->assertEquals($taxonomy->getNodeByACSId('702fd6d9'), $node);

    $parent = $node->parentNode();
    $this->assertEquals('ed671889', $parent->id());
    $this->assertEquals(3, $parent->position());

    $children = $node->childNodes();
    $this->assertEquals('1f94cfc5', $children['1f94cfc5']->id());
  }

}
