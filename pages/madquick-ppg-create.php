<div class="wrap">
    <h1><?php esc_html_e('All Legal Pages', 'madquick-ppg'); ?></h1>
    <hr>
    
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

