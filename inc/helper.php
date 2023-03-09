<?php
/**
 * Helper.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;

/**
 * Helper
 *
 * @since x.x.x
 */
class Helper {

	use Get_Instance;

	/**
	 * Keep default values of all settings.
	 *
	 * @var array
	 * @since x.x.x
	 */
	public function get_defaults() {
		return [
			FRAKTJAKT_SHIPPING_FOR_DOKAN_SETTINGS            => [
				'ordering'       => 'ASC',
				'currency'       => '$',
				'selection'      => 'multiple',
				'page_per_limit' => 10,
				'main_title'     => __( '', 'fraktjakt-shipping-for-dokan' ),
				'button_label'   => __( '', 'fraktjakt-shipping-for-dokan' ),
			],
			FRAKTJAKT_SHIPPING_FOR_DOKAN_APPEARANCE_SETTINGS => [
				'primary_bg_color'   => '#ECECEE',
				'primary_font_color' => '#000',
			],
		];
	}

	/**
	 * Get option value from database and retruns value merged with default values
	 *
	 * @param string $option option name to get value from.
	 * @return array
	 * @since x.x.x
	 */
	public function get_option( $option ) {
		$db_values = get_option( $option, [] );
		return wp_parse_args( $db_values, $this->get_defaults()[ $option ] );
	}
}
