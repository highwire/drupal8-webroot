<?php

namespace HighWire\Parser\AC;

/**
 * Access Control Identity.
 *
 * @see Response
 *
 * Example XML:
 * @code
 * <ac:identity runtime-id="70c33862-26d4-404f-a9a1-a4cc2916016a" type="individual" user-id="1911" subcode="dupjnl_subdev" customer-number="TOLGA1" display-name="olga Biasotti" email="obiasotti@highwire.org">
 *   <ac:privilege runtime-id="5e47762d-1532-4b8d-b612-8a5166a8b51c" type="subscription" user-id="1911" status="ACTIVE" expiration="9999-12-31T23:59:59.999-08:00" privilege-set="MALL" />
 *   <ac:privilege runtime-id="3f8632eb-2f3d-4af8-81e8-275ffd631eac" type="subscription" user-id="1911" status="ACTIVE" expiration="9999-12-31T23:59:59.999-08:00" privilege-set="MNO" />
 *   <ac:privilege runtime-id="91d0c73c-c501-4378-a970-9f60a11229db" type="subscription" user-id="1911" status="ACTIVE" expiration="9999-12-31T23:59:59.999-08:00" privilege-set="MSSH" />
 *   <ac:privilege runtime-id="f831968b-b799-4188-b449-7e7aa2492a41" type="privilege-set" privilege-set="AUTHUSER" />
 *   <ac:credentials method="username">tolga1</ac:credentials>
 * </ac:identity>
 * @endcode
 */
class Identity extends ACElement {

  /**
   * {@inheritdoc}
   */
  protected $id_attribute = 'runtime-id';

  /**
   * Get the identity type.
   *
   * @return string
   *   Examples: "institution", "individual"
   */
  public function getType() {
    return $this->getAttribute('type');
  }

  /**
   * Get the user ID.
   *
   * This ID is stable accross subsequent requests and responses
   * and can be used to unqiuely identify the identity when used
   * together with the subcode and the customer-number.
   *
   * Only fufilled identities have a user-id.
   *
   * @return string|null
   *   The user-id or NULL if the identity is not fufilled and has no user-id.
   */
  public function getUserId() {
    return $this->getAttribute('user-id');
  }

  /**
   * Get the subcode.
   *
   * The subcode references the AC database that the identity is from.
   * It may be used together with the user-id and the customer-number to
   * unqiquely identify the identity.
   * The subcode is often, but not always, related to the publisher.
   *
   * @return string
   *   Examples: dupjnl_subdev
   */
  public function getSubcode() {
    // AC 3, camel case.
    $subcode = $this->getAttribute('subCode');
    if (empty($subcode)) {
      // AC 2.
      $subcode = $this->getAttribute('subcode');
    }
    return $subcode;
  }

  /**
   * Get the customer number.
   *
   * The customer number unqiuely indentifies the identity
   * along with the subcode and user-id.
   * Unlike the user-id, it will be present for unfulfilled identities.
   *
   * @return string
   *   Examples: ABC-123
   */
  public function getCustomerNumber() {
    return $this->getAttribute('customer-number');
  }

  /**
   * Get the display name for the identity.
   *
   * @return string
   *   Examples: "John Smith"
   */
  public function getDisplayName() {
    return $this->getAttribute('display-name');
  }

  /**
   * Get the email for the identity.
   *
   * @return string
   *   Examples: "john.smith@example.com"
   */
  public function getEmail() {
    return $this->getAttribute('email');
  }

  /**
   * Get the credentials used to authenticate this identity.
   *
   * @return \HighWire\Parser\AC\Credential
   *   An array of credential object.
   */
  public function getCredentials() {
    $elem = $this->dom->xpathSingle(".//ac:credentials", $this->elem);
    if (empty($elem)) {
      return NULL;
    }

    return new Credential($elem, $this->dom);
  }

  /**
   * Get a privilege that this identity holds by privilege id.
   *
   * @param string $id
   *   The runtime id for the privilege. This is not
   *   stable across multiple requests / responses.
   *
   * @return Privilege|null
   *   Return a priviledge by id, or null if it's not found.
   */
  public function getPrivilege($id) {
    // AC 3
    $elem = $this->xpathSingle(".//ac:privilege[@id='$id']");

    if (empty($elem)) {
      // AC 2
      $elem = $this->xpathSingle(".//ac:privilege[@runtime-id='$id']");
    }

    if (empty($elem)) {
      return NULL;
    }

    return new Privilege($elem, $this->dom);
  }

  /**
   * Get all privilege that this identity holds.
   *
   * @return Privilege[]
   *   Return an array of privileges.
   */
  public function getAllPrivileges() {
    $privs = [];
    foreach ($this->xpath('.//ac:privilege') as $elem) {
      $priv = new Privilege($elem, $this->dom);
      if ($id = $priv->getId()) {
        $privs[$id] = $priv;
      }
      else {
        $privs[] = $priv;
      }
    }
    return $privs;
  }

  /**
   * Check if this identity is anonymous / guest.
   *
   * @return bool
   *   TRUE if this identity is an anonymous guest identity, FALSE otherwise.
   */
  public function isGuest() {
    return ($this->getId() == 'urn:ac.highwire.org:guest:identity');
  }

}
