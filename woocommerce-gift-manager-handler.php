<?php

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL_PRODUCTS', constant('WCGM_PREFIX').'_all');
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

    $present_everyone = wcgm_get_presents_for_all();

    // $present_for_category = wcgm_get_presents_for_category(); //TODO: write me
    // $present_for_product = wcgm_get_presents_for_product();


    add_presents($present_everyone, $order);

}

/**
 * @param $products The presents to be attached to a certain order.
 * @param WC_Order $order
 */
function add_presents($products, WC_Order $order)
{
    foreach ($products as $product_id) {
        $gift = new WC_Product($product_id);
        Woocommerce_Gift_Manager_Logger::log('Adding product: ' . $gift->get_name());
        $order->add_product($gift, 1, array('subtotal' => 0, 'total' => 0));
    }
}

/** This will return the product ids of the products that should be attached to all orders.
 * @return mixed The product ids of the products that should be attached to all orders.
 */
function wcgm_get_presents_for_all() {
    return explode(";", get_option(constant('WCGM_ALL_PRODUCTS'),''));
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