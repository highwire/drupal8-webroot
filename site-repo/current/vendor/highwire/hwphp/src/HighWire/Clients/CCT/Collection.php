<?php

namespace HighWire\Clients\CCT;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CCT Collection Class.
 *
 * @package HighWire\Clients\CCT
 */
class Collection {

  /**
   * The collection id.
   *
   * @var integer
   */
  protected $id;

  /**
   * The Collection name.
   *
   * @var string
   */
  protected $name;

  /**
   * The publisher id for the collection.
   *
   * @var string
   */
  protected $publisherId;

  /**
   * The Collection description.
   *
   * @var string
   */
  protected $description;

  /**
   * The Collection availablity starting timestamp.
   *
   * @var int
   */
  protected $availableStarting;

  /**
   * The Collection individual availablity.
   *
   * @var int
   */
  protected $individualAvailability;

  /**
   * The Collection institutional availablity.
   *
   * @var int
   */
  protected $institutionalAvailability;

  /**
   * The Collection status.
   *
   * @var string
   */
  protected $status;

  /**
   * The Collection created by.
   *
   * @var string
   */
  protected $createdBy;

  /**
   * The Collection modified by.
   *
   * @var string
   */
  protected $modifiedBy;

  /**
   * The Collection modified date added timestamp.
   *
   * @var int
   */
  protected $modifiedOn;

  /**
   * The Collection date added timestamp.
   *
   * @var int
   */
  protected $dateAdded;

  /**
   * The Collection primary color.
   *
   * @var string
   */
  protected $primaryColor;

  /**
   * The Collection secondary color.
   *
   * @var string
   */
  protected $secondaryColor;

  /**
   * The Collection feature image id.
   *
   * @var int
   */
  protected $featureImageId;

  /**
   * The Collection header image id.
   *
   * @var int
   */
  protected $headerImageId;

  /**
   * The Collection date format.
   *
   * @var string
   */
  protected $dateFormat;

  /**
   * The Collection landing page id.
   *
   * @var int
   */
  protected $landingPageId;

  /**
   * The Collection rss id.
   *
   * @var string
   */
  protected $rssId;

  /**
   * The Collection rss upload id.
   *
   * @var string
   */
  protected $rssUploadId;

  /**
   * The Collection path.
   *
   * @var string
   */
  protected $path;

  /**
   * The Collection acs url.
   *
   * @var string
   */
  protected $acsUrl;

  /**
   * The Collection show key words flag.
   *
   * @var int
   */
  protected $showKeywords;

  /**
   * The Collection article citation format.
   *
   * @var string
   */
  protected $artCitationFmt;

  /**
   * The Collection book citation format.
   *
   * @var string
   */
  protected $bkCitationFmt;

  /**
   * The Collection news citation format.
   *
   * @var string
   */
  protected $newsCitationFmt;

  /**
   * The Collection update frequency.
   *
   * @var string
   */
  protected $updateFrequency;

  /**
   * The Collection sort by order.
   *
   * @var string
   */
  protected $sortBy;

  /**
   * The Collection site code.
   *
   * @var string
   */
  protected $siteCode;

  /**
   * The Collection keywords.
   *
   * @var array
   */
  protected $keywords;

  /**
   * The Collection csv upload list.
   *
   * @var array
   */
  protected $csvUploadList;

  /**
   * Collection constructor.
   *
   * @param array $collection_data
   *   Return response from CCT service.
   */
  public function __construct(array $collection_data) {
    // Set the values.
    $this->setId($collection_data['collectionId']);
    $this->setName($collection_data['name']);
    $this->setPublisherId($collection_data['publisherId']);
    $this->setDescription($collection_data['description']);
    $this->setAvailableStarting($collection_data['availableStarting']);
    $this->setInstitutionalAvailability($collection_data['individualAvailability']);
    $this->setStatus($collection_data['status']);
    $this->setCreatedBy($collection_data['createdBy']);
    $this->setDateAdded($collection_data['dateAdded']);
    $this->setModifiedBy($collection_data['modifiedBy']);
    $this->setModifiedOn($collection_data['modifiedOn']);
    $this->setPrimaryColor($collection_data['primaryColor']);
    $this->setSecondaryColor($collection_data['secondaryColor']);
    $this->setFeatureImageId($collection_data['featureImageId']);
    $this->setHeaderImageId($collection_data['headerImageId']);
    $this->setDateFormat($collection_data['dateFormat']);
    $this->setLandingPageId($collection_data['landingPageId']);
    $this->setRssId($collection_data['rssId']);
    $this->setRssUploadId($collection_data['rssUploadId']);
    $this->setPath($collection_data['path']);
    $this->setAcsUrl($collection_data['acsUrl']);
    $this->setShowKeywords($collection_data['showKeywords']);
    $this->setArtCitationFmt($collection_data['artCitationFmt']);
    $this->setBkCitationFmt($collection_data['bkCitationFmt']);
    $this->setNewsCitationFmt($collection_data['newsCitationFmt']);
    $this->setUpdateFrequency($collection_data['updateFrequency']);
    $this->setSortBy($collection_data['sortBy']);
    $this->setSiteCode($collection_data['siteCode']);
    $this->setKeywords($collection_data['keywords']);
    $this->setCsvUploadList($collection_data['csvUploadList']);

  }

  /**
   * Get published value for the collection.
   *
   * @return boolean
   *  TRUE if collection is published, FALSE if it is not.
   */
  public function isPublished() {
    if ($this->getStatus() == 'Published') {
      return TRUE;
    }
    
    return FALSE;
  }

  /************************************
   * Getters and Setters.             *
   ************************************/

  /**
   * Get the id.
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set the id.
   *
   * @return int
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * Get the name.
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Set the name.
   *
   * @return string
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * Get the publisher id.
   *
   * @return string
   */
  public function getPublisherId() {
    return $this->publisherId;
  }

  /**
   * Set the publisher id.
   *
   * @return string
   */
  public function setPublisherId($publisher_id) {
    $this->publisherId = $publisher_id;
  }

  /**
   * Get the description.
   *
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set the description.
   *
   * @return void
   */
  public function setDescription($description) {
    $this->description = $description;
  }

  /**
   * Get the available starting date timestamp.
   *
   * @return int
   */
  public function getAvailableStarting() {
    return $this->availableStarting;
  }

  /**
   * Set the available starting date timestamp.
   *
   * @return void
   */
  public function setAvailableStarting($available_starting) {
    $this->availableStarting = $available_starting;
  }

  /**
   * Get the individual availability.
   *
   * @return int
   */
  public function getIndividualAvailability() {
    return $this->individualAvailability;
  }

  /**
   * Set the individual availability.
   *
   * @return void
   */
  public function setIndividualAvailability($individual_availability) {
    $this->individualAvailability = $individual_availability;
  }

  /**
   * Get the institutional availability.
   *
   * @return int
   */
  public function getInstitutionalAvailability() {
    return $this->institutionalAvailability;
  }

  /**
   * Set the institutional availability.
   *
   * @return void
   */
  public function setInstitutionalAvailability($institutional_availability) {
    $this->institutionalAvailability = $institutional_availability;
  }

  /**
   * Get the status.
   *
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * Set the status.
   *
   * @return void
   */
  public function setStatus($collection_status) {
    $this->status = $collection_status;
  }

  /**
   * Get the created by.
   *
   * @return string
   */
  public function getCreatedBy() {
    return $this->createdBy;
  }

  /**
   * Set the created by.
   *
   * @return void
   */
  public function setCreatedBy($created_by) {
    $this->createdBy = $created_by;
  }

  /**
   * Get the date added.
   *
   * @return int
   */
  public function getDateAdded() {
    return $this->dateAdded;
  }

  /**
   * Set the date added.
   *
   * @return void
   */
  public function setDateAdded($date_added) {
    $this->dateAdded = $date_added;
  }

  /**
   * Get the modified by.
   *
   * @return string
   */
  public function getModifiedBy() {
    return $this->modifiedBy;
  }

  /**
   * Set the modified by.
   *
   * @return void
   */
  public function setModifiedBy($modified_by) {
    $this->modifiedBy = $modified_by;
  }

  /**
   * Get the modified on.
   *
   * @return string
   */
  public function getModifiedOn() {
    return $this->modifiedOn;
  }

  /**
   * Set the modified on.
   *
   * @return void
   */
  public function setModifiedOn($modified_on) {
    $this->modifiedOn = $modified_on;
  }

  /**
   * Get the primary color.
   *
   * @return string
   */
  public function getPrimaryColor() {
    return $this->primaryColor;
  }

  /**
   * Set the primary color.
   *
   * @return void
   */
  public function setPrimaryColor($primary_color) {
    $this->primaryColor = $primary_color;
  }

  /**
   * Get the secondary color.
   *
   * @return string
   */
  public function getSecondaryColor() {
    return $this->secondaryColor;
  }

  /**
   * Set the secondary color.
   *
   * @return void
   */
  public function setSecondaryColor($secondary_color) {
    $this->secondaryColor = $secondary_color;
  }

  /**
   * Get the feature image id.
   *
   * @return int
   */
  public function getFeatureImageId() {
    return $this->featureImageId;
  }

  /**
   * Set the feature image id.
   *
   * @return void
   */
  public function setFeatureImageId($feature_image_id) {
    $this->featureImageId = $feature_image_id;
  }

  /**
   * Get the header image id.
   *
   * @return int
   */
  public function getHeaderImageId() {
    return $this->headerImageId;
  }

  /**
   * Set the header image id.
   *
   * @return void
   */
  public function setHeaderImageId($header_image_id) {
    $this->headerImageId = $header_image_id;
  }

  /**
   * Get the date format.
   *
   * @return string
   */
  public function getDateFormat() {
    return $this->dateFormat;
  }

  /**
   * Set the date format.
   *
   * @return void
   */
  public function setDateFormat($date_format) {
    $this->dateFormat = $date_format;
  }

  /**
   * Get the landing page id.
   *
   * @return string
   */
  public function getLandingPageId() {
    return $this->landingPageId;
  }

  /**
   * Set the landing page id.
   *
   * @return void
   */
  public function setLandingPageId($landing_page_id) {
    $this->landingPageId = $landing_page_id;
  }

  /**
   * Get the rss id.
   *
   * @return string
   */
  public function getRssId() {
    return $this->rssId;
  }

  /**
   * Set the rss id.
   *
   * @return void
   */
  public function setRssId($rss_id) {
    $this->rssId = $rss_id;
  }

  /**
   * Get the rss upload id.
   *
   * @return string
   */
  public function getRssUploadId() {
    return $this->rssUploadId;
  }

  /**
   * Set the rss id.
   *
   * @return void
   */
  public function setRssUploadId($rss_id) {
    $this->rssUploadId = $rss_id;
  }

  /**
   * Get the path.
   *
   * @return string
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Set the path.
   *
   * @return void
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * Get the acs url.
   *
   * @return string
   */
  public function getAcsUrl() {
    return $this->acsUrl;
  }

  /**
   * Set the acs url.
   *
   * @return void
   */
  public function setAcsUrl($acs_url) {
    $this->acsUrl = $acs_url;
  }

  /**
   * Get show keywords.
   *
   * @return int
   */
  public function getShowKeywords() {
    return $this->showKeywords;
  }

  /**
   * Set show keywords.
   *
   * @return void
   */
  public function setShowKeywords($show_keywords) {
    $this->showKeywords = $show_keywords;
  }

  /**
   * Get article citation format.
   *
   * @return string
   */
  public function getArtCitationFmt() {
    return $this->artCitationFmt;
  }

  /**
   * Set article citation format.
   *
   * @return void
   */
  public function setArtCitationFmt($article_citation_format) {
    $this->artCitationFmt = $article_citation_format;
  }

  /**
   * Get book citation format.
   *
   * @return string
   */
  public function getBkCitationFmt() {
    return $this->bkCitationFmt;
  }

  /**
   * Set book citation format.
   *
   * @return void
   */
  public function setBkCitationFmt($book_citation_format) {
    $this->bkCitationFmt = $book_citation_format;
  }

  /**
   * Get news citation format.
   *
   * @return string
   */
  public function getNewsCitationFmt() {
    return $this->newsCitationFmt;
  }

  /**
   * Set news citation format.
   *
   * @return void
   */
  public function setNewsCitationFmt($news_citation_format) {
    $this->newsCitationFmt = $news_citation_format;
  }

  /**
   * Get update frequency.
   *
   * @return string
   */
  public function getUpdateFrequency() {
    return $this->updateFrequency;
  }

  /**
   * Set update frequency.
   *
   * @return void
   */
  public function setUpdateFrequency($update_frequency) {
    $this->updateFrequency = $update_frequency;
  }

  /**
   * Get sort by.
   *
   * @return string
   */
  public function getSortBy() {
    return $this->sortBy;
  }

  /**
   * Set sort by.
   *
   * @return void
   */
  public function setSortBy($sort_by) {
    $this->sortBy = $sort_by;
  }

  /**
   * Get site code.
   *
   * @return string
   */
  public function getSiteCode() {
    return $this->siteCode;
  }

  /**
   * Set site code.
   *
   * @return void
   */
  public function setSiteCode($site_code) {
    $this->siteCode = $site_code;
  }

  /**
   * Get keywords.
   *
   * @return string
   */
  public function getKeywords() {
    return $this->siteCode;
  }

  /**
   * Set keywords.
   *
   * @return void
   */
  public function setKeywords($keywords) {
    $this->siteCode = $keywords;
  }

  /**
   * Get Csv Upload List.
   *
   * @return string
   */
  public function getCsvUploadList() {
    return $this->csvUploadList;
  }

  /**
   * Set Csv Upload List.
   *
   * @return void
   */
  public function setCsvUploadList($csv_upload_list) {
    $this->csvUploadList = $csv_upload_list;
  }

}