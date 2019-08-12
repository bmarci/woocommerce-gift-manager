<?php

define('WCGM_PREFIX', 'wcgm');
define('WCGM_ALL_PRODUCTS', constant('WCGM_PREFIX').'_all');
define('WCGM_CATEGORY', constant('WCGM_PREFIX').'_category');
define('WCGM_PRODUCT', constant('WCGM_PREFIX').'_product');

require_once ('woocommerce-gift-manager-logger.php');
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', 'wcgm_admin_menu');
function wcgm_admin_menu(){
    add_menu_page( 'Woocommerce Gift Manager', 'Woocommerce Gift Manager', 'manage_options', 'wcgm-menu', 'wcgm_show_menu' );
}

function wcgm_show_menu() {
    echo '<div class="wrap"><h1>Works as charm</h1><form method="post" action="options.php">';

    settings_fields( 'myoption-group' );

    add_meta_box(
        'wporg_box_id',           // Unique ID
        'Meta box',  // Box title
        'wporg_custom_box_html',  // Content callback, must be of type callable
        'page'                   // Post type
    );

    submit_button();
    echo '</form></div>';
    foreach (WC_API_Products::get_products() as $product) {
        echo '<div>'.$product->get_id().'</div>';
    }


    $args     = array( 'post_type' => 'product', 'category' => 34, 'posts_per_page' => -1 );
    $products = get_posts( $args );

    //Woocommerce_Gift_Manager_Logger::log('retrieved products: '.print_r($products, true));
}


function wporg_custom_box_html($post)
{
    ?>
    <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
        <input type="search" id="woocommerce-product-search-field-<?php echo isset( $index ) ? absint( $index ) : 0; ?>" class="search-field" placeholder="<?php echo esc_attr__( 'Search products&hellip;', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woocommerce' ); ?>"><?php echo esc_html_x( 'Search', 'submit button', 'woocommerce' ); ?></button>
        <input type="hidden" name="post_type" value="product" />
    </form>

    <?php
}
