<?php
/**
 * Centralized options accessor for Fathom Analytics Conversions.
 *
 * Replaces the use of `global $fac4wp_options` throughout the plugin
 * with a static class that caches options and provides a clean API.
 *
 * @package Fathom_Analytics_Conversions\Options
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

class FAC_Options {

	/**
	 * Cached options array.
	 *
	 * @var array|null
	 */
	private static $options = null;

	/**
	 * Get all plugin options.
	 *
	 * @param bool $force Force reload from database.
	 *
	 * @return array
	 */
	public static function get_all( $force = false ) {
		if ( null === self::$options || $force ) {
			self::$options = fac4wp_reload_options( $force );
		}

		return self::$options;
	}

	/**
	 * Get a single option value.
	 *
	 * @param string $key     The option key.
	 * @param mixed  $default Default value if key doesn't exist.
	 *
	 * @return mixed
	 */
	public static function get( $key, $default = '' ) {
		$options = self::get_all();

		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}

	/**
	 * Reload options from the database.
	 *
	 * @return array The refreshed options.
	 */
	public static function reload() {
		self::$options = null;

		return self::get_all( true );
	}
}
