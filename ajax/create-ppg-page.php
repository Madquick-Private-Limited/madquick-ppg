<?php

// defined("ABSPATH") || exit;

/* ====================== AJAX Create When user click on generate ================ */
 

// Register AJAX action for logged-in users
add_action('wp_ajax_create_custom_page', 'madquick_create_privacy_and_policy_page');

// Register AJAX action for guests (not logged-in users)
add_action('wp_ajax_nopriv_create_custom_page', 'madquick_create_privacy_and_policy_page');
  
// Function to handle form submission and create a new page
function madquick_create_privacy_and_policy_page() {
    // Sanitize and verify the nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'madquick_nonce' ) ) {
        wp_send_json_error( 'Invalid security token.' );
        exit;
    }

    // Check if the user is logged in
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( 'User is not logged in.' );
        exit;
    }

    // Collect and sanitize the form data from AJAX
    if ( isset( $_POST['post_content'] ) ) {
        // Properly unslash and sanitize the post content
        $post_content = wp_kses_post( wp_unslash( $_POST['post_content'] ) );

        // Prepare the new page data
        $new_page = array(
            'post_title'   => sanitize_text_field( "Privacy & Policy" ), // Sanitizing title
            'post_content' => $post_content, // Already sanitized with wp_kses_post()
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $new_page );

        if ( $post_id ) {
            // Return success and the new page URL
            $page_url = get_permalink( $post_id );
            wp_send_json_success( [ 'url' => esc_url_raw( $page_url ) ] );
        } else {
            wp_send_json_error( 'Error creating the page.' );
        }
    } else {
        wp_send_json_error( 'No form data received.' );
    }
}
