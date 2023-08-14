<?php

namespace HighWire\Parser\Logging;

use HighWire\Parser\DOMElementBase;
use HighWire\Parser\AC\Identity;

/**
 * Authentication holds a single "<log:authentication>" element.
 *
 * For example:
 *  @code
 *  <log:authentication profile-id='2' profile-type='publisher' identifier-type='' profile-name='Springer Publications (PUBLISHER)' />
 *  @endcode
 */
class Authentication extends DOMElementBase {

  /**
   * Default XML to load if none is provided.
   *
   * @var string
   */
  protected $default_xml = '<log:authentication xmlns:log="http://schema.highwire.org/Service/Log" />';

  /**
   * {@inheritdoc}
   */
  protected $namespaces = [
    'log' => 'http://schema.highwire.org/Service/Log',
  ];

  /**
   * Get the profile id.
   *
   * @return string
   *   The profile id as defined in the 'profile-id' attribute.
   */
  public function profileId(): string {
    return $this->getAttribute('profile-id');
  }

  /**
   * Get the profile type
   *
   * @return string
   *   One of 'organization', 'individual', 'publisher'
   */
  public function profileType(): string {
    return $this->getAttribute('profile-type');
  }

  /**
   * Get the identifier type.
   *
   * @return string
   *   The description as defined in the 'description' attribute.
   */
  public function identifierType(): string {
    return $this->getAttribute('identifier-type');
  }

  /**
   * Get the profile name.
   *
   * @return string
   *   The profile name as defined in the 'profile-name' attribute.
   */
  public function profileName(): string {
    return $this->getAttribute('profile-name');
  }

  /**
   * Set the profile id.
   *
   * @param string $profile_id
   *   The profile id to be stored in the 'profile-id' attribute.
   */
  public function setProfileId(string $profile_id) {
    $this->setAttribute('profile-id', $profile_id);
  }

  /**
   * Set the profile type.
   *
   * @param string $profile_type
   *   The profile type to be stored in the 'profile-type' attribute.
   */
  public function setProfileType(string $profile_type) {
    $this->setAttribute('profile-type', $profile_type);
  }
  
  /**
   * Set the identifier type.
   *
   * @param string $identifier_type
   *   The identifier type to be stored in the 'identifier-type' attribute.
   */
  public function SetIdentifierType(string $identifier_type) {
    $this->setAttribute('identifier-type', $identifier_type);
  }

  /**
   * Set the profile name.
   *
   * @param string $profile_name
   *   The profile name to be stored in the 'profile-name' attribute.
   */
  public function setProfileName(string $profile_name) {
    $this->setAttribute('profile-name', $profile_name);
  }

  /**
   * Fill values from AC Identity element.
   *
   * @param \HighWire\Parser\AC\Identity $ident
   *   AC Identity element object.
   */
  public function fillFromACIdentity(Identity $ident) {
    $this->setProfileId($ident->getUserId());
    $this->setProfileName($ident->getDisplayName());
    $this->setProfileType($ident->getType());
    if ($cred = $ident->getCredentials()) {
      $this->setIdentifierType($cred->getMethod());
    }
  }

}
