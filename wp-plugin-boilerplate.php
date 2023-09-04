<?php

# add plugin information
/*
Plugin Name: Wp Plugin Boilerplate
Plugin URI: https://pluginoo.com
Description: This is a plugin boilerplate
Version: 1.0.0
Author: Pluginoo
Author URI: https://pluginoo.com
License: GPLv2 or later
Text Domain: wp-plugin-boilerplate
*/


// activation and deactivation hooks
define('WPB_VERSION', '1.0.0');
define('WPB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPB', 'wp-plugin-boilerplate');
define('WPBT', 'wp_plugin_boilerplate_');

function wp_plugin_boilerplate_load_textdomain()
{
    load_plugin_textdomain(WPB, false, dirname(WPB_PLUGIN_DIR) . '/languages');
}

add_action('plugins_loaded', 'wp_plugin_boilerplate_load_textdomain');


function wp_plugin_boilerplate_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . WPBT . 'plugins';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `versions` text NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $table_name = $wpdb->prefix . WPBT . 'plugin_details';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE `$table_name` (
        `id` bigint NOT NULL AUTO_INCREMENT,
        `plugin_id` int NOT NULL,
        `details` text NOT NULL,
        `status` int DEFAULT 0,
        `created_at` datetime NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// on deactivation do the following
register_activation_hook(__FILE__, 'wp_plugin_boilerplate_activation');


// on deactivation do the following
function wp_plugin_boilerplate_deactivation()
{
    global $wpdb;
    $tables = [
        'plugins',
        'plugin_details',
    ];

    foreach ($tables as $table) {
        $table_name = $wpdb->prefix . WPBT . $table;
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    // Delete plugin options
    $options = [
        'company_name',
        'company_address',
        'company_email',

    ];

    foreach ($options as $option) {
        delete_option(WPBT . $option);
    }
}

// on deactivation do the following
register_deactivation_hook(__FILE__, 'wp_plugin_boilerplate_deactivation');



include plugin_dir_path(__FILE__) . 'includes/init.php';
