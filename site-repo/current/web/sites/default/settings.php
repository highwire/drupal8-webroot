<?php

// @codingStandardsIgnoreFile

/**
 * @file
 * Freebird Drupal 8 configuration file.
 *
 * You should not edit this file, please use environment specific files!
 * These files are NOT tracked in get and are managed by `highwire/freebird-scaffold`
 * They are loaded in this order:
 * - production.settings.php
 *   For settings only for the production environment.
 * - production.services.yml
 *   For services only for the production environment.
 * - development.settings.php
 *   For settings only for the development environment (development sites, docker).
 * - development.services.yml
 *   For services only for the development environment (development sites, docker).
 * - settings.local.php
 *   For settings only for the local environment
 * - services.local.yml
 *   For services only for the local environment
 */

/**
 * Load .env file from the repo root directory.
 */
$dotenv = Dotenv\Dotenv::createImmutable($app_root . '/..');
$dotenv->safeLoad();

$databases = [];

/**
 * Location of the site configuration files.
 */
$config_directories = [];

$config_directories = [
  CONFIG_SYNC_DIRECTORY => '../config/sync',
];

/**
 * Settings:
 *
 * $settings contains environment-specific configuration, such as the files
 * directory and reverse proxy address, and temporary configuration, such as
 * security overrides.
 *
 * @see \Drupal\Core\Site\Settings::get()
 */

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 */
if (getenv('DRUPAL_HASH_SALT')) {
  $settings['hash_salt'] = getenv('DRUPAL_HASH_SALT');
}

/**
 * Deployment identifier.
 *
 * Drupal's dependency injection container will be automatically invalidated and
 * rebuilt when the Drupal core version changes. When updating contributed or
 * custom code that changes the container, changing this identifier will also
 * allow the container to be invalidated as soon as code is deployed.
 */
# $settings['deployment_identifier'] = \Drupal::VERSION;

/**
 * Access control for update.php script.
 *
 * If you are updating your Drupal installation using the update.php script but
 * are not logged in using either an account with the "Administer software
 * updates" permission or the site maintenance account (the account that was
 * created during installation), you will need to modify the access check
 * statement below. Change the FALSE to a TRUE to disable the access check.
 * After finishing the upgrade, be sure to open this file again and change the
 * TRUE back to a FALSE!
 */
$settings['update_free_access'] = FALSE;

/**
 * Entity update backup.
 */
$settings['entity_update_backup'] = TRUE;

/**
 * The default list of directories that will be ignored by Drupal's file API.
 *
 * By default ignore node_modules and bower_components folders to avoid issues
 * with common frontend tools and recursive scanning of directories looking for
 * extensions.
 *
 * @see file_scan_directory()
 * @see \Drupal\Core\Extension\ExtensionDiscovery::scanDirectory()
 */
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

/**
 * The default number of entities to update in a batch process.
 */
if (getenv('DRUPAL_BATCH_SIZE')) {
  $settings['entity_update_batch_size'] = getenv('DRUPAL_BATCH_SIZE');
}

/**
 * Load HighWire SMART environment specific files.
 */
if(getenv('HIGHWIRE_SITE_ENVIRONMENT')){
  // Environment specific settings files.
  if (file_exists($app_root . '/' . $site_path . '/' . getenv('HIGHWIRE_SITE_ENVIRONMENT') . '.settings.php')) {
    include $app_root . '/' . $site_path . '/' . getenv('HIGHWIRE_SITE_ENVIRONMENT') . '.settings.php';
  }

  // Environment specific services files.
  if (file_exists($app_root . '/' . $site_path . '/' . getenv('HIGHWIRE_SITE_ENVIRONMENT') . '.services.yml')) {
    $settings['container_yamls'][] = $app_root . '/' . $site_path . '/' . getenv('HIGHWIRE_SITE_ENVIRONMENT') . '.services.yml';
  }
}

/**
 * Load local server's override configuration, if available.
 */
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

if (file_exists($app_root . '/' . $site_path . '/services.local.yml')) {
  $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.local.yml';
}
