<?php
/**
 * Scripts.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;
use FraktjaktShippingForDokan\Inc\Fraktjakt;

/**
 * Scripts
 *
 * @since x.x.x
 */
class Scripts extends Fraktjakt {

	use Get_Instance;

	/**
	 * Plugin version.
	 *
	 * @var string $version Current plugin version.
	 */
	public $version;

	/**
	 * Folder suffix.
	 *
	 * @var string $folder_suffix Select script folder.
	 */
	public $folder_suffix;

	/**
	 * File suffix.
	 *
	 * @var string $file_suffix Select script file.
	 */
	public $file_suffix;

	/**
	 * Constructor
	 *
	 * @since x.x.x
	 */
	public function __construct() {
		$this->version       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : FRAKTJAKT_SHIPPING_FOR_DOKAN_VER;
		$this->folder_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : 'min-';
		$this->file_suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'dynamic_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ] );
	}

	/**
	 * Dynamic styles
	 *
	 * @since x.x.x
	 *
	 * @return void
	 */
	public function dynamic_styles() {
		if ( ! $this->is_global_enabled() ) {
			return;
		}

		$dynamic_css  = ':root {';
		$dynamic_css .= "
		--fraktjakt-shipping-for-dokan-primary-background-color: {$this->get_option( 'primary_bg_color', FRAKTJAKT_SHIPPING_FOR_DOKAN_APPEARANCE_SETTINGS )};
		--fraktjakt-shipping-for-dokan-primary-font-color: {$this->get_option( 'primary_font_color', FRAKTJAKT_SHIPPING_FOR_DOKAN_APPEARANCE_SETTINGS )};
		";

		$dynamic_css .= '}';

		wp_add_inline_style( 'fraktjakt-shipping-for-dokan-css', $dynamic_css );
	}

	/**
	 * Admin enqueue scripts
	 *
	 * @since x.x.x
	 *
	 * @return void
	 */
	public function admin_styles() {
		wp_register_style( 'fraktjakt-shipping-for-dokan-admin-css', FRAKTJAKT_SHIPPING_FOR_DOKAN_URL . 'assets/' . $this->folder_suffix . 'css/admin-styles' . $this->file_suffix . '.css', [], $this->version );
		wp_enqueue_style( 'fraktjakt-shipping-for-dokan-admin-css' );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since x.x.x
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_global_enabled() ) {
			return;
		}

		wp_register_style( 'fraktjakt-shipping-for-dokan-css', FRAKTJAKT_SHIPPING_FOR_DOKAN_URL . 'assets/' . $this->folder_suffix . 'css/styles' . $this->file_suffix . '.css', [], $this->version );
		wp_enqueue_style( 'fraktjakt-shipping-for-dokan-css' );

		wp_register_script( 'fraktjakt-shipping-for-dokan-js', FRAKTJAKT_SHIPPING_FOR_DOKAN_URL . 'assets/' . $this->folder_suffix . 'js/scripts' . $this->file_suffix . '.js', [ 'jquery' ], $this->version, true );
		wp_enqueue_script( 'fraktjakt-shipping-for-dokan-js' );

		wp_localize_script(
			'fraktjakt-shipping-for-dokan-js',
			'fraktjakt_shipping_for_dokan_ajax_object',
			apply_filters(
				'fraktjakt_shipping_for_dokan_localize_script_args',
				[
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'    => wp_create_nonce( 'fraktjakt_shipping_for_dokan_ajax_nonce' ),
					'general_error' => __( 'Sometings wrong! try again later', 'fraktjakt-shipping-for-dokan' ),
				]
			)
		);
	}
}
