<?php
/**
 * Plugin Name: Fraktjakt Shipping Method for Dokan
 * Description: Fraktjakt shipping method plugin for Dokan WooCommerce Multivendor. Integrates several shipping services through Fraktjakt.
 * Author: Fraktjakt
 * Author URI: https://fraktjakt.com/
 * Version: 1.0.1
 * License: GPL v2
 * Text Domain: fraktjakt-shipping-for-dokan
 *
 * @package fraktjakt-shipping-for-dokan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Set constants
 */
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_FILE', __FILE__ );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_DIR_FILE', dirname(__FILE__) );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_BASE', plugin_basename( FRAKTJAKT_SHIPPING_FOR_DOKAN_FILE ) );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_DIR', plugin_dir_path( FRAKTJAKT_SHIPPING_FOR_DOKAN_FILE ) );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_URL', plugins_url( '/', FRAKTJAKT_SHIPPING_FOR_DOKAN_FILE ) );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_PLUGIN_PATH', untrailingslashit( FRAKTJAKT_SHIPPING_FOR_DOKAN_DIR ) );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_VER', '1.0.1' );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_API_VER', '4.3.1' );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_PLUGIN_VER', '2.5.0' );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_SETTINGS', 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general' );
define( 'FRAKTJAKT_SHIPPING_FOR_DOKAN_APPEARANCE_SETTINGS', 'FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance' );

require_once 'inc/functions.php';
require_once 'plugin-loader.php';
