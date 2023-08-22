# drupal-psr-16
Convert Drupal 8 Cache objects to PSR-16 compliant cache objects

# Example

```bash
composer require highwire\drupal-psr-16
```

```php
<?php

$drupalcache = \Drupal::cache('mybin');

$psr16cache = new \HighWire\DrupalPSR16\Cache($drupalcache);

// Now do something with the PSR-16 compiant cache
```

