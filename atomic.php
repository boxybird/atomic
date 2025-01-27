<?php

/**
 * Plugin Name: Atomic
 * Plugin URI: https://github.com/boxybird/atomic
 * Description: Atomic integrates HTMX fragments into WordPress, enabling dynamic partial page updates without full reloads.
 * Version: 0.0.1
 * Author: Andrew Rhyand
 * Author URI: https://andrewrhyand.com
 * License: MIT
 * Text Domain: atomic-htmx
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!file_exists(__DIR__.'/vendor/autoload.php')) {
    exit('Please run "composer install" in the plugin directory before activating the plugin.');
}

define('ATOMIC_URL', plugin_dir_url(__FILE__));

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/tests/test-hooks.php';

BoxyBird\Atomic\Atomic::init();

