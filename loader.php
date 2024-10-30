<?php
/*
Plugin Name: BuddyPress Instaban
Plugin URI: http://vc4africa.biz
Description: Allows site administrators to remove and permanently ban problem members with a single click.
Version: 1.0
License: GNU/GPL 2
Author: Bill Zimmerman, Nelson Kana
*/

/**
 * BP Instaban
 *
 * @package BP-Instaban
 * @subpackage Loader
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Only load the plugin code if BuddyPress is activated.
 */
function bp_instaban_init() {
    require( dirname( __FILE__ ) . '/bp-instaban.php' );
}
add_action( 'bp_include', 'bp_instaban_init' );

/**
 * Run the activation routine when BP-Instaban is activated.
 *
 * @uses dbDelta() Executes queries and performs selective upgrades on existing tables.
 */
function bp_instaban_activate() {
	global $bp, $wpdb;

    // Activation routines goes here
  
}
register_activation_hook( __FILE__, 'bp_instaban_activate' );

/**
 * Run the deactivation routine when BP-Instaban is deactivated.
 * Not used currently.
 */
function bp_instaban_deactivate() {
	// Cleanup.
}
//register_deactivation_hook( __FILE__, 'bp_instaban_deactivate' );

/**
 * Custom textdomain loader.
 *
 * Checks WP_LANG_DIR for the .mo file first, then the plugin's language folder.
 * Allows for a custom language file other than those packaged with the plugin.
 *
 * @uses load_textdomain() Loads a .mo file into WP
 */
function bp_instaban_localization() {
	$mofile		= sprintf( 'bp-instaban-%s.mo', get_locale() );
	$mofile_global	= trailingslashit( WP_LANG_DIR ) . $mofile;
	$mofile_local	= plugin_dir_path( __FILE__ ) . 'languages/' . $mofile;

	if ( is_readable( $mofile_global ) )
		return load_textdomain( 'bp-instaban', $mofile_global );
	elseif ( is_readable( $mofile_local ) )
		return load_textdomain( 'bp-instaban', $mofile_local );
	else
		return false;
}
add_action( 'plugins_loaded', 'bp_instaban_localization' );
?>