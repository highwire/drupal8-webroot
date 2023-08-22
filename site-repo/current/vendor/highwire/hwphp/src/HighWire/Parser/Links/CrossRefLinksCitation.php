<?php

namespace HighWire\Parser\Links;

use HighWire\Parser\DOMElementBase;

/**
 * A Citation holds a single "<forward_link>" element from links.highwire.org/crossref/.
 */
class CrossRefLinksCitation extends DOMElementBase {

  /**
   * Check if it's a journal-article or a book
   * 
   * @return string|null
   *   Either a "journal_cite" or a "book_cite"
   */
  public function type() {
    return $this->elem->tagName;
  }

  /**
   * Get the journal ISSN
   *
   * @var string|null $type
   *   The ISSN type, either "electronic" or "print", omit for either.
   * 
   * @return string|null
   *   The ISSN as defined in the 'issn' element.
   */
  public function issn(string $type = NULL) {
    if ($type) {
      $query = "./qrs:issn[@type='$type']";
    }
    else {
      $query = "./qrs:issn";
    }
    $elem = $this->xpathSingle($query);
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the book isbn
   * 
   * @return string|null
   *   The isbn as defined in the 'isbn' element.
   */
  public function isbn() {
    $elem = $this->xpathSingle("./qrs:isbn");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the journal title
   * 
   * @return string|null
   *   The journal as defined in the 'journal_title' element.
   */
  public function journal_title() {
    $elem = $this->xpathSingle("./qrs:journal_title");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the journal abbreviated title 
   * 
   * @return string|null
   *   The journal abbreviated title  as defined in the 'journal_abbreviation' element.
   */
  public function journal_abbreviation() {
    $elem = $this->xpathSingle("./qrs:journal_abbreviation");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the title (either article or book)
   * 
   * @return string|null
   *   The title as defined in the 'article_title' (articles) or 'volume_title' (books) element.
   */
  public function title() {
    $elem = $this->xpathSingle("(./qrs:article_title | ./qrs:volume_title)[1]");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the article contributors 
   * 
   * 
   * @var string|null $role
   *   The role, either "author", "editor" or other. Omit for all.
   * 
   * @return CrossRefLinksContributor[]
   *   The article contributors, as an array.
   */
  public function contributors(string $role = NULL) {
    if ($role) {
      $query = ".//qrs:contributor[@contributor_role='$role']";
    }
    else {
      $query = ".//qrs:contributor";
    }
    $elems = $this->xpath($query);
    $contribs = [];
    if ($elems) {
      foreach ($elems as $elem) {
        $contribs[] = new CrossRefLinksContributor($elem, $this->dom);
      }
    }
    return $contribs;
  }

  /**
   * Get the volume
   * 
   * @return string|null
   *   The volume as defined in the 'volume' element.
   */
  public function volume() {
    $elem = $this->xpathSingle("./qrs:volume");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the issue
   * 
   * @return string|null
   *   The issue as defined in the 'issue' element.
   */
  public function issue() {
    $elem = $this->xpathSingle("./qrs:issue");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the page number
   * 
   * @return string|null
   *   The first page number as defined in the 'first_page' element.
   */
  public function first_page() {
    $elem = $this->xpathSingle("./qrs:first_page");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the publication year
   * 
   * @return string|null
   *   The year as defined in the 'year' element.
   */
  public function year() {
    $elem = $this->xpathSingle("./qrs:year");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

  /**
   * Get the DOI
   * 
   * @return string|null
   *   The doi as defined in the 'doi' element.
   */
  public function doi() {
    $elem = $this->xpathSingle("./qrs:doi");
    if ($elem) {
      return $elem->nodeValue;
    }
  }

}
