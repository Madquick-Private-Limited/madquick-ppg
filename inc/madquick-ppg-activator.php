<?php
defined('ABSPATH') || exit;

/** save defualt setting of the plugin on activation */

class Madquick_PPG_Activator {
    /**
     * Run on plugin activation
     */
    public static function activate() {
        $default_settings = [
            'enable_checker' => true,
            // Add future defaults here
        ];

        $existing = get_option('madquick_ppg_settings');
        if (!is_array($existing)) {
            add_option('madquick_ppg_settings', $default_settings);
        }
    }

    /**
     * Run on plugin deactivation
     */
    public static function deactivate() {
        // delete_option('madquick_ppg_settings');
    }
}
