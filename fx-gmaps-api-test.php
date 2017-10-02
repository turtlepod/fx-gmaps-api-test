<?php
/**
 * Plugin Name: f(x) Google Maps API Key Test
 * Plugin URI: https://github.com/turtlepod/fx-gmaps-api-test
 * Description: Test Google Maps API for Geolocation.
 * Version: 1.0.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
**/

namespace fx_gmaps_api_test;
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Constants
------------------------------------------ */

define( __NAMESPACE__ . '\PREFIX', 'fx_gmaps_api_test' );
define( __NAMESPACE__ . '\URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( __NAMESPACE__ . '\PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( __NAMESPACE__ . '\FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN', plugin_basename( __FILE__ ) );
define( __NAMESPACE__ . '\VERSION', '1.0.0' );

/* Init
------------------------------------------ */

add_action( 'plugins_loaded', function() {

	// Register Settings.
	add_action( 'admin_init', function() {
		register_setting(
			$option_group      = PREFIX,
			$option_name       = PREFIX,
			$sanitize_callback = function( $in ) { // Sanitize Here!
				$out = $in;
				return $out;
			}
		);
	} );

	// Add Settings Page.
	add_action( 'admin_menu', function() {

		// Add page.
		$page = add_menu_page(
			$page_title  = 'Google Maps API Test',
			$menu_title  = 'GMaps Key Test',
			$capability  = 'manage_options',
			$menu_slug   = PREFIX,
			$function    = function() {
				?>
				<div class="wrap">
					<h1>Google Maps API Key Test</h1>
					<form method="post" action="options.php">
						<?php settings_errors(); ?>
						<?php require_once( PATH . 'test/html.php' ); ?>
						<?php do_settings_sections( PREFIX ); ?>
						<?php settings_fields( PREFIX ); ?>
						<?php submit_button( 'Test API Key' ); ?>
					</form>
				</div><!-- wrap -->
				<?php
			},
			$icon        = 'dashicons-location',
			$position    = 99
		);

		// Load assets.
		add_action( 'admin_enqueue_scripts', function( $hook_suffix ) use( $page ) {
			if ( $page === $hook_suffix ) {

				// CSS.
				wp_enqueue_style( PREFIX . '_settings', URI . 'test/style.css', array(), VERSION );

				// JS.
				$deps = array(
					'jquery',
				);
				wp_enqueue_script( PREFIX . '_settings', URI . 'test/script.js', $deps, VERSION, true );

				// JS Data.
				$option = get_option( PREFIX );
				$option = is_array( $option ) ? $option : array();
				wp_localize_script( PREFIX . '_settings', PREFIX . 'Data', $option );
			}
		} );
	} );

} );
