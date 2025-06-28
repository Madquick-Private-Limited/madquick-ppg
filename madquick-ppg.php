<?php
/**
 * Plugin Name: Privacy Policy Generator - Madquick
 * Author URI: https://github.com/Madquick-Private-Limited/
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Version: 1.0.2
 * Description: Generate privacy policy, terms, and legal pages required for your website.
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: madquick-ppg
 *
 * @package madquick-ppg
 */

defined('ABSPATH') || exit;

define('MADQUICK_PPG_PATH', plugin_dir_path(__FILE__));
define('MADQUICK_PPG_URL', plugin_dir_url(__FILE__));

// Required files
require_once MADQUICK_PPG_PATH . 'ajax/create-ppg-page.php';

if (!class_exists('Madquick_PPG')) {

    class Madquick_PPG {

        public function __construct() {
            add_action('admin_menu', [$this, 'register_admin_menu']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }

        /**
         * Enqueue plugin scripts and styles only on our pages.
         */
        public function enqueue_admin_assets($hook) {
            $screen = get_current_screen();
            if (
                isset($screen->id) &&
                (strpos($screen->id, 'madquick-ppg-home') !== false ||
                 strpos($screen->id, 'madquick-ppg-help') !== false ||
                 strpos($screen->id, 'madquick-ppg-create') !== false)
            ) {
                wp_enqueue_style(
                    'madquick-ppg-plugin-page',
                    MADQUICK_PPG_URL . 'assets/css/plugin-page.css',
                    [],
                    filemtime(MADQUICK_PPG_PATH . 'assets/css/plugin-page.css')
                );
                wp_enqueue_style(
                    'madquick-ppg-home-page',
                    MADQUICK_PPG_URL . 'assets/css/home-page.css',
                    [],
                    filemtime(MADQUICK_PPG_PATH . 'assets/css/home-page.css')
                );
                wp_enqueue_script(
                    'madquick-ppg-generate-policy',
                    MADQUICK_PPG_URL . 'assets/js/generate-policy.js',
                    ['jquery'],
                    filemtime(MADQUICK_PPG_PATH . 'assets/js/generate-policy.js'),
                    true
                );
                wp_localize_script('madquick-ppg-generate-policy', 'madquick_ppg_ajax', [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce'    => wp_create_nonce('madquick_nonce'),
                ]);
            }
        }

        /**
         * Register admin menu and submenus.
         */
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

            // "Create" page, hidden from menu but routable
            add_submenu_page(
                null,
                __('Create Legal Page', 'madquick-ppg'),
                __('Create', 'madquick-ppg'),
                'manage_options',
                'madquick-ppg-create',
                [$this, 'render_create_page']
            );
        }

        /**
         * Main dashboard/page router.
         */
        public function render_main_page() {
            $action = isset($_GET['current-action']) ? sanitize_text_field(wp_unslash($_GET['current-action'])) : '';
            $nonce  = isset($_GET['madquick_ppg_nonce']) ? sanitize_text_field(wp_unslash($_GET['madquick_ppg_nonce'])) : '';

            if ($action && $nonce && wp_verify_nonce($nonce, 'madquick_create_ppg_nonce')) {
                $this->include_page('madquick-ppg-create.php');
            } else {
                $this->include_page('madquick-ppg-main.php');
            }
        }

        /**
         * Help submenu page.
         */
        public function render_help_page() {
            $this->include_page('madquick-ppg-help.php');
        }

        /**
         * Create page, can be routed directly.
         */
        public function render_create_page() {
            $this->include_page('madquick-ppg-create.php');
        }

        /**
         * Utility to include a page from /pages.
         */
        private function include_page($file) {
            $filepath = MADQUICK_PPG_PATH . 'pages/' . $file;
            if (file_exists($filepath)) {
                include $filepath;
            } else {
                echo esc_html__('Page not found.', 'madquick-ppg');
            }
        }
    }

    // Initialize plugin
    new Madquick_PPG();
}
