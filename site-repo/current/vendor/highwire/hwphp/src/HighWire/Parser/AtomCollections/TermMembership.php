<?php

namespace HighWire\Parser\AtomCollections;

use BetterDOMDocument\DOMDoc;
use HighWire\Clients\ResponseTrait;

/**
  * Membership parses a membership XML document that provides collections membership for a given apath.
  *
  * For example:
  *   http://atom-collections-alpha.highwire.org/springer/content/atom?member=/freebird/book/978-1-6170-5275-0.atom
  */
class TermMembership extends DOMDoc {

  use ResponseTrait;

  /**
   * Current position of the iterator.
   *
   * @var int
   */
  private $position = 0;

  /**
   * {@inheritdoc}
   */
  public $ns = [
    'acs' => 'http://schema.highwire.org/AtomCollection',
    'tax' => 'http://schema.highwire.org/Taxonomy',
    'atom' => 'http://www.w3.org/2005/Atom',
  ];

  /**
   * Get the apath for the membership subject
   *
   * @return string|false
   *   Apath of the mebership subject, or FALSE if cannot be determined.
   */
  public function apaths() {
    $apaths = [];
    $elems = $this->xpath("//atom:link[@rel='proxy']");

    if (empty($elems)) {
      return FALSE;
    }

    foreach ($elems as $elem) {
      $href = $elem->getAttribute('href');
      if (!empty($href)) {
        $info = parse_url($href);
        if (!empty($info['path'])) {
          $apaths[] = $info['path'];
        }
      }
    }

    return $apaths;
  }

}
