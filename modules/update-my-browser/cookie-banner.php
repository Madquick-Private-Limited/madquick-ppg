<?php
defined('ABSPATH') || exit;

$options = get_option('madquick_ppg_settings', []);
$keyword = !empty($options['update_my_browser_keyword']) 
    ? esc_html($options['update_my_browser_keyword']) 
    : esc_html__('update my browser', 'madquick-ppg');
$link = esc_url('https://updatemybrowsers.com/');
?>
<div id="mq-cookie-banner" class="mq-cookie-banner" aria-live="polite">
    <div class="mq-cookie-content">
        <p class="mq-cookie-text">
            <?php
            printf(
                /* translators: 1: opening <a> tag, 2: closing </a> tag */
                esc_html__(
                    'We use cookies to enhance your browsing experience, provide personalized content, and improve site performance. For the best experience, please keep your browser up to date — %1$s%2$s%3$s.',
                    'madquick-ppg'
                ),
                '<a href="' . $link . '" target="_blank" rel="noopener">',
                $keyword,
                '</a>'
            );
            ?>
        </p>
        <div class="mq-cookie-actions">
            <button id="mq-cookie-accept" class="mq-cookie-btn accept">
                <?php esc_html_e('Accept', 'madquick-ppg'); ?>
            </button>
            <button id="mq-cookie-reject" class="mq-cookie-btn reject">
                <?php esc_html_e('Reject', 'madquick-ppg'); ?>
            </button>
        </div>
    </div>
</div>
