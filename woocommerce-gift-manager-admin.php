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
}


function wporg_custom_box_html($post)
{
    ?>
    <label for="wporg_field">Description for this field</label>
    <select name="wporg_field" id="wporg_field" class="postbox">
        <option value="">Select something...</option>
        <option value="something">Something</option>
        <option value="else">Else</option>
    </select>
    <?php
}
