<?php

/**
 * Plugin Name: Privacy Policy Generator - Madquick
 * Author URI: https://github.com/Madquick-Private-Limited/
 * Requires at least: 6.3
 * Requires PHP: 7.2.24
 * Version: 1.0.0
 * Description: A simple WordPress plugin that help us to generate privacy and policy page and other legal pages that are required in your website.
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Keywords: privacy, policy, terms, legal, compliance, GDPR, Madquick
 * Text Domain: madquick-ppg
 * 
 * @package madquick-ppg
 */

defined("ABSPATH") || exit;

/* imports */
// require_once "Madquick_Page_CPT.php";
require_once "ajax/create-ppg-page.php";

function madquick_enqueue_scripts($hook_suffix)
{
    // Define the plugin directory path for file versioning
    $plugin_dir = plugin_dir_path(__FILE__);

    // Register the JavaScript file
    wp_register_script(
        'generate-policy-script',
        plugin_dir_url(__FILE__) . 'assets/js/generate-policy.js',
        ['jquery'], // Dependencies
        filemtime($plugin_dir . 'assets/js/generate-policy.js'), // Dynamic version based on last modified time
        true // Load in the footer
    );

    // Enqueue the registered script
    wp_enqueue_script('generate-policy-script');

    // Localize the script immediately after enqueueing
    wp_localize_script('generate-policy-script', 'ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('madquick_nonce'),
    ]);

    // Register and enqueue the CSS files
    wp_register_style(
        'home-page-css',
        plugin_dir_url(__FILE__) . 'assets/css/home-page.css',
        [], // No dependencies
        filemtime($plugin_dir . 'assets/css/home-page.css')
    );
    wp_enqueue_style('home-page-css');

    wp_register_style(
        'plugin-page-css',
        plugin_dir_url(__FILE__) . 'assets/css/plugin-page.css',
        [], // No dependencies
        filemtime($plugin_dir . 'assets/css/plugin-page.css')
    );
    wp_enqueue_style('plugin-page-css');
}
add_action('admin_enqueue_scripts', 'madquick_enqueue_scripts');


// Hook to add the custom admin menu
add_action('admin_menu', 'madquick_ppg_add_admin_menu');

// defined in cpt page
// add_action('init', 'madquick_register_custom_post_type');

function madquick_ppg_add_admin_menu()
{

    // Main menu
    add_menu_page(
        'Privary & Policy Generator',   // Page title
        'Privary & Policy Generator',            // Menu title
        'manage_options',          // Capability required to access the menu
        'madquick-ppg-home',            // Menu slug (unique identifier)
        'madquick_ppg_add_legal_page_cb', // Function to display the page content
        'dashicons-admin-generic',
        20
    );

    add_submenu_page(
        'madquick-ppg-home',       // Parent slug (must match the slug of the main menu)
        'Help',            // Page title
        'Help',            // Submenu title
        'manage_options',          // Capability required to access this submenu
        'madquick-ppg-help',       // Submenu slug (unique identifier)
        'madquick_ppg_help_page'   // Function to display the content of the submenu page
    );
}


function madquick_ppg_add_legal_page_cb()
{
    $current_action = "none";

    // Check for 'current-action' and 'my_nonce' in the URL
    if (isset($_GET['current-action']) && isset($_GET['my_nonce'])) {
        // Properly unslash and sanitize the nonce parameter before verifying
        $nonce = sanitize_text_field(wp_unslash($_GET['my_nonce']));

        // Verify the nonce to ensure the request is valid
        if (wp_verify_nonce($nonce, 'madquick_create_ppg_nonce')) {
            // Properly unslash and sanitize the action parameter
            $current_action = wp_kses_post(wp_unslash($_GET['current-action']));
        } else {
            // If nonce verification fails, stop the process and show an error
            wp_die('Unauthorized request');
        }
    }


    ?>

    <!-- main page -->
    <?php if ($current_action === "none"): ?>

        <!-- link plugin-page.css -->

        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('All Legal Pages', 'madquick-ppg'); ?></h1>

            <table class="table-container">
                <tbody>
                    <tr>
                        <td style="width: 40%;">
                            <p class="policy-title"><?php esc_html_e('Privacy Policy', 'madquick-ppg'); ?></p>
                        </td>
                        <td>
                            <div class="policy-card">
                                <p class="policy-description">
                                    <?php esc_html_e('Create a simple Privacy Policy for your WordPress website.', 'madquick-ppg'); ?>
                                </p>
                                <?php
                                $url = wp_nonce_url(
                                    add_query_arg(
                                        [
                                            'page' => 'madquick-ppg-home',
                                            'current-action' => 'create-ppg'
                                        ],
                                        admin_url('admin.php')
                                    ),
                                    'madquick_create_ppg_nonce',
                                    'my_nonce'
                                );
                                ?>
                                <a class="button button-primary" href="<?php echo esc_url($url); ?>">
                                    <?php esc_html_e('Create', 'madquick-ppg'); ?>
                                </a>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%;">
                            <p class="policy-title"><?php esc_html_e('Terms & Conditions', 'madquick-ppg'); ?></p>
                        </td>
                        <td>
                            <div class="policy-card">
                                <p class="policy-description">
                                    <?php esc_html_e('Create Terms & Conditions for your WordPress website.', 'madquick-ppg'); ?>
                                </p>
                                <a class="button button-primary" href="#">
                                    <?php esc_html_e('Create', 'madquick-ppg'); ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


    <?php endif; ?>

    <!-- PPG Page -->
    <?php if ($current_action === "create-ppg"): ?>

        <!-- link css home-page.css -->

        <div class="wrap-main">
            <h1><?php esc_html_e('All Legal Pages', 'madquick-ppg'); ?></h1>

            <!-- ppg form -->
            <form id="privacy-policy-form" method="post" action="#">
                <h2 style="font-size: 20px;">
                    <?php echo esc_html_e("Website Information to generate privacy policy", "madquick-ppg"); ?>
                </h2>
                <!-- Website -->
                <div class="input-field">
                    <label for="websiteName">What is your website name?</label>
                    <input type="text" id="websiteName" name="websiteName" placeholder="website name name" class="form-control">
                </div>
                <!-- Website -->
                <div class="input-field">
                    <label for="websiteURL">What is your website URL?</label>
                    <input type="text" id="websiteURL" name="websiteURL" placeholder="Website URL" class="form-control">
                </div>

                <!-- Entity Type -->
                <div class="input-field">
                    <label for="entityType">Entity Type</label>

                    <div>
                        <input type="radio" name="entityType" value="Business" id="business"
                            onclick="toggleContent('companyname', true)">
                        <label for="business">I'm a Business</label>
                    </div>

                    <div>
                        <input type="radio" name="entityType" value="Individual" id="individual"
                            onclick="toggleContent('companyname', false)">
                        <label for="individual">I'm an Individual</label>
                    </div>
                </div>

                <!-- Business Details -->
                <div id="companyname" class="inner-section" style="display: none;">
                    <div class="input-field">
                        <label class="title">What is the name of the business?</label>
                        <input id="company_name" name="company_name" type="text" placeholder="My Company LLC"
                            class="form-control">
                        <div class="help-text-bottom">e.g. My Company LLC</div>
                    </div>

                    <div class="input-field">
                        <label class="title">What is the address of the business?</label>
                        <input id="company_address" name="company_address" type="text" placeholder="1 Cupertino, CA 95014"
                            class="form-control">
                        <div class="help-text-bottom">e.g. 1 Cupertino, CA 95014</div>
                    </div>
                </div>

                <!-- Country and State -->
                <div class="input-field">
                    <label for="country">Enter the country</label>
                    <input type="text" id="country" name="country" placeholder="Enter the country" class="form-control">
                </div>

                <div class="input-field">
                    <label for="state">Enter the state</label>
                    <input type="text" id="state" name="state" placeholder="Enter the state" class="form-control">
                </div>

                <!-- Personal Information Collected -->
                <div class="input-field">
                    <h2 class="" style="font-size: 24px;">
                        Data Collection Information
                    </h2>
                    <label for="personalInfo">What kind of personal information do you collect from users?</label>
                    <p>Click all that apply</p>
                    <div class="checkbox-group">
                        <div>
                            <input type="checkbox" name="types_of_data_collected" id="email" value="Email">
                            <label for="email">Email address</label>
                        </div>
                        <div>
                            <input type="checkbox" name="types_of_data_collected" id="name" value="Name">
                            <label for="name">First name and last name</label>
                        </div>
                        <div>
                            <input type="checkbox" name="types_of_data_collected" id="phone" value="Phone">
                            <label for="phone">Phone number</label>
                        </div>
                        <div>
                            <input type="checkbox" name="types_of_data_collected" id="address" value="Address">
                            <label for="address">Address, State, Province, ZIP/Postal code, City</label>
                        </div>
                        <div>
                            <input type="checkbox" name="types_of_data_collected" id="socialMedia" value="Social Media Login">
                            <label for="socialMedia">Social Media Profile information (e.g., from Facebook, Twitter)</label>
                        </div>
                    </div>
                </div>

                <!-- Contact Method -->
                <div class="input-field">
                    <h2 style="font-size: 20px;">
                        Contact Information Want To Put In Privacy Policy
                    </h2>
                    <label for="company_contact">How can the company contact users?</label>

                    <div class="checkbox-group">
                        <div>
                            <input type="checkbox" name="company_contact" id="emailCheckbox" value="Email"
                                onchange="toggleFields('email')">
                            <label for="emailCheckbox">By email</label>
                        </div>

                        <div id="emailFields" style="display: none;">
                            <input type="email" id="websiteEmail" name="websiteEmail" placeholder="Website/Email"
                                class="form-control">
                        </div>

                        <div>
                            <input type="checkbox" name="company_contact" id="linkCheckbox" value="Link"
                                onchange="toggleFields('link')">
                            <label for="linkCheckbox">By visiting a page on our website</label>
                        </div>

                        <div id="linkFields" style="display: none;">
                            <input type="text" id="websitePage" name="websitePage" placeholder="Page URL" class="form-control">
                        </div>

                        <div>
                            <input type="checkbox" name="company_contact" id="phoneCheckbox" value="Phone"
                                onchange="toggleFields('phone')">
                            <label for="phoneCheckbox">By phone number</label>
                        </div>

                        <div id="phoneFields" style="display: none;">
                            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone number"
                                class="form-control">
                        </div>

                        <div>
                            <input type="checkbox" name="company_contact" id="addressCheckbox" value="Address"
                                onchange="toggleFields('address')">
                            <label for="addressCheckbox">By sending post mail</label>
                        </div>

                        <div id="addressFields" style="display: none;">
                            <input type="text" id="postAddress" name="postAddress" placeholder="Postal Address"
                                class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button class="button button-primary">Generate</button>
            </form>

            <script>

            </script>
        </div>


    <?php endif; ?>

    <?php
}


// Function to display the content of the "Madquick PPG" (submenu) page
function madquick_ppg_help_page()
{
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Help & Documentation', 'madquick-ppg'); ?></h1>
        <p><?php esc_html_e('Welcome to the Madquick Privacy & Policy Generator Help section. Here you’ll find useful information and guides on how to use the plugin effectively.', 'madquick-ppg'); ?>
        </p>

        <h2><?php esc_html_e('Table of Contents', 'madquick-ppg'); ?></h2>
        <ul>
            <li><a href="#overview"><?php esc_html_e('Overview', 'madquick-ppg'); ?></a></li>
            <li><a href="#how-to-use"><?php esc_html_e('How to Use', 'madquick-ppg'); ?></a></li>
            <li><a href="#faq"><?php esc_html_e('Frequently Asked Questions', 'madquick-ppg'); ?></a></li>
            <li><a href="#support"><?php esc_html_e('Support', 'madquick-ppg'); ?></a></li>
        </ul>

        <hr>

        <h2 id="overview"><?php esc_html_e('Overview', 'madquick-ppg'); ?></h2>
        <p><?php esc_html_e('Madquick PPG helps you quickly generate essential legal pages like Privacy Policy and Terms & Conditions. It ensures your site stays compliant with privacy laws such as GDPR and CCPA.', 'madquick-ppg'); ?>
        </p>

        <h2 id="how-to-use"><?php esc_html_e('How to Use', 'madquick-ppg'); ?></h2>
        <ol>
            <li><?php esc_html_e('Go to the plugin settings page under "Madquick PPG".', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Select the type of legal page you want to create (Privacy Policy or Terms & Conditions).', 'madquick-ppg'); ?>
            </li>
            <li><?php esc_html_e('Click the "Create" button.', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Edit the generated content as needed.', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Save and publish the page.', 'madquick-ppg'); ?></li>
        </ol>

        <h2 id="faq"><?php esc_html_e('Frequently Asked Questions', 'madquick-ppg'); ?></h2>
        <dl>
            <dt><?php esc_html_e('Can I customize the generated pages?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('Yes, all generated pages can be edited directly within the WordPress editor.', 'madquick-ppg'); ?>
            </dd>

            <dt><?php esc_html_e('Does the plugin ensure GDPR compliance?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('The plugin provides a template that helps you stay compliant, but we recommend reviewing it to ensure it meets your specific needs.', 'madquick-ppg'); ?>
            </dd>

            <dt><?php esc_html_e('Can I regenerate the legal pages if needed?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('Yes, you can regenerate pages at any time by visiting the plugin settings.', 'madquick-ppg'); ?>
            </dd>
        </dl>

        <h2 id="support"><?php esc_html_e('Support', 'madquick-ppg'); ?></h2>
        <p><?php esc_html_e('If you encounter any issues or have questions, please contact us at', 'madquick-ppg'); ?>
            <a href="mailto:tech@madquick.in">tech@madquick.in</a>.
        </p>
    </div>
    <?php
}

