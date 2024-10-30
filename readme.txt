=== BuddyPress Instaban ===
Contributors: billz, wpnelsonkana
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=TJM2SLFYDT7F2&lc=GB&item_name=BuddyPress%20Instaban%20Donation
Tags: buddypress,community,social,oneall
Requires at least: WP 3.0 / BP 1.3
Tested up to: WP 3.5.1 / BP 1.7.2
Stable tag: trunk
License: GPLv3

BuddyPress Instaban allows site administrators to quickly remove problem members with a single click.

== Description ==
The BuddyPress Instaban plugin allows site administrators to quickly remove problem members with a single click from the front-end. A confirmation dialog prevents accidental removals. If your site uses OneAll's Social Login plugin to allow members to create new accounts, Instaban will add their unique token to a list of 'unsafe' tokens. Integration options with OneAll are included.

**Features:**

* Eliminates additional steps of searching user accounts in the WordPress admin panel.
* Integrates with member profiles on activation with predefined hooks.
* Optionally bans members if they've created an account with OneAll social login.
* Confirmation dialog with AJAX handler is fast, safe and efficient.
* Ability to customize button placement in your theme with a template tag.

== Installation ==

1. Download, install and activate the plugin.
2. To remove (and optionally ban) a member, simply visit their profile and click the 'Remove and Ban User' button.
3. Confirm the action with the dialog.
4. You're done!

== Frequently Asked Questions ==

= Do I have to add template tags to my theme? =
You should not have to change your theme template files. The Instaban plugin integrates into your site by using predefined hooks.

= Does Instaban support custom embedding with a template tag? =
Yes. You can add the Instaban button to your theme with the bp_instaban_add_kickban_button() function. We recommend embedding the tag like so:

`
<?php if ( function_exists( 'bp_instaban_add_kickban_button' ) ) : ?>
    <?php bp_instaban_add_kickban_button(); ?>
<?php endif; ?>
`

= Is it possible to integrate Instaban with OneAll Social Login? =
Yes. When removing and banning a member, Instaban adds the unique token returned by the user's social network to a list of 'unsafe' tokens.
To prevent banned members from repeatedly creating new accounts with Social Login, oa_social_login_callback() must do a check with
the function bp_instaban_is_user_token_unsafe().


== Other Notes ==

For integration with OneAll Social Login, you may perform a check with the user_token returned from the API response, like so:

`
// from oa-social-login/includes/communication.php:

/**
 * Handle the callback
 */
function oa_social_login_callback () {

	//User Data
	if (is_object ($social_data)) {
		$identity = $social_data->response->result->data->user->identity;
		$user_token = $social_data->response->result->data->user->user_token;

		// verify that user token isn't associated with an unsafe login
		if ( bp_instaban_is_user_token_unsafe( $user_token, 'oa_social_login_user_unsafe_tokens' ) ) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 501 Not Implemented', true, 501); // throw a 501 error
			die();  
		}   
	}
}	
`

In the above example, the server returns an HTTP 501 error. This effectively prevents the user from creating an account again with the same social network provider.
You may choose to handle it differently. In the future, OneAll may provide a hook to enable this integration without modifying the plugin directly.

== Screenshots ==

1. Screenshot of a member profile with the Instaban button.

== Changelog ==

= 1.0 =
* Initial release.
