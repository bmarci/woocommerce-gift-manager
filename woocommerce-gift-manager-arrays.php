<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once ('woocommerce-gift-manager-gift.php');

function contains($order, $product_id) {
    $items = $order->get_items();
    $items_product_id = array_map('wcgm_map_element_to_product_id', $items);
    return in_array($product_id, $items_product_id);
}

function wcgm_map_element_to_product_id($gift) {
    return $gift->get_product_id();
}

function wcgm_map_terms_to_category($term_item) {
    return $term_item->term_id;
}

function wcgm_filter_valid_gift($gift) {
    return $gift->is_valid();
}

function wcgm_filter_visible_gift($gift) {
    return $gift->is_visible();
}
