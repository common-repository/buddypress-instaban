<?php
/**
 * BP Instant Ban Core
 *
 * @package BP-Instaban
 * @subpackage Core
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( version_compare( BP_VERSION, '1.3' ) < 0 )
	require ( dirname( __FILE__ ) . '/_inc/bp-instaban-backpat.php' );
    require ( dirname( __FILE__ ) . '/_inc/bp-instaban-templatetags.php' );
    require ( dirname( __FILE__ ) . '/_inc/bp-instaban-hooks.php' );

/**
 * Append the globals this component will use to the $bp global.
 *
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @global $wpdb The global WordPress database access object.
 */
function bp_instaban_setup_globals() {
	global $bp, $wpdb;

	if ( !defined( 'BP_INSTABAN_SLUG' ) )
		define( 'BP_INSTABAN_SLUG', 'instaban' );

	
	// For internal identification
	$bp->instaban->id              = 'instaban';
    $bp->instaban->slug            = BP_INSTABAN_SLUG;

	/* Register this in the active components array */
	$bp->active_components[$bp->instaban->id] = $bp->instaban->id;

	// BP 1.2.x only
	if ( version_compare( BP_VERSION, '1.0' ) < 0 ) {
		$bp->instaban->format_notification_function = 'bp_instaban_format_notifications';
	}
	// BP 1.5-specific
	else {
		$bp->instaban->notification_callback        = 'bp_instaban_format_notifications';
	}
}
add_action( 'bp_setup_globals', 'bp_instaban_setup_globals' );

/**
 * Enqueues the javascript.
 *
 * The JS is used to add AJAX functionality like clicking the remove and ban button and saving a page refresh.
 */
function bp_instaban_add_js() {
        if( !wp_script_is( 'jquery-ui-dialog' , $list = 'queue' )){
         wp_enqueue_script('jquery-ui-dialog');   
        }

	wp_enqueue_script( 'bp-instaban-js', plugin_dir_url( __FILE__ ) . '_inc/bp-instaban.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'bp_instaban_add_js', 11 );

/**
 * Enqueues the css for the dialog box.
 *
 */
function bp_instaban_add_css() {
        
    if( !wp_style_is( 'wp-jquery-ui-dialog' , $list = 'queue' )){
         wp_enqueue_style('wp-jquery-ui-dialog');   
         
     }
}
add_action( 'wp_print_scripts', 'bp_instaban_add_css' );


/**
 * Allow a site admin to kick and ban a member by catching an AJAX request.
 *
 * @global $bp The global BuddyPress settings variable created in bp_core_setup_globals()
 * @uses check_admin_referer() Checks to make sure the WP security nonce matches.
 * @return bool if action is successful
 */
function bp_instaban_ajax_action_start() {
    global $wp, $wpdb;

    $user_id = $_POST['uid'];
    
    if ( isset($user_id) ) {
        
       $oa_social_login_token = get_user_meta( $user_id, 'oa_social_login_user_token' ); // check for unique oa_social login token from usermeta
       
       /* WP's built-in function wp_delete_user() does not delete the user in multisite installs, but rather removes them from the current blog.
        * We use these routines to ensure they are deleted completely.
        */
       $meta = $wpdb->get_col($wpdb->prepare("SELECT umeta_id FROM $wpdb->usermeta WHERE user_id = %d", $user_id));
        foreach ($meta as $mid)
            delete_metadata_by_mid('user', $mid);
        
       $wpdb->delete($wpdb->users, array('ID' => $user_id));
       clean_user_cache( $user_id );
       
       $message = __('Removed','bp-instaban');
       
       if ( isset( $oa_social_login_token ) ) {
           
           $unsafe_tokens = get_site_option( 'oa_social_login_user_unsafe_tokens' );
           
           if ( !in_array( $oa_social_login_token, (array)$unsafe_tokens ) ) {
                $unsafe_tokens[] = $oa_social_login_token;
                update_site_option( 'oa_social_login_user_unsafe_tokens' , $unsafe_tokens ); // add user's token to collection of unsafe ones
                $message .= __(' and Banned','bp-instaban');
            }   
       }
       
       echo $message;
    }
	exit();
}
add_action( 'wp_ajax_bp_kickban', 'bp_instaban_ajax_action_start' );

/**
 * Checks for a Social Login user token that has been previously banned.
 * 
 * @param $user_token
 * @param $meta_key
 */
function bp_instaban_is_user_token_unsafe( $user_token, $meta_key ) {

    if ( !isset($meta_key) )
        $meta_key = 'oa_social_login_user_unsafe_tokens';
    
    $meta_value = get_site_option( $meta_key ); // grab the serialized string
    $unsafeArray = maybe_unserialize( $meta_value ) ; // unserialize string into an array 
    
    if ( is_array( $unsafeArray ) && empty( $unsafeArray ) == false ) {
        
        foreach( $unsafeArray as $unsafe_item ) {
            if ( strstr ( $user_token, $unsafe_item[0] ) !== false  )
                return true;
        }
    }
    return false;
}

?>