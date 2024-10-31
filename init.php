<?php
/*
Plugin Name:    SafeCode
Description:    Add snippets and custom functions, safe and secure.
Author:         Hassan Derakhshandeh
Version:        0.2

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

defined( 'ABSPATH' ) or die( '-1' );

class SafeCode {

	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		}
		add_action( 'plugins_loaded', array( __CLASS__, 'exec_safecode' ) );
	}

	static function admin_menu() {
		$page = add_options_page( 'SafeCode', 'SafeCode', 'manage_options', 'safecode', array( __CLASS__, 'options_page' ) );
		add_action( "load-{$page}", array( __CLASS__, 'save' ) );
		add_action( "admin_print_styles-{$page}", array( __CLASS__, 'enqueue' ) );
	}

	static function options_page() {
		require_once( trailingslashit( dirname( __FILE__ ) ) . 'views/admin.php' );
	}

	static function save() {
		if ( isset( $_POST['custom-functions'] ) ) {
			check_admin_referer( 'safecode_update' );
			update_option( 'safecode', stripcslashes( $_POST['custom-functions'] ) );
			$location = "options-general.php?page=safecode&updated=1&scrollto=" . ( isset( $_REQUEST['scrollto'] ) ? (int) $_REQUEST['scrollto'] : 0 );
			header( "Location: $location" );
		}
	}

	static function exec_safecode() {
		$user_functions = get_option( 'safecode' );
		$user_functions = trim( $user_functions );
		$user_functions = trim( $user_functions, '<?php' );
		if ( $user_functions ) {
			set_error_handler( 'self::error_handler' );
			try {
				eval( $user_functions );
			}
			catch ( Error $e ) {
				trigger_error( $e->getMessage(), E_USER_WARNING );
			}
			restore_error_handler();
		}
	}

	public static function enqueue() {
		wp_enqueue_code_editor( 'php' );
		wp_enqueue_script( 'safecode', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/admin.js', null, null, true );
		wp_enqueue_style( 'safecode', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/admin.css' );
	}

	/**
	 * Handle errors in eval-ed Logic field
	 *
	 * @since 0.4
	 */
	public static function error_handler( $errno, $errstr ) {
		if ( current_user_can( 'manage_options' ) ) {
			echo $errstr;
		}

		/* Don't execute PHP internal error handler */
		return true;
	}
}
new SafeCode;