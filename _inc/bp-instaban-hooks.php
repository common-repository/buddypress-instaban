<?php
/**
 * BP Instaban Hooks
 *
 * Functions in this file allow this component to hook into BuddyPress so it interacts
 * seamlessly with the interface and existing core components.
 *
 * @package BP-Instaban
 * @subpackage Hooks
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add a "Kick and Ban" button to the profile header for a user.
 *
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @uses bp_is_my_profile() Return true if you are looking at your own profile when logged in.
 * @uses is_user_logged_in() Return true if you are logged in.
 */
function bp_instaban_add_profile_kickban_button() {
	bp_instaban_add_kickban_button();
}
add_action( 'bp_member_header_actions', 'bp_instaban_add_profile_kickban_button' );

?>