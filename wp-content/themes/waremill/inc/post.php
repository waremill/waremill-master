<?php 
function addpost(){

	$array_errors = array();
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;

	$ok_done = false;
	$ok_edit = false ; 

	if(isset($user_ID)){
		// add new post 
	    if( isset($_POST['submit-addpost']) && wp_verify_nonce($_POST['submit-addpost'], 'submit-addpost') == 1) {

    		unset($_POST['submit-addpost']);
    		unset($_POST['action']);
    		$ok_process = false;

    		$array_required = array("post_name", "post_categories[]", "post_content");

	    	foreach ($_POST as $key => $value)	{
	    		if(!isset($key) || (in_array($key, $array_required) == true && strlen($value) == 0) ){
					$clear = str_replace("_", " ", $key);
					$array_errors[$key] = "<p class='ferror fcenter'>Error: You can not leave " . $clear . " empty!</p>" . sizeof($value);
				}
	    	}

	    	if(sizeof($array_errors) == 0){
	    		$my_post = array(
                    'post_title'      	=> $_POST['post_name'],
                    'post_status'     	=> 'publish',
                   	'post_author'   	=>  $user_ID,
                    'post_type'       	=> 'post',
                    'post_content'		=> $_POST['post_content']
                );    

                $id_new_post = wp_insert_post( $my_post );

                // category 
                if(isset($_POST['post_categories']) && sizeof($_POST['post_categories']) > 0){
                    $array_items = array(); 
					foreach ($_POST['post_categories']  as $item_categories ) {
						$term_new = get_term_by('id', $item_categories, 'category'); //var_dump($term_new);
                        if ($term_new == true) {
                            $array_items[] = $term_new->term_id;
                        }
					}
					wp_set_post_terms( $id_new_post,  $array_items, 'category' );
				}

				// tags 
				if( isset($_POST['post_tags']) && strlen($_POST['post_tags']) > 0 ){
					wp_set_post_tags( $id_new_post, $_POST['post_tags'], true );
				}

                if(sizeof($array_errors) == 0){
                	$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your post have been added.</p>";
                	$ok_done = true;
                }

	    	}

	    } else if( isset($_POST['submit-editpost']) &&  wp_verify_nonce($_POST['submit-editpost'], 'submit-editpost') == 1 ){ // edit new post 

	    	if(!isset($_POST['id_post'])){
	    		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    	}else{
	    		$author 		= get_post(intval($_POST['id_post'])); // gets author from post 
				$authid 		= $author->post_author; // gets author id for the post
				$id_new_post 	= $_POST['id_post'];

				if(strcmp($user_ID, $authid) == 0 ){

			    	unset($_POST['submit-addpost']);
		    		unset($_POST['action']);
		    		$ok_process = false;

		    		
		    		$array_required = array("post_name", "post_categories[]", "post_content");

			    	foreach ($_POST as $key => $value)	{
			    		if(!isset($key) || (in_array($key, $array_required) == true && strlen($value) == 0) ){
							$clear = str_replace("_", " ", $key);
							$array_errors[$key] = "<p class='ferror fcenter'>Error: You can not leave " . $clear . " empty!</p>" . sizeof($value);
						}
			    	}

			    	if(sizeof($array_errors) == 0){
			    		
	                    $my_post = array(
							'ID'           		=> $id_new_post,
		                    'post_title'      	=> $_POST['post_name'],
		                    'post_name' 		=> $_POST['post_name'],
		                    'post_status'     	=> 'publish', 
		                    'post_content'    	=> $_POST['post_content'],
		                    'post_author'   	=> $user_ID ,
		                ); 
						$update_project = wp_update_post( $my_post );

	                    // category 
	                    if(isset($_POST['post_categories']) && sizeof($_POST['post_categories']) > 0){
		                    $array_items = array(); 
							foreach ($_POST['post_categories']  as $item_categories ) {
								$term_new = get_term_by('id', $item_categories, 'category'); //var_dump($term_new);
		                        if ($term_new == true) {
		                            $array_items[] = $term_new->term_id;
		                        }
							}
							wp_set_post_terms( $id_new_post,  $array_items, 'category' );
						}

						// tags 
						if( isset($_POST['post_tags']) && strlen($_POST['post_tags']) > 0 ){
							wp_set_post_tags( $id_new_post, $_POST['post_tags'], true );
						}

		                if(sizeof($array_errors) == 0){
		                	$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your post was updated. <br/> Please wait to update the content. </p>";
		                	$ok_done = true;
		                	$ok_edit = true;
		                }

			    	}
		    	}else{
		    		$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not the author of this post.</p>";
		    	}
	    	}

	    } else {
	        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
		
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	echo json_encode( array('message' => $array_errors , 'edit' => $ok_edit  , 'done' => $ok_done ));
   	die();

}
add_action( 'wp_ajax_addpost', 'addpost' );
add_action( 'wp_ajax_nopriv_addpost', 'addpost' );

?>