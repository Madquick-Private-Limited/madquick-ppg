document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("privacy-policy-form")
    .addEventListener("submit", (ev) => {
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
});

// Function to toggle content visibility
function toggleContent(sectionId, show) {
  const section = document.getElementById(sectionId);
  section.style.display = show ? "block" : "none";
}

// Function to toggle fields visibility based on checkboxes
function toggleFields(fieldType) {
  const fieldMap = {
    email: "emailFields",
    link: "linkFields",
    phone: "phoneFields",
    address: "addressFields",
  };

  const field = document.getElementById(fieldMap[fieldType]);
  const checkbox = document.getElementById(fieldType + "Checkbox");

  field.style.display = checkbox.checked ? "block" : "none";
}

const convertToPageContent = (formData) => {
  const post_content = `
              
                                              <h2> Privacy Policy </h2>
              
                                              <span style="font-weight: 400;">At ${
                                                formData?.websiteName ?? ""
                                              }, accessible from ${
    formData.websiteURL
  }, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by test and how we use it.</span> <br><br>
          
                                              <span style="font-weight: 400;">If you have additional questions or require more information about our Privacy Policy, do not hesitate to contact us.</span> <br><br>
          
                                              <span style="font-weight: 400;">This Privacy Policy applies only to our online activities and is valid for visitors to our website with regards to the information that they shared and/or collected in test. This policy is not applicable to any information collected offline or via channels other than this website. Our Privacy Policy was created with the help of the </span><a href="https://privacypolicy-generator.com/"><span style="font-weight: 400;">Privacy Policy Generator.</span> <br><br></a>
                                              <h2>Information we collect <br></h2>
                                              <span style="font-weight: 400;">The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.</span> <br><br>
                                              <span>The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information. We also ask users to submit new passwords, if applicable. We prioritize security and understand the importance of strong passwords. That’s why we don’t use weak passwords on our website and recommend</span><a href="https://strong-password-generator.com/"><u><span style="color:#1155cc;">&nbsp;tough password generator</span></u></a><span>&nbsp;to help safeguard your account from unauthorized access. This commitment helps protect our users' accounts and data, giving you peace of mind while using our services.</span><span style="font-weight: 400;">When you register for an Account, we may ask for your </span> <br><br>
              
                                              Personal Information We Collect from Users:<br><br>
                                              ${
                                                typeof formData.types_of_data_collected !==
                                                  "string" &&
                                                formData.types_of_data_collected
                                                  ?.map(
                                                    (data) =>
                                                      "<li>" + data + "</li>"
                                                  )
                                                  .join("")
                                              }
                                              ${
                                                "<li>" +
                                                  typeof formData.types_of_data_collected ===
                                                "string"
                                                  ? formData.types_of_data_collected
                                                  : "" + "</li>"
                                              }
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
                                              <p>For Entity Type, we use Individual platforms.</p>${
                                                formData.state ??
                                                " " + " " + formData.country ??
                                                " "
                                              }<h2>Contact Us </h2>Contact Information<br><br>Email: ${
    formData?.websiteEmail ?? ""
  }<br><br>
                                                  <br><br>
                                                  Phone: ${
                                                    formData?.phoneNumber ?? ""
                                                  }
                                                  `;

  savePost(post_content);
};

const savePost = (post_content) => {
  console.log(post_content);

  if (typeof ajax_object === "undefined") {
    console.error("ajax_object is not defined");
    return;
  }

  // Use jQuery's ajax method
  jQuery.ajax({
    url: ajax_object.ajax_url,
    type: "POST",
    dataType: "json",
    data: {
      action: "create_custom_page",
      post_content: post_content,
      nonce: ajax_object.nonce,
    },
    success: function (response) {
      if (response.success) {
        console.log(response.data); // Handle the successful response
        window.location.href = response.data.url; // Redirect to the new page URL
      } else {
        console.error("Error:", response.data); // Handle any errors
        alert("There was an error creating the privacy and policy page.");
      }
    },
    error: function (xhr, status, error) {
      console.log("AJAX Error:", error);
    },
  });
};
