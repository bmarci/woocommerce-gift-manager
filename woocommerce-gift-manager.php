<?php
/*
Plugin Name: Woocommerce Gift Manager
Plugin URI: https://github.com/bmarci/woocommerce-gift-manager
Description: Adds the ability to add gifts for certain orders as part of a campaign.
Version: 0.0.1
Author: <a href="https://blummarci.com">Marton Blum</a>
Author URI: http://blummarci.com
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: woocommerce-gift-manager
Domain Path: /languages
*/

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL_PRODUCTS', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');

require_once ('woocommerce-gift-manager-admin.php');
require_once ('woocommerce-gift-manager-handler.php');
require_once ('woocommerce-gift-manager-logger.php');
require_once ('woocommerce-gift-manager-renderer.php');

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('plugins_loaded', 'wcgm_init', 0);

function wcgm_init() {
    if ( wcgm_is_active() ) {
        Woocommerce_Gift_Manager_Logger::log('Works as charm!');
    }
}

function wcgm_is_active () {
    return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}
