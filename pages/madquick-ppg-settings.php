<?php
defined('ABSPATH') || exit;

// Handle settings POST
if ( isset( $_POST['madquick_ppg_settings_nonce'] ) ) {
    // Prefer filter_input over direct $_POST usage
    $nonce_raw = filter_input( INPUT_POST, 'madquick_ppg_settings_nonce', FILTER_UNSAFE_RAW );
    $nonce     = is_string( $nonce_raw ) ? sanitize_text_field( $nonce_raw ) : '';

    if ( wp_verify_nonce( $nonce, 'save_madquick_ppg_settings' ) ) {
        $default_settings = (array) get_option( 'madquick_ppg_settings', [] );

        // Sanitize checkboxes without touching $_POST directly
        $enable_checker_raw       = filter_input( INPUT_POST, 'enable_checker', FILTER_DEFAULT );
        $enable_cookie_banner_raw = filter_input( INPUT_POST, 'enable_cookie_banner', FILTER_DEFAULT );
        $enable_update_banner_raw = filter_input( INPUT_POST, 'enable_update_banner', FILTER_DEFAULT );

        $enable_checker       = rest_sanitize_boolean( $enable_checker_raw );
        $enable_cookie_banner = rest_sanitize_boolean( $enable_cookie_banner_raw );
        $enable_update_banner = rest_sanitize_boolean( $enable_update_banner_raw );

        $new_settings = [
            'enable_checker'       => (bool) $enable_checker,
            'enable_cookie_banner' => (bool) $enable_cookie_banner,
            'enable_update_banner' => (bool) $enable_update_banner,
        ];

        $settings = wp_parse_args( $new_settings, $default_settings );

        update_option( 'madquick_ppg_settings', $settings );

        echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'madquick-ppg' ) . '</p></div>';
    }
}

$options = (array) get_option( 'madquick_ppg_settings', [] );
?>
<div class="wrap">
    <h1><?php esc_html_e( 'Madquick PPG Settings', 'madquick-ppg' ); ?></h1>
    <hr>
    <form method="post">
        <?php wp_nonce_field( 'save_madquick_ppg_settings', 'madquick_ppg_settings_nonce' ); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><?php esc_html_e( 'Enable Strong Password Checker', 'madquick-ppg' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_checker" value="1" <?php checked( ! empty( $options['enable_checker'] ) ); ?>>
                        <?php esc_html_e( 'Enable', 'madquick-ppg' ); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Enable Cookie Banner', 'madquick-ppg' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_cookie_banner" value="1" <?php checked( ! empty( $options['enable_cookie_banner'] ) ); ?>>
                        <?php esc_html_e( 'Enable', 'madquick-ppg' ); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php esc_html_e( 'Enable Update Browser Banner', 'madquick-ppg' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="enable_update_banner" value="1" <?php checked( ! empty( $options['enable_update_banner'] ) ); ?>>
                        <?php esc_html_e( 'Enable', 'madquick-ppg' ); ?>
                    </label>
                </td>
            </tr>
        </table>

        <?php submit_button( __( 'Save Settings', 'madquick-ppg' ) ); ?>
    </form>
</div>
