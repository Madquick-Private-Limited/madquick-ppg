<?php
/*
Plugin Name: Madquick PPG
Version: 1.0.0
Author: madquick team
Author URI: https://github.com/Madquick-Private-Limited/
Text Domain: madquick-ppg
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Keywords: privacy, policy, terms, legal, compliance, GDPR, madquick
Description: A simple wordpress plugin that help us to generate privacy and policy page and other legal pages that are required in your website.
*/


defined("ABSPATH") || exit;

/* imports */
// require_once "Madquick_Page_CPT.php";
require_once "ajax/create-ppg-page.php";

function madquick_enqueue_scripts() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'madquick_enqueue_scripts');


// Hook to add the custom admin menu
add_action('admin_menu', 'madquick_ppg_add_admin_menu');

// defined in cpt page
add_action('init', 'madquick_register_custom_post_type');

function madquick_ppg_add_admin_menu() {

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

function madquick_ppg_add_legal_page_cb() {
    $current_action =  "none";

    if( isset($_GET['madquick_nonce']) && 
    wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['madquick_nonce'])), 'madquick_action_nonce') ) {
        if (isset($_GET['currect-action']) && !empty($_GET['currect-action'])) {
            // Properly unslash and sanitize the action parameter
            $current_action = wp_kses_post(wp_unslash($_GET['currect-action']));
        } 
    }
    
    ?>

    <!-- main page -->
    <?php if($current_action === "none"): ?>
    
        <style>
            .wrap {
                margin-top: 20px;
            }

            .table-container {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .table-container td {
                padding: 20px;
                vertical-align: top;
            }

            .table-container tr {
                border-bottom: 1px solid #e2e8f0;
            }

            .table-container tr:last-child {
                border-bottom: none;
            }

            .policy-card {
                border: 1px solid #dcdcdc;
                border-radius: 6px;
                padding: 20px;
                background-color: #fafafa;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .policy-title {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 8px;
            }

            .policy-description {
                margin-bottom: 12px;
                color: #444;
            }

            .button-primary {
                margin-top: 8px;
            }
        </style>

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
                                <a class="button button-primary" href="?page=madquick-ppg-home&currect-action=create-ppg">
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
    <?php if($current_action === "create-ppg"): ?>

        <style> 
            .wrap {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                max-width: 800px;
                margin: 12px auto;
            }

            #privacy-policy-form {
                margin-top: 12px;
            }

            h1 {
                line-height: 45px;
                font-weight: bold;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #eee;
                font-size: 28px;
                color: #333;
            }

            .input-field {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 20px;
            }

            label {
                font-weight: 600;
                color: #333;
            }

            input[type="text"],
            input[type="email"],
            input[type="tel"],
            input[type="url"],
            .form-control {
                width: 100%;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #ddd;
                font-size: 14px;
            }

            .checkbox-group {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 20px;
            }

            input[type="checkbox"],
            input[type="radio"] {
                margin-right: 10px;
            }

            .inner-section {
                padding-left: 20px;
                border-left: 3px solid #eee;
                margin-bottom: 20px;
                padding-top: 10px;
            }

            .help-text-bottom {
                font-size: 12px;
                color: #888;
                margin-top: 5px;
            }

            button {
                background-color: #007cba;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                margin-top: 20px;
            }

            button:hover {
                background-color: #005fa3;
            }
        </style>

    <div class="wrap">
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
                    <input type="radio" name="entityType" value="Business" id="business" onclick="toggleContent('companyname', true)">
                    <label for="business">I'm a Business</label>
                </div>

                <div>
                    <input type="radio" name="entityType" value="Individual" id="individual" onclick="toggleContent('companyname', false)">
                    <label for="individual">I'm an Individual</label>
                </div>
            </div>

            <!-- Business Details -->
            <div id="companyname" class="inner-section" style="display: none;">
                <div class="input-field">
                    <label class="title">What is the name of the business?</label>
                    <input id="company_name" name="company_name" type="text" placeholder="My Company LLC" class="form-control">
                    <div class="help-text-bottom">e.g. My Company LLC</div>
                </div>

                <div class="input-field">
                    <label class="title">What is the address of the business?</label>
                    <input id="company_address" name="company_address" type="text" placeholder="1 Cupertino, CA 95014" class="form-control">
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
                        <input type="checkbox" name="company_contact" id="emailCheckbox" value="Email" onchange="toggleFields('email')">
                        <label for="emailCheckbox">By email</label>
                    </div>

                    <div id="emailFields" style="display: none;">
                        <input type="email" id="websiteEmail" name="websiteEmail" placeholder="Website/Email" class="form-control">
                    </div>

                    <div>
                        <input type="checkbox" name="company_contact" id="linkCheckbox" value="Link" onchange="toggleFields('link')">
                        <label for="linkCheckbox">By visiting a page on our website</label>
                    </div>

                    <div id="linkFields" style="display: none;">
                        <input type="text" id="websitePage" name="websitePage" placeholder="Page URL" class="form-control">
                    </div>

                    <div>
                        <input type="checkbox" name="company_contact" id="phoneCheckbox" value="Phone" onchange="toggleFields('phone')">
                        <label for="phoneCheckbox">By phone number</label>
                    </div>

                    <div id="phoneFields" style="display: none;">
                        <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone number" class="form-control">
                    </div>

                    <div>
                        <input type="checkbox" name="company_contact" id="addressCheckbox" value="Address" onchange="toggleFields('address')">
                        <label for="addressCheckbox">By sending post mail</label>
                    </div>

                    <div id="addressFields" style="display: none;">
                        <input type="text" id="postAddress" name="postAddress" placeholder="Postal Address" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button class="button button-primary">Generate</button>
        </form>

        <script>

            // Function to toggle content visibility
            function toggleContent(sectionId, show) {
                const section = document.getElementById(sectionId);
                section.style.display = show ? 'block' : 'none';
            }

            // Function to toggle fields visibility based on checkboxes
            function toggleFields(fieldType) {
                const fieldMap = {
                    email: 'emailFields',
                    link: 'linkFields',
                    phone: 'phoneFields',
                    address: 'addressFields'
                };

                const field = document.getElementById(fieldMap[fieldType]);
                const checkbox = document.getElementById(fieldType + 'Checkbox');

                field.style.display = checkbox.checked ? 'block' : 'none';
            }

            document.getElementById("privacy-policy-form").addEventListener("submit", (ev) => {
                ev.preventDefault(); // Prevent form from submitting normally

                // Create a new FormData object, passing in the form element
                const formData = new FormData(ev.target);

                // Create an object to store the form data
                const formValues = {};

                // Iterate over the FormData object and collect data
                formData.forEach((value, key) => {
                    // Handle multiple values (like checkboxes)
                    if (formValues[key]) {
                        if (Array.isArray(formValues[key])) {
                            formValues[key].push(value); // Add value to the existing array
                        } else {
                            formValues[key] = [formValues[key], value]; // Convert to array if it's not already
                        }
                    } else {
                        formValues[key] = value; // For single values
                    }
                });

                // Log the collected data to the console
                console.log(formValues);
                convertToPageContent(formValues);
            });

            const convertToPageContent = (formData) => {

                const post_content = `
                
                <h2> Privacy Policy </h2>
                
                <span style="font-weight: 400;">At ${formData?.websiteName ?? ""}, accessible from ${formData.websiteURL}, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by test and how we use it.</span> <br><br>
            
                <span style="font-weight: 400;">If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</span> <br><br>
            
                <span style="font-weight: 400;">This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collected in test. This policy is not applicable to any information collected offline or via channels other than this website. Our Privacy Policy was created with the help of the </span><a href="https://privacypolicy-generator.com/"><span style="font-weight: 400;">Privacy Policy Generator.</span> <br><br></a>
                <h2>Information we collect <br></h2>
                <span style="font-weight: 400;">The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</span> <br><br>
                <span>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information. We also ask users to submit new passwords, if applicable. We prioritize security and understand the importance of strong passwords. That’s why we don’t use weak passwords on our website and recommend</span><a href="https://strong-password-generator.com/"><u><span style="color:#1155cc;">&nbsp;tough password generator</span></u></a><span>&nbsp;to help safeguard your account from unauthorized access. This commitment helps protect our users' accounts and data, giving you peace of mind while using our services.</span><span style="font-weight: 400;">When you register for an Account, we may ask for your </span> <br><br>
                
                Personal Information We Collect from Users:<br><br>
                ${typeof formData.types_of_data_collected !== 'string' && formData.types_of_data_collected
                .map(data => '<li>' + data + '</li>')
                .join('')}
                ${ '<li>' + typeof formData.types_of_data_collected === 'string' ? formData.types_of_data_collected : "" + '</li>' }
                <h2>How we use your information <br></h2>
                <span style="font-weight: 400;">We use the information we collect in various ways, including to:</span> <br><br>
                <ul>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Provide, operate, and maintain our website</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Improve, personalize, and expand our website</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Understand and analyze how you use our website</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Develop new products, services, features, and functionality</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Communicate with you, either directly or through one of our partners, including for customer service, to provide you with updates and other information relating to the website, and for marketing and promotional purposes</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Send you emails</span> <br><br></li>
                    <li style="font-weight: 400;" aria-level="1"><span style="font-weight: 400;">Find and prevent fraud</span> <br><br></li>
                </ul>
            
                
                <h2>Log Files <br></h2>
                <span style="font-weight: 400;">test follows a standard procedure of using log files. These files log visitors when they visit websites. All hosting companies do this as part of hosting services’ analytics. The information collected by log files include internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the number of clicks. These are not linked to any information that is personally identifiable. The purpose of the information is for analyzing trends, administering the site, tracking users’ movement on the website, and gathering demographic information.</span> <br><br>
                
                <h2>Email Privacy Policies: <br></h2><span>Your privacy and security are what we care about most. To give you a better experience, we've updated our email privacy policies. We only allow real email addresses to make the space safer for everyone, and we've improved our encryption to protect your data. We do not allow <a href="https://temp-maill.org/">tempmail</a>.</span><br><br><h2>Updated Browser Policies for Enhanced Security and Privacy <br></h2> <span><span>We are committed to your security and have updated our browser policies to improve your privacy. Our new encryption standards, reduced cookie collection, and better protection against harmful content all contribute to a safer browsing experience.</span><a href="http://updatemybrowsers.com/"><span>&nbsp;</span>Update your browser</a><span>&nbsp;today.</span></span><br><br><h2>Adult Content and Compliance with Pornography Laws:<br></h2><span>We are dedicated to providing a respectful and secure online experience by following</span> <a href="https://pornography-laws.com/"><u><span>pornography laws</span></u></a><span>. Our platform bans adult content to ensure a family-friendly atmosphere. Our effective content moderation practices prevent any adult material from being published and address any violations promptly.</span><br><br><h2>Third Party Privacy Policies <br></h2>
                <span style="font-weight: 400;">test’s Privacy Policy does not apply to other advertisers or websites. Thus, we are advising you to consult the respective Privacy Policies of these third-party ad servers for more detailed information. It may include their practices and instructions about how to opt-out of certain options.</span> <br><br>
            
                <span style="font-weight: 400;">You can choose to disable cookies through your individual browser options. To know more detailed information about cookie management with specific web browsers, it can be found at the browsers’ respective websites.</span> <br><br>
                <h2>CCPA Privacy Rights (Do Not Sell My Personal Information) <br></h2>
                <span style="font-weight: 400;">Under the CCPA, among other rights, California consumers have the right to:</span> <br><br>
            
                <span style="font-weight: 400;">Request that a business that collects a consumer’s personal data disclose the categories and specific pieces of personal data that a business has collected about consumers.</span> <br><br>
            
                <span style="font-weight: 400;">Request that a business delete any personal data about the consumer that a business has collected.</span> <br><br>
            
                <span style="font-weight: 400;">Request that a business that sells a consumer’s personal data, not sell the consumer’s personal data.</span> <br><br>
            
                <span style="font-weight: 400;">If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</span> <br><br>
                <h2>Use of Content</h2><span>We are dedicated to upholding high standards for content quality and integrity. Therefore, we strictly prohibit the use of</span><a href="https://lorem-ipsumm.com/"><u><span>&nbsp;Lorem Ipsum Generator</span></u></a><span>&nbsp;content, including but not limited to Lorem Ipsum text, on our website. All content must be original, relevant, and meaningful to our audience. This ensures that our website remains a valuable and trustworthy resource for our visitors.</span><br><br><h2>GDPR Data Protection Rights <br></h2>
                <span style="font-weight: 400;">We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:</span> <br><br>
            
                <span style="font-weight: 400;">The right to access – You have the right to request copies of your personal data. We may charge you a small fee for this service.</span> <br><br>
            
                <span style="font-weight: 400;">The right to rectification – You have the right to request that we correct any information you believe is inaccurate. You also have the right to request that we complete the information you believe is incomplete.</span> <br><br>
            
                <span style="font-weight: 400;">The right to erasure – You have the right to request that we erase your personal data, under certain conditions.</span> <br><br>
            
                <span style="font-weight: 400;">The right to restrict processing – You have the right to request that we restrict the processing of your personal data, under certain conditions.</span> <br><br>
            
                <span style="font-weight: 400;">The right to object to processing – You have the right to object to our processing of your personal data, under certain conditions.</span> <br><br>
            
                <span style="font-weight: 400;">The right to data portability – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</span> <br><br>
            
                <span style="font-weight: 400;">If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us.</span> <br><br>
                <h2>Children’s Information <br></h2>
                <span style="font-weight: 400;">Another part of our priority is adding protection for children while using the internet. We encourage parents and guardians to observe, participate in, and/or monitor and guide their online activity.</span> <br><br>
            
                <span style="font-weight: 400;">test does not knowingly collect any Personal Identifiable Information from children under the age of 13. If you think that your child provided this kind of information on our website, we strongly encourage you to contact us immediately and we will do our best efforts to promptly remove such information from our records.</span> <br><br>
                <p>For Entity Type, we use Individual platforms.</p>${formData.state ?? " " + " " + formData.country ?? " "}<h2>Contact Us </h2>Contact Information<br><br>Email: ${formData?.websiteEmail ?? ""}<br><br>
                    <br><br>
                    Phone: ${formData?.phoneNumber ?? ""}
                    `;
 
                savePost(post_content);
            }

            const savePost = (post_content) => {
                console.log(post_content);

                // Use jQuery's ajax method
                jQuery.ajax({
                    url: '<?php echo esc_js(admin_url("admin-ajax.php")); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'create_custom_page',
                        post_content: post_content,
                        nonce: '<?php echo esc_js(wp_create_nonce('madquick_nonce')); ?>'
                    },
                    success: function(response) {
                        if(response.success) {
                            console.log(response.data); // Handle the successful response
                            window.location.href = response.data.url; // Redirect to the new page URL
                        } else {
                            console.error('Error:', response.data); // Handle any errors
                            alert('There was an error creating the privacy and policy page.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); // Handle AJAX-level errors
                    }
                });
            };

        </script>
    </div>


    <?php endif; ?>

    <?php
}
 

// Function to display the content of the "Madquick PPG" (submenu) page
function madquick_ppg_help_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Help & Documentation', 'madquick-ppg'); ?></h1>
        <p><?php esc_html_e('Welcome to the Madquick Privacy & Policy Generator Help section. Here you’ll find useful information and guides on how to use the plugin effectively.', 'madquick-ppg'); ?></p>

        <h2><?php esc_html_e('Table of Contents', 'madquick-ppg'); ?></h2>
        <ul>
            <li><a href="#overview"><?php esc_html_e('Overview', 'madquick-ppg'); ?></a></li>
            <li><a href="#how-to-use"><?php esc_html_e('How to Use', 'madquick-ppg'); ?></a></li>
            <li><a href="#faq"><?php esc_html_e('Frequently Asked Questions', 'madquick-ppg'); ?></a></li>
            <li><a href="#support"><?php esc_html_e('Support', 'madquick-ppg'); ?></a></li>
        </ul>

        <hr>

        <h2 id="overview"><?php esc_html_e('Overview', 'madquick-ppg'); ?></h2>
        <p><?php esc_html_e('Madquick PPG helps you quickly generate essential legal pages like Privacy Policy and Terms & Conditions. It ensures your site stays compliant with privacy laws such as GDPR and CCPA.', 'madquick-ppg'); ?></p>

        <h2 id="how-to-use"><?php esc_html_e('How to Use', 'madquick-ppg'); ?></h2>
        <ol>
            <li><?php esc_html_e('Go to the plugin settings page under "Madquick PPG".', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Select the type of legal page you want to create (Privacy Policy or Terms & Conditions).', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Click the "Create" button.', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Edit the generated content as needed.', 'madquick-ppg'); ?></li>
            <li><?php esc_html_e('Save and publish the page.', 'madquick-ppg'); ?></li>
        </ol>

        <h2 id="faq"><?php esc_html_e('Frequently Asked Questions', 'madquick-ppg'); ?></h2>
        <dl>
            <dt><?php esc_html_e('Can I customize the generated pages?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('Yes, all generated pages can be edited directly within the WordPress editor.', 'madquick-ppg'); ?></dd>

            <dt><?php esc_html_e('Does the plugin ensure GDPR compliance?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('The plugin provides a template that helps you stay compliant, but we recommend reviewing it to ensure it meets your specific needs.', 'madquick-ppg'); ?></dd>

            <dt><?php esc_html_e('Can I regenerate the legal pages if needed?', 'madquick-ppg'); ?></dt>
            <dd><?php esc_html_e('Yes, you can regenerate pages at any time by visiting the plugin settings.', 'madquick-ppg'); ?></dd>
        </dl>

        <h2 id="support"><?php esc_html_e('Support', 'madquick-ppg'); ?></h2>
        <p><?php esc_html_e('If you encounter any issues or have questions, please contact us at', 'madquick-ppg'); ?> 
            <a href="mailto:tech@madquick.in">tech@madquick.in</a>.
        </p>
    </div>
    <?php
}

