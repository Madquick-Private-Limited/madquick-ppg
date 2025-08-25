<?php

class Madquick_ppg_strong_pass_checker {
    const META_FLAG  = '_mq_weak_pw_notice';
    const GEN_URL    = 'https://strong-password-generator.com';
    const TEXTDOMAIN = 'madquick-ppg';
    private static $settings = [];

    public static function init() {
        self::$settings = get_option('madquick_ppg_settings');

        // server checks (optional advisory after login)
        add_filter('authenticate', [__CLASS__, 'check_on_login'], 30, 3);
        add_action('user_register', [__CLASS__, 'check_on_register'], 10);

        add_action('admin_notices', [__CLASS__, 'maybe_admin_notice']);
        add_action('user_admin_notices', [__CLASS__, 'maybe_admin_notice']);

        // Render SSR info notice *after* the login/register form
        add_action('login_footer', [__CLASS__, 'login_ssr_info_notice']);

        // Enqueue the external JS for live checks
        add_action('login_enqueue_scripts', [__CLASS__, 'enqueue_password_checker']);
        add_action('wp_enqueue_scripts',    [__CLASS__, 'enqueue_password_checker']);
    }

    public static function is_strong_password(string $pw): bool {
        if (strlen($pw) < 12) return false;
        $classes  = (int) preg_match('/[a-z]/', $pw);
        $classes += (int) preg_match('/[A-Z]/', $pw);
        $classes += (int) preg_match('/\d/', $pw);
        $classes += (int) preg_match('/[^a-zA-Z0-9]/', $pw);
        return $classes >= 3;
    }

    public static function check_on_login($user, $username, $password) {
        if ($user instanceof WP_User && is_string($password) && $password !== '') {
            if (!self::is_strong_password($password)) {
                update_user_meta($user->ID, self::META_FLAG, time());
            }
        }
        return $user;
    }

    public static function check_on_register($user_id) {
        $pw = isset($_POST['user_pass']) ? (string) wp_unslash($_POST['user_pass']) : '';
        if ($pw !== '' && !self::is_strong_password($pw)) {
            update_user_meta($user_id, self::META_FLAG, time());
        }
    }

    public static function maybe_admin_notice() {
        if (empty(self::$settings['enable_checker'])) {
            return; // Disabled
        }

        if (!is_user_logged_in()) return;
        $uid  = get_current_user_id();
        $flag = get_user_meta($uid, self::META_FLAG, true);
        if (!$flag) return;

        delete_user_meta($uid, self::META_FLAG);
        $url = esc_url(self::GEN_URL);

        $strong_password_keyword = self::$settings['strong_password_generator_keyword'] ?? "Strong password generator";

        echo '<div class="notice notice-warning is-dismissible"><p>'
        . esc_html__('For better security, please update your password to a strong one.', 'madquick-ppg')
        . ' '
        . sprintf(
            wp_kses(
                sprintf(
                    __('You can generate a strong password at <a href="%s" target="_blank" rel="noopener">%s</a>.', 'madquick-ppg'),
                    esc_url($url),
                    esc_html($strong_password_keyword)
                ),
                ['a' => ['href' => true, 'target' => true, 'rel' => true]]
            ),
            $url
            )
        . '</p></div>';
    }


    public static function login_ssr_info_notice() {
        if (empty(self::$settings['enable_checker'])) {
            return; // Disabled
        }

        // Detect the wp-login action
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        $action = $action ? sanitize_key($action) : 'login';

        // Skip for register page (if it doesn’t have a password field)
        if ($action === 'register') {
            return;
        }

        $title = esc_html__('Make your password stronger', 'madquick-ppg');
        $strong_password_keyword = self::$settings['strong_password_generator_keyword'] ?? "Strong password generator";
        $intro = sprintf(
        /* translators: %s: promotion link */
            __('For best security, try to meet all the checks below. - %s', 'madquick-ppg'),
            '<a href="https://strong-password-generator.com" target="_blank" rel="noopener">' .esc_html__($strong_password_keyword, 'madquick-ppg').'</a>'
        );

        $items = [
            'lower' => esc_html__('Includes at least one lowercase letter (a–z)', 'madquick-ppg'),
            'upper' => esc_html__('Includes at least one uppercase letter (A–Z)', 'madquick-ppg'),
            'num'   => esc_html__('Includes at least one number (0–9)', 'madquick-ppg'),
            'spec'  => esc_html__('Includes at least one special character (e.g., ! @ # $ %)', 'madquick-ppg'),
            'len'   => esc_html__('Has a minimum length of 6 characters', 'madquick-ppg'),
        ];
        ?>
        <div id="mq-pass-notice" class="notice notice-info">
            <p class="mq-notice-title"><strong><?php echo $title; ?></strong></p>
            <p class="mq-notice-desc"><?php echo wp_kses_post($intro); ?></p>
            <ul id="mq-pass-req" class="mq-pass-req">
                <li data-req="lower"><?php echo $items['lower']; ?></li>
                <li data-req="upper"><?php echo $items['upper']; ?></li>
                <li data-req="num"><?php echo $items['num']; ?></li>
                <li data-req="spec"><?php echo $items['spec']; ?></li>
                <li data-req="len"><?php echo $items['len']; ?></li>
            </ul>
        </div>
        <?php
    }

    /**
     * Enqueue external JS that performs live checks (no inline JS)
     */
    public static function enqueue_password_checker() {
        if (empty(self::$settings['enable_checker'])) {
            return; // Disabled
        }

        // Core meter libs (safe to enqueue twice)
        wp_enqueue_script('zxcvbn-async');
        wp_enqueue_script('password-strength-meter');

        $handle   = 'madquick-ppg-strong-pass';
        $rel_path = 'assets/js/strong-pass.js';
        $path     = defined('MADQUICK_PPG_PATH') ? MADQUICK_PPG_PATH . $rel_path : plugin_dir_path(__FILE__) . $rel_path;
        $url      = defined('MADQUICK_PPG_URL')  ? MADQUICK_PPG_URL  . $rel_path : plugin_dir_url(__FILE__)  . $rel_path;

        wp_register_script(
            $handle,
            $url,
            ['password-strength-meter', 'zxcvbn-async'],
            file_exists($path) ? (string) filemtime($path) : null,
            true
        );

        // Pass minimal config / labels if you need them later
        wp_localize_script($handle, 'mqStrongPassCfg', [
            'minLen' => 6,
        ]);

        wp_enqueue_script($handle);

        $css_handle = "madquick-ppg-strong-pass-css";
        wp_register_style(
            $css_handle,
            MADQUICK_PPG_URL . "assets/css/strong-pass.css",
            [],
            null
        );

        wp_enqueue_style($css_handle);
    }
}

Madquick_ppg_strong_pass_checker::init();
