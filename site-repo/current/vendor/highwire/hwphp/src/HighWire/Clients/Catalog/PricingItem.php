<?php

namespace HighWire\Clients\Catalog;

/**
 * Class PricingItem
 *
 * @package HighWire\Clients\Catalog
 */
class PricingItem {

  protected $id;
  protected $products;

  /**
   * PricingItem constructor.
   *
   * @param array $data
   *   Array representation of an offer from the Catalog service..
   *
   * @param string $currency
   *   The user's currency.
   */
  public function __construct(array $data, string $currency = '') {
    if (!empty($data['item'])) {
      $this->setId($data['item']);
    }

    if (!empty($data['productOffers'])) {
      foreach ($data['productOffers'] as $product_offer) {
        $product = new Product($product_offer);
        $product_id = $product->getIdFromScheme();
        $this->addProduct($product, $product_id);
      }
    }
  }

  /**
   * @return array
   *   Get an array of product objects for the pricing item.
   */
  public function getProducts(): array {
    if (!empty($this->products)) {
      return $this->products;
    }
    return [];
  }

  /**
   * @param \HighWire\Clients\Catalog\Product $product
   *   Add the Product object to the products array.
   *
   * @param string $key
   *   Id of the object to key product in products array.
   */
  public function addProduct(Product $product, string $key = '') {
    if (empty($key)) {
      $this->products[] = $product;
    }
    else {
      // If there is already a product under this ID, just add the new purchase options.
      $existing = !empty($this->products[$key]) ? $this->products[$key] : FALSE;
      if ($existing) {
        $purchase_options = $product->getPurchaseOptions();
        foreach ($purchase_options as $disposition => $purchase_option) {
          foreach ($purchase_option as $interval => $prices) {
            $existing->addPurchaseOption($prices, $disposition, $interval);
          }
        }
      }
      else {
        $this->products[$key] = $product;
      }
    }
  }

  /**
   * Get the id for the pricing item.
   *
   * @return string|null
   *   The id for the pricing item.
   */
  public function getId(): string {
    if (!empty($this->id)) {
      return $this->id;
    }

    return NULL;
  }

  /**
   * Set the id for the pricing item.
   *
   * @param string $id
   *   The id to set for the pricing item.
   */
  public function setId(string $id) {
    $this->id = $id;
  }

  /**
   * @param string $apath
   *   Apath of product in array to return.
   *
   * @return \HighWire\Clients\Catalog\Product|null
   *   The product object requested.
   */
  public function getProductByApath(string $apath) {
    $products = $this->getProducts();
    if (!empty($products[$apath])) {
      return $products[$apath];
    }

    return NULL;
  }

  /**
   * Get all the products of Type/Unit of items in the PricingItem.
   *
   * @param string $type
   *   Values: ebook, chapter, (article?, issue?)
   *
   * @return array
   *   Array of product objects, keyed by id.
   */
  public function getProductByType(string $type): array {
    $products_by_type = [];

    $products = $this->getProducts();
    foreach ($products as $product) {
      $unit = $product->getUnit();
      if ($unit == $type) {
        $products_by_type[$product->getId()] = $product;
      }
    }

    return $products_by_type;
  }

}
