<?php
/*
Plugin Name: Disable New User Notification
Description: Disable the user notification email that is sent to the user, with a password reset link, after registration.
Plugin URI: http://rocapress.com
Author: RocaPress
Author URI: http://rocapress.com
Version: 1.1
License: GPL2
*/

/*
*
* Disable new user notification email 
*
*/

if ( ! function_exists( 'wp_new_user_notification' ) ) :
	
function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
	return;
}

endif;