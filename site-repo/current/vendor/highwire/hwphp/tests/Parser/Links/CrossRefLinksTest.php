<?php

use HighWire\Parser\Links\CrossRefLinks;
use PHPUnit\Framework\TestCase;

class CrossRefLinksTest extends TestCase {

  public function testCrossRefLinks() {
    $crossref = new CrossRefLinks(file_get_contents(__DIR__  . '/cross_ref_links.xml'));

    $this->assertEquals(67, $crossref->count());
    
    $article = $crossref->current();
    $this->assertEquals($article->type(), 'journal_cite');
    $this->assertEquals($article->title(), "1-Isobutyl-4-methoxy-1H-imidazo[4,5-c]quinoline");
    $this->assertEquals($article->issn(), "1600-5368");
    $this->assertEquals($article->journal_title(), "Acta Crystallographica Section E Structure Reports Online");
    $this->assertEquals($article->journal_abbreviation(), "Acta Crystallogr E Struct Rep Online");
    $this->assertEquals($article->volume(), "67");
    $this->assertEquals($article->issue(), "9");
    $this->assertEquals($article->first_page(), "o2331");
    $this->assertEquals($article->year(), "2011");
    $this->assertEquals($article->doi(), "10.1107/S1600536811031801");

    $this->assertEquals(count($article->contributors()), 6);
    $this->assertEquals(count($article->contributors('author')), 5);
    $this->assertEquals(count($article->contributors('editor')), 1);

    $author = $article->contributors()[0];
    $this->assertEquals($author->role(), "author");
    $this->assertEquals($author->given_name(), "Hoong-Kun");
    $this->assertEquals($author->surname(), "Fun");

    $crossref->next();
    $book = $crossref->current();
    $this->assertEquals($book->type(), 'book_cite');
    $this->assertEquals($book->title(), 'Comprehensive Medicinal Chemistry II');
    $this->assertEquals($book->isbn(), "9780080450445");
    $this->assertEquals(count($book->contributors()), 4);

  }
}
