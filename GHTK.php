<?php

 add_action( 'woocommerce_shipping_init', 'phatjk_shipping_init' );
 
 function phatjk_shipping_init() {
     if ( ! class_exists( 'WC_PHATJK_SHIPPING') ) {
         class WC_PHATJK_SHIPPING extends WC_Shipping_Method {
            
            public function __construct() {
                $this->id                 = 'ghtk_shipping'; // Id for your shipping method. Should be uunique.
				$this->method_title       = __( 'Giao hàng tiết kiệm' );  // Title shown in admin
				$this->method_description = __( 'Description of your Techiepress DHL Shipping' ); // Description shown in admin

				$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
				$this->title              = "Giao hàng tiết kiệm"; // This can be added as an setting but for this example its forced.

				$this->init();
            }
            
            public function init() {
                // Load the settings API
				$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
				$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

				// Save settings in admin if you have any defined
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            public function calculate_shipping($package = array()) {
                $billing_city       = WC()->customer->get_billing_city();
                $billing_state      = WC()->customer->get_billing_state();
                // $total_weight = WC()->cart->cart_contents_weight;
                 error_log(WC()->cart->cart_contents_weight  , 0);
                 $total_weight = '40'; //Cân nặng hàng hoá
                 //Địa chỉ lấy hàng
                $shipPrice = CalShippingPrice($billing_city,"Hồ Chí Minh","TP Hồ Chí Minh","Quận Bình Thạnh","Phường 3","Đường Nguyễn Duy","63",$total_weight,'none');
                $rate = array(
					'label' => $this->title,
					'cost' => $shipPrice
				);

				// Register the rate
				$this->add_rate( $rate );
                
            }
            
         }
     }
 }
 
 
 // Disable zip/postcode field
add_filter( 'woocommerce_checkout_fields' , 'QuadLayers_remove_billing_postcode_checkout' );
function QuadLayers_remove_billing_postcode_checkout( $fields ) {
unset($fields['billing']['billing_postcode']);
return $fields;
}


 
 add_filter( 'woocommerce_shipping_methods', 'add_phatjk_method');
 
 function add_phatjk_method( $methods ) {
    $methods['techipress_dhl_shipping'] = 'WC_PHATJK_SHIPPING';
    return $methods;
 }
function CalShippingPrice($_district,$_province,$_pick_province,$_pick_district,$_pick_ward,$_pick_street,$_pick_address,$_weight,$_deliver_option,$defaultPrice = 20000){
$data = array(
    "pick_province" => $_pick_province,
    "pick_district" => $_pick_district,
    "province" => $_province,
    "district" => $_district,
    "weight" => $_weight
);
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/fee?" . http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
        "Token: {api}",// API KEY GHTK
    ),
));

$result = curl_exec($curl);

if (curl_errno($curl)) {
    return $defaultPrice;
}
try{
  $p = json_decode($result, true)['fee']['fee'];
   return $p;
    }
    catch(Exception $e){
        return $defaultPrice;
    }
curl_close($curl);

}
