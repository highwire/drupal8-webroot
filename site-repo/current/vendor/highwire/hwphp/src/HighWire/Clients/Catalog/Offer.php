<?php

namespace HighWire\Clients\Catalog;

/**
 * Class Offer
 *
 * @package HighWire\Clients\Catalog
 */
class Offer {

  protected $items;

  /**
   * Offer constructor.
   *
   * @param array $offers
   *   Return response from Catalog service.
   */
  public function __construct(array $offers) {
    foreach ($offers as $offer) {
      if (!empty($offer)) {
        $pricing_item = new PricingItem($offer);
        $pricing_item_key = $pricing_item->getId();
        $this->addPricingItem($pricing_item, $pricing_item_key);
      }
    }
  }

  /**
   * @return array
   *   Get an array of PricingItem objects.
   */
  public function getPricingItems(): array {
    if (!empty($this->items)) {
      return $this->items;
    }

    return [];

  }

  /**
   * @param PricingItem $item
   *   PricingItem to add to items array.
   *
   * @param string $key
   *   A PricingItem id to be used as a key for the PricingItem.
   */
  public function addPricingItem(PricingItem $item, $key = NULL) {
    if (!empty($key)) {
      $this->items[$key] = $item;
    }
    else {
      $this->items[] = $item;
    }
  }

  /**
   * @param string $apath
   *   Apath for the PricingItem you want returned.
   *
   * @return PricingItem|null
   *   PricingItem object.
   */
  public function getPricingItemByApath(string $apath): PricingItem {
    $pricing_items = $this->getPricingItems();
    if (!empty($pricing_items[$apath])) {
      return $pricing_items[$apath];
    }

    return NULL;
  }

  /**
   * @return array
   *   An array of apaths of product offered.
   */
  public function getAllProductApaths(): array {
    $apaths = [];
    $pricing_items = $this->getPricingItems();
    foreach ($pricing_items as $pricing_item) {
      $products = $pricing_item->getProducts();
      foreach ($products as $product) {
        $apaths[] = $product->getId();
      }
    }
    return $apaths;
  }

  /**
   * We need a function to get all the apaths in the offer.
   * From here, we can make a request to load all the nodes for easier processing.
   *
   * @return array
   *   An array of all the apaths in the offer.
   */
  public function getAllApaths(): array {
    $apaths = [];
    $pricing_items = $this->getPricingItems();

    foreach ($pricing_items as $pricing_item) {
      $apaths[] = $pricing_item->getId();

      $products = $pricing_item->getProducts();
      foreach ($products as $product) {
        $product_id = $product->getId();
        if (!in_array($product_id, $apaths)) {
          $apaths[] = $product_id;
        }
      }
    }

    return $apaths;
  }

  /**
   * Get a child Product object by apath.
   *
   * @param string $apath
   *   Apath for product to retrieve.
   *
   * @return Product|null
   *   Product object requested.
   */
  public function getProductByApath(string $apath) {
    $pricing_items = $this->getPricingItems();

    foreach ($pricing_items as $pricing_item) {
      $product = $pricing_item->getProductByApath($apath);
      if (!empty($product)) {
        return $product;
      }
    }

    return NULL;
  }

}
