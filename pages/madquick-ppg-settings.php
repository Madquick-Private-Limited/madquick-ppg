<?php
defined('ABSPATH') || exit;
?>
<div class="wrap">
    <h1><?php esc_html_e('Settings', 'madquick-ppg'); ?></h1>
    <hr>
    <form method="post" action="options.php">
        <?php
        settings_fields('madquick_ppg_options_group');
        do_settings_sections('madquick-ppg-settings');
        submit_button(__('Save Settings', 'madquick-ppg'));
        ?>
    </form>
</div>
