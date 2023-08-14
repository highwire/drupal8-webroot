<?php

namespace HighWire\Clients\Catalog;

class Product {
  protected $id;
  protected $sku;
  protected $scheme;
  protected $unit;
  protected $purchaseOptions;

  /**
   * Product constructor.
   *
   * @param array $data
   *   Array representation of a product from the Catalog service response.
   */
  public function __construct(array $data) {
    $this->setUnit($data['unit']);

    if (empty($data['scheme']) && !empty($data['sku'])) {
      $this->setScheme($data['sku']);
    }
    else {
      $this->setScheme($data['scheme']);
    }

    if (!empty($data['sku'])) {
      $this->setSku($data['sku']);
    }
    $id = $this->getIdFromScheme();
    $this->setId($id);
    $this->setPurchaseOptions($data['disposition'], $data['selectors'][0]['prices']);
  }

  /**
   * Get the sku for the item.
   *
   * @return string
   *   The sku for the item.
   */
  public function getSku(): string {
    if (!empty($this->sku)) {
      return $this->sku;
    }

    return '';
  }

  /**
   * Set the sku for the item.
   *
   * @param string $sku
   *   The sku to set for the item.
   */
  public function setSku(string $sku) {
    $this->sku = $sku;
  }

  /**
   * Get the scheme for the product.
   *
   * @return string|null
   *   The scheme for the product.
   */
  public function getScheme(): string {
    if (!empty($this->scheme)) {
      return $this->scheme;
    }

    return NULL;
  }

  /**
   * Set the scheme for the product.
   *
   * @param string $scheme
   *   The scheme for the product.
   */
  public function setScheme(string $scheme) {
    $this->scheme = $scheme;
  }

  /**
   * Get the unit for the item (e.g. article, issue, volume, journal).
   *
   * @return string|null
   *   The unit for the item.
   */
  public function getUnit(): string {
    if (!empty($this->unit)) {
      return $this->unit;
    }

    return NULL;
  }

  /**
   * Set the unit for the item (e.g. article, issue, volume, journal).
   *
   * @param string $unit
   *   The unit for the item.
   */
  public function setUnit(string $unit) {
    $this->unit = $unit;
  }

  /**
   * Get an array of Price objects.
   *
   * @param string $disposition
   *   The disposition for the prices to get.
   *
   * @return array
   *   An array of Price objects.
   */
  public function getPrices(string $disposition): array {
    return $this->getPurchaseOptions($disposition);
  }

  /**
   * Add price object to the purchase options array for the product.
   *
   * @param \HighWire\Clients\Catalog\Price $price
   *   Add a Price object to the prices array.
   * @param string $disposition
   *   The disposition for the price to be added.
   */
  public function addPrice(Price $price, string $disposition) {
    $currency = $price->getCurrency();
    $interval = $price->getInterval();

    if (!empty($disposition) && !empty($interval)) {
      $this->purchaseOptions[$disposition][$interval->format(Catalog::INTERVAL_FORMAT)][$currency] = $price;
    }
  }

  /**
   * Get the id for the product.
   *
   * @return string
   *   The id for the product.
   */
  public function getId(): string {
    if (!empty($this->id)) {
      return $this->id;
    }

    return '';
  }

  /**
   * Set the id for the product.
   *
   * @param string $id
   *   Id to set for the product.
   */
  public function setId(string $id) {
    $this->id = $id;
  }

  /**
   * Get the purchase options for the product.
   *
   * @param string $disposition
   *   Optionally look up purchase options by disposition. If empty, will return all options.
   *
   * @return array
   *   The purchase options for the product.
   */
  public function getPurchaseOptions(string $disposition = ''): array {
    if (!empty($this->purchaseOptions)) {
      if (!empty($disposition) && !empty($this->purchaseOptions[$disposition])) {
        return $this->purchaseOptions[$disposition];
      }
      return $this->purchaseOptions;
    }

    return [];
  }

  /**
   * Set the purchase options for the product.
   *
   * @param string $disposition
   *   Disposition for the purchase options.
   * @param array $prices
   *   An array of prices.
   */
  public function setPurchaseOptions(string $disposition, array $prices) {
    if (empty($this->purchaseOptions[$disposition])) {
      $this->purchaseOptions[$disposition] = [];
    }
    foreach ($prices as $price) {
      $price_item = new Price($price);
      $this->addPrice($price_item, $disposition);
    }

  }

  /**
   * Add a purchase option to the product.
   *
   * @param array $prices
   *   An array of prices, keyed by currency.
   * @param string $disposition
   *   Disposition for the purchase options.
   * @param string $interval
   *   Interval for the purchase options.
   */
  public function addPurchaseOption(array $prices, string $disposition, string $interval) {
    if (!empty($disposition) && !empty($interval) && is_array($prices)) {
      if (empty($this->purchaseOptions[$disposition][$interval])) {
        $this->purchaseOptions[$disposition][$interval] = $prices;
      }
      else {
        $this->purchaseOptions[$disposition][$interval] = array_merge($this->purchaseOptions[$disposition][$interval], $prices);
      }
    }
  }

  /**
   * Generate the Id for the product from the scheme property.
   *
   * @param string $scheme
   *   Scheme for the product.
   *
   * @return string|null
   *   Identifier for the product.
   */
  public function getIdFromScheme(string $scheme = ''): string {
    if (empty($scheme)) {
      $scheme = $this->scheme;
    }

    if (!empty($scheme)) {
      $parts = explode(':', $scheme);
      $id = end($parts);
      if (!empty($id)) {
        return $id;
      }
    }

    return NULL;
  }

  /**
   * Look up the price object by the disposition, interval, and currency.
   *
   * @param string $interval
   *   The interval for the price object you want to return.
   * @param string $currency
   *   The currency for the price object you want to return.
   * @param string $disposition
   *   Optionally include disposition for the price object you want to return (i.e. 'perpetual' or 'rental').
   *
   * @return \HighWire\Clients\Catalog\Price|null
   *   The requested price object.
   */
  public function lookupPrice(string $interval, string $currency, string $disposition = '') {
    $prices = [];
    if (!empty($interval)) {
      if (empty($disposition)) {
        $disposition = ($interval == -1) ? 'perpetual' : 'rental';
      }
      $prices = $this->getPrices($disposition);
    }

    if (!empty($prices[$currency])) {
      return $prices[$currency];
    }
    elseif (!empty($prices[$interval][$currency])) {
      return $prices[$interval][$currency];
    }

    return NULL;
  }

  /**
   * Parse the content id from the product sku.
   *
   * @return string|null
   *   The content id.
   */
  public function getContentIdFromSku() {
    $sku = $this->sku;
    if (!empty($sku)) {
      $parts = explode(":", $sku);

      if (!empty($parts[2])) {
        return $parts[2];
      }
    }

    return NULL;
  }

  /**
   * Parse the content id type from the product sku.
   *
   * @return string|null
   *   The type for the content id (e.g. doi, eisbn).
   */
  public function getContentIdTypeFromSku() {
    $sku = $this->sku;
    if (!empty($sku)) {
      $parts = explode(":", $sku);

      if (!empty($parts[1])) {
        return $parts[1];
      }
    }

    return NULL;
  }

}
