<?php 
 

/********************************************* customer: update settings  *********************************************/
function user_update_customer(){

    $user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
	$user_email = $user->user_email; 
	$user_hash 	= $user->data->user_pass;
	$array_errors = array();

	if(isset($user_ID)){
		if (isset($_POST['submit-updatecustomer']) ) {
	        if(wp_verify_nonce($_POST['submit-updatecustomer'], 'submit-updatecustomer') == 1) { 

	        	$u_first_name 		= $_POST['update_first_name_customer'];
	        	$u_last_name 		= $_POST['update_last_name_customer'];
	        	$u_company			= $_POST['update_company_customer'];
	        	$u_email 			= $_POST['update_email_customer'];
	        	$u_old_password 	= $_POST['update_old_password_customer'];
	        	$u_new_password 	= $_POST['update_new_password_customer'];
	        	$u_new_password2 	= $_POST['update_new_password_customer_2'];

	        	// check password => only if user have used the normal login
	        	if(get_user_register_type() == 3){
			        $validate_password =  wp_check_password( $u_old_password, $user_hash, $user_ID ); 
		        	if($validate_password == false ){
		        		$array_errors['message'] = "<p class='ferror fcenter'>Error: The current password is wrong.</p>";
		        	}	
		        }
	        		
	        	if(!isset($u_email) || validEmail($u_email) == false ){
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: An email address is required.</p>";
	        	}

	        	// check if email exist and is not the current user 
	        	$check_user = get_user_by( 'email', $u_email );
	        	$check_user_id = $check_user->ID;

	        	if($check_user != false && $user_ID != $check_user_id){
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: This email is already used.</p>";
	        	}
		        	
	        	if(  ( isset($u_new_password) && isset($u_new_password2) &&  strcmp($u_new_password, $u_new_password2) != 0 ) ){
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: The newly entered passwords don’t match.</p>";
	        	}else if(  isset($u_new_password) && isset($u_new_password2) && strlen($u_new_password) < 5 && strlen($u_new_password) > 5  ){
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: The new password must contain at least 5 characters.</p>";
	        	}

	        	if(sizeof($array_errors) == 0 ){
	        	
	        		if(strlen($u_new_password) > 5 ){ // password > 5 
	        			$user_id = wp_update_user( array( 
    						'ID' 			=> $user_ID, 
    						'first_name' 	=> $u_first_name,
    						'last_name'		=> $u_last_name,
    						'description'	=> $u_company,
    						'user_email'	=> $u_email,
    						'user_pass'		=> $u_new_password,
    						'nickname'		=> $u_email,
    						'user_nicename'	=> $u_email
    					) );
    					
	        			if (  is_wp_error( $user_id )  ) {
    						$array_errors['message'] = "<p class='ferror fcenter'>Error: Your information has not been updated. Please try again later.</p>";
    						
    					}else{
    						update_notifications_customer();
    						$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your information has been successfully updated.</p>";
    					}
	        		}else if(!isset($u_new_password) || strlen($u_new_password) == 0){ // normal update
	        			$user_id = wp_update_user( array( 
    						'ID' 			=> $user_ID, 
    						'first_name' 	=> $u_first_name,
    						'last_name'		=> $u_last_name,
    						'description'	=> $u_company,
    						'user_email'	=> $u_email,
    						'nickname'		=> $u_email,
    						'user_nicename'	=> $u_email
    						
    					) );

    					if (  is_wp_error( $user_id )  ) {
    						$array_errors['message'] = "<p class='ferror fcenter'>Error: Your information has not been updated. Please try again later.</p>";
    						
    					}else{
    						update_notifications_customer();
    						$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your information has been successfully updated.</p>";
    					}
	        		} 

	        		if( send_email_customer_settings($u_new_password ) == false){
                       	$array_errors['message'] = "<p class='ferror fcenter'>Error: Your information has not been updated. Please try again later.</p>";
                    }else{
                      	$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your information has been successfully updated.</p>";
                    }
	        	}	
	        }else {
	           $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	        }
	    }else {
	        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
   
   echo json_encode(array('message' => $array_errors['message']  ));
   die();
}
add_action( 'wp_ajax_user_update_customer', 'user_update_customer' );
add_action( 'wp_ajax_nopriv_user_update_customer', 'user_update_customer' );





/********************************************* customer: add project *********************************************/
function addproject_customer(){

	if (isset($_POST['submit-addprojectcustomer']) || isset($_POST['submit-addprojectcustomer-notregister'])  ) { 													 // add a project withouth having an account // set as draft  						
	    if(wp_verify_nonce($_POST['submit-addprojectcustomer'], 'submit-addprojectcustomer') == 1 || wp_verify_nonce($_POST['submit-addprojectcustomer-notregister'], 'submit-addprojectcustomer-notregister') == 1  ) { 

			$customer_new_project 		= $_POST['new_project_customer']; // title (*)
			$customer_industries 		= $_POST['new_project_industries']; // (*)
			$customer_services  		= $_POST['new_project_services']; // (*)
			//$customer_material  		= $_POST['new_project_material']; // (*)
			$customer_description 		= $_POST['new_project_description']; // (*)
			$customer_color				= $_POST['new_project_color']; // (*)
			$customer_expire_date 		= $_POST['new_project_expire_date']; // (*)
			$customer_deadline_date 	= $_POST['new_project_deadline_date']; // (*)
			$customer_quantity 			= $_POST['new_project_quantity']; // (*)
			$customer_anual_quantity 	= $_POST['new_project_anual_quantity']; //..
			$customer_country			= $_POST['new_project_country']; // (*)
			$customer_target			= $_POST['new_project_target_price']; // ..
			$customer_files		    	= $_FILES['new_project_files']; // ..
			$customer_protect 			= $_POST['new_project_protect']; //..
			$ok_done = false; 
			$ok_id = false ; 
			if(isset($customer_new_project) && strlen($customer_new_project ) > 0 
				&& isset($customer_industries) && sizeof($customer_industries) > 0 
				&& isset($customer_services) && sizeof($customer_services) > 0 
				&& isset($customer_description)  && strlen($customer_description) > 100 
				&& isset($customer_color) && strlen($customer_color) > 0 
				&& isset($customer_expire_date) 
				&& isset($customer_deadline_date) 
				&& isset($customer_quantity) && intval($customer_quantity) > 0 && intval($customer_quantity) < 1000000
				&& isset($customer_country) && strlen($customer_country) > 1 ){
				
				$total_field 	= 0;
				$ok_file 		= true; 
				
				if(isset($customer_files) && $customer_files['size'][0] != 0 ){  // ?? first item has the size 0 ? 

					$extensions = array('jpg','png', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif');
					$filename 	= $_FILES['new_project_files']['name'];
					$ok_extension = 0;
					
					foreach ($_FILES['new_project_files']['name'] as $key => $value) {  
						$ext = pathinfo($_FILES['new_project_files']['name'][$key], PATHINFO_EXTENSION);
						//echo $ext."=======================";
						if(!in_array($ext, $extensions) ) {
						    $ok_extension++;
						}
					}

					if($ok_extension != 0){ // error = files aren't accepted 
						$ok_file = false; 
						$_POST['message'] = "<p class='ferror fcenter'>Error: Files aren't accepted. </p>";
					}else {
						$ok_file = true; 
					}

					if($ok_file == true ){ 
						$total_field = get_total_size_file($_FILES['new_project_files']); 
						//var_dump($total_field);
						if($total_field < 10485760){  // < 10 Mb
							$ok_file = true; 
						}else{
							$ok_file = false; 
							$_POST['message'] = "<p class='ferror fcenter'>Error: Files aren't accepted. The total size is over 10MB.</p>";
						}
					}else{

						$ok_file = true; 
					}
				}

				if($ok_file == true){
					$time = strtotime($customer_expire_date);
					$customer_expire_date2 = date('Ymd',$time);

					$time = strtotime($customer_deadline_date);
					$customer_deadline_date2 = date('Ymd',$time);

					//echo intval($customer_expire_date2) ;echo intval($customer_deadline_date2).""; 
					//echo (intval($customer_expire_date2) <= intval($customer_deadline_date2));

					if(intval($customer_expire_date2) <= intval($customer_deadline_date2)){
						
						$user_ID =  get_current_user_id(); 
						if(wp_verify_nonce($_POST['submit-addprojectcustomer-notregister'], 'submit-addprojectcustomer-notregister') == 1){ // if user is not login save the project as draft
							$my_post = array(
			                    'post_title'      	=>  $customer_new_project,
			                    'post_status'     	=>   'draft',
			                    'post_content'    	=>  $customer_description ,
			                    //'post_author'   	=>  $user_ID ,
			                    'post_type'       	=>  'project',
			                ); 
						}else {
							$my_post = array(
			                    'post_title'      	=>  $customer_new_project,
			                    'post_status'     	=>  'publish', // draft ??? 
			                    'post_content'    	=>  $customer_description ,
			                    'post_author'   	=>  $user_ID ,
			                    'post_type'       	=>  'project',
			                ); 
						}
						
						$id_new_post = wp_insert_post( $my_post );
						if (!is_wp_error($id_new_post)) {
							
							$field_color		= "field_587f438ce97c3"; 
							$field_expiredate 	= "field_587f4399e97c4";
							$field_deadline		= "field_587f43bee97c5";
							$field_quantity		= "field_587f43d6e97c6";
							$field_annual_qnt 	= "field_587f43e1e97c7"; //...
							$field_country		= "field_587f43f5e97c8";
							$field_target_price	= "field_587f4400e97c9"; // ..
							$field_fields		= "field_587f4407e97ca"; // repeater
							$field_protect		= "field_587f446ac94f0"; // ..

							$field_project_author = "field_5892f091314da"; // project author

							if(wp_verify_nonce($_POST['submit-addprojectcustomer-notregister'], 'submit-addprojectcustomer-notregister') != 1){
								update_field($field_project_author, $user_ID , $id_new_post);
							}

							// + taxonomy
							$array_items = array(); 
							foreach ($customer_industries  as $item_industry ) {
								$term_new = get_term_by('id', $item_industry, 'industry'); //var_dump($term_new);
		                        if ($term_new == true) {
		                            $array_items[] = $term_new->term_id;
		                        }
							}
							wp_set_post_terms( $id_new_post,  $array_items, 'industry' );

							$array_items = array(); 
							foreach ($customer_services  as $item_service ) {
								$term_new = get_term_by('id', $item_service, 'service'); //var_dump($term_new);
		                        if ($term_new == true) {
		                        	$array_items[] = $term_new->term_id;
		                        }
							}
							wp_set_post_terms( $id_new_post, $array_items, 'service' );
					
							update_field($field_color, $customer_color , $id_new_post);

	                        $date = DateTime::createFromFormat('Ymd',  $customer_expire_date2 );
	                        $date = $date->format('Ymd');
	                        update_field( $field_expiredate, $date,  $id_new_post );
		                        
	                        $date = DateTime::createFromFormat('Ymd',  $customer_deadline_date2 );
	                        $date = $date->format('Ymd');
	                        update_field( $field_deadline, $date,  $id_new_post );

	                        update_field($field_quantity, $customer_quantity , $id_new_post);
	                        update_field($field_country, $customer_country , $id_new_post);

	                        //var_dump($customer_anual_quantity);
	                        if(isset($customer_anual_quantity) && intval($customer_anual_quantity) > 0 && intval($customer_anual_quantity) < 1000000){
	                        	update_field($field_annual_qnt, $customer_anual_quantity , $id_new_post);
	                        }
	                       
	                        if(isset($customer_target) && strlen($customer_target)>0){
	                        	update_field($field_target_price, $customer_target , $id_new_post);
	                        }

	                        if(isset($customer_protect) && $customer_protect == "on" ){
	                        	update_field($field_protect, $customer_protect , $id_new_post);
	                        }

	                        $repeater = array();
	                        if( $total_field > 0 ){
		                     
	                        	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								require_once(ABSPATH . "wp-admin" . '/includes/file.php');
								require_once(ABSPATH . "wp-admin" . '/includes/media.php');

								$repeater = array();
	                           
                                $files = $_FILES["new_project_files"];  
                                foreach ($files['name'] as $key => $value) {            
                                    if ($files['name'][$key]) { 
                                        $file = array( 
                                            'name' => $files['name'][$key],
                                            'type' => $files['type'][$key], 
                                            'tmp_name' => $files['tmp_name'][$key], 
                                            'error' => $files['error'][$key],
                                            'size' => $files['size'][$key]
                                        ); 
                                        $_FILES = array ("new_project_files" => $file); 

                                        $size_files_uploaded = 0; 
                                        
                                        foreach ($_FILES as $file => $array) {              
                                            $attachment_id = kv_handle_attachment($file, $id_new_post); 
                                            $img_url = wp_get_attachment_url( $attachment_id ); 

                                            $repeater[] = array( 
                                                'file'       => $attachment_id,
   				                                'file_path'	 => $img_url
                                            );
                                        }
                                       
                                    } 
                                }
	                          	update_field( $field_fields , $repeater, $id_new_post );
	                        }

	                        $meta_array = array(
                                'project_info' => json_encode(array(
                                        'post_title'          	=> $customer_new_project,
                                        'material'            	=> $customer_industries,
                                        'services'        		=> $customer_services,
                                       // 'material'            	=> $customer_material,
                                        'description'           => $customer_description,
                                        'color'               	=> $customer_color,
                                        'expire_date'           => $customer_expire_date,
                                        'deadline_date'         => $customer_deadline_date,
                                        'quantity'              => $customer_quantity, 
                                        'anual_quantity'        => $customer_anual_quantity,
                                        'country'               => $customer_country,
                                        'target'                => $customer_target,
                                        'files'           		=> $repeater,
                                        'protect'              	=> $customer_protect,
                                        'author'				=> $user_ID, 
                                        'id'					=> $id_new_post
                                    )
                                )
                            );  
	                       // add_meta_box($id_new_post, 'meta_input',  $meta_array);
                         	$my_post = array(
                         		'ID'           	  => $id_new_post,
                                'meta_input'      =>  $meta_array,

                            );    

                            $update_project = wp_update_post( $my_post );
							if (!is_wp_error($update_project)) {
								if(wp_verify_nonce($_POST['submit-addprojectcustomer-notregister'], 'submit-addprojectcustomer-notregister') != 1){
		                        	$_POST['message'] = "<p class='fsuccess fcenter'>Success: The project has been added.</p>";
		                        	$ok_done = true; 
		                    	}else{ // add project withouth an account
		                    		$_POST['message'] 	= "<p class='fsuccess fcenter'>Success: The project has been added, but is not visible.<br/> Please register to make this project active.</p>";
		                    		$ok_id 				= $id_new_post;
		                    		$_SESSION['draft']	= $id_new_post;
		                    		$ok_done 			= true; 
		                    	}
		                    }else{
		                    	$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		                    }

						}else{
							$_POST['message'] = "<p class='ferror fcenter'>Error: The project has not been added.</p>";
						} 

					}else{
						$_POST['message'] = "<p class='ferror fcenter'>Error: Please check the expiration and deadline dates.</p>";
					}
				}else {
					$_POST['message'] = "<p class='ferror fcenter'>Error: Problems on uploading the file(s).</p>";
				}
				
			}else{
				$_POST['message'] = "<p class='ferror fcenter'>Error: Please check all the required fields.</p>";
			}
 	 	
 	 	} else {
	        $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
   	echo json_encode(array('message' => $_POST['message'], 'done' =>  $ok_done, 'id' => $ok_id ));
   	die();
}
add_action( 'wp_ajax_addproject_customer', 'addproject_customer' );
add_action( 'wp_ajax_nopriv_addproject_customer', 'addproject_customer' );



/********************************************* customer: edit project *********************************************/
function editproject_customer(){

	if (isset($_POST['submit-editprojectcustomer']) ) {
	    if(wp_verify_nonce($_POST['submit-editprojectcustomer'], 'submit-editprojectcustomer') == 1) { 
	    	$customer_new_project 		= $_POST['new_project_customer']; // title (*)
			$customer_industries 		= $_POST['new_project_industries']; // (*)
			$customer_services  		= $_POST['new_project_services']; // (*)
			//$customer_material  		= $_POST['new_project_material']; // (*)
			$customer_description 		= $_POST['new_project_description']; // (*)
			$customer_color				= $_POST['new_project_color']; // (*)
			$customer_expire_date 		= $_POST['new_project_expire_date']; // (*)
			$customer_deadline_date 	= $_POST['new_project_deadline_date']; // (*)
			$customer_quantity 			= $_POST['new_project_quantity']; // (*)
			$customer_anual_quantity 	= $_POST['new_project_anual_quantity']; //..
			$customer_country			= $_POST['new_project_country']; // (*)
			$customer_target			= $_POST['new_project_target_price']; // ..
			$customer_files		    	= $_FILES['new_project_files']; // ..
			$customer_protect 			= $_POST['new_project_protect']; //..

			$customer_old_files		    = $_POST['update_old_images']; // ..
			$customer_project_id 		= $_POST['project'];

			$ok_done = false; 
			if(isset($customer_new_project) && strlen($customer_new_project ) > 0 
				&& isset($customer_industries) && sizeof($customer_industries) > 0 
				&& isset($customer_services) && sizeof($customer_services) > 0 
				//&& isset($customer_material) && strlen($customer_material) > 0 
				&& isset($customer_description)  && strlen($customer_description) > 100 
				&& isset($customer_color) && strlen($customer_color) > 0 
				&& isset($customer_expire_date) 
				&& isset($customer_deadline_date) 
				&& isset($customer_quantity) && intval($customer_quantity) > 0 && intval($customer_quantity) < 1000000
				&& isset($customer_country) && strlen($customer_country) > 1 
				&& isset($customer_project_id) && strlen($customer_project_id) > 0	){

				$total_field 	= 0;
				$ok_file 		= true; 
				$user_ID =  get_current_user_id(); 
				$post_author_id = get_post_field( 'post_author', $customer_project_id );
				// check if current user is author for the project

				if(strcmp($user_ID, $post_author_id)==0){ // same author
					if(isset($customer_files) && $customer_files['size'][0] != 0 ){  // ?? first item has the size 0 ? 

						$extensions = array('jpg','png', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif');
						$filename 	= $_FILES['new_project_files']['name'];
						$ok_extension = 0;
						
						foreach ($_FILES['new_project_files']['name'] as $key => $value) {  
							$ext = pathinfo($_FILES['new_project_files']['name'][$key], PATHINFO_EXTENSION);
							//echo $ext."=======================";
							if(!in_array($ext, $extensions) ) {
							    $ok_extension++;
							}
						}

						if($ok_extension != 0){ // error = files aren't accepted 
							$ok_file = false; 
							$_POST['message'] = "<p class='ferror fcenter'>Error: Files aren't accepted. </p>";
						}else {
							$ok_file = true; 
						}

						if($ok_file == true ){ 
							$total_field = get_total_size_file($_FILES['new_project_files']); 
							//var_dump($total_field);
							if($total_field < 10485760){  // < 10 Mb
								$ok_file = true; 
							}else{
								$ok_file = false; 
								$_POST['message'] = "<p class='ferror fcenter'>Error: Files aren't accepted. The total size is over 10MB.</p>";
							}
						}else{

							$ok_file = true; 
						}
					}

					if($ok_file == true){
						$time = strtotime($customer_expire_date);
						$customer_expire_date2 = date('Ymd',$time);

						$time = strtotime($customer_deadline_date);
						$customer_deadline_date2 = date('Ymd',$time);

						//echo intval($customer_expire_date2) ;echo intval($customer_deadline_date2).""; 
						//echo (intval($customer_expire_date2) <= intval($customer_deadline_date2));

						if(intval($customer_expire_date2) <= intval($customer_deadline_date2)){
							$my_post = array(
								'ID'           		=> $customer_project_id,
			                    'post_title'      	=> $customer_new_project,
			                    'post_status'     	=> 'publish', // draft ??? 
			                    'post_content'    	=> $customer_description ,
			                    'post_author'   	=> $user_ID ,
			                ); 
							$update_project = wp_update_post( $my_post );
							if (!is_wp_error($update_project)) {

								$id_new_post = $customer_project_id ;

								//$field_material 	= "field_587f4387e97c2";
								$field_color		= "field_587f438ce97c3"; 
								$field_expiredate 	= "field_587f4399e97c4";
								$field_deadline		= "field_587f43bee97c5";
								$field_quantity		= "field_587f43d6e97c6";
								$field_annual_qnt 	= "field_587f43e1e97c7"; //...
								$field_country		= "field_587f43f5e97c8";
								$field_target_price	= "field_587f4400e97c9"; // ..
								$field_fields		= "field_587f4407e97ca"; // repeater
								$field_protect		= "field_587f446ac94f0"; // ..

								// + taxonomy
								$array_items = array(); 
								foreach ($customer_industries  as $item_industry ) {
									$term_new = get_term_by('id', $item_industry, 'industry'); //var_dump($term_new);
			                        if ($term_new == true) {
			                            $array_items[] = $term_new->term_id;
			                        }
								}
								wp_set_post_terms( $id_new_post,  $array_items, 'industry' );

								$array_items = array(); 
								foreach ($customer_services  as $item_service ) {
									$term_new = get_term_by('id', $item_service, 'service'); //var_dump($term_new);
			                        if ($term_new == true) {
			                        	$array_items[] = $term_new->term_id;
			                        }
								}
								wp_set_post_terms( $id_new_post, $array_items, 'service' );

								//update_field($field_material, $customer_material , $id_new_post);
								update_field($field_color, $customer_color , $id_new_post);

		                        $date = DateTime::createFromFormat('Ymd',  $customer_expire_date2 );
		                        $date = $date->format('Ymd');
		                        update_field( $field_expiredate, $date,  $id_new_post );
			                        
		                        $date = DateTime::createFromFormat('Ymd',  $customer_deadline_date2 );
		                        $date = $date->format('Ymd');
		                        update_field( $field_deadline, $date,  $id_new_post );

		                        update_field($field_quantity, $customer_quantity , $id_new_post);
		                        update_field($field_country, $customer_country , $id_new_post);

		                        if(isset($customer_anual_quantity) && intval($customer_anual_quantity) > 0 && intval($customer_anual_quantity) < 1000000){
		                        	update_field($field_annual_qnt, $customer_anual_quantity , $id_new_post);
		                        }
		                       
		                        if(isset($customer_target) && strlen($customer_target)>0){
		                        	update_field($field_target_price, $customer_target , $id_new_post);
		                        }

		                        if(isset($customer_protect) && $customer_protect == "on" ){
		                        	update_field($field_protect, $customer_protect , $id_new_post);
		                        }
		                        $repeater = array();
		                      
		                        // Add old files 
		                        $box = get_field('files_project', $id_new_post); 
								if ($box){ 
									foreach ($box as $box1) {	
										$file = $box1['file'];			
										if(!empty($file)) { 
											//echo $box1['slider_description']; 
											$file_id = $file['ID'];
											$file_url = $file['url'];
											if(in_array($file_id, $customer_old_files)){
												$repeater[] = array(
													'file'		=> $file_id,
													'file_path'	=> $file_url 
												);
												
											}
										}
									} 
								} 


		                        if( $total_field > 0 ){
			                     
		                        	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
									require_once(ABSPATH . "wp-admin" . '/includes/file.php');
									require_once(ABSPATH . "wp-admin" . '/includes/media.php');

									
	                                $files = $_FILES["new_project_files"];  
	                                foreach ($files['name'] as $key => $value) {            
	                                    if ($files['name'][$key]) { 
	                                        $file = array( 
	                                            'name' => $files['name'][$key],
	                                            'type' => $files['type'][$key], 
	                                            'tmp_name' => $files['tmp_name'][$key], 
	                                            'error' => $files['error'][$key],
	                                            'size' => $files['size'][$key]
	                                        ); 
	                                        $_FILES = array ("new_project_files" => $file); 

	                                        $size_files_uploaded = 0; 
	                                        
	                                        foreach ($_FILES as $file => $array) {              
	                                            $attachment_id = kv_handle_attachment($file, $id_new_post); 
	                                            $img_url = wp_get_attachment_url( $attachment_id ); 

	                                            $repeater[] = array( 
	                                                'file'       => $attachment_id,
	   				                                'file_path'	 => $img_url
	                                            );
	                                        }
	                                       
	                                    } 
	                                }
		                          	
		                        }

		                        update_field( $field_fields , $repeater, $id_new_post );

	                        	$meta_array = array(
	                                'project_info' => json_encode(array(
	                                        'post_title'          	=> $customer_new_project,
	                                        'material'            => $customer_industries,
	                                        'services'        		=> $customer_services,
	                                        //'material'            	=> $customer_material,
	                                        'description'           => $customer_description,
	                                        'color'               	=> $customer_color,
	                                        'expire_date'           => $customer_expire_date,
	                                        'deadline_date'         => $customer_deadline_date,
	                                        'quantity'              => $customer_quantity, 
	                                        'anual_quantity'        => $customer_anual_quantity,
	                                        'country'               => $customer_country,
	                                        'target'                => $customer_target,
	                                        'files'           		=> $repeater,
	                                        'protect'              	=> $customer_protect,
	                                        'author'				=> $user_ID, 
	                                        'id'					=> $id_new_post
	                                    )
	                                )
	                            );  
		                       // add_meta_box($id_new_post, 'meta_input',  $meta_array);
	                         	$my_post = array(
	                         		'ID'           	  => $id_new_post,
	                                'meta_input'      =>  $meta_array,

	                            );    

	                            $update_project = wp_update_post( $my_post );
								if (!is_wp_error($update_project)) {
			                        $_POST['message'] = "<p class='fsuccess fcenter'>Success: The project has been updated.</p>";
			                        $ok_done = true; 
			                    }else{
			                    	$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
			                    }
		                       // $_POST['message'] = "<p class='fsuccess fcenter'>Success: The project has been updated.</p>";
		                       // $ok_done = true; 

							}else{
								$_POST['message'] = "<p class='ferror fcenter'>Error: The project has not been updated.</p>";
							} 

						}else{
							$_POST['message'] = "<p class='ferror fcenter'>Error: Please check the expiration and deadline dates.</p>";
						}
					}else {
						$_POST['message'] = "<p class='ferror fcenter'>Error: Problems on uploading the file(s).</p>";
					}
				}else{
					$_POST['message'] = "<p class='ferror fcenter'>Error: It seems that you're not the author of this project. Please contact the support team.</p>";	
				}

			}else{
				$_POST['message'] = "<p class='ferror fcenter'>Error: Please check all the required fields.</p>";
			}

	    }else {
	        $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }
	}else{
		$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
	echo json_encode(array('message' => $_POST['message'], 'done' =>  $ok_done ));
   	die();
}
add_action( 'wp_ajax_editproject_customer', 'editproject_customer' );
add_action( 'wp_ajax_nopriv_editproject_customer', 'editproject_customer' );

/********************************************* customer: get size files *********************************************/
/* 
*  Function  : get_total_size_file
*  Parameters: array (files)
*  Return    : int (total size)
*/
function get_total_size_file($files){
	$files_sizes = $files['size']; 
	$total = -1;
	if(is_array ($files_sizes)){
		foreach ($files_sizes as $files_size) {
			$total += intval($files_size); 
		}
	}else{
		$total = $files['size'];
	}
	return $total; 
}

/********************************************* customer: check if a user is the author of a project *********************************************/
/* 
*  Function  : check_project_autor
*  Parameters: project_id, user_id
*  Return    : boolean
*/
function check_project_autor($project_id, $user_ID){
	$post_type = get_post_type($project_id);  
	if($post_type == "project"){
		$post_author = get_post_field( 'post_author', $project_id );
		if(intval($post_author) == intval($user_ID)){
			return true;
		}else{
			return false;
		}
	}else{	
		return false;
	}
}

/********************************************* customer: get project info (section with extra info)*********************************************/
/* 
*  Function  : get_project_inf
*  Parameters: project_id
*  Return    : html (attachments, industry/material, service, color, quantity, annual quantity, delivery date, target price)
*/
function get_project_inf($project_id){ ob_start(); ?>
	<?php $box = get_field('files_project', $project_id); ?>
	<?php	if ($box){ ?>
		<div class="row services-list">	
			<div class="cell x12">
				<p><b><?php _e('Attachments:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<ul class="servfiles"> 
				<?php foreach ($box as $box1) {  ?>					
					<?php $file_item = $box1['file']; //var_dump($file_item); ?>
					<?php if(isset($file_item)){ ?>
						<?php $file_path = $box1['file_path']; ?>
						<?php if(isset($file_path)) { ?>
							<li>
								<a href="<?php echo $file_path; ?>" target="_blank" title="<?php echo $file_item['filename']; ?>">
									<i class="fa fa-file-text-o" aria-hidden="true"></i> 
									<?php echo $file_item['filename']; ?>
								</a>
							</li>
						<?php } ?>		
					<?php } ?>
				<?php } ?>
				</ul>
			</div>
		</div>	
	<?php } ?> 

	<div class="row services-list">
	<?php 
		$terms = get_the_terms( $project_id, 'industry' );
		if ( $terms && ! is_wp_error( $terms ) ) : 
		    $draught_links = array();
		    foreach ( $terms as $term ) {
		        $draught_links[] = $term->name;
		    }
		    $on_draught = join( ", ", $draught_links );  ?>
		 
		   	<div class="cell x6">
				<p><b><?php _e('Material:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<p><?php printf( esc_html( $on_draught ) ); ?></p>
			</div>   
		<?php endif; ?>
		
	<?php 
		$terms = get_the_terms( $project_id, 'service' );
		if ( $terms && ! is_wp_error( $terms ) ) : 
		    $draught_links = array();
		    foreach ( $terms as $term ) {
		        $draught_links[] = $term->name;
		    }
		    $on_draught = join( ", ", $draught_links );  ?>
		 
		   	<div class="cell x6">
				<p><b><?php _e('Services:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<p><?php printf( esc_html( $on_draught ) ); ?></p>
			</div>   
		<?php endif; ?>
		
	</div>
	<div class="row services-list">
		<?php $color_project = get_field('color_project', $project_id); ?>
		<?php if(!empty($color_project)){ ?>
		<div class="cell x6">
			<p><b><?php _e('Color:', THEME_TEXT_DOMAIN); ?></b></p>
			<div class="sep"></div>
			<p><?php echo $color_project; ?></p>
		</div>
		<?php } ?>
	</div>

	<div class="row services-list">
		<?php $quantity_project = get_field('quantity_project', $project_id); ?>
		<?php if(!empty($quantity_project)){ ?>
			<div class="cell x6">
				<p><b><?php _e('Quantity:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<p><?php echo $quantity_project; ?></p>
			</div>
		<?php } ?>
		<?php $aqp = get_field('annual_quantity_project', $project_id); ?>
		<?php if(!empty($aqp)){ ?>
			<div class="cell x6">
				<p><b><?php _e('Annual quantity:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<p><?php echo $aqp; ?></p>
			</div>
		<?php } ?>
	</div>

	<div class="row services-list">
		<?php $pddp = get_field('project_delivey_deadline_project', $project_id); ?>
		<?php if(!empty( $pddp )){ ?>
			<div class="cell x6">
				<p><b><?php _e('Delivery date:', THEME_TEXT_DOMAIN); ?></b></p>
				<div class="sep"></div>
				<?php $date = new DateTime($pddp); ?>
				<p><?php echo $date->format('d/m/Y'); ?></p>
			</div>
		<?php } ?>
		<?php $tpp = get_field('target_price_project', $project_id); ?>
		<?php if(!empty($tpp)){ ?>
		<div class="cell x6">
			<p><b><?php _e('Target Price:', THEME_TEXT_DOMAIN); ?></b></p>
			<div class="sep"></div>
			<p><?php echo $tpp; ?></p>
		</div>
		<?php } ?>
	</div>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 								
}

/********************************************* customer: get project industry / material *********************************************/
/* 
*  Function  : get_project_industry
*  Parameters: project_id
*  Return    : html (industry/material)
*/
function get_project_industry($project_id){ ob_start(); ?>
	<?php 
	$terms = get_the_terms( $project_id, 'industry' );
	if ( $terms && ! is_wp_error( $terms ) ) : 
	    $draught_links = array();
	    foreach ( $terms as $term ) {
	        $draught_links[] = $term->name;
	    }
	    $on_draught = join( ", ", $draught_links );  ?>
	 
	  	<div class="countries">
			<h6><?php _e('Material: ', THEME_TEXT_DOMAIN); ?></h6>
			<p><?php printf( esc_html( $on_draught ) ); ?></p>
		</div>   
	<?php endif; ?>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 								
}

/********************************************* customer: get project Services *********************************************/
/* 
*  Function  : get_project_service
*  Parameters: project_id
*  Return    : html (Services)
*/
function get_project_service($project_id){ ob_start(); ?>		
	<?php 
		$terms = get_the_terms( $project_id, 'service' );
		if ( $terms && ! is_wp_error( $terms ) ) : 
		    $draught_links = array();
		    foreach ( $terms as $term ) {
		        $draught_links[] = $term->name;
		    }
		    $on_draught = join( ", ", $draught_links );  ?>
		 
		   <div class="countries">
				<h6><?php _e('Services: ', THEME_TEXT_DOMAIN); ?></h6>
				<p><?php printf( esc_html( $on_draught ) ); ?></p>
			</div>   
		<?php endif; ?>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 								
}

/********************************************* customer: generate links (edit, delete )*********************************************/
/* 
*  Function  : generate_project_links
*  Parameters: project_id
*  Return    : html (edit, remove)
*/
function generate_project_links($project_id){ ob_start(); ?>
	<?php // edit project ?>
	<?php $edpg = get_field('customer_project_edit_page_individual', 'options')->ID; ?>
	<?php if(!empty($edpg)){ ?>
		<a href="<?php echo get_permalink($edpg);?>?id=<?php echo $project_id; ?>" title="Edit" class="edit-project left">
			<i class="fa fa-pencil-square-o"></i>
			<p class="smalllabel"><?php _e('Edit', THEME_TEXT_DOMAIN); ?></p>
		</a>
	<?php } ?>
	<?php // remove project ?>
	<a href="#" class="edit-project  left removeproject">
		<i class="fa fa-times-circle" aria-hidden="true"></i>		
		<p class="smalllabel"><?php _e('Remove', THEME_TEXT_DOMAIN); ?></p>								
	</a>

	<div class="removemessage" data-id="<?php echo $project_id; ?>">
		<div class="wrappremovemsg">
			<div class="wrappload">
				<div class="loader">
					<img src="img/loading.gif" alt="">
				</div>
				<div class="contentloader">
					<div class="wrappbasictext" data-id="<?php echo $project_id; ?>"> 
						<p class="large"><?php _e('Are you sure that you want to remove this project?', THEME_TEXT_DOMAIN); ?></p>
						<div class="wrappbuttons">
							<a href="#" title="" class="button green nobutton" data-id="<?php echo $project_id; ?>"><?php _e('NO', THEME_TEXT_DOMAIN); ?> <i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
							<a href="#" title="" class="button red yesbutton" data-id="<?php echo $project_id; ?>"><?php _e('YES', THEME_TEXT_DOMAIN); ?> <i class="fa fa-times-circle-o" aria-hidden="true"></i></a> 
						</div>
					</div>
					<div class="answerremoveproject" data-id="<?php echo $project_id; ?>"></div>
				</div>
			</div>
		</div>
	</div>


<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 	
}

/********************************************* customer: generate links  delete  *********************************************/
/* 
*  Function  : generate_project_links_delete
*  Parameters: project_id
*  Return    : html (remove)
*/
function generate_project_links_delete($project_id){ ob_start(); ?>
	<?php // remove project ?>
	<a href="#" class="edit-project  left removeproject">
		<i class="fa fa-times-circle" aria-hidden="true"></i>		
		<p class="smalllabel"><?php _e('Remove', THEME_TEXT_DOMAIN); ?></p>								
	</a>

	<div class="removemessage" data-id="<?php echo $project_id; ?>">
		<div class="wrappremovemsg">
			<div class="wrappload">
				<div class="loader">
					<img src="img/loading.gif" alt="">
				</div>
				<div class="contentloader">
					<div class="wrappbasictext" data-id="<?php echo $project_id; ?>"> 
						<p class="large"><?php _e('Are you sure that you want to remove this project?', THEME_TEXT_DOMAIN); ?></p>
						<p class="large"><?php _e('This action is irreversible. You will remove all the messages and all the bids for related with this project. ', THEME_TEXT_DOMAIN); ?></p>
						<div class="wrappbuttons">
							<a href="#" title="" class="button green nobutton" data-id="<?php echo $project_id; ?>"><?php _e('NO', THEME_TEXT_DOMAIN); ?> <i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
							<a href="#" title="" class="button red yesbutton" data-id="<?php echo $project_id; ?>"><?php _e('YES', THEME_TEXT_DOMAIN); ?> <i class="fa fa-times-circle-o" aria-hidden="true"></i></a> 
						</div>
					</div>
					<div class="answerremoveproject" data-id="<?php echo $project_id; ?>"></div>
				</div>
			</div>
		</div>
	</div>

<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 	
}

/********************************************* customer: remove project  *********************************************/
function removeproject() {
	$ok_output = false; 
	
	if(isset($_POST['action']) && strcmp($_POST['action'], 'removeproject') == 0){
		$user = wp_get_current_user(); 
	    $userid = get_current_user_id(); 
	    $project_id = $_POST['id']; 

	    if(isset($project_id) && strlen($project_id) > 0){
	    	$auth = get_post($project_id); // gets author from post
			$authid = $auth->post_author;

			if($userid == $authid){
				// delete project 
				$output = wp_trash_post(  $project_id); // Set to False if you want to send them to Trash.
				
				// remove all the bids for this project 
				$args_2 =  array( 
		            'ignore_sticky_posts' 	=> true, 
		            'post_type'           	=> 'bid',
		            'order'              	=> 'DESC',
		            'posts_per_page'		=> -1,
		            'meta_query' => array(
						array(
					        'key'		=> 'project',
					        'compare'	=> '=',
					        'value'		=> $project_id,
					    )
				    ),
				);   
			 	$loop = new WP_Query( $args_2 );
			 	if ($loop->have_posts()) { 
 				 	while ($loop->have_posts())	{  $loop->the_post();
 				 		$bid_id = get_the_ID();
 				 		$output = wp_trash_post( $bid_id ); 	
 				 	}
 				}
			 	wp_reset_postdata();

			 	// remove all the messages for this project 
			 	$args_3 =  array( 
		            'ignore_sticky_posts' 	=> true, 
		            'post_type'           	=> 'message',
		            'order'              	=> 'DESC',
		            'posts_per_page'		=> -1,
		            'meta_query' => array(
						array(
					        'key'		=> 'project',
					        'compare'	=> '=',
					        'value'		=> $project_id,
					    )
				    ),
				);   
			 	$loop = new WP_Query( $args_3 );
			 	if ($loop->have_posts()) { 
 				 	while ($loop->have_posts())	{  $loop->the_post();
 				 		$message_id = get_the_ID();
 				 		$output = wp_trash_post( $message_id ); 	
 				 	}
 				}
			 	wp_reset_postdata();


				if($output != false){
					$_POST['message'] = "<p class='fsuccess fcenter large'>Success: Project successfully removed. <br/><br/>Please wait to update the information. </p>";
					$ok_output = true;
				}else{
					$_POST['message'] = "<p class='ferror fcenter large'>Error: Problems on remove this project.</p>"; 
				}

			}else{
				$_POST['message'] = "<p class='ferror fcenter large'>Error: You are not the author of this project.</p>";
			}

	    }else{
	    	$_POST['message'] = "<p class='ferror fcenter large'>Error: Please try again later.</p>";
	    }

	}else {
		$_POST['message'] = "<p class='ferror fcenter large'>Error: You are not allow to remove projects.</p>";
	}
	echo json_encode(array('message' => $_POST['message'], 'done' =>  $ok_output ));
   	die();
}
add_action( 'wp_ajax_removeproject', 'removeproject' );
add_action( 'wp_ajax_nopriv_removeproject', 'removeproject' );


/********************************************* customer: get expiration date == achive  *********************************************/
/* 
*  Function  : get_expiration_date_archive
*  Parameters: project_id
*  Return    : html (Expired on)
*/
function get_expiration_date_archive($project_id){ ob_start(); ?>
	<?php 
		$date = get_field('project_expire_date_project', $project_id);
		$date = new DateTime($date);
	?>
		<p><b><?php _e('Expired on:', THEME_TEXT_DOMAIN); ?></b>
		<?php echo $date->format('d.m.Y'); ?></p>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 			
}

/********************************************* customer: get expiration date == no achive  *********************************************/
/* 
*  Function  : get_expiration_date
*  Parameters: project_id
*  Return    : html (Expires)
*/
function get_expiration_date($project_id){ ob_start(); ?>
	<?php 
		$date = get_field('project_expire_date_project', $project_id);
		$date = new DateTime($date);
	?>
		<p><b><?php _e('Expires:', THEME_TEXT_DOMAIN); ?></b>
		<?php echo $date->format('d/m/Y'); ?></p>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 			
}

/********************************************* customer: get creation date *********************************************/
/* 
*  Function  : get_creation_date
*  Parameters: project_id
*  Return    : html (Created)
*/
function get_creation_date($project_id){ob_start(); ?> 
	<p><b><?php _e('Created:', THEME_TEXT_DOMAIN); ?></b>
	<?php echo get_the_date('d/m/Y', $project_id); ?></p>
<?php 
	$project_info = ob_get_clean(); 
	return $project_info; 			
}


/********************************************* customer: associate project draft with current user  *********************************************/
/* 
*  Function  : associate_draft
*  Parameters: 
*  Return    : none; update value from $_Session['draft'] for user that is login
*/
function associate_draft(){
	if(isset($_SESSION['draft']) && strlen($_SESSION['draft']) > 0) {
		if( is_user_logged_in()){
			$user = wp_get_current_user(); 
		 	$user_ID = $user->ID;

			$id_project = intval($_SESSION['draft']);

			// update field in project post type
			$field_author = 'field_5892f091314da';
			update_field($field_author, $user_ID , $id_project); // update id 

			// update author of a project 
			$arg = array(
			    'ID' 			=> $id_project,
			    'post_author' 	=> $user_ID,
			);
			wp_update_post( $arg );
		}
	}
}


/********************************************* customer: activate a project that is draft  *********************************************/

function action_activate_project(){
 
    $user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
	$user_email = $user->user_email; 

	$array_errors = array();
	$ok_done = false;

	if(isset($user_ID)){
		if (isset($_POST['action']) && strcmp($_POST['action'], "action_activate_project") == 0) {
	       	$project_id = $_POST['project_id'];
	       	if(isset($project_id) && strlen($project_id) > 0 ){
	       		// check if user is the author of this project 
	       		$author = get_field( 'project_author' , $project_id ); 
	       		if( strcmp($user_ID, $author['ID']) == 0 ){
	       			// change status of this project 
	       			$arg = array(
					    'ID' 			=> $project_id,
					    'post_status' 	=> 'publish',
					);
					wp_update_post( $arg );

					// check the expiration day 
					$today = date('Ymd');
					$exp_date = get_field('project_expire_date_project' , $project_id ); 
					$dev_date = get_field('project_delivey_deadline_project', $project_id );

					$date = DateTime::createFromFormat('Ymd',  $exp_date );
                    $date = $date->format('Ymd');

                    if(intval($date) < intval($today)){
                    	update_field( $field_expiredate, $today,  $id_new_post );
                    }
                    
                    $date = DateTime::createFromFormat('Ymd',  $dev_date );
                    $date = $date->format('Ymd');
                    if(intval($date) < intval($today)){
                    	update_field( $field_deadline, $today,  $id_new_post );

                	}

                	$ok_done = true;

	       		}else{
	       			$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not the author of this project.</p>";
	       		}

	       	}else {
	       		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	       	}
	    }else {
	        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
   
   echo json_encode(array('message' => $array_errors , 'done' => $ok_done ));
   die();
}
add_action( 'wp_ajax_action_activate_project', 'action_activate_project' );
add_action( 'wp_ajax_nopriv_action_activate_project', 'action_activate_project' );



?>