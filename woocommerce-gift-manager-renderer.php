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
    $cart = WC()->cart;
    $categories = array();
    foreach ( $cart->get_cart() as $cart_item ) {
        $ordered_product = $cart_item['data'];
        if(!empty($ordered_product)){
            $order_product_id = $ordered_product->get_id();
            $product_categories = wcgm_get_categories_for_product($order_product_id);
            $categories = array_merge($categories, $product_categories);

            render_product($ordered_product);
            render_presents_for_product($order_product_id);
        }
    }

    if (!$cart->is_empty()) {
        render_presents_for_categories($categories);
        render_presents_for_all();
    }
    echo "</div>";
}

/**
 * @param $ordered_product
 */
function render_product($ordered_product)
{
    echo "<div><p>" . wcgm_get_product_image($ordered_product->id) . "</p>";
    echo "<p>" . $ordered_product->get_price() . " Ft</p></div>";
}

/**
 * @param $present_item
 */
function render_present_item($present_item)
{
    Woocommerce_Gift_Manager_Logger::log('render_present_item: '. print_r($present_item, true));
    if($present_item->is_visible()) {
        $present_item_id = $present_item->get_product_id();
        echo "<div class='card_plus'><i class='fa fa-plus' aria-hidden='true' style='color: #73060f; font-size: 70px'></i></div>";
        echo "<div><p>" . wcgm_get_product_image($present_item_id) . "</p>";
        echo "<del>" . wc_get_product($present_item_id)->get_price() . "Ft</del><span style='color:red'> Ajándék</span></div>";
    }
}

/**
 * @param $present_items
 */
function render_present_items($present_items)
{
    foreach ($present_items as $present_item) {
        render_present_item($present_item);
    }
}

/**
 * @param $ordered_product_id
 */
function render_presents_for_product($ordered_product_id)
{
    $present_items = wcgm_get_presents_for_product($ordered_product_id);
    render_present_items($present_items);
}

/**
 * @param $product_category_id
 */
function render_presents_for_category($product_category_id)
{
    $present_items = wcgm_get_presents_for_category($product_category_id);
    render_present_items($present_items);
}

/**
 * @param $product_categories Array of product category ids
 */
function render_presents_for_categories($product_categories)
{
    foreach ($product_categories as $product_category) {
        render_presents_for_category($product_category);
    }
}


function render_presents_for_all()
{
    $present_items = wcgm_get_presents_for_all();
    render_present_items($present_items);
}