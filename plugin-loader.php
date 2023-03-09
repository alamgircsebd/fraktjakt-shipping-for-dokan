<?php
/**
 * Plugin Loader.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan;

use FraktjaktShippingForDokan\Inc\Scripts;
use FraktjaktShippingForDokan\Inc\Settings;
use FraktjaktShippingForDokan\Inc\Hooks;

/**
 * Plugin_Loader
 *
 * @since x.x.x
 */
class Plugin_Loader {

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class Instance.
	 * @since x.x.x
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since x.x.x
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Autoload classes.
	 *
	 * @param string $class class name.
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class_to_load
			)
		);

		$file = FRAKTJAKT_SHIPPING_FOR_DOKAN_DIR . $filename . '.php';

		// if the file redable, include it.
		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}

	/**
	 * Constructor
	 *
	 * @since x.x.x
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', [ $this, 'load_classes' ] );
		add_filter( 'plugin_action_links_' . FRAKTJAKT_SHIPPING_FOR_DOKAN_BASE, [ $this, 'action_links' ] );
		register_activation_hook( FRAKTJAKT_SHIPPING_FOR_DOKAN_FILE, [ $this, 'activate' ] );
	}

	/**
	 * Create roles on plugin activation.
	 *
	 * @return void
	 */
	public function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Load Plugin Text Domain.
	 * This will load the translation textdomain depending on the file priorities.
	 *      1. Global Languages /wp-content/languages/fraktjakt-shipping-for-dokan/ folder
	 *      2. Local dorectory /wp-content/plugins/fraktjakt-shipping-for-dokan/languages/ folder
	 *
	 * @since x.x.x
	 * @return void
	 */
	public function load_textdomain() {
		// Default languages directory.
		$lang_dir = FRAKTJAKT_SHIPPING_FOR_DOKAN_DIR . 'languages/';

		/**
		 * Filters the languages directory path to use for plugin.
		 *
		 * @param string $lang_dir The languages directory path.
		 */
		$lang_dir = apply_filters( 'wpb_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter.
		global $wp_version;

		$get_locale = get_locale();

		if ( $wp_version >= 4.7 ) {
			$get_locale = get_user_locale();
		}

		/**
		 * Language Locale for plugin
		 *
		 * @var $get_locale The locale to use.
		 * Uses get_user_locale()` in WordPress 4.7 or greater,
		 * otherwise uses `get_locale()`.
		 */
		$locale = apply_filters( 'plugin_locale', $get_locale, 'fraktjakt-shipping-for-dokan' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'fraktjakt-shipping-for-dokan', $locale );

		// Setup paths to current locale file.
		$mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;
		$mofile_local  = $lang_dir . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/fraktjakt-shipping-for-dokan/ folder.
			load_textdomain( 'fraktjakt-shipping-for-dokan', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/fraktjakt-shipping-for-dokan/languages/ folder.
			load_textdomain( 'fraktjakt-shipping-for-dokan', $mofile_local );
		} else {
			// Load the default language files.
			load_plugin_textdomain( 'fraktjakt-shipping-for-dokan', false, $lang_dir );
		}
	}

	/**
	 * Loads plugin classes as per requirement.
	 *
	 * @return void
	 * @since X.X.X
	 */
	public function load_classes() {
		Scripts::get_instance();
		Settings::get_instance();
		Hooks::get_instance();
	}

	/**
	 * Adds links in Plugins page
	 *
	 * @param array $links existing links.
	 * @return array
	 * @since x.x.x
	 */
	public function action_links( $links ) {
		$plugin_links = apply_filters(
			'fraktjakt_shipping_for_dokan_action_links',
			[
				'fraktjakt_shipping_for_dokan_settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=dokan_fraktjakt_shipping_method' ) . '">' . __( 'Settings', 'fraktjakt-shipping-for-dokan' ) . '</a>',
			]
		);

		return array_merge( $plugin_links, $links );
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Plugin_Loader::get_instance();
