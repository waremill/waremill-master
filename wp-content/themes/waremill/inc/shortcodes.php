<?php 

//[username]
function username_func( $atts ){
	$user = ''; 
	if(check_user_login() == true ){ // if is nobody logged
		$current_user = wp_get_current_user();
		$user =  $current_user->user_firstname .' ' . $current_user->user_lastname ;
	} 
	
	return $user ; 
	
}
add_shortcode( 'username', 'username_func' );

?>