<?php
/*
Plugin Name: _International Shipping Disclaimer
Description: International Shipping Disclaimer on checkout
Version: 1.1
*/


/**
 * Add international disclaimer checkboxes
 */
add_action( 'woocommerce_review_order_before_payment', 'international_checkout_fields' );

function international_checkout_fields() {

    echo '<div id="international_checkout_field"><h3><p>International Shipping</h3>Isodiol International ("ISO") will take all necessary precautions to protect your order from damages, theft and general harm. However, ISO will not be held responsible for loss, damage and or shipments held by regulatory authorities.</p>';

    woocommerce_form_field( 'international_loss', array(
        'type'          => 'checkbox',
        'class'         => array('intl_agree'),
        'label'         => __('ISO shall not be responsible for any loss or damage to the merchandise and/or injury to any third party and held by regulatory authorities.'),
        'required'   => true,
        ));

    woocommerce_form_field( 'international_release', array(
        'type'          => 'checkbox',
        'class'         => array('intl_agree'),
        'label'         => __('I release ISO and their representatives of any and all liabilities for damages and regulatory holdings that may occur during the shipping of my package. Damages include but are not limited to loss, damage and regulatory authority intervention while the package is in transit to my location.'),
        'required'   => true,
        ));

    woocommerce_form_field( 'international_taxes', array(
        'type'          => 'checkbox',
        'class'         => array('intl_agree'),
        'label'         => __('I acknowledge that I am responsible for all international taxes and duties incurred on these products.'),
        'required'   => true,
        ));

    echo '</div>';
	
	?>
	<script>
		jQuery('#shipping_country').change(function() {
			if ( jQuery(this).val() != 'US' ) {
				jQuery('#international_checkout_field').show(150);
			} else {
				jQuery('#international_checkout_field').hide(150);
			}
		});
</script>
	
	<?php
	
	echo '<h3>Payment Method</h3>';

}
add_action('woocommerce_checkout_process', 'international_checkout_field_process');

function international_checkout_field_process() {
	if ( $_POST['shipping_country'] != 'US' ) {
		if ( ! $_POST['international_loss'] )
			wc_add_notice( __( 'Please agree to the Loss or Damage disclaimer.' ), 'error' );
		if ( ! $_POST['international_release'] )
			wc_add_notice( __( 'Please agree to the International Shipping Release.' ), 'error' );
		if ( ! $_POST['international_taxes'] )
			wc_add_notice( __( 'Please agree to the Taxes and Duties disclaimer.' ), 'error' );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'international_checkout_field_update_order_meta' );

function international_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['international_loss'] ) ) {
        update_post_meta( $order_id, '_international_loss', 'Agreed - '.current_time( 'mysql' ) );
    }
    if ( ! empty( $_POST['international_release'] ) ) {
        update_post_meta( $order_id, '_international_release', 'Agreed - '.current_time( 'mysql' ) );
    }
    if ( ! empty( $_POST['international_taxes'] ) ) {
        update_post_meta( $order_id, '_international_taxes', 'Agreed - '.current_time( 'mysql' ) );
    }
}
?>
