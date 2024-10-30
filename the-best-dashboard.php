<?php
/**
 * Plugin Name: The Best Dashboard Plugin From AI
 * Description: A custom dashboard widget plugin with a settings page.
 * Version: 3.2
 * Author: Chris
 * Text Domain: the-best-dashboard
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Put this toward the top of your main plugin.php file

//Tells the plugin to load the plugin checker
require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/chrismalone617/the-very-best-dashboard-widget', //USE YOUR GITHUB REPO URL
	__FILE__,
	'the-very-best-dashboard-widget' //THIS IS THE SLUG OF YOUR PLUGIN
);

// Set the branch that contains the stable release. 
$myUpdateChecker->setBranch('main');

class The_Best_Dashboard {

    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'create_custom_table']);
        register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

        // Load dependencies
        require_once plugin_dir_path(__FILE__) . 'includes/class-settings-page.php';
        require_once plugin_dir_path(__FILE__) . 'includes/class-dashboard-widget.php';

        // Initialize settings page and dashboard widget
        new Settings_Page();
        new Dashboard_Widget();
    }

    // Create custom table for plugin settings
    public function create_custom_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'best_dashboard_settings';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            widget_title text NOT NULL,
            widget_text longtext NOT NULL,
            button_text text NOT NULL,
            button_url text NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

new The_Best_Dashboard();