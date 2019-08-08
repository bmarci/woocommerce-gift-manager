<?php

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL_PRODUCTS', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');

require_once ('woocommerce-gift-manager-logger.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
