<?php

namespace Knapsack\Compass\Support;

class DB
{
    public static function query($query)
    {
        global $wpdb;
        return $wpdb->get_results($query);
    }

    public static function prepare($query, ...$args)
    {
        global $wpdb;
        return $wpdb->prepare($query, ...$args);
    }
}
