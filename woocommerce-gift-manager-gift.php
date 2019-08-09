<?php

define('WCGM_DISPLAY_ON_SCREEN', 'd'); // Gift is visible on cart or checkout page
define('WCGM_HIDE_FROM_SCREEN', 'h'); // Gift is NOT visible on cart or checkout page

/**
 * How the db string should be formated
 */
define('WCGM_INDEX_PRODUCT_ID', 0);
define('WCGM_INDEX_VISIBILITY', 1);
define('WCGM_INDEX_START_DATE', 2);
define('WCGM_INDEX_END_DATE', 3);
define('WCGM_DEFAULT_DELIMITER', '_');
define('WCGM_DEFAULT_DATE_FORMAT', 'U');

/**
 * Strict default values to avoid accidents.
 */
define('WCGM_DEFAULT_PRODUCT_ID', 0);
define('WCGM_DEFAULT_VISIBILITY', constant('WCGM_HIDE_FROM_SCREEN'));
define('WCGM_DEFAULT_START_DATE', 0);
define('WCGM_DEFAULT_END_DATE', 0);


class Woocommerce_Gift_Manager_Gift {

    private $product_id;
    private $visibility;
    private $start_date;
    private $end_date;

    public function __construct() {
        $this->product_id = WCGM_DEFAULT_PRODUCT_ID;
        $this->visibility = WCGM_DEFAULT_VISIBILITY;
        $this->start_date = WCGM_DEFAULT_START_DATE;
        $this->end_date = WCGM_DEFAULT_END_DATE;
    }

    /**
     * @param $gift_string Default format is: <PRODUCT_ID>_<VISIBILITY>_<START DATE ON SECONDS>_<END DATE IN SECONDS>
     * @return $this
     */
    public function parse($gift_string) {
        Woocommerce_Gift_Manager_Logger::log('wcgm_gift_manager_gift->parse <= '.$gift_string);
        $gift_representation = explode(WCGM_DEFAULT_DELIMITER, $gift_string);
        $this->product_id = $gift_representation[WCGM_INDEX_PRODUCT_ID] ?: WCGM_DEFAULT_PRODUCT_ID;
        $this->visibility = $gift_representation[WCGM_INDEX_VISIBILITY] ?: WCGM_DEFAULT_VISIBILITY;
        $this->start_date = $gift_representation[WCGM_INDEX_START_DATE] ?: WCGM_DEFAULT_START_DATE;
        $this->end_date = $gift_representation[WCGM_INDEX_END_DATE] ?: WCGM_DEFAULT_END_DATE;
        Woocommerce_Gift_Manager_Logger::log('wcgm_gift_manager_gift->parse => '.print_r($this, true));
    }

    public function is_visible() {
        Woocommerce_Gift_Manager_Logger::log('wcgm_gift_manager_gift->is_visible: '.$this->visibility);
        $visible = $this->visibility == WCGM_DISPLAY_ON_SCREEN;
        Woocommerce_Gift_Manager_Logger::log('wcgm_gift_manager_gift->is_visible => '.$visible);
        return $visible;
    }

    public function is_valid() {
        return $this->start_date <= date(WCGM_DEFAULT_DATE_FORMAT)
            && date(WCGM_DEFAULT_DATE_FORMAT) <= $this->end_date;
    }

    public function get_product_id() {
        return $this->product_id;
    }
}