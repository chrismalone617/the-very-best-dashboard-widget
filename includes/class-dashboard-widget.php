<?php
if (!defined('ABSPATH')) {
    exit;
}

class Dashboard_Widget {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'best_dashboard_settings';
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'best_dashboard_widget',
            __('The Best Dashboard Widget', 'the-best-dashboard'),
            [$this, 'render_dashboard_widget']
        );
    }

    public function render_dashboard_widget() {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT * FROM {$this->table_name} WHERE id = 1");

        if ($settings) {
            ?>
            <div style="background-color: #e0f7fa; padding: 15px; border-radius: 10px;">
                <h3 style="color: #4CAF50; text-transform: capitalize; font-size: 1.5em;"><?php echo esc_html($settings->widget_title); ?></h3>
                <p style="font-size: 1.2em;"><?php echo wp_kses_post($settings->widget_text); ?></p>
                <a href="<?php echo esc_url($settings->button_url); ?>" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;"><?php echo esc_html($settings->button_text); ?></a>
            </div>
            <?php
        }
    }
}