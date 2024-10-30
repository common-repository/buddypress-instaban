<?php
/**
 * BP Instaban Template Tags
 *
 * @package BP-Instaban
 * @subpackage Template
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Output a 'kick and ban' button for a given user.
 *
 * @param mixed $args Arguments can be passed as an associative array or as a URL argument string. See bp_instaban_get_kickban_button() for full arguments.
 * @uses bp_instaban_get_kickban_button() Returns the kick/ban button
 * @author billz
 * @since 1.0
 */
function bp_instaban_add_kickban_button( $args = '' ) {
   echo bp_instaban_get_kickban_button( $args );
}
	/**
	 * Returns a 'kick and ban' button for a given user
	 *
	 * @param mixed $args Arguments can be passed as an associative array or as a URL argument string
	 * @return mixed string of the button on success. Boolean false on failure.
	 * @uses bp_get_button() Renders a button using the BP Button API
	 * @author billz
	 * @since 1.0
	 */
	function bp_instaban_get_kickban_button( $args = '' ) {
		global $bp, $members_template;

		$defaults = array(
			'user_id'   => bp_displayed_user_id(),
		);
	
		$r = wp_parse_args( $args, $defaults );
		extract( $r );

		if ( !$user_id || !current_user_can('administrator') ) // limit to site administrators
			return false;
  
		// setup some variables
        $id        = 'bp-instaban';
        $action    = 'kickban';
        $class     = 'kickban';
        $link_text = $link_title = sprintf( __( 'Remove and Ban User', 'bp-instaban' ) );
        $domain   = bp_displayed_user_domain();

		// setup the button arguments
		$button = array(
			'id'                => $id,
			'component'         => 'core',
			'must_be_logged_in' => true,
			'block_self'        => empty( $members_template->member ) ? true : false,
			'wrapper_class'     => 'instaban-button ' . $id,
			'wrapper_id'        => 'instaban-button-' . $user_id,
			'link_href'         => wp_nonce_url( $domain . $bp->instaban->slug . $action .'/', $action  . '_kickban' ),
			'link_text'         => $link_text,
			'link_title'        => $link_title,
			'link_id'           => $class . '-' . $user_id,
			'link_class'        => $class
		);
       
		// Filter and return the HTML button
        return bp_get_button( apply_filters( 'bp_instaban_get_kickban_button', $button, $user_id ) );
	}
?>