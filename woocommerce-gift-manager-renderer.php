<?php

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL_PRODUCTS', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');

require_once ('woocommerce-gift-manager-logger.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function wcgm_get_product_image($id) {
    $product = new WC_product($id);
    return $product->get_image();
}

add_action('woocommerce_before_checkout_form', 'wcgm_displays_cart_products_feature_image');
function wcgm_displays_cart_products_feature_image() {
    echo "<div class='cards'>";
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $item = $cart_item['data'];
        //print_r($item);
        if(!empty($item)){
            echo "<div><p>".wcgm_get_product_image($item->id)."</p>";
            echo "<p>".$item->get_price()." Ft</p></div>";
            if(!empty($item->get_attribute('present'))){
                echo "<div class='card_plus'><i class='fa fa-plus' aria-hidden='true' style='color: #73060f; font-size: 70px'></i></div>";
                echo "<div><p>".wcgm_get_product_image($item->get_attribute('present'))."</p>";
                echo "<del>".wc_get_product( $item->get_attribute('present') )->get_price()."Ft</del><span style='color:red'> Ajándék</span></div>";
            }
        }
    }
    echo "</div>";
}

/*
function pe_fontawesome(){
    wp_enqueue_style('font-awesome', 'external/css/all.css');
    wp_enqueue_script('font-awesome', 'external/css/all.js');
}
add_action('wp_enqueue_scripts','pe_fontawesome');
*?
