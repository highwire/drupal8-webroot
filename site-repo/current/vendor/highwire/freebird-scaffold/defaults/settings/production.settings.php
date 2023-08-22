<?php
/**
 * @file
 * Freebird production environment configuration file.
 *
 * This file will only be included on production environments.
 */

$config['system.logging']['error_level'] = 'hide';
$config['system.performance']['cache']['page']['max_age'] = 900;
$config['system.performance']['css']['preprocess'] = 1;
$config['system.performance']['js']['preprocess'] = 1;