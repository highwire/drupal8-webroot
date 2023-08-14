<?php

namespace HighWire\Clients\Catalog;

class Price {

  protected $currency;
  protected $amount;
  protected $interval;

  /**
   * Price constructor.
   *
   * @param array $data
   *   Response from the catalog service with properties:
   *     string $interval
   *     string $currency
   *     numeric $amount.
   */
  public function __construct(array $data) {
    if (!isset($data['interval'])) {
      // Intervals that are suppressed are '-1' (i.e. 'perpetual access')
      $this->setInterval(Catalog::PERPETUAL_INTERVAL);
    }
    elseif (!empty($data['interval'])) {
      $this->setInterval($data['interval']);
    }

    $this->setAmount($data['amount']);
    $this->setCurrency($data['currency']);
  }

  /**
   * Get the currency code for the price.
   *
   * @return string
   *   Three character currency code.
   */
  public function getCurrency(): string {
    if (!empty($this->currency)) {
      return $this->currency;
    }

    return '';
  }

  /**
   * Set the currency for the price.
   *
   * @param string $currency
   *   Three character currency code.
   */
  public function setCurrency(string $currency) {
    $this->currency = $currency;
  }

  /**
   * Get the amount value for the price.
   *
   * @return float
   *   The amount value for the price.
   */
  public function getAmount(): float {
    if (!empty($this->amount)) {
      return floatval($this->amount);
    }

    return 0;
  }

  /**
   * Set the price amount.
   *
   * @param string $amount
   *   The price amount.
   */
  public function setAmount(string $amount) {
    $this->amount = $amount;
  }

  /**
   * Get the interval value or formatted interval for the price.
   *
   * @return \DateInterval|mixed
   *   The code for access duration of purchase.
   */
  public function getInterval($interval_formatter = NULL) {
    if (($interval_formatter instanceof \Closure) && is_callable($interval_formatter)) {
      return $interval_formatter($this->interval);
    }
    else {
      return $this->interval;
    }
  }

  /**
   * Set the interval value for the Price.
   *
   * @param string $interval
   *   The code for access duration of purchase.
   */
  public function setInterval(string $interval) {
    $this->interval = new \DateInterval($interval);
  }

  /**
   * Get the disposition for the price.
   *
   * @return string
   *   The disposition of the price.
   */
  public function getDisposition(): string {
    if ($this->interval->format(Catalog::INTERVAL_FORMAT) == Catalog::PERPETUAL_INTERVAL) {
      return 'perpetual';
    }
    elseif (!empty($this->interval)) {
      return 'rental';
    }

    return '';
  }

  /**
   * Get the formatted display price for the product.
   *
   * @return string
   *   Formatted display price (e.g. $5.99).
   */
  public function getDisplayPrice(): string {
    $amount = $this->getAmount();
    $currency = $this->getCurrency();

    if (!empty($amount) && !empty($currency)) {
      $currency_formatter = /** @scrutinizer ignore-call */ new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
      $display_price = $currency_formatter->formatCurrency($amount, $currency);
      if (!empty($display_price)) {
        return $display_price;
      }
    }

    return '';
  }

}
