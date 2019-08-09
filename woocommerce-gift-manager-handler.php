<?php

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');


require_once ('woocommerce-gift-manager-logger.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('woocommerce_pre_payment_complete', 'wcgm_add_presents');

/** Attaching predefined presents for the order.
 * @param $order_id The presents should be attached to this order.
 */
function wcgm_add_presents( $order_id ) {
    Woocommerce_Gift_Manager_Logger::log('Payment completed, it\'s time to add some extras..');

    $order = new WC_Order($order_id);
    $presents_all = fetch_presents_for_order($order);

    add_presents($presents_all, $order);

}

/**
 * @param $order_id
 * @return array
 */
function fetch_presents_for_order($order) {
    Woocommerce_Gift_Manager_Logger::log('fetch_presents_for_order: '.$order->get_id);

    $presents_all = array();

    $items = $order->get_items();

    foreach ($items as $item) {
        $product_id = $item->get_product_id();
        Woocommerce_Gift_Manager_Logger::log('fetch_presents_for_order - Finding presents product id: ' . $product_id);
        $presents_for_product = wcgm_get_presents_for_product($product_id);
        $presents_all = array_merge($presents_all, $presents_for_product); // Attach present for product
        $product_categories = get_the_terms($product_id, 'product_cat');
        foreach ($product_categories as $product_category) { // Attach present for product category
            $product_category_id = $product_category->term_id;
            Woocommerce_Gift_Manager_Logger::log('fetch_presents_for_order - Finding presents category id: ' . $product_category_id);
            $presents_for_category = wcgm_get_presents_for_category($product_category_id);
            $presents_all = array_merge($presents_all, $presents_for_category);
        }

    }

    $presents_everyone = wcgm_get_presents_for_all();

    return array_merge($presents_all, $presents_everyone);

}

/**
 * @param $products The presents to be attached to a certain order.
 * @param WC_Order $order
 */
function add_presents($products, WC_Order $order) {
    foreach ($products as $product_id) {
        if( $product_id != '' ) {
            $gift = new WC_Product($product_id);
            Woocommerce_Gift_Manager_Logger::log('Adding product: ' . $gift->get_name());
            $order->add_product($gift, 1, array('subtotal' => 0, 'total' => 0));
        }
    }
}

/** This will return the product ids of the products that should be attached to all orders.
 * @return mixed The product ids of the products that should be attached to all orders.
 */
function wcgm_get_presents_for_all() {
    return explode(";", get_option(constant('WCGM_ALL'),''));
}

/** This will return the product ids of the products that should be attached to orders which has at least one product
 * in the given category.
 * @param $category_id At least one product should be in this category.
 * @return mixed The product ids to be attached.
 */
function wcgm_get_presents_for_category($category_id) {
    $category = sanitize_key($category_id);
    return explode(";",get_option(constant('WCGM_CATEGORY').'_'.$category,''));
}

/** This will return the product ids to be attached to the orders containing the param product.
 * @param $product_id At least one product should be this.
 * @return mixed The product ids to be attached.
 */
function wcgm_get_presents_for_product($product_id) {
    $product = sanitize_key($product_id);
    return explode(";",get_option(constant('WCGM_PRODUCT').'_'.$product,''));
}