<?php
/**
 * Shipping.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;
use FraktjaktShippingForDokan\Inc\Fraktjakt;

/**
 * Shipping Class
 *
 * @since x.x.x
 */
class Shipping extends Fraktjakt {

	use Get_Instance;

	/**
     * Hooks class construct
     *
     * @since 1.0.0
     */
    public function __construct(){
        //add_action( 'template_redirect', [ $this, 'save_settings' ], 12 );
    }

    /**
     * Load Settings Content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function save_settings() {
        if (
            ! isset( $_POST['dokan_fraktjakt_shipping_settings_nonce'] ) ||
            ! wp_verify_nonce( sanitize_key( $_POST['dokan_fraktjakt_shipping_settings_nonce'] ), 'dokan_fraktjakt_shipping_settings' )
        ) {
            return;
        }

        $data      = [];
        $vendor_id = (int) dokan_get_current_user_id();

        $data['fraktjakt_prefix']    = isset( $_POST['fraktjakt_prefix'] ) ? sanitize_text_field( wp_unslash( $_POST['fraktjakt_prefix'] ) ) : '';
        $data['consignor_id']        = isset( $_POST['consignor_id'] ) ? sanitize_text_field( wp_unslash( $_POST['consignor_id'] ) ) : '';
        $data['consignor_key']       = isset( $_POST['consignor_key'] ) ? sanitize_text_field( wp_unslash( $_POST['consignor_key'] ) ) : '';
        $data['integrator_code']     = isset( $_POST['integrator_code'] ) ? sanitize_text_field( wp_unslash( $_POST['integrator_code'] ) ) : '';
        $data['home_delivery_title'] = isset( $_POST['home_delivery_title'] ) ? sanitize_text_field( wp_unslash( $_POST['home_delivery_title'] ) ) : '';

        // Saving metas
        update_user_meta( $vendor_id, '_dokan_vendor_fraktjakt_settings', $data );
    }
}
