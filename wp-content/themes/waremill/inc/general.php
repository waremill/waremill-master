<?php defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );

// ACf Options 
if (function_exists('acf_add_options_page')){

	// add parent
	$parent =  acf_add_options_page(array(
		'page_title' => 'Options',
		'menu_title' => 'Options',
		'menu_slug'  => 'theme-options',
		'capability' => 'manage_options',
		'position' => false,
		'parent_slug' => '',
		'autoload' => false,	
	));

	// add subpage
	acf_add_options_sub_page(array(
		'parent'     => 'theme-options',
		'title'      => 'General',
		'slug'       => 'theme-general',
		'capability' => 'manage_options'
	));
	
	acf_add_options_sub_page(array(
		'parent'     => 'theme-options',
		'title'      => 'Special Content',
		'slug'       => 'special-content',
		'capability' => 'manage_options',
		
	));
	acf_add_options_sub_page(array(
		'parent'     => 'theme-options',
		'title'      => 'Special Pages',
		'slug'       => 'special-pages',
		'capability' => 'manage_options',
		
	));
	acf_add_options_sub_page(array(
		'parent'     => 'theme-options',
		'title'      => 'Emails',
		'slug'       => 'theme-emails',
		'capability' => 'manage_options',
		
	));
	acf_add_options_sub_page(array(
		'parent'     => 'theme-options',
		'title'      => 'APIs',
		'slug'       => 'theme-apis',
		'capability' => 'manage_options',
		
	));
	
}


// Image Sizes
add_image_size('large', 1200, 600, true);
add_image_size('large-icon', 124, 130, true);
add_image_size('large-icon2', 160, 120, true);
add_image_size('logo', 300, 60, true);
add_image_size('logo-small', 74, 43, true);
add_image_size('type', 256, 256, true);
add_image_size('logo_company', 138, 101, true);
add_image_size('portofolio', 293, 215, true);
add_image_size('smallimg', 120, 90, true);
add_image_size('vsmall-logo', 58, 45, true);


// Register menus 
function menus(){
	register_nav_menus(array(
		'header-menu' 				=> __( 'Main Menu' ),
		//'header-menu-users' 		=> __( 'Main Menu Users' ),
		'header-menu-customer' 		=> __( 'Main Menu Customer' ), 
		'header-menu-contractor' 	=> __( 'Main Menu Contractor' ),
		'menu-login' 				=> __( 'Menu Login' ), 
		'menu-user-contractor' 		=> __( 'Menu User Contractor' ), 
		'menu-user-customer' 		=> __( 'Menu User Customer' ), 
		'footer-menu-1'	 			=> __( 'Footer Menu 1' ),
		'footer-menu-2' 			=> __( 'Footer Menu 2' ),
		'mobile-header-menu' 		=> __( 'Mobile Menu' ),
	));
}

add_action( 'init', 'menus' );


// Hide editor for specific page templates. 

add_action( 'admin_init', 'hide_editor' );
function hide_editor() {
    // Get the Post ID.
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    if( !isset( $post_id ) ) return;

    // Get the name of the Page Template file.
    $template_file = get_post_meta($post_id, '_wp_page_template', true);
    
    $array_remove_editor = array(
    	'page-home.php', 'homepage.php' ,
    	'customer-dashboard-templates/dashboard-edit-project.php',
    	'customer-dashboard-templates/dashboard-projects.php',
    	'customer-dashboard-templates/dashboard-create-project.php' ,
    	'page-add-new-post.php',
    	'contractor-dashboard-templates/dashboard-archive.php',
    	'contractor-dashboard-templates/dashboard-bids.php',
    	'contractor-dashboard-templates/dashboard-create-new-message.php',
    	'contractor-dashboard-templates/dashboard-settings.php',
    	'contractor-dashboard-templates/dashboard-messages.php',
    	'page-contractors.php',
    	'customer-dashboard-templates/dashboard-messages.php',
    	'customer-dashboard-templates/dashboard-archive.php',
    	'customer-dashboard-templates/dashboard-create-new-message.php' ,
    	'customer-dashboard-templates/dashboard-settings.php',
    	'page-edit-post.php',
    	'page-forum.php',
    	'page-login.php',
    	'page-my-posts.php',
    	'page-projects.php',
    	'page-search-contractors.php',
    	'contractor-dashboard-templates/dashboard-change-password.php',
    	'page-invitation.php',
    	'admin/page-get-token.php',
    	'admin/page-invitation.php'
    );

    if( in_array ($template_file, $array_remove_editor ) ){ // edit the template name
        remove_post_type_support('page', 'editor');
    }

    $array_remove_thumbnail = array(
    	'page-home.php' , 'homepage.php' ,
    	'customer-dashboard-templates/dashboard-edit-project.php', 
    	'customer-dashboard-templates/dashboard-projects.php' ,
    	'customer-dashboard-templates/dashboard-create-project.php' ,
    	'page-add-new-post.php', 
    	'contractor-dashboard-templates/dashboard-archive.php',
    	'contractor-dashboard-templates/dashboard-bids.php',
    	'contractor-dashboard-templates/dashboard-create-new-message.php',
    	'contractor-dashboard-templates/dashboard-settings.php',
    	'contractor-dashboard-templates/dashboard-messages.php',
    	'page-contractors.php',
    	'customer-dashboard-templates/dashboard-messages.php',
    	'customer-dashboard-templates/dashboard-archive.php',
    	'customer-dashboard-templates/dashboard-create-new-message.php',
    	'customer-dashboard-templates/dashboard-settings.php',
    	'customer-dashboard-templates/dashboard-user-role.php',
    	'page-edit-post.php',
    	'page-forum.php',
    	'page-login.php' ,
		'page-my-posts.php',
		'page-projects.php',
		'page-search-contractors.php',
		'contractor-dashboard-templates/dashboard-change-password.php',
		'page-invitation.php',
		'admin/page-get-token.php',
		'admin/page-invitation.php'
    );

    if( in_array ($template_file, $array_remove_thumbnail) ){
        remove_post_type_support('page', 'thumbnail'); 
    }
   	remove_post_type_support( 'post', 'thumbnail' );
}


function check_user_login(){
	if ( is_user_logged_in() ) {
		return true;
	}else{
		return false; 
	}
}

function check_user_customer(){
	$user = wp_get_current_user();
	if ( in_array( 'customer', (array) $user->roles ) ) {
	    return true;
	}
	return false; 
}

function check_user_customer_param($user){
	
	if ( in_array( 'customer', (array) $user->roles ) ) {
	    return true;
	}
	return false; 
}

//function check_user_contributor(){
function check_user_contractor(){
	$user = wp_get_current_user();
	if ( in_array( 'contractor', (array) $user->roles ) ) {
	    return true;
	}
	return false; 
}

//function check_user_contributor_param($user){
function check_user_contractor_param($user){	
	if ( in_array( 'contractor', (array) $user->roles ) ) {
	    return true;
	}
	return false; 
}

function get_username(){
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		return $user->user_firstname;
	}
	return '';
}


function kv_handle_attachment($file_handler,$post_id,$set_thu=false) {
	// check to make sure its a successful upload
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$attach_id = media_handle_upload( $file_handler, $post_id );

         // If you want to set a featured image frmo your uploads. 
	if ($set_thu) set_post_thumbnail($post_id, $attach_id);
	return $attach_id;
}