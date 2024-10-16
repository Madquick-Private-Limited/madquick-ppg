<?php

defined("ABSPATH") || exit;

/* ====================== AJAX Create When user click on generate ================ */
 

// Register AJAX action for logged-in users
add_action('wp_ajax_create_custom_page', 'madquick_create_privacy_and_policy_page');

// Register AJAX action for guests (not logged-in users)
add_action('wp_ajax_nopriv_create_custom_page', 'madquick_create_privacy_and_policy_page');

// Function to handle form submission and create a new page
function madquick_create_privacy_and_policy_page() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'madquick_nonce' ) ) {
        wp_send_json_error('Invalid security token.');
        exit;
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User is not logged in.');
        exit;
    }

    // Collect the form data from AJAX
    if (isset($_POST['post_content'])) {
        // Create a new page
        $new_page = array(
            'post_title'    => "Privacy & Policy",
            'post_content'  => wp_kses_post($_POST['post_content']), // Sanitize the content
            'post_status'   => 'publish',
            'post_type'     => 'page', 
        );

        // Insert the post into the database
        $post_id = wp_insert_post($new_page);

        if ($post_id) {
            // Return success and the new page URL
            $page_url = get_permalink($post_id);
            wp_send_json_success(['url' => $page_url]);
        } else {
            wp_send_json_error('Error creating the page.');
        }
    } else {
        wp_send_json_error('No form data received.');
    }
}