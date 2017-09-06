<?php 


function project() {

	// register post type project 
	$labels = array(
		'name'                  => _x( 'Project', 'Post Type General Name', THEME_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', THEME_TEXT_DOMAIN ),
		'menu_name'             => __( 'Projects', THEME_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Project', THEME_TEXT_DOMAIN),
		'archives'              => __( 'Projects Archives', THEME_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Project:', THEME_TEXT_DOMAIN ),
		'all_items'             => __( 'All Projects', THEME_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Project', THEME_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', THEME_TEXT_DOMAIN ),
		'new_item'              => __( 'New Item', THEME_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Project', THEME_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Project', THEME_TEXT_DOMAIN ),
		'view_item'             => __( 'View Project', THEME_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Project', THEME_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', THEME_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', THEME_TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Insert into Project', THEME_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Project', THEME_TEXT_DOMAIN ),
		'items_list'            => __( 'Items Project', THEME_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Projects list navigation', THEME_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter items Project', THEME_TEXT_DOMAIN ),
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'project' ),
		'capability_type'    => 'post',
		'capabilities' 		 => array(
			'read_post'		 		=> 'read_project',
		    'publish_posts' 	 	=> 'publish_projects',
		    'edit_posts' 		 	=> 'edit_projects',
		    'edit_others_posts' 	=> 'edit_others_projects',
		    'delete_posts' 			=> 'delete_projects',
		    'delete_others_posts' 	=> 'delete_others_projects',
		    'read_private_posts' 	=> 'read_private_projects',
		    'edit_post' 			=> 'edit_project',
		    'delete_post' 			=> 'delete_project',
	    
	    ),
		'map_meta_cap' 		 => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'supports'           => array( 'title', 'editor', 'custom-fields', 'revisions' ),


	);

	register_post_type( 'project', $args );

	// register taxonomy industy for post type project 
	$labels = array(
		'name'              => _x( 'Material', 'taxonomy general name' ),
		'singular_name'     => _x( 'Material', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Material' ),
		'all_items'         => __( 'All Materials' ),
		'parent_item'       => __( 'Parent Material' ),
		'parent_item_colon' => __( 'Parent Material:' ),
		'edit_item'         => __( 'Edit Material' ),
		'update_item'       => __( 'Update Material' ),
		'add_new_item'      => __( 'Add New Material' ),
		'new_item_name'     => __( 'New Material Name' ),
		'menu_name'         => __( 'Materials' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'industry' ),
	);

	register_taxonomy( 'industry', array( 'project' , 'contractor'), $args );

	// register taxonomy service for post type project 
	$labels = array(
		'name'              => _x( 'Services', 'taxonomy general name' ),
		'singular_name'     => _x( 'Service', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Services' ),
		'all_items'         => __( 'All Services' ),
		'parent_item'       => __( 'Parent Service' ),
		'parent_item_colon' => __( 'Parent Service:' ),
		'edit_item'         => __( 'Edit Service' ),
		'update_item'       => __( 'Update Service' ),
		'add_new_item'      => __( 'Add New Service' ),
		'new_item_name'     => __( 'New Service Name' ),
		'menu_name'         => __( 'Services' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'service' ),
	);

	register_taxonomy( 'service', array( 'project', 'contractor' ), $args );


	

}
add_action( 'init', 'project', 0 );

function add_event_caps() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'edit_project' ); 
	$role->add_cap( 'edit_projects' ); 
	$role->add_cap( 'edit_others_projects' ); 
	$role->add_cap( 'publish_projects' ); 
	$role->add_cap( 'read_project' ); 
	$role->add_cap( 'read_private_projects' ); 
	$role->add_cap( 'delete_project' ); 

	$role->add_cap( 'edit_message' ); 
	$role->add_cap( 'edit_messages' ); 
	$role->add_cap( 'edit_others_messages' ); 
	$role->add_cap( 'publish_message' ); 
	$role->add_cap( 'read_message' ); 
	$role->add_cap( 'read_private_messages' ); 
	$role->add_cap( 'delete_message' ); 
}
add_action( 'admin_init', 'add_event_caps');

/*function company() { 
	// register post type company 
	$labels = array(
		'name'                  => _x( 'Company', 'Post Type General Name', THEME_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Company', 'Post Type Singular Name', THEME_TEXT_DOMAIN ),
		'menu_name'             => __( 'Companies', THEME_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Company', THEME_TEXT_DOMAIN),
		'archives'              => __( 'Companies Archives', THEME_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Company:', THEME_TEXT_DOMAIN ),
		'all_items'             => __( 'All Companies', THEME_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Company', THEME_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', THEME_TEXT_DOMAIN ),
		'new_item'              => __( 'New Item', THEME_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Company', THEME_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Company', THEME_TEXT_DOMAIN ),
		'view_item'             => __( 'View Company', THEME_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Company', THEME_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', THEME_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', THEME_TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Insert into Company', THEME_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Company', THEME_TEXT_DOMAIN ),
		'items_list'            => __( 'Items Company', THEME_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Companies list navigation', THEME_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter items Company', THEME_TEXT_DOMAIN ),
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'company' ),
		'capability_type'    => 'post',
		'capabilities' 		 => array(
			'read_post'		 		=> 'read_company',
		    'publish_posts' 	 	=> 'publish_companies',
		    'edit_posts' 		 	=> 'edit_companies',
		    'edit_others_posts' 	=> 'edit_others_companies',
		    'delete_posts' 			=> 'delete_companies',
		    'delete_others_posts' 	=> 'delete_others_companies',
		    'read_private_posts' 	=> 'read_private_companies',
		    'edit_post' 			=> 'edit_company',
		    'delete_post' 			=> 'delete_company',
	     
	    ),
		'map_meta_cap' 		 => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 30,
		'supports'           => array( 'title', 'editor', 'custom-fields', 'revisions' ),


	);

	register_post_type( 'company', $args );
}
add_action( 'init', 'company', 0 );*/
function company() {
	$labels = array(
		'name'                  => _x( 'Contractor', 'Post Type General Name', THEME_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Contractor', 'Post Type Singular Name', THEME_TEXT_DOMAIN ),
		'menu_name'             => __( 'Contractors', THEME_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Contractor', THEME_TEXT_DOMAIN),
		'archives'              => __( 'Contractors Archives', THEME_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Contractor:', THEME_TEXT_DOMAIN ),
		'all_items'             => __( 'All Contractors', THEME_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Contractor', THEME_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', THEME_TEXT_DOMAIN ),
		'new_item'              => __( 'New Item', THEME_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Contractor', THEME_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Contractor', THEME_TEXT_DOMAIN ),
		'view_item'             => __( 'View Contractor', THEME_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Contractor', THEME_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', THEME_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', THEME_TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Insert into Contractor', THEME_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Contractor', THEME_TEXT_DOMAIN ),
		'items_list'            => __( 'Items Contractor', THEME_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Companies list navigation', THEME_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter items Contractor', THEME_TEXT_DOMAIN ),
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'contractor' ),
		'capability_type'    => 'post', 
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'supports'           => array( 'title',  'custom-fields', 'revisions' ),
	);

	register_post_type( 'contractor', $args );



}
add_action( 'init', 'company', 0 ); 


function bid() {
	$labels = array(
		'name'                  => _x( 'Bid', 'Post Type General Name', THEME_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Bid', 'Post Type Singular Name', THEME_TEXT_DOMAIN ),
		'menu_name'             => __( 'Bids', THEME_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Bid', THEME_TEXT_DOMAIN),
		'archives'              => __( 'Bids Archives', THEME_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Bid:', THEME_TEXT_DOMAIN ),
		'all_items'             => __( 'All Bids', THEME_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Bid', THEME_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', THEME_TEXT_DOMAIN ),
		'new_item'              => __( 'New Item', THEME_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Bid', THEME_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Bid', THEME_TEXT_DOMAIN ),
		'view_item'             => __( 'View Bid', THEME_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Bid', THEME_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', THEME_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', THEME_TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Insert into Bid', THEME_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Bid', THEME_TEXT_DOMAIN ),
		'items_list'            => __( 'Items Bid', THEME_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Bid list navigation', THEME_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter items Bid', THEME_TEXT_DOMAIN ),
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'bid' ),
		'capability_type'    => 'post', 
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'supports'           => array( 'title',  'custom-fields', 'revisions' ),
	);

	register_post_type( 'bid', $args );



}
add_action( 'init', 'bid', 0 ); 

//var_dump($GLOBALS['wp_post_types']['company'] ); 

function message() {
	$labels = array(
		'name'                  => _x( 'Message', 'Post Type General Name', THEME_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Message', 'Post Type Singular Name', THEME_TEXT_DOMAIN ),
		'menu_name'             => __( 'Messages', THEME_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Message', THEME_TEXT_DOMAIN),
		'archives'              => __( 'Messages Archives', THEME_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Message:', THEME_TEXT_DOMAIN ),
		'all_items'             => __( 'All Messages', THEME_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Message', THEME_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', THEME_TEXT_DOMAIN ),
		'new_item'              => __( 'New Item', THEME_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Message', THEME_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Message', THEME_TEXT_DOMAIN ),
		'view_item'             => __( 'View Message', THEME_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Message', THEME_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', THEME_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', THEME_TEXT_DOMAIN ),
		'insert_into_item'      => __( 'Insert into Message', THEME_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Message', THEME_TEXT_DOMAIN ),
		'items_list'            => __( 'Items Message', THEME_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Message list navigation', THEME_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter items Message', THEME_TEXT_DOMAIN ),
	);
	
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'message' ),
		'capability_type'    => 'post',
		'capabilities' 		 => array(
			'read_post'		 		=> 'read_message',
		    'publish_posts' 	 	=> 'publish_messages',
		    'edit_posts' 		 	=> 'edit_messages',
		    'edit_others_posts' 	=> 'edit_others_messages',
		    'delete_posts' 			=> 'delete_messages',
		    'delete_others_posts' 	=> 'delete_others_messages',
		    'read_private_posts' 	=> 'read_private_messages',
		    'edit_post' 			=> 'edit_message',
		    'delete_post' 			=> 'delete_message',
	    
	    ),
		'map_meta_cap' 		 => true,
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 27,
		'supports'           => array( 'title', 'custom-fields', 'revisions' ),
	);

	register_post_type( 'message', $args );



}
add_action( 'init', 'message', 0 ); 

?>