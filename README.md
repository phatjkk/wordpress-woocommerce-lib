# wordpress-woocommerce-lib

Check phone vaild
Steps to Add a Phone Number Validation on the WooCommerce Checkout Page
Here are the steps that you need to follow:

Log into your WordPress site and access the dashboard as the admin user
From the dashboard menu, click on the Appearance Menu > Theme Editor Menu. When the theme editor page is opened, look for the theme functions file with the extension functions.php. Open this functions file to add the function to add a phone number validation on the WooCommerce checkout page.
Add the following line of code to the functions.php file:
// Limit Woocommerce phone field to 10 digits number

add_action('woocommerce_checkout_process', 'njengah_custom_checkout_field_process');

  function njengah_custom_checkout_field_process() {

    global $woocommerce;

      // Check if set, if its not set add an error. This one is only requite for companies

    if ( ! (preg_match('/^[0-9]{10}$/D', $_POST['billing_phone'] ))){

        wc_add_notice( "Incorrect Phone Number! Please enter valid 10 digits phone number"  ,'error' );

    }

}
This is the outcome:error on the checkout page
Instead of my preg_match, you can check anything else and adjust your conditional code to your needs.
You can also add custom validation for other default fields by checking on the right $_POST variable or your custom checkout fields after you correctly set them up

Conclusion
In this brief post, you have learned how to add a WooCommerce checkout validation. This will help you limit fake orders, as customers will be required to enter a valid number. However, you should be careful when editing the functions.php file because you can break your site if you do not know what you are doing.
