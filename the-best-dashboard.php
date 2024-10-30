<?php
/**
 * Plugin Name:       Best Dashboard Widget
 * Description:       Best Dashboard Widget's plugin description
 * Requires at least: 6.3.0
 * Requires PHP:      8.0
 * Version:           5.0.0
 * Author:            Chris Malone
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       best_dashboard_widget
 * Website:           https://solidwp.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$plugin_prefix = 'BESTDASHBOARDWIDGET';

// Extract the version number
$plugin_data = get_file_data(__FILE__, ['Version' => 'Version']);

// Plugin Constants
define($plugin_prefix . '_DIR', plugin_basename(__DIR__));
define($plugin_prefix . '_BASE', plugin_basename(__FILE__));
define($plugin_prefix . '_PATH', plugin_dir_path(__FILE__));
define($plugin_prefix . '_VER', $plugin_data['Version']);
define($plugin_prefix . '_CACHE_KEY', 'best_dashboard_widget-cache-key-for-plugin');
define($plugin_prefix . '_REMOTE_URL', 'https://plugins.withchris.dev/wp-content/uploads/downloads/24/info.json');

require constant($plugin_prefix . '_PATH') . 'inc/update.php';

new DPUpdateChecker(
	constant($plugin_prefix . '_BASE'),
	constant($plugin_prefix . '_VER'),
	constant($plugin_prefix . '_CACHE_KEY'),
	constant($plugin_prefix . '_REMOTE_URL'),
);


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