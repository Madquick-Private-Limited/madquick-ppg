<?php
/**
 * Plugin Name: Privacy Policy Generator - Madquick
 * Author URI: https://github.com/Madquick-Private-Limited/
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Version: 1.0.3
 * Description: Generate privacy policy, terms, and legal pages required for your website.
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: madquick-ppg
 * Domain Path: /languages
 *
 * @package madquick-ppg
 */

defined('ABSPATH') || exit;

define('MADQUICK_PPG_PATH', plugin_dir_path(__FILE__));
define('MADQUICK_PPG_URL', plugin_dir_url(__FILE__));

// Required files
require_once MADQUICK_PPG_PATH . 'ajax/create-ppg-page.php';
require_once MADQUICK_PPG_PATH . 'inc/class-madquick-ppg-strong-pass-checker.php';

if (!class_exists('Madquick_PPG')) {
    final class Madquick_PPG {
        public function __construct() {
            add_action('plugins_loaded', [$this, 'load_textdomain']);
            add_action('admin_menu', [$this, 'register_admin_menu']);                 // default priority 10
            add_action('admin_menu', [$this, 'hide_create_submenu_item'], 999);       // remove it from UI
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

            add_action('admin_init', [$this, 'register_settings']);
        }

        public function register_settings() {
            register_setting(
                'madquick_ppg_options_group', // Option group
                'madquick_ppg_settings',      // Option name (array)
                [
                    'type'              => 'array',
                    'sanitize_callback' => [$this, 'sanitize_settings'],
                    'default'           => [
                        'enable_checker' => true,
                        // add other defaults here later
                    ]
                ]
            );

            add_settings_section(
                'madquick_ppg_main_section',
                __('General Settings', 'madquick-ppg'),
                '__return_false',
                'madquick-ppg-settings'
            );

            add_settings_field(
                'enable_checker',
                __('Enable Strong Password Checker', 'madquick-ppg'),
                function () {
                    $options = get_option('madquick_ppg_settings', []);
                    $value   = isset($options['enable_checker']) ? (bool) $options['enable_checker'] : true;
                    ?>
                    <input type="checkbox" name="madquick_ppg_settings[enable_checker]" value="1" <?php checked($value, true); ?>>
                    <label><?php esc_html_e('Check to enforce strong password rules on login/registration.', 'madquick-ppg'); ?></label>
                    <?php
                },
                'madquick-ppg-settings',
                'madquick_ppg_main_section'
            );
        }

        public function sanitize_settings($input) {
            $output = [];
            $output['enable_checker'] = !empty($input['enable_checker']);
            // Add more sanitization for future settings here
            return $output;
        }


        public function load_textdomain() {
            load_plugin_textdomain('madquick-ppg', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        /**
         * Enqueue plugin scripts/styles only on our admin pages.
         * Use $hook_suffix (string) to avoid null-to-string deprecations.
         *
         * @param string $hook_suffix
         */
        public function enqueue_admin_assets($hook_suffix) {
            $hook = (string)($hook_suffix ?? '');
            if ($hook === '') {
                return;
            }

            // Accept multiple variants WP may generate for submenu parents.
            // e.g. 'toplevel_page_madquick-ppg-home', 'privacy-policy-generator_page_madquick-ppg-settings', etc.
            $is_plugin_screen =
                preg_match('~_page_madquick-ppg-(home|help|create|settings)$~', $hook) === 1
                || $hook === 'toplevel_page_madquick-ppg-home';

            if (!$is_plugin_screen) {
                return;
            }

            $css_plugin_page = MADQUICK_PPG_PATH . 'assets/css/plugin-page.css';
            $css_home_page   = MADQUICK_PPG_PATH . 'assets/css/home-page.css';
            $js_generate     = MADQUICK_PPG_PATH . 'assets/js/generate-policy.js';

            if (file_exists($css_plugin_page)) {
                wp_enqueue_style(
                    'madquick-ppg-plugin-page',
                    MADQUICK_PPG_URL . 'assets/css/plugin-page.css',
                    [],
                    (string) filemtime($css_plugin_page)
                );
            }

            if (file_exists($css_home_page)) {
                wp_enqueue_style(
                    'madquick-ppg-home-page',
                    MADQUICK_PPG_URL . 'assets/css/home-page.css',
                    [],
                    (string) filemtime($css_home_page)
                );
            }

            if (file_exists($js_generate)) {
                wp_enqueue_script(
                    'madquick-ppg-generate-policy',
                    MADQUICK_PPG_URL . 'assets/js/generate-policy.js',
                    ['jquery'],
                    (string) filemtime($js_generate),
                    true
                );

                wp_localize_script('madquick-ppg-generate-policy', 'madquick_ppg_ajax', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce'    => wp_create_nonce('madquick_nonce'),
                ]);
            }
        }

        public function register_admin_menu() {
            add_menu_page(
                __('Privacy & Policy Generator', 'madquick-ppg'),
                __('Privacy & Policy Generator', 'madquick-ppg'),
                'manage_options',
                'madquick-ppg-home',
                [$this, 'render_main_page'],
                'dashicons-shield-alt',
                20
            );

            add_submenu_page(
                'madquick-ppg-home',
                __('Help', 'madquick-ppg'),
                __('Help', 'madquick-ppg'),
                'manage_options',
                'madquick-ppg-help',
                [$this, 'render_help_page']
            );

            // ✅ Register "Create" under the real parent (string), not null.
            add_submenu_page(
                'madquick-ppg-home',
                __('Create Legal Page', 'madquick-ppg'),
                __('Create', 'madquick-ppg'),
                'manage_options',
                'madquick-ppg-create',
                [$this, 'render_create_page']
            );

            add_submenu_page(
                'madquick-ppg-home',
                __('Settings', 'madquick-ppg'),
                __('Settings', 'madquick-ppg'),
                'manage_options',
                'madquick-ppg-settings',
                [$this, 'render_settings_page']
            );
        }

        public function render_main_page() {
            $action = isset($_GET['current-action']) ? sanitize_text_field(wp_unslash($_GET['current-action'])) : '';
            $nonce  = isset($_GET['madquick_ppg_nonce']) ? sanitize_text_field(wp_unslash($_GET['madquick_ppg_nonce'])) : '';

            if ($action && $nonce && wp_verify_nonce($nonce, 'madquick_create_ppg_nonce')) {
                $this->include_page('madquick-ppg-create.php');
                return;
            }
            $this->include_page('madquick-ppg-main.php');
        }

        public function render_help_page() {
            $this->include_page('madquick-ppg-help.php');
        }

        public function render_create_page() {
            $this->include_page('madquick-ppg-create.php');
        }

        public function render_settings_page() {
            $this->include_page('madquick-ppg-settings.php');
        }

        private function include_page($file) {
            $filepath = MADQUICK_PPG_PATH . 'pages/' . ltrim($file, '/');
            if (is_file($filepath)) {
                include $filepath;
                return;
            }
            echo esc_html__('Page not found.', 'madquick-ppg');
        }

        /**
         * Remove the "Create" item from the visible submenu, but keep it routable.
         */
        public function hide_create_submenu_item() {
            remove_submenu_page('madquick-ppg-home', 'madquick-ppg-create');
        }
    }

    new Madquick_PPG();
}
