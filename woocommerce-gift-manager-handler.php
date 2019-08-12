<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once ('woocommerce-gift-manager-arrays.php');
require_once ('woocommerce-gift-manager-gift.php');
require_once ('woocommerce-gift-manager-logger.php');

define('WCGM_DEFAULT_OPTIONS_DELIMITER', ';');
define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');

add_action('woocommerce_pre_payment_complete', 'wcgm_extend_order');
add_action('woocommerce_order_status_completed', 'wcgm_extend_order');

/** Attaching predefined presents for the order.
 * @param $order_id The presents should be attached to this order.
 */
function wcgm_extend_order( $order_id ) {
    Woocommerce_Gift_Manager_Logger::log('Payment completed, it\'s time to add some extras..');

    $order = new WC_Order($order_id);
    $presents_all = wcgm_fetch_presents_for_order($order);

    wcgm_add_presents($presents_all, $order);

}

/**
 * @param $order_id
 * @return array
 */
function wcgm_fetch_presents_for_order($order) {
    Woocommerce_Gift_Manager_Logger::log('fetch_presents_for_order: '.$order->get_id);
    $items = $order->get_items();
    return wcgm_get_presents_for_items($items);
}

/**
 * @param $items
 * @param array $presents_all
 * @return array
 */
function wcgm_get_presents_for_items($items)
{
    $presents_all = array();
    foreach ($items as $item) {
        $product_id = $item->get_product_id();
        Woocommerce_Gift_Manager_Logger::log('get_presents_for_items - Finding presents product id: ' . $product_id);
        $presents_for_product = wcgm_get_presents_for_product($product_id);
        $presents_all = array_merge($presents_all, $presents_for_product); // Attach present for product
        $product_categories = wcgm_get_categories_for_product($product_id);
        foreach ($product_categories as $product_category_id) { // Attach present for product category
            Woocommerce_Gift_Manager_Logger::log('get_presents_for_items - Finding presents category id: ' . $product_category_id);
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
function wcgm_add_presents($products, WC_Order $order) {
    $product_ids = array_map('wcgm_map_element_to_product_id', $products);
    foreach ($product_ids as $product_id) {
        if( $product_id != '' && !contains($order, $product_id)) {
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
    Woocommerce_Gift_Manager_Logger::log('wcgm_get_presents_for_all');
    return wcgm_get_valid_gifts_from_option(WCGM_ALL);
}

/** This will return the product ids of the products that should be attached to orders which has at least one product
 * in the given category.
 * @param $category_id At least one product should be in this category.
 * @return mixed The product ids to be attached.
 */
function wcgm_get_presents_for_category($category_id) {
    Woocommerce_Gift_Manager_Logger::log('wcgm_get_presents_for_category');
    return wcgm_get_valid_gifts_from_option(WCGM_CATEGORY.'_'.$category_id);
}

/** This will return the product ids to be attached to the orders containing the param product.
 * @param $product_id At least one product should be this.
 * @return mixed The product ids to be attached.
 */
function wcgm_get_presents_for_product($product_id) {
    Woocommerce_Gift_Manager_Logger::log('wcgm_get_presents_for_product');
    return wcgm_get_valid_gifts_from_option(WCGM_PRODUCT.'_'.$product_id);
}

function wcgm_get_valid_gifts_from_option($option_key) {
    Woocommerce_Gift_Manager_Logger::log('wcgm_get_valid_gifts_from_option');
    $gifts = wcgm_get_gifts_from_option($option_key);
    return array_filter($gifts, 'wcgm_filter_valid_gift');
}

function wcgm_get_gifts_from_option($option_key) {
    Woocommerce_Gift_Manager_Logger::log('wcgm_get_gifts_from_option');
    $option_key = sanitize_key($option_key);
    $options_string = get_option($option_key, array());
    return wcgm_parse_to_gifts($options_string);
}

function wcgm_get_categories_for_product($product_id) { // TODO: refactor me
    $product_terms = get_the_terms($product_id, 'product_cat');
    return array_map("wcgm_map_terms_to_category", $product_terms);
}

function wcgm_parse_to_gifts($gift) {
    Woocommerce_Gift_Manager_Logger::log('wcgm_parse_to_gifts');
    $gifts = array();
    $gift_strings = explode(WCGM_DEFAULT_OPTIONS_DELIMITER, $gift);
    foreach ($gift_strings as $gift_string) {
        $gift = wcgm_parse_to_gift($gift_string);
        array_push($gifts, $gift);
    }
    return $gifts;
}

function wcgm_parse_to_gift($gift_string){
    Woocommerce_Gift_Manager_Logger::log('wcgm_parse_to_gift <= '.$gift_string);
    $gift = new Woocommerce_Gift_Manager_Gift();
    $gift->parse($gift_string);
    return $gift;
}