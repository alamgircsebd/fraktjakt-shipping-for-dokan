<?php
/**
 * Settings.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;
use FraktjaktShippingForDokan\Inc\Fraktjakt;

/**
 * Settings Class
 *
 * @since x.x.x
 */
class Settings extends Fraktjakt {

	use Get_Instance;

	/**
     * Hooks class construct
     *
     * @since 1.0.0
     */
    public function __construct(){
        add_filter( 'dokan_get_dashboard_settings_nav', [ $this, 'load_settings_menu' ], 99 );
        add_filter( 'dokan_dashboard_settings_heading_title', array( $this, 'load_settings_header' ), 10, 2 );
        add_filter( 'dokan_dashboard_settings_helper_text', array( $this, 'load_settings_helper_text' ), 10, 2 );
        add_action( 'dokan_render_settings_content', array( $this, 'load_settings_content' ), 10 );
        add_action( 'template_redirect', [ $this, 'save_settings' ], 12 );
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

    /**
     * Load Settings Content
     *
     * @since 1.0.0
     *
     * @param  array $query_vars
     *
     * @return void
     */
    public function load_settings_content( $query_vars ) {
        if ( isset( $query_vars['settings'] ) && $query_vars['settings'] == 'fraktjakt' ) {
             if ( ! current_user_can( 'dokan_view_store_social_menu' ) ) {
                dokan_get_template_part('global/dokan-error', '', array( 'deleted' => false, 'message' => __( 'You have no permission to view this page', 'fraktjakt-shipping-for-dokan' ) ) );
            } else {
                $vendor_id = (int) dokan_get_current_user_id();
                $settings  = get_user_meta( $vendor_id, '_dokan_vendor_fraktjakt_settings', true );

                fraktjakt_shipping_for_dokan_get_template_part(
                    'settings/fraktjakt',
                    '',
                    [
                        'fraktjakt_settings' => $settings
                    ]
                );
            }
        }
    }

    /**
     * Load Settings Header
     *
     * @since 1.0.0
     *
     * @param  string $header
     * @param  array $query_vars
     *
     * @return string
     */
    public function load_settings_header( $header, $query_vars ) {
        if ( $query_vars == 'fraktjakt' ) {
            $header = __( 'Fraktjakt Settings', 'fraktjakt-shipping-for-dokan' );
        }

        return $header;
    }

    /**
     * Load Settings page helper
     *
     * @since 1.0.0
     *
     * @param  string $help_text
     * @param  array $query_vars
     *
     * @return string
     */
    public function load_settings_helper_text( $help_text, $query_vars ) {
        if ( $query_vars == 'fraktjakt' ) {
            $help_text = __( 'Fraktjakt profiles help you to gain more trust.', 'fraktjakt-shipping-for-dokan' );
        }

        return $help_text;
    }

    /**
     * Load Settings Menu
     *
     * @since 1.0.0
     *
     * @param  array $sub_settins
     *
     * @return array
     */
    public function load_settings_menu( $sub_settins ) {
        if ( $this->is_global_enabled() ) {
            $sub_settins['fraktjakt'] = array(
                'title'      => __( 'Fraktjakt', 'fraktjakt-shipping-for-dokan' ),
                'icon'       => '<i class="fas fa-truck"></i>',
                'url'        => dokan_get_navigation_url( 'settings/fraktjakt' ),
                'pos'        => 90,
            );
        }

        return $sub_settins;
    }
}
