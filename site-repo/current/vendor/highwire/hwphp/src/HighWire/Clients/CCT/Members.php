<?php

namespace HighWire\Clients\CCT;

use GuzzleHttp\Client as GuzzleClient;
use HighWire\Clients\Client;
use GuzzleHttp\Promise\Promise;
use HighWire\Clients\HWResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class Members
 *
 * @package HighWire\Clients\CCT
 */
class Members {

  /**
   * Member items array.
   *
   * @var array
   */
  protected $members;

  /**
   * The beginning index for the members results.
   *
   * @var int
   */
  protected $startIndex;

  /**
   * The number of results returned in the cct request.
   *
   * @var int
   */
  protected $numResults;

  /**
   * The number of total items in the collection.
   *
   * @var int
   */
  protected $totalResults;



  /**
   * Members constructor.
   *
   * @param array $members_data
   *   Return response from Catalog service.
   */
  public function __construct(array $members_data) {

    $members_data = $members_data;
    $this->setStartIndex($members_data['startIndex']);
    $this->setNumResults($members_data['numResults']);
    $this->setTotalResults($members_data['totalResults']);
    foreach ($members_data['feed'] as $member_data) {
      if (!empty($member_data['entry'])) {
        $member_item = new Member($member_data['entry']);
        $this->addMember($member_item, $member_item->getUri());
      }
    }
  }

  // Getters and Setters.
  /**
   * Get the start index.
   *
   * @return int
   */
  public function getStartIndex() {
    return $this->startIndex;
  }

  /**
   * Set the start index.
   *
   * @return int
   */
  public function setStartIndex($start_index) {
    $this->startIndex = $start_index;
  }

  /**
   * Get the number of results.
   *
   * @return int
   */
  public function getNumResults() {
    return $this->numResults;
  }

  /**
   * Set the number of results.
   *
   * @return int
   */
  public function setNumResults($num_results) {
    $this->numResults = $num_results;
  }
  
  /**
   * Get the total number of items in the collection.
   *
   * @return int
   */
  public function getTotalResults() {
    return $this->totalResults;
  }

  /**
   * Set the total number of items in the collection.
   *
   * @return int
   */
  public function setTotalResults($total_results) {
    $this->totalResults = $total_results;
  }

  /**
   * Get the members.
   *
   * @return array
   *   Returns an array of Members objects.
   */
  public function getMembers() {
    return $this->members;
  }

  /**
   * Gets the first member.
   *
   * @return Member|NULL
   *   A Member object or NULL.
   */
  public function getFirstMember() {
    if (empty($this->members)) {
      return NULL;
    }
    foreach ($this->members as $member) {
      return $member;
    }
  }

  /**
   * @param Member $member
   *   Member to add to items array.
   *
   * @param string $key
   *   An id to be used as a key for the Member.
   */
  public function addMember(Member $member, $key = NULL) {
    if (!empty($key)) {
      $this->members[$key] = $member;
    }
    else {
      $this->members[] = $member;
    }
  }

}