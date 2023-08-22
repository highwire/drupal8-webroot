<?php

namespace HighWire\Clients;

use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\Yaml\Yaml;
use HighWire\Cache\Cache;

/**
 * A factory class for getting and creating client objects.
 */
class ClientFactory {
  const PRODUCTION_ENV = 'production';
  const DEVELOPMENT_ENV = 'development';

  /**
   * Static cache for client config.
   *
   * @var array
   */
  protected static $clientConfigCache;

  /**
   * Get a client object.
   *
   * Clients are constructed using the client.config.yml.
   *
   * @param string $client_name
   *   The name of the service you are trying to instantiate.
   * @param array $config
   *   The factory configuration options are as follows:
   *   $config = [
   *     'guzzle-config' => [any additonal guzzle configuration that
   *                      will be passed to the creation of the guzzle client],
   *     'client-config' => [any addional service specific configuration]
   *   ].
   * @param string $environment
   *   The environment this client should be built for.
   *   Thinks like the base_uri change based on this value.
   *   Use the constants defined in the class for standrard required envs.
   *
   * @return object
   *   A fully formed client object for the requested client
   */
  public static function get($client_name, array $config = [], $environment = self::PRODUCTION_ENV) {
    $clientConfig = self::getClientConfig();

    // Do this to avoid notices.
    if (empty($config['client-config'])) {
      $config['client-config'] = [];
    }

    if (!empty($config['client-config']['env'])) {
      $environment = $config['client-config']['env'];
    }

    if (empty($clientConfig[$client_name])) {
      self::throwError("Could not locate service for $client_name");
    }

    $client_config = $clientConfig[$client_name];

    // Validate that the service configuration has all required fields.
    $required_service_config_fields = ['class', 'apiVersion'];
    foreach ($required_service_config_fields as $field) {
      if (empty($client_config[$field])) {
        self::throwError("Missing required service configuration field $field");
      }
    }

    $guzzle_config = [];
    $guzzle_config_overrides = !empty($config['guzzle-config']) ? $config['guzzle-config'] : [];

    // Merge in any additional guzzle configuration.
    $guzzle_config = array_merge($guzzle_config, $guzzle_config_overrides);

    // If base_uri is not passed in use the url
    // for the provided $eviornment from the service yaml file.
    if (!isset($guzzle_config['base_uri'])) {
      if ($environment == 'custom' && !empty($config['client-config']['custom_url'])) {
        if (empty($config['client-config']['custom_url'])) {
          self::throwError("Custom URL was selected but no URL configured");
        }
        $guzzle_config['base_uri'] = $config['client-config']['custom_url'];
      }
      else {
        if (empty($client_config['environmentBaseUrls'][$environment])) {
          self::throwError("Could not find base uri for environment $environment");
        }
        $guzzle_config['base_uri'] = $client_config['environmentBaseUrls'][$environment];
      }
    }

    // Setup timeout
    if (empty($guzzle_config['timeout']) && !empty($client_config['timeout'])) {
      $guzzle_config['timeout'] = $client_config['timeout'];
    }

    // If timeout is still empty default to 10 seconds.
    if (empty($guzzle_config['timeout'])) {
      $guzzle_config['timeout'] = 10;
    }

    // Security consideration: we must not use the certificate authority
    // file shipped with Guzzle because it can easily get outdated if a
    // certificate authority is hacked. Instead, we rely on the certificate
    // authority file provided by the operating system which is more likely
    // going to be updated in a timely fashion. This overrides the default
    // path to the pem file bundled with Guzzle.
    $guzzle_config['verify'] = TRUE;

    if (!empty($client_config['factory'])) {
      return $client_config['factory']::get($guzzle_config);
    }

    $client = new GuzzleClient($guzzle_config);
    return new $client_config['class']($client, $config['client-config']);
  }

  /**
   * Load the service config from the yaml file.
   *
   * @param string $client_id
   *   The key from the client.config.xml.
   *
   * @return array
   *   Parsed yaml file into an array
   */
  public static function getClientConfig($client_id = NULL): array {
    $clients = [];

    if (!empty(self::$clientConfigCache)) {
      $clients = self::$clientConfigCache;
    }
    else {
      $clients = Yaml::parse(file_get_contents('client.config.yml', TRUE));
      self::$clientConfigCache = $clients;
    }

    if (!empty($client_id)) {
      if (!array_key_exists($client_id, $clients) || !is_array($clients[$client_id])) {
        throw new \Exception("Invalid client configuration");
      }
      return $clients[$client_id];
    }

    return $clients;
  }

  /**
   * Helper function for throwing an error.
   *
   * @param string $error_message
   *   The error message to throw.
   *
   * @throws \Exception
   */
  protected static function throwError($error_message) {
    throw new \Exception("HighWire ServiceFactory Error: $error_message");
  }

}
