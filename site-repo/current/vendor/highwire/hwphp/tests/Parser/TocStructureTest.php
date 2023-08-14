<?php

use HighWire\Parser\Toc\TocParser;
use PHPUnit\Framework\TestCase;

class TocStructureTest extends TestCase {

  /**
   * Test cases for a general single level journal toc.
   */
  public function testTocParserToc() {
    $path = __DIR__ . '/../assets/tocs/ajpcell.310.11.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $structure = $parser->getTocStructure();

    // Header values.
    $this->assertEquals('Themes', $structure['Themes']['heading']);
    $this->assertEquals('Review', $structure['Review']['heading']);
    $this->assertEquals('Editorial Focus', $structure['EditorialFocus']['heading']);
    $this->assertEquals('CALL FOR PAPERS | Cell Signaling: Proteins, Pathways and
            Mechanisms', $structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['heading']);
    $this->assertEquals('CALL FOR PAPERS | Gasotransmitters', $structure['CALLFORPAPERSGasotransmitters']['heading']);
    $this->assertEquals('CALL FOR PAPERS | Regulation of Cell Signaling
            Pathways', $structure['CALLFORPAPERSRegulationofCellSignalingPathways']['heading']);
    $this->assertEquals('Articles', $structure['Articles']['heading']);

    // Header-id values.
    $this->assertEquals('Themes', $structure['Themes']['header-id']);
    $this->assertEquals('Review', $structure['Review']['header-id']);
    $this->assertEquals('EditorialFocus', $structure['EditorialFocus']['header-id']);
    $this->assertEquals('CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms', $structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['header-id']);
    $this->assertEquals('CALLFORPAPERSGasotransmitters', $structure['CALLFORPAPERSGasotransmitters']['header-id']);
    $this->assertEquals('CALLFORPAPERSRegulationofCellSignalingPathways', $structure['CALLFORPAPERSRegulationofCellSignalingPathways']['header-id']);
    $this->assertEquals('Articles', $structure['Articles']['header-id']);

    // GroupingKey values.
    $this->assertEquals('Themes', $structure['Themes']['groupingkey']);
    $this->assertEquals('Review', $structure['Review']['groupingkey']);
    $this->assertEquals('Editorial Focus', $structure['EditorialFocus']['groupingkey']);
    $this->assertEquals('CALL FOR PAPERS | Cell Signaling: Proteins, Pathways and
            Mechanisms', $structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['groupingkey']);
    $this->assertEquals('CALL FOR PAPERS | Gasotransmitters', $structure['CALLFORPAPERSGasotransmitters']['groupingkey']);
    $this->assertEquals('CALL FOR PAPERS | Regulation of Cell Signaling Pathways', $structure['CALLFORPAPERSRegulationofCellSignalingPathways']['groupingkey']);
    $this->assertEquals('Articles', $structure['Articles']['groupingkey']);

    // Items count.
    $this->assertTrue(is_array($structure['Themes']['items']));
    $this->assertCount(1, $structure['Themes']['items']);
    $this->assertTrue(is_array($structure['Review']['items']));
    $this->assertCount(1, $structure['Review']['items']);
    $this->assertTrue(is_array($structure['EditorialFocus']['items']));
    $this->assertCount(1, $structure['EditorialFocus']['items']);
    $this->assertTrue(is_array($structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['items']));
    $this->assertCount(3, $structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['items']);
    $this->assertTrue(is_array($structure['CALLFORPAPERSGasotransmitters']['items']));
    $this->assertCount(1, $structure['CALLFORPAPERSGasotransmitters']['items']);
    $this->assertTrue(is_array($structure['CALLFORPAPERSRegulationofCellSignalingPathways']['items']));
    $this->assertCount(4, $structure['CALLFORPAPERSRegulationofCellSignalingPathways']['items']);
    $this->assertTrue(is_array($structure['Articles']['items']));
    $this->assertCount(7, $structure['Articles']['items']);

    // Item values
    $this->assertEquals($structure['Themes']['items'][0], '/ajpcell/310/11/C955.atom');
    $this->assertEquals($structure['Review']['items'][0], '/ajpcell/310/11/C968.atom');
    $this->assertEquals($structure['EditorialFocus']['items'][0], '/ajpcell/310/11/C841.atom');
    $this->assertEquals($structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['items'][0], '/ajpcell/310/11/C857.atom');
    $this->assertEquals($structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['items'][1], '/ajpcell/310/11/C874.atom');
    $this->assertEquals($structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['items'][2], '/ajpcell/310/11/C885.atom');
    $this->assertEquals($structure['CALLFORPAPERSGasotransmitters']['items'][0], '/ajpcell/310/11/C894.atom');
    $this->assertEquals($structure['CALLFORPAPERSRegulationofCellSignalingPathways']['items'][0], '/ajpcell/310/11/C844.atom');
    $this->assertEquals($structure['CALLFORPAPERSRegulationofCellSignalingPathways']['items'][1], '/ajpcell/310/11/C983.atom');
    $this->assertEquals($structure['Articles']['items'][0], '/ajpcell/310/11/C903.atom');
    $this->assertEquals($structure['Articles']['items'][1], '/ajpcell/310/11/C911.atom');
    $this->assertEquals($structure['Articles']['items'][2], '/ajpcell/310/11/C921.atom');

    // Parent values.
    $this->assertEmpty($structure['Themes']['parent']);
    $this->assertEmpty($structure['Review']['parent']);
    $this->assertEmpty($structure['EditorialFocus']['parent']);
    $this->assertEmpty($structure['CALLFORPAPERSCellSignalingProteinsPathwaysandMechanisms']['parent']);
    $this->assertEmpty($structure['CALLFORPAPERSGasotransmitters']['parent']);
    $this->assertEmpty($structure['CALLFORPAPERSRegulationofCellSignalingPathways']['parent']);
    $this->assertEmpty($structure['Articles']['parent']);

  }

  /**
   * Test cases for a toc that has nested sections two levels deep.
   */
  public function testTocParserNestedToc() {
    $path = __DIR__ . '/../assets/tocs/btr.1.1-2.xml';
    $xml = file_get_contents($path);
    $parser = new TocParser($xml);
    $structure = $parser->getTocStructure();

    // First Section
    $this->assertEquals("EDITOR'S PAGE", $structure['EDITORSPAGE']['heading']);
    $this->assertEquals("EDITOR'S PAGE", $structure['EDITORSPAGE']['groupingkey']);
    $this->assertEquals('EDITORSPAGE', $structure['EDITORSPAGE']['header-id']);
    $this->assertEmpty($structure['EDITORSPAGE']['parent']);
    $this->assertNotEmpty(TRUE, $structure['EDITORSPAGE']['items']);
    $this->assertEquals('/btr/1/1-2/1.atom', $structure['EDITORSPAGE']['items'][0]);

    // Nested Section - Parent
    $this->assertEquals("PRE-CLINICAL RESEARCH", $structure['PRE-CLINICALRESEARCH']['heading']);
    $this->assertEquals("PRE-CLINICAL RESEARCH", $structure['PRE-CLINICALRESEARCH']['groupingkey']);
    $this->assertEquals('PRE-CLINICALRESEARCH', $structure['PRE-CLINICALRESEARCH']['header-id']);
    $this->assertEmpty($structure['PRE-CLINICALRESEARCH']['parent']);
    $this->assertNotEmpty(TRUE, $structure['PRE-CLINICALRESEARCH']['items']);
    // Non-nested children
    $this->assertEquals('/btr/1/1-2/14.atom', $structure['PRE-CLINICALRESEARCH']['items'][0]);
    $this->assertEquals('/btr/1/1-2/32.atom', $structure['PRE-CLINICALRESEARCH']['items'][1]);
    $this->assertEquals('/btr/1/1-2/49.atom', $structure['PRE-CLINICALRESEARCH']['items'][2]);
    $this->assertEquals('/btr/1/1-2/61.atom', $structure['PRE-CLINICALRESEARCH']['items'][3]);

    // Nested Group - First child section
    $this->assertEquals('Editorial Comment', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['heading']);
    $this->assertEquals('PRE-CLINICAL RESEARCH/Editorial Comment', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['groupingkey']);
    $this->assertEquals('PRE-CLINICALRESEARCHEditorialComment-1', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['header-id']);
    $this->assertEquals('PRE-CLINICALRESEARCH', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['parent']);
    $this->assertNotEmpty(TRUE, $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['items']);
    $this->assertEquals('/btr/1/1-2/29.atom', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-1']['items'][0]);

    // Nested Group - Second child section
    $this->assertEquals('Editorial Comment', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['heading']);
    $this->assertEquals('PRE-CLINICAL RESEARCH/Editorial Comment', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['groupingkey']);
    $this->assertEquals('PRE-CLINICALRESEARCHEditorialComment-2', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['header-id']);
    $this->assertEquals('PRE-CLINICALRESEARCH', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['parent']);
    $this->assertNotEmpty(TRUE, $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['items']);
    $this->assertEquals('/btr/1/1-2/45.atom', $structure['PRE-CLINICALRESEARCH']['items']['PRE-CLINICALRESEARCHEditorialComment-2']['items'][0]);

  }

  /**
   * Test cases for a toc that has toc blurbs.
   */
  public function testTocParserTocBlurb() {
    $path = __DIR__ . '/../assets/tocs/roybiolett.9.6.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    $this->assertEquals('Special feature', $structure['Specialfeature']['heading']);
    $this->assertEquals('Special feature', $structure['Specialfeature']['groupingkey']);
    $this->assertEquals('Specialfeature', $structure['Specialfeature']['header-id']);
    $this->assertEmpty($structure['Specialfeature']['parent']);
    $this->assertNotEmpty($structure['Specialfeature']['items']);
    $this->assertEquals('/roybiolett/9/6/20130792.atom', $structure['Specialfeature']['items'][0]);
    $this->assertEquals('/roybiolett/9/6/20130335.atom', $structure['Specialfeature']['items'][1]);
    $this->assertEquals('/roybiolett/9/6/20130395.atom', $structure['Specialfeature']['items'][2]);
    $this->assertEquals('/roybiolett/9/6/20130416.atom', $structure['Specialfeature']['items'][3]);
    $this->assertEquals('/roybiolett/9/6/20130365.atom', $structure['Specialfeature']['items'][4]);
    $this->assertEquals('/roybiolett/9/6/20130199.atom', $structure['Specialfeature']['items'][5]);
    $this->assertEquals('/roybiolett/9/6/20130309.atom', $structure['Specialfeature']['items'][6]);
    $this->assertEquals('/roybiolett/9/6/20130334.atom', $structure['Specialfeature']['items'][7]);
    $this->assertEquals('/roybiolett/9/6/20130491.atom', $structure['Specialfeature']['items'][8]);
    $this->assertEquals('/roybiolett/9/6/20130454.atom', $structure['Specialfeature']['items'][9]);
    $this->assertEquals('/roybiolett/9/6/20130444.atom', $structure['Specialfeature']['items'][10]);
    $this->assertEquals('/roybiolett/9/6/20130367.atom', $structure['Specialfeature']['items'][11]);
    $this->assertNotEmpty($structure['Specialfeature']['toc-blurb']);
    $this->assertEquals('50 Years on: the legacy of William Donald Hamilton', $structure['Specialfeature']['toc-blurb'][0]);
    $this->assertEquals('Organized by Joan Herbers and Neil Tsutsui', $structure['Specialfeature']['toc-blurb'][1]);

  }

  /**
   * Test cases for toc section pdfs.
   */
  public function testTocParserSectionPdf() {
    $path = __DIR__ . '/../assets/tocs/bmj.352.8048.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    $this->assertCount(13, $structure['ThisWeek']['items']);
    $this->assertEquals('http://sass.highwire.org/bmj/352/8048/This_Week.full.pdf', $structure['ThisWeek']['pdf']);

    $this->assertCount(6, $structure['ResearchUpdate']['items']);
    $this->assertEquals('http://sass.highwire.org/bmj/352/8048/Research_Update.full.pdf', $structure['ResearchUpdate']['pdf']);

    $this->assertCount(19, $structure['Comment']['items']);
    $this->assertEquals('http://sass.highwire.org/bmj/352/8048/Comment.full.pdf', $structure['Comment']['pdf']);

    $this->assertCount(5, $structure['Education']['items']);
    $this->assertEquals('http://sass.highwire.org/bmj/352/8048/Education.full.pdf', $structure['Education']['pdf']);

  }

  /**
   * Test cases for a tocs that have <map:citations> not in a <map:group>.
   */
  public function testTocParserTopLevelCitations() {
    $path = __DIR__ . '/../assets/tocs/sgrvv_32_1.atom';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    $this->assertCount(11, $structure[0]['items']);
    $this->assertEquals('/sgrvv/32/1/3.atom', $structure[0]['items'][0]);
    $this->assertEquals('/sgrvv/32/1/22.atom', $structure[0]['items'][1]);
    $this->assertEquals('/sgrvv/32/1/46.atom', $structure[0]['items'][2]);
    $this->assertEquals('/sgrvv/32/1/60.atom', $structure[0]['items'][3]);
    $this->assertEquals('/sgrvv/32/1/78.atom', $structure[0]['items'][4]);
    $this->assertEquals('/sgrvv/32/1/93.atom', $structure[0]['items'][5]);
    $this->assertEquals('/sgrvv/32/1/110.atom', $structure[0]['items'][6]);
    $this->assertEquals('/sgrvv/32/1/126.atom', $structure[0]['items'][7]);
    $this->assertEquals('/sgrvv/32/1/141.atom', $structure[0]['items'][8]);
    $this->assertEquals('/sgrvv/32/1/159.atom', $structure[0]['items'][9]);
    $this->assertEquals('/sgrvv/32/1/181.atom', $structure[0]['items'][10]);

  }

  /**
   * Test cases for tocs that have <map:citation-ref> not in a <map:group>.
   */
  public function testTocParserTopLevelCitationRef() {
    $path = __DIR__ . '/../assets/tocs/9780071356237.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    $this->assertEquals('/mheaeworks/book/9780071356237/front-matter/preface1.atom', $structure[0]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/front-matter/preface2.atom', $structure[1]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/front-matter/preface3.atom', $structure[2]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/chapter/chapter1/search-section/section17.atom', $structure['INTRODUCTION']['items'][4]);
    $this->assertEquals('/mheaeworks/book/9780071356237/back-matter/appendix1.atom', $structure[3]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/back-matter/appendix2.atom', $structure[4]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/back-matter/glossary1.atom', $structure[5]['apath']);
    $this->assertEquals('/mheaeworks/book/9780071356237/back-matter/appendix3.atom', $structure[6]['apath']);

  }

  /**
   * Test cases for meeting abstract tocs.
   */
  public function testTocParserMeetingAbstract() {
    $path = __DIR__ . '/../assets/tocs/jov_8_17.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    // Test url values
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Between%20the%20Eyes%20and%20the%20Cortex%3A%20Active%20and%20Passive%20Filtering%20in%20the%20Geniculate&amp;volume=8&amp;issue=17', $structure['BetweentheEyesandtheCortexActiveandPassiveFilteringintheGeniculate']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Color%20and%20Motion%20Processing&amp;volume=8&amp;issue=17', $structure['ColorandMotionProcessing']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Contributed%20Talk%20Session%3A%20Color&amp;volume=8&amp;issue=17', $structure['ContributedTalkSessionColor']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Gene%20Therapy%20Approaches%20to%20Basic%20and%20Clinical%20Vision%20Sciences&amp;volume=8&amp;issue=17', $structure['GeneTherapyApproachestoBasicandClinicalVisionSciences']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Makous%20Festschrift&amp;volume=8&amp;issue=17', $structure['MakousFestschrift']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Measuring%20Population%20Activity%20in%20the%20Visual%20Cortex&amp;volume=8&amp;issue=17', $structure['MeasuringPopulationActivityintheVisualCortex']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Retinopathy%20and%20Visual%20Dysfunction&amp;volume=8&amp;issue=17', $structure['RetinopathyandVisualDysfunction']['url']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Poster%20Abstracts&amp;volume=8&amp;issue=17', $structure['PosterAbstracts']['url']);

    // Test search parameter items
    $this->assertEquals('Between%20the%20Eyes%20and%20the%20Cortex%3A%20Active%20and%20Passive%20Filtering%20in%20the%20Geniculate', $structure['BetweentheEyesandtheCortexActiveandPassiveFilteringintheGeniculate']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['BetweentheEyesandtheCortexActiveandPassiveFilteringintheGeniculate']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['BetweentheEyesandtheCortexActiveandPassiveFilteringintheGeniculate']['search-parameters']['issue']);

    $this->assertEquals('Color%20and%20Motion%20Processing', $structure['ColorandMotionProcessing']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['ColorandMotionProcessing']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['ColorandMotionProcessing']['search-parameters']['issue']);

    $this->assertEquals('Contributed%20Talk%20Session%3A%20Color', $structure['ContributedTalkSessionColor']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['ContributedTalkSessionColor']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['ContributedTalkSessionColor']['search-parameters']['issue']);

    $this->assertEquals('Gene%20Therapy%20Approaches%20to%20Basic%20and%20Clinical%20Vision%20Sciences', $structure['GeneTherapyApproachestoBasicandClinicalVisionSciences']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['GeneTherapyApproachestoBasicandClinicalVisionSciences']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['GeneTherapyApproachestoBasicandClinicalVisionSciences']['search-parameters']['issue']);

    $this->assertEquals('Makous%20Festschrift', $structure['MakousFestschrift']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['MakousFestschrift']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['MakousFestschrift']['search-parameters']['issue']);

    $this->assertEquals('Measuring%20Population%20Activity%20in%20the%20Visual%20Cortex', $structure['MeasuringPopulationActivityintheVisualCortex']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['MeasuringPopulationActivityintheVisualCortex']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['MeasuringPopulationActivityintheVisualCortex']['search-parameters']['issue']);

    $this->assertEquals('Retinopathy%20and%20Visual%20Dysfunction', $structure['RetinopathyandVisualDysfunction']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['RetinopathyandVisualDysfunction']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['RetinopathyandVisualDysfunction']['search-parameters']['issue']);

    $this->assertEquals('Poster%20Abstracts', $structure['PosterAbstracts']['search-parameters']['tocsectionid']);
    $this->assertEquals('8', $structure['PosterAbstracts']['search-parameters']['volume']);
    $this->assertEquals('17', $structure['PosterAbstracts']['search-parameters']['issue']);

  }

  /**
   * Test cases for a nested meeting abstract tocs.
   * This situation where <map:group> has child of <map:group>, instead of the
   * traditional structure where <map:group> has a child of <map:citations>.
   */
  public function testTocParserNestedMeetingAbstract() {
    $path = __DIR__ . '/../assets/tocs/bloodjournal.124.21.meetingAbstract.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $structure = $parser->getTocStructure();

    // First section
    $this->assertEquals('Plenary Abstracts', $structure['PlenaryAbstracts']['heading']);
    $this->assertEquals('Plenary Abstracts', $structure['PlenaryAbstracts']['groupingkey']);
    $this->assertEquals('PlenaryAbstracts', $structure['PlenaryAbstracts']['header-id']);
    $this->assertEmpty($structure['PlenaryAbstracts']['parent']);
    $this->assertCount(1, $structure['PlenaryAbstracts']['items']);

    // First section - first child.
    $this->assertEquals('Plenary Scientific Session', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['heading']);
    $this->assertEquals('Plenary Abstracts/Plenary Scientific Session', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['groupingkey']);
    $this->assertEquals('PlenaryAbstractsPlenaryScientificSession', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['header-id']);
    $this->assertEquals('PlenaryAbstracts', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['parent']);
    $this->assertEmpty($structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['items']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=Plenary%20Scientific%20Session&amp;volume=124&amp;issue=21', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['url']);
    $this->assertEquals('Plenary%20Scientific%20Session', $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['search-parameters']['tocsectionid']);
    $this->assertEquals(124, $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['search-parameters']['volume']);
    $this->assertEquals(21, $structure['PlenaryAbstracts']['items']['PlenaryAbstractsPlenaryScientificSession']['search-parameters']['issue']);


    // Second section
    $this->assertEquals('Oral Abstracts', $structure['OralAbstracts']['heading']);
    $this->assertEquals('Oral Abstracts', $structure['OralAbstracts']['groupingkey']);
    $this->assertEquals('OralAbstracts', $structure['OralAbstracts']['header-id']);
    $this->assertEmpty($structure['OralAbstracts']['parent']);
    $this->assertCount(142, $structure['OralAbstracts']['items']);

    // Second section - first child.
    $this->assertEquals('101. Red Cells and Erythropoiesis, Structure and Function, Metabolism, and Survival, Excluding Iron: Pathogenic Mechanisms Affecting Red Cells & Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['heading']);
    $this->assertEquals('Oral Abstracts/101. Red Cells and Erythropoiesis, Structure and Function, Metabolism, and Survival, Excluding Iron: Pathogenic Mechanisms Affecting Red Cells & Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['groupingkey']);
    $this->assertEquals('OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['header-id']);
    $this->assertEquals('OralAbstracts', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['parent']);
    $this->assertEmpty($structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['items']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=101.%20Red%20Cells%20and%20Erythropoiesis%2C%20Structure%20and%20Function%2C%20Metabolism%2C%20and%20Survival%2C%20Excluding%20Iron%3A%20Pathogenic%20Mechanisms%20Affecting%20Red%20Cells%20%26%20Erythropoiesis&amp;volume=124&amp;issue=21', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['url']);
    $this->assertEquals('101.%20Red%20Cells%20and%20Erythropoiesis%2C%20Structure%20and%20Function%2C%20Metabolism%2C%20and%20Survival%2C%20Excluding%20Iron%3A%20Pathogenic%20Mechanisms%20Affecting%20Red%20Cells%20%26%20Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['search-parameters']['tocsectionid']);
    $this->assertEquals(124, $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['search-parameters']['volume']);
    $this->assertEquals(21, $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronPathogenicMechanismsAffectingRedCellsErythropoiesis']['search-parameters']['issue']);

    // Second section - second child.
    $this->assertEquals('101. Red Cells and Erythropoiesis, Structure and Function, Metabolism, and Survival, Excluding Iron: Systems Biology of Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['heading']);
    $this->assertEquals('Oral Abstracts/101. Red Cells and Erythropoiesis, Structure and Function, Metabolism, and Survival, Excluding Iron: Systems Biology of Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['groupingkey']);
    $this->assertEquals('OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['header-id']);
    $this->assertEquals('OralAbstracts', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['parent']);
    $this->assertEmpty($structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['items']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=101.%20Red%20Cells%20and%20Erythropoiesis%2C%20Structure%20and%20Function%2C%20Metabolism%2C%20and%20Survival%2C%20Excluding%20Iron%3A%20Systems%20Biology%20of%20Erythropoiesis&amp;volume=124&amp;issue=21', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['url']);
    $this->assertEquals('101.%20Red%20Cells%20and%20Erythropoiesis%2C%20Structure%20and%20Function%2C%20Metabolism%2C%20and%20Survival%2C%20Excluding%20Iron%3A%20Systems%20Biology%20of%20Erythropoiesis', $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['search-parameters']['tocsectionid']);
    $this->assertEquals(124, $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['search-parameters']['volume']);
    $this->assertEquals(21, $structure['OralAbstracts']['items']['OralAbstracts101.RedCellsandErythropoiesisStructureandFunctionMetabolismandSurvivalExcludingIronSystemsBiologyofErythropoiesis']['search-parameters']['issue']);

    // Second section - Third child.
    $this->assertEquals('102. Regulation of Iron Metabolism: Iron Overload - Novel Mechanisms and Treatments', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['heading']);
    $this->assertEquals('Oral Abstracts/102. Regulation of Iron Metabolism: Iron Overload - Novel Mechanisms and Treatments', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['groupingkey']);
    $this->assertEquals('OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['header-id']);
    $this->assertEquals('OralAbstracts', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['parent']);
    $this->assertEmpty($structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['items']);
    $this->assertEquals('/search?submit=yes&amp;tocsectionid=102.%20Regulation%20of%20Iron%20Metabolism%3A%20Iron%20Overload%20-%20Novel%20Mechanisms%20and%20Treatments&amp;volume=124&amp;issue=21', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['url']);
    $this->assertEquals('102.%20Regulation%20of%20Iron%20Metabolism%3A%20Iron%20Overload%20-%20Novel%20Mechanisms%20and%20Treatments', $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['search-parameters']['tocsectionid']);
    $this->assertEquals(124, $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['search-parameters']['volume']);
    $this->assertEquals(21, $structure['OralAbstracts']['items']['OralAbstracts102.RegulationofIronMetabolismIronOverload-NovelMechanismsandTreatments']['search-parameters']['issue']);

  }

  /**
   * Test cases to flatten the toc and get an array of apaths in toc order.
   */
  public function testTocParserFlatToc() {
    $path = __DIR__ . '/../assets/tocs/ajpcell.310.11.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $flat_toc = $parser->getTocFlat();

    $this->assertEquals('/ajpcell/310/11/C955.atom', $flat_toc[0]);
    $this->assertEquals('/ajpcell/310/11/C968.atom', $flat_toc[1]);
    $this->assertEquals('/ajpcell/310/11/C841.atom', $flat_toc[2]);
    $this->assertEquals('/ajpcell/310/11/C857.atom', $flat_toc[3]);
    $this->assertEquals('/ajpcell/310/11/C874.atom', $flat_toc[4]);
    $this->assertEquals('/ajpcell/310/11/C885.atom', $flat_toc[5]);
    $this->assertEquals('/ajpcell/310/11/C894.atom', $flat_toc[6]);
    $this->assertEquals('/ajpcell/310/11/C844.atom', $flat_toc[7]);
    $this->assertEquals('/ajpcell/310/11/C983.atom', $flat_toc[8]);
    $this->assertEquals('/ajpcell/310/11/C993.atom', $flat_toc[9]);
    $this->assertEquals('/ajpcell/310/11/C1001.atom', $flat_toc[10]);
    $this->assertEquals('/ajpcell/310/11/C903.atom', $flat_toc[11]);
    $this->assertEquals('/ajpcell/310/11/C911.atom', $flat_toc[12]);
    $this->assertEquals('/ajpcell/310/11/C921.atom', $flat_toc[13]);
    $this->assertEquals('/ajpcell/310/11/C931.atom', $flat_toc[14]);
    $this->assertEquals('/ajpcell/310/11/C942.atom', $flat_toc[15]);
    $this->assertEquals('/ajpcell/310/11/C1010.atom', $flat_toc[16]);
    $this->assertEquals('/ajpcell/310/11/C1024.atom', $flat_toc[17]);

  }

  /**
   * Test cases to flatten a nested toc and get an array of apaths in toc order.
   */
  public function testTocParserFlatTocNested() {
    $path = __DIR__ . '/../assets/tocs/btr.1.1-2.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $flat_toc = $parser->getTocFlat();

    $this->assertEquals('/btr/1/1-2/1.atom', $flat_toc[0]);
    $this->assertEquals('/btr/1/1-2/3.atom', $flat_toc[1]);
    $this->assertEquals('/btr/1/1-2/14.atom', $flat_toc[2]);
    $this->assertEquals('/btr/1/1-2/29.atom', $flat_toc[3]);
    $this->assertEquals('/btr/1/1-2/32.atom', $flat_toc[4]);
    $this->assertEquals('/btr/1/1-2/45.atom', $flat_toc[5]);
    $this->assertEquals('/btr/1/1-2/49.atom', $flat_toc[6]);
    $this->assertEquals('/btr/1/1-2/61.atom', $flat_toc[7]);
    $this->assertEquals('/btr/1/1-2/73.atom', $flat_toc[8]);
    $this->assertEquals('/btr/1/1-2/87.atom', $flat_toc[9]);

  }

  /**
   * Test section level book toc structure.
   */
  public function testTocParserSectionLevelBookTocStructure() {
    $path = __DIR__ . '/../assets/tocs/9780071357586.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $toc = $parser->getTocStructure();

    // Check the number of sections.
    $this->assertCount(7, $toc);

    // First Section.
    $this->assertNotEmpty($toc['Introduction']);
    $this->assertEquals('Introduction', $toc['Introduction']['heading']);
    $this->assertEquals('Introduction', $toc['Introduction']['groupingkey']);
    $this->assertEquals('Introduction', $toc['Introduction']['header-id']);
    $this->assertEmpty($toc['Introduction']['parent']);
    $this->assertNotEmpty($toc['Introduction']['items']);
    $this->assertCount(14, $toc['Introduction']['items']);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section1.atom', $toc['Introduction']['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section2.atom', $toc['Introduction']['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section3.atom', $toc['Introduction']['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section4.atom', $toc['Introduction']['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section13.atom', $toc['Introduction']['items'][4]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section17.atom', $toc['Introduction']['items'][5]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section18.atom', $toc['Introduction']['items'][6]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section19.atom', $toc['Introduction']['items'][7]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section23.atom', $toc['Introduction']['items'][8]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section24.atom', $toc['Introduction']['items'][9]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section39.atom', $toc['Introduction']['items'][10]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section41.atom', $toc['Introduction']['items'][11]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section47.atom', $toc['Introduction']['items'][12]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section48.atom', $toc['Introduction']['items'][13]);

    // Second Section
    $this->assertNotEmpty($toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']);
    $this->assertEquals('Mechanical Aspects and Macroscopic Fracture-Surface Orientation', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['heading']);
    $this->assertEquals('Mechanical Aspects and Macroscopic Fracture-Surface Orientation', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['groupingkey']);
    $this->assertEquals('MechanicalAspectsandMacroscopicFracture-SurfaceOrientation', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['header-id']);
    $this->assertEmpty($toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['parent']);
    $this->assertNotEmpty($toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items']);
    $this->assertCount(17, $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items']);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter2/section/section49.atom', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter2/section/section50.atom', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter2/section/section51.atom', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter2/section/section52.atom', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter2/section/section53.atom', $toc['MechanicalAspectsandMacroscopicFracture-SurfaceOrientation']['items'][4]);

    // Third Section
    $this->assertNotEmpty($toc['FractureMechanismsandMicrofractographicFeatures']);
    $this->assertEquals('Fracture Mechanisms and Microfractographic Features', $toc['FractureMechanismsandMicrofractographicFeatures']['heading']);
    $this->assertEquals('Fracture Mechanisms and Microfractographic Features', $toc['FractureMechanismsandMicrofractographicFeatures']['groupingkey']);
    $this->assertEquals('FractureMechanismsandMicrofractographicFeatures', $toc['FractureMechanismsandMicrofractographicFeatures']['header-id']);
    $this->assertEmpty($toc['FractureMechanismsandMicrofractographicFeatures']['parent']);
    $this->assertNotEmpty($toc['FractureMechanismsandMicrofractographicFeatures']['items']);
    $this->assertCount(21, $toc['FractureMechanismsandMicrofractographicFeatures']['items']);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section66.atom', $toc['FractureMechanismsandMicrofractographicFeatures']['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section67.atom', $toc['FractureMechanismsandMicrofractographicFeatures']['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section68.atom', $toc['FractureMechanismsandMicrofractographicFeatures']['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section69.atom', $toc['FractureMechanismsandMicrofractographicFeatures']['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section70.atom', $toc['FractureMechanismsandMicrofractographicFeatures']['items'][4]);

    // Fourth Section
    $this->assertNotEmpty($toc['FractureModesandMacrofractographicFeatures']);
    $this->assertEquals('Fracture Modes and Macrofractographic Features', $toc['FractureModesandMacrofractographicFeatures']['heading']);
    $this->assertEquals('Fracture Modes and Macrofractographic Features', $toc['FractureModesandMacrofractographicFeatures']['groupingkey']);
    $this->assertEquals('FractureModesandMacrofractographicFeatures', $toc['FractureModesandMacrofractographicFeatures']['header-id']);
    $this->assertEmpty($toc['FractureModesandMacrofractographicFeatures']['parent']);
    $this->assertNotEmpty($toc['FractureModesandMacrofractographicFeatures']['items']);
    $this->assertCount(9, $toc['FractureModesandMacrofractographicFeatures']['items']);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter4/section/section87.atom', $toc['FractureModesandMacrofractographicFeatures']['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter4/section/section88.atom', $toc['FractureModesandMacrofractographicFeatures']['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter4/section/section89.atom', $toc['FractureModesandMacrofractographicFeatures']['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter4/section/section90.atom', $toc['FractureModesandMacrofractographicFeatures']['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter4/section/section91.atom', $toc['FractureModesandMacrofractographicFeatures']['items'][4]);

    // Fifth Section
    $this->assertNotEmpty($toc['FailureAnalysisofComposites']);
    $this->assertEquals('Failure Analysis of Composites', $toc['FailureAnalysisofComposites']['heading']);
    $this->assertEquals('Failure Analysis of Composites', $toc['FailureAnalysisofComposites']['groupingkey']);
    $this->assertEquals('FailureAnalysisofComposites', $toc['FailureAnalysisofComposites']['header-id']);
    $this->assertEmpty($toc['FailureAnalysisofComposites']['parent']);
    $this->assertNotEmpty($toc['FailureAnalysisofComposites']['items']);
    $this->assertCount(6, $toc['FailureAnalysisofComposites']['items']);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section96.atom', $toc['FailureAnalysisofComposites']['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section97.atom', $toc['FailureAnalysisofComposites']['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section98.atom', $toc['FailureAnalysisofComposites']['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section112.atom', $toc['FailureAnalysisofComposites']['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section115.atom', $toc['FailureAnalysisofComposites']['items'][4]);
  }


  /**
   * Flatten the toc of a section level book.
   */
  public function testTocParserSectionLevelBookTocFlat() {
    $path = __DIR__ . '/../assets/tocs/9780071357586.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $flat_toc = $parser->getTocFlat();

    // Ensure the correct number of items are in the toc.
    $this->assertCount(86, $flat_toc);

    // Check the order.
    // First Section.
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section1.atom', $flat_toc[0]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section2.atom', $flat_toc[1]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section3.atom', $flat_toc[2]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section4.atom', $flat_toc[3]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section13.atom', $flat_toc[4]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section17.atom', $flat_toc[5]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section18.atom', $flat_toc[6]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section19.atom', $flat_toc[7]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter1/section/section23.atom', $flat_toc[8]);

    // Third section
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section66.atom', $flat_toc[31]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section67.atom', $flat_toc[32]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section68.atom', $flat_toc[33]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section69.atom', $flat_toc[34]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section70.atom', $flat_toc[35]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter3/section/section71.atom', $flat_toc[36]);

    // Fifth section
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section96.atom', $flat_toc[61]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section97.atom', $flat_toc[62]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section98.atom', $flat_toc[63]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section112.atom', $flat_toc[64]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section115.atom', $flat_toc[65]);
    $this->assertEquals('/mheaeworks/book/9780071357586/toc-chapter/chapter5/section/section122.atom', $flat_toc[66]);

  }

  /**
   * Test the toc structure of chapter level books.
   */
  public function testTocParserChapterLevelBookTocStructure() {
    $path = __DIR__ . '/../assets/tocs/9780071795531.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $toc = $parser->getTocStructure();

    // Ensure the correct number of items are in the toc.
    $this->assertCount(59, $toc[0]['items']);

    $this->assertEmpty($toc[0]['heading']);
    $this->assertEmpty($toc[0]['groupingkey']);
    $this->assertEmpty($toc[0]['header-id']);
    $this->assertEmpty($toc[0]['parent']);
    $this->assertNotEmpty($toc[0]['items']);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter1.atom', $toc[0]['items'][0]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter2.atom', $toc[0]['items'][1]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter3.atom', $toc[0]['items'][2]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter4.atom', $toc[0]['items'][3]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter5.atom', $toc[0]['items'][4]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter6.atom', $toc[0]['items'][5]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter7.atom', $toc[0]['items'][6]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter8.atom', $toc[0]['items'][7]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter9.atom', $toc[0]['items'][8]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter10.atom', $toc[0]['items'][9]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter11.atom', $toc[0]['items'][10]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter12.atom', $toc[0]['items'][11]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter13.atom', $toc[0]['items'][12]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter14.atom', $toc[0]['items'][13]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter15.atom', $toc[0]['items'][14]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter16.atom', $toc[0]['items'][15]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter17.atom', $toc[0]['items'][16]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter18.atom', $toc[0]['items'][17]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter19.atom', $toc[0]['items'][18]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter20.atom', $toc[0]['items'][19]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter21.atom', $toc[0]['items'][20]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter22.atom', $toc[0]['items'][21]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter23.atom', $toc[0]['items'][22]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter24.atom', $toc[0]['items'][23]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter25.atom', $toc[0]['items'][24]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter26.atom', $toc[0]['items'][25]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter27.atom', $toc[0]['items'][26]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter28.atom', $toc[0]['items'][27]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter29.atom', $toc[0]['items'][28]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter30.atom', $toc[0]['items'][29]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter31.atom', $toc[0]['items'][30]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter32.atom', $toc[0]['items'][31]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter33.atom', $toc[0]['items'][32]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter34.atom', $toc[0]['items'][33]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter35.atom', $toc[0]['items'][34]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter36.atom', $toc[0]['items'][35]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter37.atom', $toc[0]['items'][36]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter38.atom', $toc[0]['items'][37]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter39.atom', $toc[0]['items'][38]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter40.atom', $toc[0]['items'][39]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter41.atom', $toc[0]['items'][40]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter42.atom', $toc[0]['items'][41]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter43.atom', $toc[0]['items'][42]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter44.atom', $toc[0]['items'][43]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter45.atom', $toc[0]['items'][44]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter46.atom', $toc[0]['items'][45]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter47.atom', $toc[0]['items'][46]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter48.atom', $toc[0]['items'][47]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter49.atom', $toc[0]['items'][48]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter50.atom', $toc[0]['items'][49]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter51.atom', $toc[0]['items'][50]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter52.atom', $toc[0]['items'][51]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter53.atom', $toc[0]['items'][52]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter54.atom', $toc[0]['items'][53]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter55.atom', $toc[0]['items'][54]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter56.atom', $toc[0]['items'][55]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter57.atom', $toc[0]['items'][56]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter58.atom', $toc[0]['items'][57]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter59.atom', $toc[0]['items'][58]);

  }

  /**
   * Flatten the toc of a section level book.
   */
  public function testTocParserChapterLevelBookTocFlat() {
    $path = __DIR__ . '/../assets/tocs/9780071795531.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $flat_toc = $parser->getTocFlat();

    // Ensure the correct number of items are in the toc.
    $this->assertCount(59, $flat_toc);

    // Check the order.
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter1.atom', $flat_toc[0]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter2.atom', $flat_toc[1]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter3.atom', $flat_toc[2]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter4.atom', $flat_toc[3]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter5.atom', $flat_toc[4]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter6.atom', $flat_toc[5]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter7.atom', $flat_toc[6]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter8.atom', $flat_toc[7]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter9.atom', $flat_toc[8]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter10.atom', $flat_toc[9]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter11.atom', $flat_toc[10]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter12.atom', $flat_toc[11]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter13.atom', $flat_toc[12]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter14.atom', $flat_toc[13]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter15.atom', $flat_toc[14]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter16.atom', $flat_toc[15]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter17.atom', $flat_toc[16]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter18.atom', $flat_toc[17]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter19.atom', $flat_toc[18]);



  }

  /**
   * Flatten the toc of a Chapter level book.
   * This function includes everything.
   */
  public function testTocParserChapterLevelBookFullTocFlat() {
    $path = __DIR__ . '/../assets/tocs/9780071795531.full.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);
    $flat_toc = $parser->getFullTocFlat(0);

    // Ensure the correct number of items are in the toc.
    $this->assertCount(59, $flat_toc);

    // Check the order.
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter1.atom', $flat_toc[0]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter2.atom', $flat_toc[1]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter3.atom', $flat_toc[2]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter4.atom', $flat_toc[3]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter5.atom', $flat_toc[4]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter6.atom', $flat_toc[5]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter7.atom', $flat_toc[6]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter8.atom', $flat_toc[7]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter9.atom', $flat_toc[8]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter10.atom', $flat_toc[9]);

    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter11.atom', $flat_toc[10]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter12.atom', $flat_toc[11]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter13.atom', $flat_toc[12]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter14.atom', $flat_toc[13]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter15.atom', $flat_toc[14]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter16.atom', $flat_toc[15]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter17.atom', $flat_toc[16]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter18.atom', $flat_toc[17]);
    $this->assertEquals('/mheaeworks/book/9780071795531/chapter/chapter19.atom', $flat_toc[18]);


  }

  /**
   * Flatten the toc of a Section level book.
   * This function includes everything.
   */
  public function testTocParserSectionLevelBookFullTocFlat() {
    $path = __DIR__ . '/../assets/tocs/9780070419995.full.toc.xml';
    $xml = file_get_contents($path);

    $parser = new TocParser($xml);

    $flat_toc = $parser->getFullTocFlat(1);
    // Ensure the correct number of items are in the toc
    // when we pass 1 as the argument.
    $this->assertCount(762, $flat_toc);

    $flat_toc = $parser->getFullTocFlat(2);

    // Ensure the correct number of items are in the toc
    // when we pass 2 as the argument.
    $this->assertCount(1439, $flat_toc);

    // Check the order against deepth 2.
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1.atom', $flat_toc[0]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section1.atom', $flat_toc[1]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section2.atom', $flat_toc[2]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section3.atom', $flat_toc[3]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section4.atom', $flat_toc[4]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section12.atom', $flat_toc[12]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter1/section/section13.atom', $flat_toc[13]);

    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2.atom', $flat_toc[17]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2/section/section17.atom', $flat_toc[18]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2/section/section18.atom', $flat_toc[19]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2/section/section21.atom', $flat_toc[22]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2/section/section21/search-section/section22.atom', $flat_toc[23]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter2/section/section21/search-section/section23.atom', $flat_toc[24]);

    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter3/section/section87/search-section/section92.atom', $flat_toc[94]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter3/section/section87/search-section/section93.atom', $flat_toc[95]);

    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter7.atom', $flat_toc[458]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter19.atom', $flat_toc[1423]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter19/section/section1406.atom', $flat_toc[1424]);
    $this->assertEquals('/mheaeworks/book/9780070419995/toc-chapter/chapter19/section/section1408/search-section/section1409.atom', $flat_toc[1427]);

  }

}
