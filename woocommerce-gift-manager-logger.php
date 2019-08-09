<?php


class Woocommerce_Gift_Manager_Logger
{
    public static function log($message) {
        $date = new DateTime();
        $date = $date->format("r");
        error_log($date.': '.$message."\n", 3, "/Users/martonblum/off/log/wc_gift_manager.log");
    }

}