<?php 

/********************************************* Change Roles *********************************************/
function changerole(){

	$ok_redirect = false; 
	if (isset($_POST['action']) ) {
	    if(strcmp($_POST['action'], 'changerole') == 0) { 

	    	$role_to_change = $_POST['role'];
	    	if(isset($role_to_change) && (strcmp($role_to_change, "customer") == 0 || strcmp($role_to_change, "contractor") == 0)){
	    		// change role
	    		$user = wp_get_current_user(); 
	    		$userid = get_current_user_id(); 
	    		$user_id = wp_update_user( 
	    				array( 
	    					'ID' 	=> $userid, 
	    					'role' 	=> $role_to_change
	    				)  
	    		);

				if ( is_wp_error( $user_id ) ) {
					$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
				}else{
					// success

					// check if user is contractor and if has a page associated
					if(strcmp($role_to_change, "contractor") == 0){
						$associated_company = get_field('associated_company', 'user_'.$user_id );
						//var_dump($user_id);
						//var_dump($associated_company);
						if(empty($associated_company)){
							// create new post type for this user
							$full_user_name = ucwords($user->user_firstname).' '. ucwords($user->user_lastname) ;

						  	$my_post = array(
                                'post_title'      	=> $full_user_name ,
                                'post_name' 		=> "contractor-".$full_user_name,
                                'post_status'     	=> 'publish', // draft 
                                'post_content'    	=>  $post_content,
                                'post_author'   	=>  $userid,
                                'post_type'       	=> 'contractor',
                                // 'meta_input'      	=>  $meta_array
                            );    

                            $id_new_post = wp_insert_post( $my_post );
                            // update the fields 
                            $fields_associated_user = "field_588b5eec56820"; 
                            update_field($fields_associated_user, $userid, $id_new_post);

                            // add automatic company name
                            //$comp_name = get_field('company_name', $id_new_post);
                            //if(empty($comp_name)){ 
                            	//$fields_company_name = "field_5889a8373161f";
                            	//update_field($fields_company_name, "" , $id_new_post);

                            	// 
								// update contractor title 
							 	//  	$my_post = array(
							 	//      	'ID'           => $id_new_post,
							 	//      	'post_title'   => 'Contractor: '. $value,
							 	//  	);
								// // Update the post into the database
								// wp_update_post( $my_post );
								
                        	//}

                            $field_associated_company = "field_5889c47ba218a";
                            update_field($field_associated_company, $id_new_post, 'user_'.$userid);

						}
					}

					$_POST['message'] = "<p class='fsuccess fcenter'>Updating your roles...</p>";
					$ok_redirect = true;
					if(strcmp($role_to_change, "customer") == 0){

						$link_redirect = get_permalink(get_field('client_dashboard', 'options')->ID);

					}else if(strcmp($role_to_change, "contractor") == 0) {

						$link_redirect = get_permalink(get_field('contractor_dashboard', 'options')->ID);
					}

					if(isset($_POST['once']) && strlen($_POST['once']) > 0 ){
						if(strcmp($role_to_change, "customer") == 0){
							$link_redirect = get_permalink(get_field('client_dashboard', 'options')->ID);
						}else if(strcmp($role_to_change, "contractor") == 0) {
							$link_redirect = get_permalink(get_field('contractor_page_for_switching_roles', 'options')->ID);
						}
					}
					
					// get_syncronized page 
					if(isset($_POST['redirect']) && strlen($_POST['redirect']) > 0) {
						if(isset($_POST['page']) && strlen($_POST['page']) > 0){
							
							// search in repeater the corespondent of this page  // else redirect on the same page (?)
							$redirect_id = get_syncronized_page($_POST['page']);
							if($redirect_id != null){
								$redirect_page = get_permalink($redirect_id); 	// redirect on syncronized page
							}else{
								$redirect_page = get_permalink($_POST['page']); // redirect on the same page
							}
						}else if(strlen($_POST['page']) == 0 ) {
							$redirect_page = '';
						}
					}
				}
 
	    	}else{
	    		$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    	}

	    }else{
	    	$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }
	}else{
		$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
	echo json_encode(array('message' => $_POST['message'], 'redirect' =>  $ok_redirect , 'link' => $link_redirect, 'page' => $redirect_page ));
   	die();
}
add_action( 'wp_ajax_changerole', 'changerole' );
add_action( 'wp_ajax_nopriv_changerole', 'changerole' );


/********************************************* customer: get page syncronized *********************************************/
/* 
*  Function  : get_syncronized_page
*  Parameters: page_id
*  Return    : page_id that is syncronized with the initial page_id// acf repeated dashboard
*/
function get_syncronized_page($page_id){
	$id_return = null;
	$box = get_field('synchronize_pages', 'options'); 
	if ($box){
		foreach ($box as $box1) { 
			$id_val1 = $box1['page_customer'];
			$id_val2 = $box1['page_contractor'];
			if($id_val1 == $page_id){
				$id_return = $id_val2;
				break;
			}else if($id_val2 == $page_id){
				$id_return = $id_val1;
				break;
			}
		} 
	}
	return $id_return;
}


?>