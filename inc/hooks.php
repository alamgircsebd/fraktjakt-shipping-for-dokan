<?php
/**
 * Hooks.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;
use FraktjaktShippingForDokan\Inc\Fraktjakt;

/**
 * Hooks Class
 *
 * @since x.x.x
 */
class Hooks extends Fraktjakt {

	use Get_Instance;

	/**
     * Hooks class construct
     *
     * @since 1.0.0
     */
    public function __construct(){
        add_filter( 'woocommerce_checkout_get_value', [ $this, 'get_checkout_get_value' ], 10, 2 );
        add_action( 'woocommerce_review_order_after_shipping', [ $this, 'agent_selection_link' ] );
        add_filter( 'woocommerce_email_order_meta', [ $this, 'custom_woocommerce_email_order_meta' ], 10, 3 );
        add_action( 'wp_ajax_fraktjakt_create_order_connection', [ $this, 'create_order_connection' ] );
        add_action( 'dokan_order_inside_content', [ $this, 'create_order_connection_vendor_dashboard' ] );
        add_filter( 'woocommerce_admin_order_actions', [ $this, 'fraktjakt_order_actions' ], 10, 2 );
        add_action( 'add_meta_boxes', [ $this, 'fraktjakt_order_meta_box' ] );
        add_action( 'dokan_order_detail_after_order_general_details', [ $this, 'order_meta_box_content_vendor_dashboard' ] );
        add_action( 'woocommerce_view_order', [ $this, 'order_meta_box_content_view_order' ], 11 );
    }

    /**
     * Fraktjakt order meta box content customer dashboard
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function order_meta_box_content_view_order( $order_id ) {
        $order                   = wc_get_order( $order_id );
        $fraktjakt_tracking_link = get_post_meta( $order->get_id(), 'fraktjakt_tracking_link', true);

        if ( empty( $fraktjakt_tracking_link ) ) {
            return;
        }
        ?>
        <div class="" style="width:100%">
            <div class="dokan-panel dokan-panel-default">
                <div class="dokan-panel-heading"><strong><?php esc_html_e( 'Fraktjakt', 'fraktjakt-shipping-for-dokan' ); ?></strong></div>
                <div class="dokan-panel-body" id="dokan-fraktjakt-notes">
                    <?php
                    if ( ! empty( $fraktjakt_tracking_link ) ) {
                        echo '&nbsp; <input id="fraktjakt_trace_button" class="button dokan-btn dokan-btn-default" type="button" value="'.__( 'Trace shipment', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Trace the shipment', 'fraktjakt-shipping-for-dokan' ).'">';    
                        echo "<script type=\"text/javascript\" >
                            jQuery('#fraktjakt_trace_button').click(function($) {
                                var data = {
                                    'action': 'fraktjakt-trace-shipment',
                                    'postId': '".$order->get_id()."' 
                                };
                                window.open('".$fraktjakt_tracking_link."','_blank');
                            });
                        </script>"; 
                    } 
                    ?>
                    </div>
                </div>
            </div>
        <?php
   }

    /**
     * Fraktjakt order meta box content vendor dashboard
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function order_meta_box_content_vendor_dashboard( $order ) {
        //Get the Fraktjakt order_id
        $fraktjakt_order_id      = get_post_meta( $order->get_id(), 'fraktjakt_order_id', true); //36250;
        $fraktjakt_shipment_id   = get_post_meta( $order->get_id(), 'fraktjakt_shipment_id', true); 
        $fraktjakt_access_code   = get_post_meta( $order->get_id(), 'fraktjakt_access_code', true); 
        $fraktjakt_access_link   = get_post_meta( $order->get_id(), 'fraktjakt_access_link', true); 
        $fraktjakt_tracking_link = get_post_meta( $order->get_id(), 'fraktjakt_tracking_link', true); 
        
        $fraktjakt_shipping_method_settings = get_option( 'woocommerce_dokan_fraktjakt_shipping_method_settings' );
        $testmode = $fraktjakt_shipping_method_settings['test_mode'];
        
        if ($testmode == 'test') {
            $uri = 'https://testapi.fraktjakt.se/';
        } else {
            $uri = 'https://api.fraktjakt.se/';
        }
        ?>
        <div class="" style="width:100%; margin: 20px 0px;">
            <div class="dokan-panel dokan-panel-default">
                <div class="dokan-panel-heading"><strong><?php esc_html_e( 'Fraktjakt', 'fraktjakt-shipping-for-dokan' ); ?></strong></div>
                <div class="dokan-panel-body" id="dokan-fraktjakt-notes">
                <?php
                // Fraktjakt access link
                if (!empty($fraktjakt_access_link)) {
                    // Fraktjakt button to order
                    echo '<input id="fraktjakt_order_button" class="button-primary dokan-btn dokan-btn-default" type="button" value="'.__( 'Manage shipment', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Manage the order in Fraktjakt', 'fraktjakt-shipping-for-dokan' ).'">';    
                    echo "<script type=\"text/javascript\" >
                        jQuery('#fraktjakt_order_button').click(function($) {
                            var data = {
                                'action': 'fraktjakt-access-shipment',
                                'postId': '".$order->get_id()."' 
                            };
                        window.open('".$fraktjakt_access_link."','_blank');
                        });
                    </script>"; 
                } 
                
                // Fraktjakt shipment
                if (empty($fraktjakt_access_link) && !empty($fraktjakt_shipment_id) && $fraktjakt_access_code != '') {
                    // Fraktjakt button to shipment
                    echo "<input id=\"fraktjakt_shipment_button\" class=\"button-primary dokan-btn dokan-btn-default\" type=\"button\" value=\"".__( 'Manage shipment', 'fraktjakt-shipping-for-dokan' )."\" title=\"".__( 'Manage the shipment in Fraktjakt', 'fraktjakt-shipping-for-dokan' )."\">";
                    echo "<script type=\"text/javascript\" >
                        jQuery('#fraktjakt_shipment_button').click(function($) {
                            var data = {
                                'action': 'fraktjakt-access-shipment',
                                'postId': '".$order->get_id()."' 
                            };
                            window.open('".$uri."shipments/show/".join('',$fraktjakt_shipment_id)."?access_code=".join('',$fraktjakt_access_code)."','_blank'); 
                        });
                    </script>";    
                }
            
                // Fraktjakt trace link
                if (!empty($fraktjakt_tracking_link)) {
                    // Fraktjakt button to order
                    echo '&nbsp; <input id="fraktjakt_trace_button" class="button dokan-btn dokan-btn-default" type="button" value="'.__( 'Trace shipment', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Trace the shipment', 'fraktjakt-shipping-for-dokan' ).'">';    
                    echo "<script type=\"text/javascript\" >
                        jQuery('#fraktjakt_trace_button').click(function($) {
                            var data = {
                                'action': 'fraktjakt-trace-shipment',
                                'postId': '".$order->get_id()."' 
                            };
                        window.open('".$fraktjakt_tracking_link."','_blank');
                        });
                    </script>"; 
                } 
            
                if (empty($fraktjakt_access_link) && empty($fraktjakt_shipment_id)) {
                    echo __( 'Order connection missing', 'fraktjakt-shipping-for-dokan' );
                    echo '&nbsp; <a href="' . esc_url( wp_nonce_url( add_query_arg( [ 'order_id' => $order->get_id(), 'fraktjakt_create_order_connection' => 'yes' ], dokan_get_navigation_url( 'orders' ) ), 'dokan_view_order' ) ) . '"><input id="fraktjakt_create_button" class="button dokan-btn dokan-btn-default" type="button" value="'.__( 'Create order connection', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Create order connection to Fraktjakt', 'fraktjakt-shipping-for-dokan' ).'"></a>';    
                } 
                ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Fraktjakt order meta box
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function fraktjakt_order_meta_box() {
        add_meta_box(
            'fraktjakt_woocommerce_shipping_method-order-button',
            __( 'Fraktjakt', 'fraktjakt-shipping-for-dokan' ),
            [ $this, 'fraktjakt_order_meta_box_content' ],
            'shop_order',
            'side',
            'default'
        );
    }

    /**
     * Fraktjakt order meta box content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function fraktjakt_order_meta_box_content() {
        global $woocommerce, $post;

        $order                   = new \WC_Order($post->ID);
        $fraktjakt_order_id      = get_post_meta( $order->get_id(), 'fraktjakt_order_id', true); //36250;
        $fraktjakt_shipment_id   = get_post_meta( $order->get_id(), 'fraktjakt_shipment_id', true); 
        $fraktjakt_access_code   = get_post_meta( $order->get_id(), 'fraktjakt_access_code', true); 
        $fraktjakt_access_link   = get_post_meta( $order->get_id(), 'fraktjakt_access_link', true); 
        $fraktjakt_tracking_link = get_post_meta( $order->get_id(), 'fraktjakt_tracking_link', true); 
        $method_settings         = get_option( 'woocommerce_dokan_fraktjakt_shipping_method_settings' );
        $testmode                = $method_settings['test_mode'];
        
        if ( $testmode == 'test') {
            $uri = 'https://testapi.fraktjakt.se/';
        } else {
            $uri = 'https://api.fraktjakt.se/';
        }
        
        // Fraktjakt access link
        if (!empty($fraktjakt_access_link)) {
                // Fraktjakt button to order
                echo '<input id="fraktjakt_order_button" class="button-primary" type="button" value="'.__( 'Manage shipment', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Manage the order in Fraktjakt', 'fraktjakt-shipping-for-dokan' ).'">';    
                echo "<script type=\"text/javascript\" >
                    jQuery('#fraktjakt_order_button').click(function($) {
                        var data = {
                            'action': 'fraktjakt-access-shipment',
                            'postId': '".$post->ID."' 
                        };
                      window.open('".$fraktjakt_access_link."','_blank');
                    });
                </script>"; 
        } 
        
        // Fraktjakt shipment
        if (empty($fraktjakt_access_link) && !empty($fraktjakt_shipment_id) && $fraktjakt_access_code != '') {
            // Fraktjakt button to shipment
            echo "<input id=\"fraktjakt_shipment_button\" class=\"button-primary\" type=\"button\" value=\"".__( 'Manage shipment', 'fraktjakt-shipping-for-dokan' )."\" title=\"".__( 'Manage the shipment in Fraktjakt', 'fraktjakt-shipping-for-dokan' )."\">";
            echo "<script type=\"text/javascript\" >
                jQuery('#fraktjakt_shipment_button').click(function($) {
                    var data = {
                        'action': 'fraktjakt-access-shipment',
                        'postId': '".$post->ID."' 
                    };
                    window.open('".$uri."shipments/show/".join('',$fraktjakt_shipment_id)."?access_code=".join('',$fraktjakt_access_code)."','_blank'); 
                });
            </script>";    
        }
    
        // Fraktjakt trace link
        if (!empty($fraktjakt_tracking_link)) {
                // Fraktjakt button to order
                echo '&nbsp; <input id="fraktjakt_trace_button" class="button" type="button" value="'.__( 'Trace shipment', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Trace the shipment', 'fraktjakt-shipping-for-dokan' ).'">';    
                echo "<script type=\"text/javascript\" >
                    jQuery('#fraktjakt_trace_button').click(function($) {
                        var data = {
                            'action': 'fraktjakt-trace-shipment',
                            'postId': '".$post->ID."' 
                        };
                      window.open('".$fraktjakt_tracking_link."','_blank');
                    });
                </script>"; 
        } 
    
        if (empty($fraktjakt_access_link) && empty($fraktjakt_shipment_id)) {
            echo __( 'Order connection missing', 'fraktjakt-shipping-for-dokan' );
            echo '&nbsp; <input id="fraktjakt_create_button" class="button" type="button" value="'.__( 'Create order connection', 'fraktjakt-shipping-for-dokan' ).'" title="'.__( 'Create order connection to Fraktjakt', 'fraktjakt-shipping-for-dokan' ).'">';    
            echo "<script type=\"text/javascript\" >
              jQuery('#fraktjakt_create_button').click(function($) {
                  var data = {
                      'action': 'view fraktjakt-create-order-connection',
                      'postId': '".$post->ID."' 
                  };
                  window.open('".add_query_arg( '_wpnonce', wp_create_nonce( 'action' ), 'admin-ajax.php?action=fraktjakt_create_order_connection&order_id=' . $order->get_id() )."','_top');
              });
                </script>";
        } 
    }

    /**
     * Fraktjakt order actions
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function fraktjakt_order_actions( $actions, $the_order ) {
        $method_settings = get_option( 'woocommerce_dokan_fraktjakt_shipping_method_settings' );
        $testmode        = $method_settings['test_mode'];
        
        if ($testmode == 'test') {
            $uri = 'https://testapi.fraktjakt.se/';
        } else {
            $uri = 'https://api.fraktjakt.se/';
        }
        
        $fraktjakt_order_id      = get_post_meta( $the_order->get_id(), 'fraktjakt_order_id', true);
        $fraktjakt_shipment_id   = get_post_meta( $the_order->get_id(), 'fraktjakt_shipment_id', true); 
        $fraktjakt_access_code   = get_post_meta( $the_order->get_id(), 'fraktjakt_access_code', true);
        $fraktjakt_access_link   = get_post_meta( $the_order->get_id(), 'fraktjakt_access_link', true);
        $fraktjakt_tracking_link = get_post_meta( $the_order->get_id(), 'fraktjakt_tracking_link', true);

        if ((!empty($fraktjakt_shipment_id) && $fraktjakt_access_code != '') || !empty($fraktjakt_access_link)) {
            $url = (!empty($fraktjakt_access_link) ? $fraktjakt_access_link : $uri."shipments/show/".$fraktjakt_shipment_id."?access_code=".$fraktjakt_access_code);
            
            if (!empty($fraktjakt_access_link)) {
                $manage = __( 'Manage shipment', 'fraktjakt-shipping-for-dokan' );
                $ikon   = "view fraktjakt-handle-shipment";
            } else {
                $manage = __( 'Manage shipment', 'fraktjakt-shipping-for-dokan' );
                $ikon   = "view fraktjakt-handle-shipment";
            }
            $actions['fraktjakt-view-shipment'] = array(
                'url'    => $url,
                'name'   => $manage,
                'action' => $ikon
            );
            if (!empty($fraktjakt_tracking_link)) {
                $track =__( 'Trace shipment', 'fraktjakt-shipping-for-dokan' );
                
                $actions['fraktjakt-track-shipment'] = array(
                    'url'    => $fraktjakt_tracking_link,
                    'name'   => $track,
                    'action' => "view fraktjakt-track-shipment"
                );
            }
                
        } else {
            $actions['fraktjakt-create--connection'] = array(
                'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=fraktjakt_create_order_connection&order_id=' . $the_order->get_id() ), 'fraktjakt-create-shipment' ),
                'name'   => __( 'Create order connection to Fraktjakt', 'fraktjakt-shipping-for-dokan' ), //tooltip
                'action' => "view fraktjakt-create-order-connection" //css classes (view is used to get correct button style)
            );
        }
        return $actions;
    }

    /**
     * Create order connection for vendor dashboard
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function create_order_connection_vendor_dashboard() {
        if ( isset( $_GET['fraktjakt_create_order_connection'] ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'dokan_view_order' ) ) {
            $order_id        = intval( wp_unslash( $_GET['order_id'] ) );  
            $order           = wc_get_order( $order_id );
            $get_status      = $order->get_status();
            $method_settings = get_option( 'woocommerce_dokan_fraktjakt_shipping_method_settings' );
            $trigger_state   = empty( $method_settings['trigger_state'] ) ? 'processing' : $method_settings['trigger_state'];
      
            if ( $get_status == 'wc-' . $trigger_state || $order->get_status() == $trigger_state ) {
                Dokan_Fraktjakt_api_selecter($order_id);
            }
        }
    }

    /**
     * Create order connection
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function create_order_connection() {
        $order_id = intval( $_GET['order_id'] );
    
        Dokan_Fraktjakt_api_selecter( $order_id );
        
        if ( dokan_fraktjakt_get_current_post_type() === 'wc-processing' ) {
            header("Location: ".admin_url("edit.php?post_status=wc-processing&post_type=shop_order"));
            echo '<html><head><meta http-equiv="refresh" content="0; url='.admin_url("edit.php?post_status=wc-processing&post_type=shop_order").'"></head></html>';
        } else {
            header("Location: ".admin_url("edit.php?post_type=shop_order"));
            echo '<html><head><meta http-equiv="refresh" content="0; url='.admin_url("edit.php?post_type=shop_order").'"></head></html>';
        }
        
        wp_die();
    }

    /**
     * Agent selection link
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function custom_woocommerce_email_order_meta( $order, $sent_to_admin, $plain_text ) {
        $tracking_link        = get_post_meta( $order->get_id(), 'fraktjakt_tracking_link', true );
        $fraktjakt_agent_link = get_post_meta( $order->get_id(), 'fraktjakt_agent_link', true );
        $agent_info           = get_post_meta( $order->get_id(), 'agent_info', true );
        $method_settings      = get_option('woocommerce_dokan_fraktjakt_shipping_method_settings');
        
        if (
            isset( $method_settings['enable_tracking_in_email'] ) &&
            ( $method_settings['enable_tracking_in_email'] == 'yes' ) &&
            ( ! empty( $tracking_link ) )
        ) {
            if ( $plain_text === false ) {
                echo "<div style='margin-bottom:40px'><h2>".__( 'Follow the shipment', 'fraktjakt-shipping-for-dokan' )."</h2>
                    <a href='".$tracking_link."' class='tracking_button button button-primary'>".__( 'Track your package', 'fraktjakt-shipping-for-dokan' )."</a>\n</div>\r\n";
            } else {
                echo __( 'Follow the shipment', 'fraktjakt-shipping-for-dokan' ).'\n
                '.__( 'Track your package', 'fraktjakt-shipping-for-dokan' ).'\n
                '.$tracking_link.'\n\n';	
            }
        }
        
        if (
            isset( $method_settings['enable_agent_email_selection'] ) &&
            ( $method_settings['enable_agent_email_selection'] === 'yes' ) &&
            ( ! empty( $fraktjakt_agent_link ) )
        ) {
            if ( empty( $agent_info ) ) {
                $agent_info = __( 'Select shipping agent', 'fraktjakt-shipping-for-dokan' );
            }

            if ( $plain_text === false ) {
                echo "<div style='margin-bottom:40px'><h2>".__( 'Shipping agent', 'fraktjakt-shipping-for-dokan' )."</h2>
                    <a href='".$fraktjakt_agent_link."' class='agent_button button'>".$agent_info."</a>\n<br />
                    ".__( 'If you need to change the selected shipping agent, then please do so before the shipment is created.', 'fraktjakt-shipping-for-dokan' )."\n</div>\r\n";
            } else {
                echo __( 'Shipping agent', 'fraktjakt-shipping-for-dokan' ).'\n
                    '.$agent_info.'\n
                    '.$fraktjakt_agent_link.'\n
                    '.__( 'If you need to change the selected shipping agent, then please do so before the shipment is created.', 'fraktjakt-shipping-for-dokan' )."\n\n";	
            }
        }

        return;
    }

    /**
     * Agent selection link
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function agent_selection_link() {
        if ( ! empty( $_COOKIE["fraktjakt_agent_selection_link"] ) ) {
            $fraktjakt_agent_selection_link = $_COOKIE["fraktjakt_agent_selection_link"].'&orig='.wc_get_checkout_url(); 
        };
        
        if ( ! empty( $_COOKIE["agent_link"] ) ) {
            $fraktjakt_agent_selection_link = $_COOKIE["agent_link"].'&orig='.wc_get_checkout_url();
        };
    
        $fraktjakt_shipping_method_settings = get_option('woocommerce_dokan_fraktjakt_shipping_method_settings');
        
        if (
            isset( $fraktjakt_shipping_method_settings['enable_agent_checkout_selection'] ) &&
            ( $fraktjakt_shipping_method_settings['enable_agent_checkout_selection'] == 'yes' ) &&
            ( $fraktjakt_shipping_method_settings['enable_frontend'] == 'yes' ) &&
            ( ! empty( $fraktjakt_agent_selection_link ) )
        ) {
            echo '<tr><td></td><td><p><a href="'.$fraktjakt_agent_selection_link.'" target="_top">'.__( 'Change shipping agent', 'fraktjakt-shipping-for-dokan' ).'</a></p></td></tr>';
        };
    }

    /**
     * Get checkout get value
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_checkout_get_value( $input_value, $input_key ) {
        if ( ! isset( $_POST[ 'post_data' ] ) ) {
            return $input_value;
        }

        $form_data = explode( "&", $_POST[ 'post_data' ] );
        
        foreach ( $form_data as $field_data ) {
            $field = explode( "=", $field_data );
            
            if ( count( $field ) <= 1 ) {
                continue;
            }
            
            if ( $input_key == $field[ 0 ] ) {
                return wc_clean( wp_unslash( $field[ 1 ] ) );
            }
        }

        return $input_value;
    }
}
