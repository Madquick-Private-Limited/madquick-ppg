<?php
defined('ABSPATH') || exit;

$options = (array) get_option( 'madquick_ppg_settings', [] );

$raw_keyword = ! empty( $options['update_my_browser_keyword'] )
    ? $options['update_my_browser_keyword']
    : __( 'update my browser', 'madquick-ppg' );

// Escape AFTER choosing the value, right before output.
$keyword = esc_html( $raw_keyword );

// Always escape URLs at output time.
$link = 'https://updatemybrowsers.com/';
?>
<div id="mq-cookie-banner" class="mq-cookie-banner" aria-live="polite">
    <div class="mq-cookie-content">
        <p class="mq-cookie-text">
            <?php
            // Build the translatable sentence with placeholders, then allow only <a>.
            $text = sprintf(
                /* translators: 1: opening <a> tag, 2: keyword text, 3: closing </a> tag */
                __( 'We use cookies to enhance your browsing experience, provide personalized content, and improve site performance. For the best experience, please keep your browser up to date — %1$s%2$s%3$s.', 'madquick-ppg' ),
                '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">',
                $keyword,
                '</a>'
            );

            echo wp_kses(
                $text,
                [
                    'a' => [
                        'href'   => true,
                        'target' => true,
                        'rel'    => true,
                    ],
                ]
            );
            ?>
        </p>
        <div class="mq-cookie-actions">
            <button id="mq-cookie-accept" class="mq-cookie-btn accept">
                <?php esc_html_e( 'Accept', 'madquick-ppg' ); ?>
            </button>
            <button id="mq-cookie-reject" class="mq-cookie-btn reject">
                <?php esc_html_e( 'Reject', 'madquick-ppg' ); ?>
            </button>
        </div>
    </div>
</div>
