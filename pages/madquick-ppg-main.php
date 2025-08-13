<?php
// Main plugin dashboard page
?>
<div class="wrap">
    <h1 class=""><?php esc_html_e('All Legal Pages', 'madquick-ppg'); ?></h1>
    <hr>

    <h2 class="mq-policy-title"><?php esc_html_e('Privacy Policy', 'madquick-ppg'); ?></h2>
    <p class="mq-policy-description">
        <?php esc_html_e('Create a simple Privacy Policy for your WordPress website.', 'madquick-ppg'); ?>
    </p>

    <div class="">
        <?php
        $url = wp_nonce_url(
            add_query_arg([
                'page' => 'madquick-ppg-home',
                'current-action' => 'create-ppg'
            ], admin_url('admin.php')),
            'madquick_create_ppg_nonce',
            'madquick_ppg_nonce'
        );
        ?>
        <a class="button button-primary" href="<?php echo esc_url($url); ?>">
            <?php esc_html_e('Create', 'madquick-ppg'); ?>
        </a>
    </div>

    <div style="height: 32px; border-bottom: 1px dashed #d1d1d1" >

    </div>
    <h2 class=""><?php esc_html_e('Terms & Conditions', 'madquick-ppg'); ?></h2>

    <div class="">
        <p class="mq-policy-description">
            <?php esc_html_e('Create Terms & Conditions for your WordPress website.', 'madquick-ppg'); ?>
        </p>
        <a class="button button-primary" href="#">
            <?php esc_html_e('Create', 'madquick-ppg'); ?>
        </a>
    </div> 
</div>
