<?php
defined('ABSPATH') || exit;

if (isset($_POST['madquick_ppg_settings_nonce']) && wp_verify_nonce($_POST['madquick_ppg_settings_nonce'], 'save_madquick_ppg_settings')) {
    $default_settings = get_option('madquick_ppg_settings', []);

    $settings = array_merge($default_settings, [
        'enable_checker'       => !empty($_POST['enable_checker']),
        'enable_cookie_banner' => !empty($_POST['enable_cookie_banner']),
        'enable_update_banner' => !empty($_POST['enable_update_banner']),
    ]);

    update_option('madquick_ppg_settings', $settings);

    echo '<div class="updated"><p>' . esc_html__('Settings saved.', 'madquick-ppg') . '</p></div>';
}

$options = get_option('madquick_ppg_settings', []);
?>
<div class="wrap">
    <h1><?php esc_html_e('Madquick PPG Settings', 'madquick-ppg'); ?></h1>
    <hr>
    <form method="post">
        <?php wp_nonce_field('save_madquick_ppg_settings', 'madquick_ppg_settings_nonce'); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Strong Password Checker', 'madquick-ppg'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_checker" value="1" <?php checked(!empty($options['enable_checker'])); ?>>
                        <?php esc_html_e('Enable', 'madquick-ppg'); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e('Enable Cookie Banner', 'madquick-ppg'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_cookie_banner" value="1" <?php checked(!empty($options['enable_cookie_banner'])); ?>>
                        <?php esc_html_e('Enable', 'madquick-ppg'); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e('Enable Update Browser Banner', 'madquick-ppg'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_update_banner" value="1" <?php checked(!empty($options['enable_update_banner'])); ?>>
                        <?php esc_html_e('Enable', 'madquick-ppg'); ?>
                    </label>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Settings', 'madquick-ppg')); ?>
    </form>
</div>
