<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */
 
get_header();

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields1' );
function custom_override_checkout_fields1( $fields ) {
	unset($fields['order']['order_comments']);
    return $fields;
}

/* WooCommerce: The Code Below Removes The Additional Information Tab */
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}

add_filter('woocommerce_product_additional_information_heading', 'isa_product_additional_information_heading');
function isa_product_additional_information_heading() {
    echo '';
}

add_filter("woocommerce_checkout_fields", "custom_override_checkout_fields2", 1);
function custom_override_checkout_fields2($fields) {
    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_email']['priority'] = 3;
    $fields['billing']['billing_phone']['priority'] = 4;
    $fields['billing']['billing_company']['priority'] = 10;
    $fields['billing']['billing_country']['priority'] = 11;
    $fields['billing']['billing_state']['priority'] = 5;
    $fields['billing']['billing_address_1']['priority'] = 6;
    $fields['billing']['billing_address_2']['priority'] = 7;
    $fields['billing']['billing_city']['priority'] = 8;
    $fields['billing']['billing_postcode']['priority'] = 9;
    return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'custom_override_default_locale_fields' );
function custom_override_default_locale_fields( $fields ) {
    $fields['email']['priority'] = 3;
    $fields['address_1']['priority'] = 6;
    $fields['address_2']['priority'] = 7;
    return $fields;
}

?>


<form name="checkout" method="post" class="checkout checkout_layout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	
	<div class="checkout-sidebar">
		<div class="checkout-sidebar-item">
			<img src="https://forcefactor.me/assets/images/product/aspire.png">
		</div>
		<div class="checkout-sidebar-item">
			<h3>What Our Members Are Saying...</h3>
		</div>
		<div class="checkout-sidebar-item">
			<img src="https://forcefactor.me/assets/images/testimonial/checkout.jpg">
		</div>
		<div class="checkout-sidebar-item">
			<h3>HERE'S WHAT YOU ARE GETTING...</h3>
			<ul class="check-list">
				<li>Access to our private online membership site.</li>
				<li>Eye-opening video success training that provides simple, step by step rules for getting your online business up and running.</li>
				<li>Access to your own personal team of coaches who are ready to work one-on-one with you to help you achieve success.</li>
				<li>Exceptional support. We care about your results, so we have provided live chat and email support so you can get the answers you need when you need them.</li>
				<li>And more than a dozen other high-value online business resources that will provide the motivation, the tools and the know-how you need to get to the next level in your business.</li>
			</ul>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-1">
				<h4>100% MONEY BACK GUARANTEE</h4>
				<p>If you decide that it's not for you, just let us know and you'll be issued a full and prompt refund, no questions asked.</p>
			</div>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-2">
				<h4>YOUR INFORMATION IS SAFE</h4>
				<p>We will not sell, rent, or share your contact information for any reason.</p>
			</div>
		</div>
		<div class="checkout-sidebar-item">
			<div class="checkout-icon-box checkout-icon-box-3">
				<h4>YOUR INFORMATION IS SAFE</h4>
				<p>All information is encrypted and transmitted without risk using a SSL protocol.</p>
			</div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

