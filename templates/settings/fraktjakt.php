<?php
/**
 * Dokan Fraktjakt Shipping Template
 *
 * @since 1.0.0
 *
 * @package dokan
 */

$auto_process            = isset( $fraktjakt_settings['auto_process'] ) ? $fraktjakt_settings['auto_process'] : '';
$fraktjakt_prefix        = isset( $fraktjakt_settings['fraktjakt_prefix'] ) ? $fraktjakt_settings['fraktjakt_prefix'] : 'Order';
$order_reference         = isset( $fraktjakt_settings['order_reference'] ) ? $fraktjakt_settings['order_reference'] : 'order_number';
$consignor_id            = isset( $fraktjakt_settings['consignor_id'] ) ? $fraktjakt_settings['consignor_id'] : '';
$consignor_key           = isset( $fraktjakt_settings['consignor_key'] ) ? $fraktjakt_settings['consignor_key'] : '';
$integrator_code         = isset( $fraktjakt_settings['integrator_code'] ) ? $fraktjakt_settings['integrator_code'] : '';
$home_delivery_title     = isset( $fraktjakt_settings['home_delivery_title'] ) ? $fraktjakt_settings['home_delivery_title'] : 'Door-to-Door delivery';
$shipping_company        = isset( $fraktjakt_settings['shipping_company'] ) ? $fraktjakt_settings['shipping_company'] : 'yes';
$shipping_agent          = isset( $fraktjakt_settings['shipping_agent'] ) ? $fraktjakt_settings['shipping_agent'] : 'yes';
$estimated_delivery_time = isset( $fraktjakt_settings['estimated_delivery_time'] ) ? $fraktjakt_settings['estimated_delivery_time'] : 'yes';
?>
<?php do_action( 'dokan_fraktjakt_shipping_setting_start' ); ?>

<form method="post" id="dokan-fraktjakt-shipping-setting-form" action="" class="dokan-form-horizontal">

    <?php
    /**
     * Dokan Fraktjakt Setting Form Hook
     *
     * @since 1.0.0
     */
    do_action( 'dokan_fraktjakt_shipping_setting_form' );
    ?>
    <?php if ( empty( $consignor_id ) || empty( $consignor_key ) ) : ?>
        <p style="color:brown; text-align:left;"><?php esc_html_e( 'Register your', 'fraktjakt-shipping-for-dokan' ); ?> <a href="https://fraktjakt.se/shipper/register_company" target="_blank" title="https://fraktjakt.se/shipper/register_company" rel="nofollow ugc"><u><?php esc_html_e( 'free account on Fraktjakt', 'fraktjakt-shipping-for-dokan' ); ?></u></a> <?php esc_html_e( 'for Consignor ID & Key', 'fraktjakt-shipping-for-dokan' ); ?>.</p>
    <?php endif; ?>  

    <h4 class="dokan-text-left"><?php esc_html_e( 'General', 'fraktjakt-shipping-for-dokan' ); ?></h4>

    <div class="dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="fraktjakt_prefix"><?php esc_html_e( 'Reference prefix', 'fraktjakt-shipping-for-dokan' ); ?></label>
        <div class="dokan-w5 dokan-text-left">
            <input id="fraktjakt_prefix" value="<?php echo esc_attr( $fraktjakt_prefix ); ?>" name="fraktjakt_prefix" placeholder="<?php esc_attr_e( 'Reference prefix', 'fraktjakt-shipping-for-dokan' ); ?>" class="dokan-form-control input-md" type="text">
        </div>
    </div>

    <h4 class="dokan-text-left"><?php esc_html_e( 'Authentication', 'fraktjakt-shipping-for-dokan' ); ?></h4>
    <p class="dokan-text-left">
        <?php esc_html_e( 'Enter your Consignor ID and key from your Fraktjakt integration to connect this extension to your Fraktjakt account.', 'fraktjakt-shipping-for-dokan' ); ?>
        <br><a href="https://api.fraktjakt.se/webshops/change?consignor_id=<?php echo esc_attr( $consignor_id ); ?>&amp;consignor_key=<?php echo esc_attr( $consignor_key ); ?>" target="_blank"><u><?php esc_html_e( 'Direct link to Fraktjakt PROD API webshop settings', 'fraktjakt-shipping-for-dokan' ); ?></u></a>
    </p>
    
    <div class="dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="consignor_id"><?php esc_html_e( 'Consignor ID', 'fraktjakt-shipping-for-dokan' ); ?></label>
        <div class="dokan-w5 dokan-text-left">
            <input id="consignor_id" value="<?php echo esc_attr( $consignor_id ); ?>" name="consignor_id" placeholder="<?php esc_attr_e( 'Consignor ID', 'fraktjakt-shipping-for-dokan' ); ?>" class="dokan-form-control input-md" type="text">
        </div>
    </div>

    <div class="dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="consignor_key"><?php esc_html_e( 'Consignor Key', 'fraktjakt-shipping-for-dokan' ); ?></label>
        <div class="dokan-w5 dokan-text-left">
            <input id="consignor_key" value="<?php echo esc_attr( $consignor_key ); ?>" name="consignor_key" placeholder="<?php esc_attr_e( 'Consignor Key', 'fraktjakt-shipping-for-dokan' ); ?>" class="dokan-form-control input-md" type="text">
        </div>
    </div>

    <div class="dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="integrator_code"><?php esc_html_e( '(Integrator code)', 'fraktjakt-shipping-for-dokan' ); ?></label>
        <div class="dokan-w5 dokan-text-left">
            <input id="integrator_code" value="<?php echo esc_attr( $integrator_code ); ?>" name="integrator_code" placeholder="<?php esc_attr_e( 'Integrator code', 'fraktjakt-shipping-for-dokan' ); ?>" class="dokan-form-control input-md" type="text">
        </div>
    </div>

    <h4 class="dokan-text-left"><?php esc_html_e( 'Shipping alternatives in customer controlled shipping', 'fraktjakt-shipping-for-dokan' ); ?></h4>
    <p class="dokan-text-left"><?php esc_html_e( 'Choose which information to display about each shipping alternative in the shipping calculator, cart and checkout.', 'fraktjakt-shipping-for-dokan' ); ?></p>

    <div class="dokan-form-group">
        <label class="dokan-w3 dokan-control-label" for="home_delivery_title"><?php esc_html_e( 'Home delivery title', 'fraktjakt-shipping-for-dokan' ); ?></label>
        <div class="dokan-w5 dokan-text-left">
            <input id="home_delivery_title" value="<?php echo esc_attr( $home_delivery_title ); ?>" name="home_delivery_title" placeholder="<?php esc_attr_e( 'Home delivery title', 'fraktjakt-shipping-for-dokan' ); ?>" class="dokan-form-control input-md" type="text">
            <p class="dokan-text-left"><?php esc_html_e( 'Only shipping products which include Door-to-Door delivery will display this text. Displayed in the shipping alternatives customers see in the cart and in checkout.', 'fraktjakt-shipping-for-dokan' ); ?></p>
        </div>
    </div>

    <div class="dokan-form-group">
        <div class="dokan-w4 dokan-text-left">

            <?php wp_nonce_field( 'dokan_fraktjakt_shipping_settings', 'dokan_fraktjakt_shipping_settings_nonce' ); ?>
            <input type="submit" name="dokan_update_fraktjakt_shipping_settings" class="dokan-btn dokan-btn-danger dokan-btn-theme" value="<?php esc_attr_e( 'Update Settings', 'fraktjakt-shipping-for-dokan' ); ?>">
        </div>
    </div>
</form><!-- .fraktjakt-shipping-setting-form -->

<?php do_action( 'dokan_fraktjakt_shipping_setting_end' ); ?>

