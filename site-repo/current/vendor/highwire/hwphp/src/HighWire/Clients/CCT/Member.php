<?php

namespace HighWire\Clients\CCT;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class Member
 *
 * @package HighWire\Clients\CCT
 */
class Member {

  /**
   * The member type.
   *
   * @var string
   */
  protected $type;

  /**
   * The member uri.
   *
   * @var string
   */
  protected $uri;

  /**
   * The member publication date timestamp.
   *
   * @var int
   */
  protected $publicationDate;

  /**
   * The member title.
   *
   * @var string
   */
  protected $title;

  /**
   * The member electronic publication date.
   *
   * @var string
   */
  protected $ePubDate;

  /**
   * The member print publication date.
   *
   * @var string
   */
  protected $pPubDate;


  /**
   * Member constructor.
   *
   * @param array $member
   *   An array representing a collection member.
   */
  public function __construct(array $member) {
      
    $this->setType($member['type']);
    $this->setUri($member['uri']);
    $this->setPublicationDate($member['publication-date']);
    $this->setTitle($member['title']);
    $this->setEPubDate($member['epub-date']);      
  }

  // Getters and Setters.

  /**
   * Get the member type.
   *
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the member type.
   *
   * @return void
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * Get the member uri.
   *
   * @return string
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * Set the member uri.
   *
   * @return void
   */
  public function setUri($uri) {
    $this->uri = $uri;
  }

  /**
   * Get the member publication date.
   *
   * @return int
   */
  public function getPublicationDate() {
    return $this->uri;
  }

  /**
   * Set the member uri.
   *
   * @return void
   */
  public function setPublicationDate($publication_date) {
    $this->publicationDate = $publication_date;
  }

  /**
   * Get the member title.
   *
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Set the member title.
   *
   * @return void
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * Get the member electronic publication date.
   *
   * @return string
   */
  public function getEPubDate() {
    return $this->ePubDate;
  }

  /**
   * Set the member electronic publication date.
   *
   * @return void
   */
  public function setEPubDate($e_pub_date) {
    $this->ePubDate = $e_pub_date;
  }

  /**
   * Get the member print publication date.
   *
   * @return string
   */
  public function getPPubDate() {
    return $this->pPubDate;
  }

  /**
   * Set the member print publication date.
   *
   * @return void
   */
  public function setPPubDate($p_pub_date) {
    $this->pPubDate = $p_pub_date;
  }


  
}
