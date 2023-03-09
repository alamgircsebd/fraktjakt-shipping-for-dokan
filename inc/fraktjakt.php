<?php
/**
 * Fraktjakt.
 *
 * @package fraktjakt-shipping-for-dokan
 * @since x.x.x
 */

namespace FraktjaktShippingForDokan\Inc;

use FraktjaktShippingForDokan\Inc\Traits\Get_Instance;

/**
 * Fraktjakt Class
 *
 * @since x.x.x
 */
class Fraktjakt {

	use Get_Instance;

	/**
	 * Get setting option data.
	 *
	 * @since x.x.x
	 *
	 * @param string $option Option name.
	 * @param string $section Option section.
	 * @param string $default Default value.
	 */
	public function get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		$helper  = Helper::get_instance();

		if ( isset( $options[ $option ] ) ) {
			return '' === $options[ $option ] ? $default : $options[ $option ];
		}

		if ( empty( $default ) && isset( $helper->get_option( $section )[ $option ] ) ) {
			return $helper->get_option( $section )[ $option ];
		}

		return $default;
	}

	/**
	 * Check script enabled.
	 *
	 * @since x.x.x
	 */
	public function is_global_enabled() {
		return true;
	}
}
