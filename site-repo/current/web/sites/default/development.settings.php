<?php
/**
 * @file
 * Freebird development environment configuration file.
 *
 * This file will only be included on development environments.
 */

$config['system.logging']['error_level'] = 'all';
$config['google_analytics.settings']['account'] = 'UA-XXXXXXXX-YY';
$config['system.performance']['cache']['page']['max_age'] = 0;
$config['system.performance']['css']['preprocess'] = 0;
$config['system.performance']['js']['preprocess'] = 0;

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

$settings['extension_discovery_scan_tests'] = 0;