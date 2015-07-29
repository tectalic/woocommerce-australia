<?php
/*
Plugin Name: WooCommerce Australia
Plugin URI: https://github.com/OM4/woocommerce-australia
Description: Improvements for Australian-based stores using WooCommerce. Only required if running WooCommerce 2.2.x or older.
Version: 1.3
Author: OM4
Author URI: https://om4.com.au/
Text Domain: woocommerce-australia
Git URI: https://github.com/OM4/woocommerce-australia
Git Branch: release
License: GPLv2
*/

/*
Copyright 2014-2015 OM4 (email: info@om4.com.au    web: http://om4.com.au/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'WC_Australia' ) ) {

	/**
	 * This class is a singleton.
	 *
	 * Class WC_Australia
	 */
	class WC_Australia {

		/**
		 * Refers to a single instance of this class
		 */
		private static $instance = null;

		private $selling_to_au_only = false;

		/**
		 * Creates or returns an instance of this class
		 * @return WC_Australia A single instance of this class
		 */
		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		}

		/**
		 * If WooCommerce is active, initialise this plugin
		 */
		public function plugins_loaded() {
			if ( class_exists( 'WooCommerce' ) ) {
				if (  version_compare( WOOCOMMERCE_VERSION, '2.3.0', '>=' ) ) {
					// No need for this plugin when using WooCommerce v2.3.0 or newer
					return;
				} else {
					// WooCommerce v2.2.x or older
					add_action( 'init', array( $this, 'init' ), 11 );
				}
			}
		}

		/**
		 * Initialise the actual functionality of this plugin during the 'init' hook so that WooCommerce has been intialised
		 */
		public function init() {
			add_filter( 'gettext', array( $this, 'gettext' ), 10, 3 );

			$countries =WC()->countries->get_allowed_countries();
			if ( isset( $countries['AU'] ) && 1 ==sizeof( $countries ) ) {
				$this->selling_to_au_only = true;
			}
		}

		/**
		 * Filter WooCommerce strings so we can change them.
		 */
		public function gettext( $translation, $text, $domain ) {

			if ( 'woocommerce' == $domain ) {
				// Only override WooCommerce text

				// Rename WooCommmerce's "Sort Code" to "BSB". Useful for Australian stores
				// WooCommerce v2.3.0 does this automatically, so this is only required for WooCommerce v2.2.x and older
				// Initial idea courtesy of https://gist.github.com/renegadesk/8312649
				if ( 'Sort Code' == $translation ) {
					return 'BSB';
				}

				// Improve checkout labels if selling to Australia only
				// WooCommerce v2.2.0 does this automatically, so this functionality is only required for WooCommerce v2.1.x and older
				if ( $this->selling_to_au_only ) {
					switch ( $translation ) {
						case 'Town / City':
							return 'Suburb';
							break;
						case 'State / County':
							return 'State';
							break;
						case 'Postcode / Zip':
							return 'Postcode';
							break;
					}
				}
			}

			return $translation;
		}

	}

	WC_Australia::instance();

}