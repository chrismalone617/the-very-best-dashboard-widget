<?php
if (!defined('ABSPATH')) {
    exit;
}

class Settings_Page {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'best_dashboard_settings';
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    public function add_settings_page() {
        add_options_page(
            __('Dashboard Widget Settings', 'the-best-dashboard'),
            __('Dashboard Widget', 'the-best-dashboard'),
            'manage_options',
            'dashboard-widget-settings',
            [$this, 'render_settings_page']
        );
    }

    public function enqueue_styles() {
        wp_enqueue_style('best-dashboard-styles', plugin_dir_url(__FILE__) . '../assets/styles.css');
    }

    public function render_settings_page() {
        global $wpdb;
    
        // Define the table name
        $table_name = $this->table_name;
    
        if ($_POST && check_admin_referer('save_dashboard_widget_settings')) {
            // Sanitize the form data
            $widget_title = sanitize_text_field($_POST['widget_title']);
            $widget_text = wp_kses_post($_POST['widget_text']);
            $button_text = sanitize_text_field($_POST['button_text']);
            $button_url = esc_url_raw($_POST['button_url']);
    
            // Check if the row exists
            $existing_settings = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1");
    
            // If row exists, update; otherwise, insert
            if ($existing_settings) {
                $wpdb->update(
                    $table_name,
                    [
                        'widget_title' => $widget_title,
                        'widget_text' => $widget_text,
                        'button_text' => $button_text,
                        'button_url' => $button_url
                    ],
                    ['id' => 1]
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    [
                        'id' => 1,
                        'widget_title' => $widget_title,
                        'widget_text' => $widget_text,
                        'button_text' => $button_text,
                        'button_url' => $button_url
                    ]
                );
            }
        }
    
        // Fetch settings from the database
        $settings = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1");
    
        // Default values to avoid warnings
        $widget_title = isset($settings->widget_title) ? $settings->widget_title : '';
        $widget_text = isset($settings->widget_text) ? $settings->widget_text : '';
        $button_text = isset($settings->button_text) ? $settings->button_text : '';
        $button_url = isset($settings->button_url) ? $settings->button_url : '';
    
        ?>
        <div class="wrap" style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">
            <h1 style="font-size: 24px; font-weight: bold;"><?php _e('Dashboard Widget Settings', 'the-best-dashboard'); ?></h1>
            <form method="POST">
                <?php wp_nonce_field('save_dashboard_widget_settings'); ?>
                <table class="form-table" style="width: 100%; border-spacing: 20px;">
                    <tr>
                        <th scope="row" style="text-align: left; width: 150px; font-weight: bold;"><?php _e('Widget Title', 'the-best-dashboard'); ?></th>
                        <td><input type="text" name="widget_title" value="<?php echo esc_attr($widget_title); ?>" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align: left; font-weight: bold;"><?php _e('Widget Text', 'the-best-dashboard'); ?></th>
                        <td><?php wp_editor($widget_text, 'widget_text', ['textarea_rows' => 5, 'editor_class' => 'regular-text']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align: left; font-weight: bold;"><?php _e('Button Text', 'the-best-dashboard'); ?></th>
                        <td><input type="text" name="button_text" value="<?php echo esc_attr($button_text); ?>" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align: left; font-weight: bold;"><?php _e('Button URL', 'the-best-dashboard'); ?></th>
                        <td><input type="url" name="button_url" value="<?php echo esc_url($button_url); ?>" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;"></td>
                    </tr>
                </table>
                <input type="submit" class="button button-primary" value="<?php _e('Save Settings', 'the-best-dashboard'); ?>" style="background-color: #4CAF50; border-color: #4CAF50; color: white; padding: 10px 20px; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; margin-top: 20px;">
            </form>
        </div>
        <?php
    }
}