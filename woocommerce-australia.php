<?php
/*
Plugin Name: WooCommerce Australia
Plugin URI: https://github.com/OM4/woocommerce-australia
Description: For Australian-based stores using WooCommerce. It improves WooCommerce by renaming "Sort Code" to "BSB".
Version: 1.0
Author: OM4
Author URI: http://om4.com.au/
Text Domain: woocommerce-australia
Git URI: https://github.com/OM4/woocommerce-australia
Git Branch: release
License: GPLv2
*/

/*
Copyright 2014 OM4 (email: info@om4.com.au    web: http://om4.com.au/)

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
				add_filter( 'gettext', array( $this, 'sort_code_rename_to_bsb' ), 10, 3 );
			}
		}

		/**
		 * Rename WooCommmerce's "Sort Code" to "BSB". Useful for Australian stores
		 *
		 * Initial idea courtesy of https://gist.github.com/renegadesk/8312649
		 */
		public function sort_code_rename_to_bsb( $translation, $text, $domain ) {
			if ( 'woocommerce' == $domain ) {
				switch ( $text ) {
					case 'Sort Code':
						$translation = 'BSB';
						break;
				}
			}
			return $translation;
		}

	}

	WC_Australia::instance();

}