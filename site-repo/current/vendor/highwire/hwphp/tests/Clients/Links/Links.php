<?php

use HighWire\Clients\ClientFactory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class LinksTest extends TestCase {

  /**
   * @group requiresVPN
   */
  public function testExternalRef() {

    // Test ISI-CITING
    $mock = new MockHandler([
        new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
          <atom:entry>
          <atom:link href="http://gateway.isiknowledge.com/gateway/Gateway.cgi?GWVersion=2&amp;SrcAuth=stanwire&amp;SrcApp=PARTNER_APP&amp;DestLinkType=CitingArticles&amp;KeyAID=&amp;DestApp=WOS_CPL&amp;KeyUT=000285576500004" type="text/html"/>
          </atom:entry>
          </atom:feed>
        ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->externalRef("/plantcell/22/11/3509.atom", "ISI-CITING");
    $link = $result->getData();

    $this->assertEquals($link, "http://gateway.isiknowledge.com/gateway/Gateway.cgi?GWVersion=2&SrcAuth=stanwire&SrcApp=PARTNER_APP&DestLinkType=CitingArticles&KeyAID=&DestApp=WOS_CPL&KeyUT=000285576500004");


    // Test PUBMED
    $mock = new MockHandler([
        new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
          <atom:entry>
          <atom:link href="http://www.ncbi.nlm.nih.gov/pubmed/?dopt=Abstract" type="text/html"/>
          </atom:entry>
          </atom:feed>
        ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->externalRef("/jbc/286/13/11047.atom", "PUBMED");
    $link = $result->getData();

    $this->assertEquals($link, "http://www.ncbi.nlm.nih.gov/pubmed/?dopt=Abstract");

    // Test ENTREZLINKS
    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
        <atom:entry>
        <atom:link href="http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=display&amp;db=pubmed&amp;dopt=pubmed_gene&amp;from_uid=21097709" type="text/html"/>
        </atom:entry>
        </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->externalRef("/plantcell/22/11/3634.atom", "ENTREZLINKS", ['id' => 'pubmed_gene', 'pmid' => '21097709']);
    $link = $result->getData();

    $this->assertEquals($link, "http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=display&db=pubmed&dopt=pubmed_gene&from_uid=21097709");


    // Test PERMISSIONDIRECT
    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
        <atom:entry>
        <atom:link href="http://www.copyright.com/OpenURL/search?sid=pd_hw1532298X&amp;issn=1532298X&amp;WT.mc_id=pd_hw1532298X" type="text/html"/>
        </atom:entry>
        </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $client = ClientFactory::get('links');

    $result = $client->externalRef("/plantcell/23/2/471.atom", "PERMISSIONDIRECT");
    $link = $result->getData();

    $this->assertEquals($link, "http://www.copyright.com/OpenURL/search?sid=pd_hw1532298X&issn=1532298X&WT.mc_id=pd_hw1532298X");

    // Test AUTHORSEARCH
    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
        <atom:entry>
        <atom:link href="http://www.ncbi.nlm.nih.gov/sites/entrez?cmd=search&amp;db=pubmed&amp;term=Xiao Z[au]&amp;dispmax=50" type="text/html"/>
        </atom:entry>
        </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->externalRef("/jbc/286/13/11047.atom", "AUTHORSEARCH", ['access_num' => 'Xiao Z']);
    $link = $result->getData();

    $this->assertEquals($link, "http://www.ncbi.nlm.nih.gov/sites/entrez?cmd=search&db=pubmed&term=Xiao Z[au]&dispmax=50");
  }

  public function testISI() {

    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
        <atom:id>http://links.highwire.org/isi/pnas/103/10/3552.atom</atom:id>
        <atom:link href="http://links.highwire.org/isi/pnas/103/10/3552.atom" rel="self"/>
        <atom:title>isi service  </atom:title>
        <atom:updated>2018-10-04T13:47:15.966-07:00</atom:updated>
        <atom:entry>
        <atom:id>http://links.highwire.org/isi/pnas/103/10/3552.atom.isi</atom:id>
        <atom:title> isi service </atom:title>
        <atom:updated>2018-10-04T13:47:15.966-07:00</atom:updated>
        <atom:link rel="citing" href="http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&amp;SrcApp=PARTNER_APP&amp;SrcAuth=LinksAMR&amp;KeyUT=WOS:000236225300012&amp;DestLinkType=CitingArticles&amp;DestApp=ALL_WOS&amp;UsrCustomerID=269fc60adb004b0b719031a97aedf5e9"/>
        <atom:link rel="related" href="http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&amp;SrcApp=PARTNER_APP&amp;SrcAuth=LinksAMR&amp;KeyUT=WOS:000236225300012&amp;DestLinkType=RelatedRecords&amp;DestApp=ALL_WOS&amp;UsrCustomerID=269fc60adb004b0b719031a97aedf5e9"/>
        <atom:link rel="source" href="http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&amp;SrcApp=PARTNER_APP&amp;SrcAuth=LinksAMR&amp;KeyUT=WOS:000236225300012&amp;DestLinkType=FullRecord&amp;DestApp=ALL_WOS&amp;UsrCustomerID=269fc60adb004b0b719031a97aedf5e9"/>
        <atom:content type="text">72</atom:content>
        <timescited:count xmlns:timescited="http://schema.highwire.org/Isi/Links">72</timescited:count>
        </atom:entry>
        </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->isi("/pnas/103/10/3552.atom");

    $this->assertEquals($result->getLink(), "http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&SrcApp=PARTNER_APP&SrcAuth=LinksAMR&KeyUT=WOS:000236225300012&DestLinkType=CitingArticles&DestApp=ALL_WOS&UsrCustomerID=269fc60adb004b0b719031a97aedf5e9");
    $this->assertEquals($result->getLink('source'), "http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&SrcApp=PARTNER_APP&SrcAuth=LinksAMR&KeyUT=WOS:000236225300012&DestLinkType=FullRecord&DestApp=ALL_WOS&UsrCustomerID=269fc60adb004b0b719031a97aedf5e9");
    $this->assertEquals($result->getLink('citing'), "http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&SrcApp=PARTNER_APP&SrcAuth=LinksAMR&KeyUT=WOS:000236225300012&DestLinkType=CitingArticles&DestApp=ALL_WOS&UsrCustomerID=269fc60adb004b0b719031a97aedf5e9");
    $this->assertEquals($result->getLink('related'), "http://gateway.webofknowledge.com/gateway/Gateway.cgi?GWVersion=2&SrcApp=PARTNER_APP&SrcAuth=LinksAMR&KeyUT=WOS:000236225300012&DestLinkType=RelatedRecords&DestApp=ALL_WOS&UsrCustomerID=269fc60adb004b0b719031a97aedf5e9");
    $this->assertEquals($result->timescited(), 72);
  }

  public function testScopus() {

    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
      <atom:id>http://links.highwire.org/scopus/bmj/339/bmj.b4164</atom:id>
      <atom:title>Scopus cited by count for 10.1136/bmj.b4164</atom:title>
      <atom:link href="http://links.highwire.org/scopus/bmj/339/bmj.b4164" rel="self"/>
      <atom:updated>2018-10-04T14:10:23.6-07:00</atom:updated>
      <atom:entry>
      <atom:id>http://links.highwire.org/scopus/bmj/339/bmj.b4164/scopus</atom:id>
      <atom:title>Scopus cited by count for 10.1136/bmj.b4164</atom:title>
      <atom:updated>2018-10-04T14:10:23.6-07:00</atom:updated>
      <atom:link rel="citing" href="http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&amp;rel=R6.0.0&amp;eid=2-s2.0-70350555543&amp;md5=292fd49c6c7b6598fcc45e63f04b5a15">87</atom:link>
      <atom:link rel="related" href="http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&amp;rel=R6.0.0&amp;eid=2-s2.0-70350555543&amp;md5=292fd49c6c7b6598fcc45e63f04b5a15">87</atom:link>
      <cited:count xmlns:cited="http://schema.highwire.org/Scopus/Links">87</cited:count>
      <atom:content type="xhtml">
      <xhtml:div xmlns:xhtml="http://www.w3.org/1999/xhtml">
      <xhtml:a href="http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&amp;rel=R6.0.0&amp;eid=2-s2.0-70350555543&amp;md5=292fd49c6c7b6598fcc45e63f04b5a15">                                    Scopus (87)                                </xhtml:a>
      </xhtml:div>
      </atom:content>
      </atom:entry>
      </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->scopus("/bmj/339/bmj.b4164.atom");

    $this->assertEquals($result->getLink(), "http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&rel=R6.0.0&eid=2-s2.0-70350555543&md5=292fd49c6c7b6598fcc45e63f04b5a15");
    $this->assertEquals($result->getLink('citing'), "http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&rel=R6.0.0&eid=2-s2.0-70350555543&md5=292fd49c6c7b6598fcc45e63f04b5a15");
    $this->assertEquals($result->getLink('related'), "http://www.scopus.com/scopus/inward/citedby.url?partnerID=c9DrA512&rel=R6.0.0&eid=2-s2.0-70350555543&md5=292fd49c6c7b6598fcc45e63f04b5a15");
    $this->assertEquals($result->timescited(), 87);
  }

  public function testCrossRef() {

    // NOTE CROSSREF SUPPORT IS A STUB, PLEASE EXPAND TESTS AS REQUIRED.
    $mock = new MockHandler([
      new Response(200, [], '<?xml version="1.0" encoding="UTF-8"?>
      <atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
         <atom:id>http://links.highwire.org/crossref/pnas/101/18/6835.atom</atom:id>
         <atom:title>crossrefs forward linking service</atom:title>
         <atom:updated>2018-10-04T14:27:18.856-07:00</atom:updated>
         <atom:entry>
            <atom:id>http://dx.doi.org/10.1107/S1600536811031801</atom:id>
            <atom:title>1-Isobutyl-4-methoxy-1H-imidazo[4,5-c]quinoline</atom:title>
            <atom:updated>2018-10-04T14:27:18.856-07:00</atom:updated>
            <atom:content type="application/xml">
               <forward_link xmlns="http://www.crossref.org/qrschema/2.0" doi="10.1073/pnas.0401347101">
                  <journal_cite fl_count="0">
                     <issn type="electronic">1600-5368</issn>
                     <journal_title>Acta Crystallographica Section E Structure Reports Online</journal_title>
                     <journal_abbreviation>Acta Crystallogr E Struct Rep Online</journal_abbreviation>
                     <article_title>1-Isobutyl-4-methoxy-1H-imidazo[4,5-c]quinoline</article_title>
                     <contributors>
                        <contributor first-author="true" sequence="first" contributor_role="author">
                           <given_name>Hoong-Kun</given_name>
                           <surname>Fun</surname>
                        </contributor>
                        <contributor first-author="false" sequence="additional" contributor_role="author">
                           <given_name>Wan-Sin</given_name>
                           <surname>Loh</surname>
                        </contributor>
                        <contributor first-author="false" sequence="additional" contributor_role="author">
                           <surname>Dinesha</surname>
                        </contributor>
                        <contributor first-author="false" sequence="additional" contributor_role="author">
                           <given_name>Reshma</given_name>
                           <surname>Kayarmar</surname>
                        </contributor>
                        <contributor first-author="false" sequence="additional" contributor_role="author">
                           <given_name>G. K.</given_name>
                           <surname>Nagaraja</surname>
                        </contributor>
                     </contributors>
                     <volume>67</volume>
                     <issue>9</issue>
                     <first_page>o2331</first_page>
                     <year>2011</year>
                     <publication_type>full_text</publication_type>
                     <doi type="journal_article">10.1107/S1600536811031801</doi>
                  </journal_cite>
               </forward_link>
            </atom:content>
         </atom:entry>
      </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->crossref("/pnas/101/18/6835.atom", "pnas", "asdfasdfasdf");

    $this->assertNotEmpty($result);
  }

  public function testIJLinks() {

    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
      <atom:id>tag:links@highwire.org:2018-10-04T14:40:24.366-07:00:pediatrics;126/5/e1119</atom:id>
      <atom:title>Higwire Internal URL Lookup </atom:title>
      <atom:updated>2018-10-04T14:40:24.366-07:00</atom:updated>
      <atom:entry>
      <atom:id>tag:links@highwire.org:2018-10-04T14:40:24.366-07:00:pediatrics;126/5/e1119</atom:id>
      <atom:title>Relationship Between Bed Sharing and Breastfeeding: Longitudinal, Population-Based Analysis</atom:title>
      <atom:updated>2018-10-04T14:40:24.366-07:00</atom:updated>
      <atom:link rel="self" href="http://localhost/internal/pediatrics/126/5/e1119.atom"/>
      <atom:link rel="related" href="http://sass.highwire.org/pediatrics/126/5/e1119.atom"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/default" type="application/xhtml+xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.toc?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/table-of-contents" type="application/xhtml+xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.atom?form=feed&amp;ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/Publishing/builtin" type="application/atom+xml; type=feed" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.full.pdf?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/full-text" type="application/pdf" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.abstract.html?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/abstract" type="application/xhtml+xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.full.html?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/full-text" type="application/xhtml+xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.source.xml?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/source" type="application/xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.ref-links.xml?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/ref-links" type="application/xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.ref-stubs.xml?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/ref-stubs" type="application/xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.concepts.rdf?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/concepts" type="application/rdf+xml" xmlns:c="http://schema.highwire.org/Compound"/>
      <atom:link href="http://pediatrics.aappublications.org/content/126/5/e1119.extract.jpg?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&amp;keytype2=tf_ipsecsha" rel="alternate" c:role="http://schema.highwire.org/variant/extract" type="image/jpeg" xmlns:c="http://schema.highwire.org/Compound"/>
      </atom:entry>
      </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->ijlinks("/pediatrics/126/5/e1119.atom");

    $this->assertEquals($result->getLink(), "http://pediatrics.aappublications.org/content/126/5/e1119?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&keytype2=tf_ipsecsha");
    $this->assertEquals($result->getLink('full-text'), "http://pediatrics.aappublications.org/content/126/5/e1119.full.html?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&keytype2=tf_ipsecsha");
    $this->assertEquals($result->getLink('full-text', 'application/pdf'), "http://pediatrics.aappublications.org/content/126/5/e1119.full.pdf?ijkey=4f8d50281c7fa32c3ea90a6b95f9af5890e6e22c&keytype2=tf_ipsecsha");
  }

  public function testGlencoe() {

    // NOTE GLENCOE SUPPORT IS A STUB, PLEASE EXPAND TESTS AS REQUIRED.
    $mock = new MockHandler([
      new Response(200, [], '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom">
      <atom:id>http://links.highwire.org/glencoe/jcb/early/2012/06/26/jcb.201202053</atom:id>
      <atom:title>Glencoe original data service for 10.1083/jcb.201202053</atom:title>
      <atom:link href="http://links.highwire.org/glencoe/jcb/early/2012/06/26/jcb.201202053" rel="self"/>
      <atom:updated>2018-10-04T15:00:58.102-07:00</atom:updated>
      <atom:entry>
      <atom:id>http://links.highwire.org/glencoe/jcb/early/2012/06/26/jcb.201202053/glencoe</atom:id>
      <atom:title>Glencoe original data links for 10.1083/jcb.201202053</atom:title>
      <atom:updated>2018-10-04T15:00:58.102-07:00</atom:updated>
      <atom:link rel="article" href="http://jcb-dataviewer.rupress.org/jcb/browse/5414"/>
      <atom:link rel="figure" href="http://jcb-dataviewer.rupress.org/jcb/browse/5414/15472">fig3</atom:link>
      <atom:link rel="figure" href="http://jcb-dataviewer.rupress.org/jcb/browse/5414/15475">fig7</atom:link>
      <atom:link rel="figure" href="http://jcb-dataviewer.rupress.org/jcb/browse/5414/15476">fig1</atom:link>
      <atom:link rel="figure" href="http://jcb-dataviewer.rupress.org/jcb/browse/5414/16479">fig8</atom:link>
      </atom:entry>
      </atom:feed>
      ')
    ]);
    $handler = HandlerStack::create($mock);
    $client = ClientFactory::get('links', ['guzzle-config' => ['handler'  => $handler]]);

    $result = $client->glencoe("/jcb/early/2012/06/26/jcb.201202053.atom");

    $this->assertNotEmpty($result);
  }

}
