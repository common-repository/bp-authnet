<?php
/*
Plugin Name: BP Authorize.net
Plugin URI: http://buddypress.org/
Description: Simple Authorize.net payment class
Author: John James Jacoby
Version: 1.0
Author URI: http://buddypress.org/developers/johnjamesjacoby/
Site Wide Only: true
*/

/**
 * BP_Authnet_Loader
 *
 * Loads plugin
 */
class BP_Authnet_Loader {
	/**
	 * constants()
	 *
	 * Default component constants that can be overridden or filtered
	 */
	function constants() {

		// Default slug for component
		if ( !defined( 'BP_AUTHNET_SLUG' ) )
			define( 'BP_AUTHNET_SLUG', apply_filters( 'bp_authnet_slug', 'payment' ) );

		// Response codes
		define( 'BP_AUTHNET_STATUS_SUCCESS', 1 );
		define( 'BP_AUTHNET_STATUS_DECLINE', 2 );
		define( 'BP_AUTHNET_STATUS_REFERRAL', 3 );
		define( 'BP_AUTHNET_STATUS_KEEPCARD', 4 );

		// More response codes
		define( 'BP_AUTHNET_RESPONSE_STATUS', 0 );
		define( 'BP_AUTHNET_RESPONSE_MESSAGE', 3 );
		define( 'BP_AUTHNET_RESPONSE_TOTAL', 9 );

		// Uncomment to send transactions to authorize.net test server instead
		//define( 'BP_AUTHNET_DEBUG', true );

	}

	/**
	 * includes()
	 *
	 * Load required files
	 *
	 * @uses is_admin If in WordPress admin, load additional file
	 */
	function includes() {
		// Load the files
		require_once( WP_PLUGIN_DIR . '/bp-authnet/bp-authnet-classes.php' );
		require_once( WP_PLUGIN_DIR . '/bp-authnet/bp-authnet-templatetags.php' );

		// Quick admin check
		if ( is_admin() )
			require_once( WP_PLUGIN_DIR . '/bp-authnet/bp-authnet-admin.php' );
	}

	/**
	 * init()
	 *
	 * Initialize plugin
	 *
	 * @uses BP_Authnet_Loader::constants()
	 * @uses BP_Authnet_Loader::includes()
	 * @uses BP_Authnet::init()
	 * @uses BP_Authnet_User::init()
	 * @uses BP_Authnet_Admin::init()
	 * @uses is_admin()
	 * @uses do_action Calls custom action to allow external enhancement
	 */
	function init() {

		// Define all the constants
		BP_Authnet_Loader::constants();

		// Include required files
		BP_Authnet_Loader::includes();

		// Initialize site action hooks
		BP_Authnet::init();

		// Initialize user action hooks
		//BP_Authnet_User::init();

		// Admin initialize
		if ( is_admin() )
			BP_Authnet_Admin::init();

		/**
		 * For developers:
		 * ---------------------
		 * If you want to make sure your code is loaded after this plugin
		 * have your code load on this action
		 */
		do_action ( 'bp_authnet_init' );
	}
}

// Do the ditty
if ( defined( 'BP_VERSION' ) )
	BP_Authnet_Loader::init();
else
	add_action( 'bp_init', array( 'BP_Authnet_Loader', 'init' ) );



?>
