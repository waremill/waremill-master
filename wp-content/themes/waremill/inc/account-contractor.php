<?php 


/********************************************* Change structure $_FILE *********************************************/
/* 
*  Function  : reArrayFiles
*  Parameters: $_FILE
*  Return    : reorder array
*/
function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

/********************************************* contractor: update settings  *********************************************/
function user_update_settings_contractor(){
	
	$user 			= wp_get_current_user(); 
	$user_ID 		= $user->ID;
	$user_hash 		= $user->data->user_pass;
	$array_errors 	= array();

	if(isset($user_ID)){
		if (isset($_POST['submit-form']) ) {
	        if(wp_verify_nonce($_POST['submit-form'], 'submit-form') == 1) {

	        	if(check_role_current_user("contractor") == true){

	        		unset($_POST['submit-form']);
	        		unset($_POST['action']);
	        		$ok_process = false;

	        		$array_required = array("first_name", "last_name",  "email", "additional_text", "contact_person_name","contact_person_email", "country", "city" );
	        		$pass	= $_POST["contractor_password"];
	        		$pass1 	= $_POST["contractor_new_password"];
			        $pass2 	= $_POST["contractor_new_password2"];
			        $next 	= true;

			        // For users that have this => user that register normaly
			        if(get_user_register_type() == 3){ 
			        	$validate_password =  wp_check_password( $pass, $user_hash, $user_ID ); 
			        	if($validate_password == false ){
			        		$array_errors['message'] = "<p class='ferror fcenter'>Error: The current password is wrong.</p>";
			        		$next = false;
			        	}	
			        }

			        // Validate passwords 
			        if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) != 0){
			        	$array_errors[$key] = '<p class="ferror fcenter">Error: The newly entered passwords don’t match.</p>';
			            $next = false;
			        }else if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) == 0 && strlen($pass1) < 5 &&  strlen($pass1) > 0 ) {
			        	$array_errors[$key]= '<p class="ferror fcenter">Error: The passwords should have at least 5 characters!</p>';
			            $next = false;
			        }


			        if($next == true) { $count = 0; 
			        	$count_porofolio = 0; 
			        	foreach ($_POST as $key => $value)	{
			        		
							if(!isset($key) || (in_array($key, $array_required) == true && strlen($value) == 0) ){
								$clear = str_replace("_", " ", $key);
								$array_errors[$key] = "<p class='ferror fcenter'>Error: You can not leave " . $clear . " empty!</p>" . sizeof($value);
							}
			        		
							if(isset($key) && strpos( $key, 'email' )!== false  ){
								if(validEmail($value) == false){
									$clear = str_replace("_", " ", $key);
									$array_errors[$key] = "<p class='ferror fcenter'>Error: " . ucfirst ($clear) . " is not valid!</p>";
								}
							}

							if(isset($key) && strpos( $key, 'website' )!== false && strlen($value) > 0 ){
								if(validURL($value) == false && strlen($value) > 0  ){
									$clear = str_replace("_", " ", $key);
									
									$array_errors[$key] = "<p class='ferror fcenter'>Error: " . ucfirst($clear) . " is not valid2!</p>";
								}
							}

							if(isset($key) && (strcmp($key, 'service') == 0 || strcmp($key, 'industry') == 0 ) && sizeof($value) == 0 ){
								if(sizeof($value) < 1){
									$clear = str_replace("_", " ", $key);
									if(strcmp($key, 'industry') == 0){
										$array_errors[$key] = "<p class='ferror fcenter'>Error: Please select at least a  material!</p>";
									}else{
										$array_errors[$key] = "<p class='ferror fcenter'>Error: Please select at least a " . $clear . "!</p>";
									}
									
								}
							}

							if(isset($key) && strcmp($key, 'additional_text') == 0 ){
								if( (strlen($value) >= 0  && strlen($value) < 100)  ||  (strlen($value) > 100000) ){
									$clear = str_replace("_", " ", $key);
									$array_errors[$key] = "<p class='ferror fcenter'>Error: You need to add at between 100-100000 characters on " . $clear . "!</p>";
								}
							}

							if(isset($key) && strpos( $key, 'contractor_old_portofolio' )!== false  ){
								$old_portofolio = $_POST['contractor_old_portofolio'];
								foreach ($old_portofolio as $key_val => $value) {
									$old_portofolio_size = sizeof($old_portofolio[$key_val]['id'.$key_val]);
									if($old_portofolio_size == 0 || $old_portofolio_size >3 ){
										$array_errors[$key] = "<p class='ferror fcenter'>Error: Problem with your old portfolio!</p>";
										break;
									}
								}
							}
							if(isset($key) && strpos( $key, 'contractor_old_certificates' )!== false  ){
								$old_certificates = $_POST['contractor_old_certificates'];
								foreach ($old_certificates as $key_val => $value) {
									if(strlen($old_certificates[$key_val]['name']) <=0 ){
										$array_errors[$key] = "<p class='ferror fcenter'>Error: Problem with your old certificates!</p>";
										break;
									}
								}
							}
						}	

						//  check files in portfolio
						$extensions = array('jpg','png', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif');
						$extensions_images = array('jpg','png', 'jpeg', 'gif');

						// portofolio
						if(isset($_POST['portofolio']) && sizeof($_POST['portofolio']) > 0 ){
							$count_porofolio = sizeof($_POST['portofolio']);
							foreach( $_POST['portofolio'] as $key_p => $value_p ){
								if(isset($_FILES['portofolio'])){
									// remove empty fields 
									$count_files_row 	= 0; 
									$size_files_row 	= 0;
									$total_portofolio 	= array(); 

									$portofolio_new = reArrayFiles($_FILES['portofolio']);
									$portofolio_new_size = sizeof(reArrayFiles($_FILES['portofolio']));
									
									foreach ($portofolio_new as $key => $value)	{
										if(isset($value['name']['images'.$key_p]) && strlen($value['name']['images'.$key_p][0]) > 0 ){
											
											$count_files_row ++;
											$size_files_row += $value['size']['images'.$key_p][0];
											$ext = pathinfo($value['name']['images'.$key_p][0], PATHINFO_EXTENSION);

											if(empty($ext)){
												$array_errors['portofolio'] = "<p class='ferror fcenter'>Error: File is missing from portfolio!</p>";
											   break; 
											}else if(!in_array($ext, $extensions_images) ) {
											   $array_errors['portofolio'] = "<p class='ferror fcenter'>Error: File not accepted in portfolio field!</p>";
											   break; 
											}
										}
									}
									
									if($count_files_row > 3){
										$array_errors['portofolio'] = "<p class='ferror fcenter'>Error: Maximum 3 images per gallery!</p>";
										break; 
									}else if($size_files_row > 20971520){
										$array_errors['portofolio_size'] = "<p class='ferror fcenter'>Error: A gallery shouldn't have more than 20 MB!</p>";
										break; 
									}else if(  (strlen($value_p['name']) == 0 && $count_files_row > 0 ) || 
												(strlen($value_p['name']) > 0 && $count_files_row == 0 )){
										$array_errors['portofolio_no'] = "<p class='ferror fcenter'>Error: A gallery should have a name and some files!</p>";
										break; 
									}
								}
							}
						}

						// test logo 
						if(isset($_FILES['logo'])){
							if(isset($_FILES['logo']['name']) && strlen($_FILES['logo']['name'])>0){
								$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
								if(!in_array($ext, $extensions_images) ) {
									$array_errors['logo'] = "<p class='ferror fcenter'>Error: File not accepted in logo field!</p>";
								}else if($_FILES['logo']['size'] > 5242880 ){
									$array_errors['logo'] = "<p class='ferror fcenter'>Error: The logo shouldn't have more than 5 MB!</p>";
								}
							}
						}

						// company associated
						$associated_company = get_field('associated_company', 'user_'.$user_ID );
						if(!empty($associated_company)){
							$associated_user = get_field('associated_user', $associated_company);
							if(!empty($associated_user)){
								if($associated_user['ID'] == $user_ID){
									// ok start validate and update
									$ok_status = false; 
									if(get_post_status ( $associated_company ) == "draft" || get_post_status ( $associated_company ) == "publish"){
										$ok_status = true;

									}else{
										$array_errors['message'] = "<p class='ferror fcenter'>Error: You company status can't be changed. Please contact us.</p>"; 
									}
									
								}else{
									$array_errors['message'] = "<p class='ferror fcenter'>Error: User and company aren't match.</p>";
								}
							}else{
								$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not associated with a company page. Please contact us.</p>";
							}
						}else {// create new post??
							$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not associated with a company page. Please contact us.</p>";
						}

						// check if there is a username with this email address 

						$exists = email_exists($_POST['email']);
  						if ( $exists && strcmp($user->user_email , $_POST['email']) !=0 ){ // user exist 
  							$array_errors['message'] = "<p class='ferror fcenter'>Error: This email address is already used another user.</p>";
  						}
						
			        }

			        if(sizeof($array_errors) == 0 ){
			        	// ok - update fields 
			        	$answer = contractor_update_info();
			        	if(!empty($answer)){
			        		$array_errors = $answer;
			        	}else{
			        		$array_errors['success'] = "<p class='fsuccess fcenter'>Success: Your information was successfully updated.</p>";
			        	}
			        }
				}else{
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not a contractor.</p>";
	        	}
	        }else {
	       	 	$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    	}	
	    }else {
	        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid User.</p>";
	}

	echo json_encode( array('message' => $array_errors ));
   	die();

}
add_action( 'wp_ajax_user_update_settings_contractor', 'user_update_settings_contractor' );
add_action( 'wp_ajax_nopriv_user_update_settings_contractor', 'user_update_settings_contractor' );

/********************************************* contractor:  update settings update_fields  *********************************************/
function contractor_update_info(){
	$field_company_name 	= "field_5889a8373161f";
	$field_additional_text 	= "field_5889a8b631622";
	$field_country			= "field_5889a89c31620";
	$field_city				= "field_5889a8f431627";
	$field_webpage			= "field_5889a8e331625";
	$field_workshop_address = "field_5889a8ec31626";
	$field_phone_number		= "field_5889a8d431624";
	$field_machinery_pack	= "field_5889a8a131621"; // List of machines (Machinery Pack)
	$field_vat_number 		= "field_5889a8fd31628";
	$field_company_registration_number = "field_5889a90331629";
	$field_established_in 	= "field_5889a90b3162a";
	$field_portofolio 		= "field_5889a9193162c";
	$field_certificates		= "field_5889a97331630";
	$field_logo				= "field_5889a99e31634"; 
	$field_url_logo			= "field_5889a9b531635"; 
	$field_location_google_maps	= "field_5889aa25292fc";
	// new
	$field_company_address 		= "field_58b00d15736b6";
	$field_company_phone_number = "field_58b00d29736b7";
	$field_company_list_of_machines = "field_58b00d42736b8"; 

	$field_new_procurement 	= "field_58c8f0e3a0327";
	$field_new_message 		= "field_58c8f10aa0328";
	$field_get_hired		= "field_58c8f12ba0329";
	$field_new_forum_posts 	= "field_58c8f13fa032a";
	$field_newsletter 		= "field_58c8f153a032b";


	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	$associated_company = get_field('associated_company', 'user_'.$user_ID );

	$array_errors_update = array();
	$array_portofolio 	= array();
	$array_certificates = array();

	//var_dump($_POST);

	foreach ($_POST as $key => $value)	{
		
		switch ($key) {
		    case "contractor_new_password":
		       	$user_id = wp_update_user( array( 
					'ID' 			=> $user_ID, 
					'user_pass'		=> $value 
				) );
				if (  is_wp_error( $user_id )  ) {
					$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Your password has not been updated. Please try again later.</p>";
				}
		        break;
		    case "first_name":
		        $user_id = wp_update_user( array( 
					'ID' 			=> $user_ID, 
					'first_name'	=> $value
				) );
				if (  is_wp_error( $user_id )  ) {
					$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Your first name has not been updated. Please try again later.</p>";
				}	
		        break;
		    case "last_name":
		       	$user_id = wp_update_user( array( 
					'ID' 			=> $user_ID, 
					'last_name'		=> $value
				) );
				if (  is_wp_error( $user_id )  ) {
					$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Your last name has not been updated. Please try again later.</p>";
				}	
		        break;
		    case "company_name":
		    	$user_id = wp_update_user( array( 
					'ID' 			=> $user_ID, 
					'description'	=> $value
				) );
		    	if (  is_wp_error( $user_id )  ) {
					$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Your company name has not been updated. Please try again later.</p>";
				}
				update_field( $field_company_name , $value, $associated_company );	


				// update contractor title 
				$associated_company_content = get_field('associated_company', 'user_'. $user_ID );
				if(!empty($associated_company_content)){
				  	$my_post = array(
				      	'ID'           	=> $associated_company_content,
				      	'post_title'   	=> $value,
				      	'post_name' 	=> "contractor-".$value,
				  	);
					// Update the post into the database
					wp_update_post( $my_post );
				}
		    	break;
		    case "email":
		    	$user_id = wp_update_user( array( 
					'ID' 			=> $user_ID, 
					'user_email'	=> $value
				) );

				if (  is_wp_error( $user_id )  ) {
					$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Your email has not been updated. Please try again later.</p>";
				}	
		    	break;
		    case "additional_text":	
		    	update_field( $field_additional_text , $value, $associated_company );	
		     	break;
		    case "service":
		    case "industry":
		    	$array_items = array(); 
				foreach ($value  as $item_industry ) {
					$term_new = get_term_by('id', $item_industry, $key); //var_dump($term_new);
                    if ($term_new == true) {
                        $array_items[] = $term_new->term_id;
                    }
				}
				
				wp_set_post_terms( $associated_company,  $array_items, $key );
		    	break;
		    case "company_address":
		    	update_field( $field_company_address , $value, $associated_company );
		    	$search_string = str_replace(' ', '+', $value).'+'.str_replace(' ', '+', $_POST['country']).'+'.str_replace(' ', '+', $_POST['city']); 
		    	$coordonates = get_lat_long($search_string);
		    	
		    	if(!empty($coordonates)){
		    		$value_location = array("address" => $value, "lat" => $coordonates['lat'], "lng" => $coordonates['lng'], "zoom" => 14);
					update_field($field_location_google_maps, $value_location, $associated_company);
		    	}
		    	break;
		    case "company_phone_number":
		    	update_field( $field_company_phone_number , $value, $associated_company );
		    	break;	
		    case "contact_person_name":
		    	update_field( $field_contact_person , $value, $associated_company );	
		    	break;
		    case "company_list_of_machines":
		    	update_field( $field_company_list_of_machines , $value, $associated_company );	
		    	break;
		    case "contact_person_phone_number":
		    	update_field( $field_phone_number, $value, $associated_company );	
		    	break;
		    case "website":	
		    	update_field( $field_webpage, $value, $associated_company );	
		     	break;
		    case "workshop_address":
		    	update_field( $field_workshop_address, $value, $associated_company );		
		    	break;
		    case "country":
		    	update_field( $field_country, $value, $associated_company );
		    	break;
		    case "city":
		    	update_field( $field_city, $value, $associated_company );	
		     	break;
		    case 'skills_and_know_how':
		    	update_field( $field_skills_and_know, $value, $associated_company );	
		    	break;
		    case 'list_of_machines':
		   	 	update_field( $field_machinery_pack, $value, $associated_company );
		    	break;
		    case 'vat_number':
		    	update_field( $field_vat_number, $value, $associated_company );
		    	break;
		    case 'company_registration_number':
		    	update_field( $field_company_registration_number, $value, $associated_company );
		    	break;
		    case 'established_in':
		    	update_field( $field_established_in, $value, $associated_company );
		    	break;
		    case 'no_of_employees':
		    	update_field( $field_no_of_employees, $value, $associated_company );
		     	break;
		    case 'contractor_old_portofolio':
		   		$size_old_certificate = sizeof($value);
		   		if($size_old_certificate > 0 ){ 
		   			foreach ($value as $key_val => $value_val) {
		   				if(isset($value_val['id'.$key_val]) && isset($value_val['name']) && sizeof($value_val['id'.$key_val]) > 0 && strlen($value_val['name']) > 0 ){
		   					$array_files = array();
		   					foreach ($value_val['id'.$key_val] as $key_single => $value_single) {
		   						$att_url = wp_get_attachment_url($value_val['id'.$key_val][$key_single]);
		   						$array_files[] = array(
									'file' 				=> $value_val['id'.$key_val][$key_single],
						       		'url_document'		=> $att_url
		   						);
		   					}	

		   					$array_portofolio[] = array( 
				    			'description' 		=> $value_val['name'],
						       	'images'      		=> $array_files 
						    );	
		   				}
		   			}
		   		}
		    	break;
		    case 'contractor_old_certificates':
		    	
		    	$size_old_certificate = sizeof($value);
		    	if($size_old_certificate > 0 ){ 
			    	foreach ($value as $key_val => $value_val) {
			    		if( isset($value_val['name']) && strlen($value_val['name']) > 0 && trim($value_val['name']) ){
			    			//$att_url = wp_get_attachment_url($value_val['id']);
				    		$array_certificates[] = array( 
				    			'certificate_name' 	=> $value_val['name'],
						    );	
			    		}else{
			    			$array_errors['['.$key.']']  = "<p class='ferror fcenter'>Error: Problems on upload the old certificates. Please try again later.</p>";
			    			break;
			    		}
			    	}		
		    	} 
		    	break;	
		    case 'certificates':
		    	$size_certificates = sizeof($value);
	    		if($size_certificates > 0 ){ 
	    			foreach ($value as $key_val => $value_val) {
	    				if( isset($value_val['name']) && strlen($value_val['name']) > 0 && trim($value_val['name']) ){
		    				$array_certificates[] = array( 
					    		'certificate_name' 	=> $value_val['name'],
							);	
	    				}
	    			}
	    		}
		    	break;
		    case 'contractor_logo': // exist old logo - exit 

		    	break;	
		    default:
		        break;
		}
	}

	update_notifications_contractor();

	if( !isset($_POST['company_name'] ) || strlen($_POST['company_name']) == 0){
		$associated_company_content = get_field('associated_company', 'user_'. $user_ID );
		$full_user_name = ucwords($_POST['first_name']).' '. ucwords($_POST['last_name']) ;
		if(!empty($associated_company_content)){
		  	$my_post = array(
		      	'ID'           	=> $associated_company_content,
		      	'post_title'   	=>  $full_user_name,
		      	'post_name' 	=> "contractor-". $full_user_name,
		  	);
			// Update the post into the database
			wp_update_post( $my_post );
		}
	}

	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	foreach ($_FILES as $key => $value)	{
		switch ($key) {
		    case "portofolio":
		    	$portofolio_new = reArrayFiles($_FILES['portofolio']);
		    	$files = $_FILES["portofolio"];  
		    	foreach( $_POST['portofolio'] as $key_p => $value_p ){
		    		$array_files = array();
			    	foreach ($files['name'] as $key => $value) {            
				        if(isset($files['name'][$key]['images'.$key_p]) && strlen($files['name'][$key]['images'.$key_p][0]) > 0 ){	
				        	$file = array( 
				                    'name' 		=> $files['name'][$key]['images'.$key_p][0],
				                    'type' 		=> $files['type'][$key]['images'.$key_p][0], 
				                    'tmp_name'	=> $files['tmp_name'][$key]['images'.$key_p][0], 
				                    'error' 	=> $files['error'][$key]['images'.$key_p][0],
				                    'size' 		=> $files['size'][$key]['images'.$key_p][0]
				            ); 
				            $_FILES = array ("portofolio" => $file); 
				            foreach ($_FILES as $file => $array) {	
								$attachment_id = kv_handle_attachment($file, $associated_company); 
								$att_url = wp_get_attachment_url($attachment_id);
								$array_files[$key] = array(
						       		'file' 				=> $attachment_id,
						       		'url_document'		=> $att_url
							    );
							}
				        }
				    }
					if(sizeof($array_files)){
						$array_portofolio[] = array( 
			    			'description' 		=> $_POST['portofolio'][$key_p]['name'],
					       	'images'      		=> $array_files
				    	);
					}
							
				}
		    	break; 
		    case "logo":

				$attachment_id = media_handle_upload('logo',  $associated_company);
				if(!empty($attachment_id) && !is_wp_error($attachment_id) ){
					update_field($field_logo, $attachment_id,  $associated_company);
					$att_url = wp_get_attachment_image_src($attachment_id);
					update_field($field_url_logo, $att_url[0],  $associated_company);
				} else {
					if(!isset($_POST['contractor_logo'])){ // empty field => remove logo
						update_field($field_logo, null,  $associated_company);
						$att_url = wp_get_attachment_image_src($attachment_id);
						update_field($field_url_logo, null,  $associated_company);
					}
				}	
		    	break;	
		    default:
		    	break;
		}
	}

	update_field( $field_portofolio, $array_portofolio, $associated_company );
	update_field( $field_certificates, $array_certificates, $associated_company );    
	return $array_errors_update; 
}

/********************************************* Get lat and long of a address *********************************************/
/* 
*  Function  : get_lat_long
*  Parameters: string (address)
*  Return    : array (with lang and long)
*/
function get_lat_long($address){
	$coordonates = array();
	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
	$json = json_decode($json);
	$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
	$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	$coordonates['lat'] = $lat;
	$coordonates['lng'] = $long;
	return $coordonates;
}


/********************************************* contractor: change password  *********************************************/
function change_password_form(){

	$user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
	$user_hash 	= $user->data->user_pass;
	$array_errors = array();

	if(isset($user_ID) ){
		if (isset($_POST['submit-change-password']) ) {
	        if(wp_verify_nonce($_POST['submit-change-password'], 'submit-change-password') == 1) {
	        	if(check_role_current_user("contractor") == true){

	        		$pass	= $_POST["contractor_password"];
	        		$pass1 	= $_POST["contractor_new_password"];
			        $pass2 	= $_POST["contractor_new_password2"];

			        $next = true;

			        if(get_user_register_type() == 3){ // for users that have this 
			        	 $validate_password =  wp_check_password( $pass, $user_hash, $user_ID ); 
			        	if($validate_password == false ){
			        		$array_errors['message'] = "<p class='ferror fcenter'>Error: The current password is wrong.</p>";
			        		$next = false;
			        	}	
			        }

			        if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) != 0){
			        	$array_errors['message'] = '<p class="ferror fcenter">Error: The newly entered passwords don’t match.</p>';
			            $next = false;
			        }else if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) == 0 && strlen($pass1) < 5 &&  strlen($pass1) > 0 ) {
			        	$array_errors['message']= '<p class="ferror fcenter">Error: The passwords should have at least 5 characters!</p>';
			            $next = false;
			        }	

			        if(sizeof($array_errors['message']) == 0 ){
			        	$user_id = wp_update_user( array( 
							'ID' 			=> $user_ID, 
							'user_pass'		=> $pass1 
						) );
						if (  is_wp_error( $user_id )  ) {
							$array_errors['message']  = "<p class='ferror fcenter'>Error: Your password has not been updated. Please try again later.</p>";
						}else {
							$array_errors['message']  = "<p class='fsuccess fcenter'>Success: Your password has been updated.</p>";
						}
			        }

	        	}else{
	        		$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not a contractor.</p>";
	        	}

	        }else{
	        	$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	        }
	    }else{
	    	$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid User.</p>";
	}
	echo json_encode( array('message' => $array_errors ));
   	die();

}
add_action( 'wp_ajax_change_password_form', 'change_password_form' );
add_action( 'wp_ajax_nopriv_change_password_form', 'change_password_form' );

/********************************************* Get all users with a specific role *********************************************/
/* 
*  Function  : get_all_contractors_selected
*  Parameters: id ( to mark the current), role
*  Return    : options (with users) and select the user with the id in parameters; and with a company associated
*/
function get_all_contractors_selected($contractor_id, $role){ ob_start(); ?>
	<?php 
	
	$user 			= wp_get_current_user(); 
	$user_ID 		= $user->ID;

	$args = array(
		'role__in' => $role,
		'meta_query' => array(
			'relation'	  => "AND",
			array(
				'key'     => 'associated_company',
				'value'   => '',
	 			'compare' => '!='
			),
			array(
		        'key'		=> 'inactive_account',
		        'compare'	=> '!=',
		        'value'		=> true,
		    )
		),
		'exclude' => array( $user_ID  ) 
	);

	$user_query = new WP_User_Query( $args );
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) { ?>
			<option value="<?php echo $user->ID; ?>" <?php if($user->ID == $contractor_id){ echo "selected='selected'"; } ?>><?php echo ucwords($user->user_firstname).' '.ucwords($user->user_lastname);  //$user->user_email; ?></option>
		<?php 
		}
	} 

	$project_info = ob_get_clean(); 
	return $project_info; 
}

/********************************************* Get all users with a specific role = no mark *********************************************/
/* 
*  Function  : get_all_customers_selected
*  Parameters: id ( to mark the current), role
*  Return    : options (with users) and select the user with the id in parameters
*/
function get_all_customers_selected($contractor_id, $role){ ob_start(); ?>
	<?php 
	//var_dump($role);
	$user 			= wp_get_current_user(); 
	$user_ID 		= $user->ID;

	$args = array(
		'role__in' => $role,
		'exclude' => array( $user_ID  ),
		'meta_query' => array(
			array(
		        'key'		=> 'inactive_account',
		        'compare'	=> '!=',
		        'value'		=> true,
		    )
	    ) 
	);

	$user_query = new WP_User_Query( $args );
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) { ?>
			<option value="<?php echo $user->ID; ?>" <?php if($user->ID == $contractor_id){ echo "selected='selected'"; } ?>><?php  echo ucwords($user->user_firstname).' '.ucwords($user->user_lastname); //echo $user->user_email; ?></option>
		<?php 
		}
	} 
	
	$project_info = ob_get_clean(); 
	return $project_info; 
}




/********************************************* Verify if a user is associated with a company *********************************************/
/* 
*  Function  : check_associated_user
*  Parameters: company_id, user_id
*  Return    : boolean
*/
function check_associated_user($company_id, $user_id){

	$as_user = get_field('associated_user', $company_id); 
	if(strcmp($as_user['ID'], $user_id) == 0 ){
		return true;
	}else {
		return false; 
	}

}


/********************************************* OLD contractors (from old site): change password - and update the account  *********************************************/
function user_get_new_password(){

	$user_id 		= $_POST['user_id_send'];
	$token_id 		= $_POST['user_token_send'];	
	$token_id_field = get_field('hash_inactive_account', 'user_'. $user_id); 
	$array_errors 	= array();
	$ok_redirect	= false; 
	$ok_link 		= '';

	if(isset($user_id) && isset($token_id) && strcmp($token_id, $token_id_field )==0){ 
		$check_inactive = get_field('inactive_account', 'user_'.$user_id);
		if($check_inactive == true){ // true == 1 
			if (isset($_POST['submit-get-new-password']) ) {
		        if(wp_verify_nonce($_POST['submit-get-new-password'], 'submit-get-new-password') == 1) {
		        	
		        		$pass1 	= $_POST["contractor_new_password"];
				        $pass2 	= $_POST["contractor_new_password2"];

				        if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) != 0){
				        	$array_errors['message'] = '<p class="ferror fcenter">Error: The newly entered passwords don’t match.</p>';
				            $next = false;
				        }else if(isset($pass1) && isset($pass2) && strcmp($pass1, $pass2) == 0 && strlen($pass1) < 5 &&  strlen($pass1) > 0 ) {
				        	$array_errors['message']= '<p class="ferror fcenter">Error: The passwords should have at least 5 characters!</p>';
				            $next = false;
				        }	

				        if(sizeof($array_errors['message']) == 0 ){
				        	$user_id = wp_update_user( array( 
								'ID' 			=> $user_id , 
								'user_pass'		=> $pass1 
							) );
							if (  is_wp_error( $user_id )  ) {
								$array_errors['message']  = "<p class='ferror fcenter'>Error: Your password has not been updated. Please try again later.</p>";
							}else {

								update_user_meta( $user_id, 'user_active', 'true'); 
								update_user_meta( $user_id, 'user_first_login', 'true');
								$fist_login = "field_58a6bf22a7e69";
                                update_field(  $fist_login, "1",  "user_".$user_id );

								// update field inactive account
								$field_inactive_account = "field_58be7e89a983f";
								update_field(  $field_inactive_account, "0",  "user_".$user_id );

								$field_type_of_account = "field_58bfe3b6965a0";
								update_field(  $field_type_of_account, "old",  "user_".$user_id );
								
								$ok_redirect 				= true; 
								$ok_link 					= get_permalink(get_field('login_page_redirect', 'option')->ID); 
								$array_errors['message']  	= "<p class='fsuccess fcenter'>Success: Your password has been updated. You will be redirected to login page. </p>";
								$ok_link 		= get_permalink(get_field('login_page_redirect', 'options')); // login page;  
							}
				        }
		        }else{
		        	$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		        }
		    }else{
		    	$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		    }
		}else {
			$array_errors['message'] = "<p class='ferror fcenter'>Error: You account was validated.</p>";
		}
		
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: You account is not valid.</p>";
	}
	
	echo json_encode( array('message' => $array_errors , 'redirect' => $ok_redirect, 'link' => $ok_link ) );
   	die();

}
add_action( 'wp_ajax_user_get_new_password', 'user_get_new_password' );
add_action( 'wp_ajax_nopriv_user_get_new_password', 'user_get_new_password' );


/********************************************* OLD contractors (old site): remove user old   *********************************************/
function remove_user_old(){
	$user_id 		= $_POST['id'];
	$token_id 		= $_POST['token'];	
	$token_id_field = get_field('hash_inactive_account', 'user_'. $user_id); 
	$associated_company = get_field('associated_company', 'user_'.$user_id );
	$array_errors 	= array();
	if(isset($_POST['action']) && strcmp($_POST['action'], 'remove_user_old') == 0){
		if(isset($user_id) && isset($token_id) && strcmp($token_id, $token_id_field )==0){ 
			$del = wp_delete_user($user_id); 
			if($del == true){
				wp_trash_post($associated_company); 
				$array_errors['message'] = "<p class='fsuccess fcenter'>Success: Your account was removed.</p>";

			}else{
				$array_errors['message'] = "<p class='ferror fcenter'>Error: Problems on delete this account. Please contact us.</p>";
			}
		}else{
			$array_errors['message'] = "<p class='ferror fcenter'>Error: You account is not valid.</p>";
		}
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Problems on delete this account. Please contact us.</p>";
	}

	echo json_encode( array('message' => $array_errors ) );
   	die();
}
add_action( 'wp_ajax_remove_user_old', 'remove_user_old' );
add_action( 'wp_ajax_nopriv_remove_user_old', 'remove_user_old' );


/********************************************* contractor: update settings  *********************************************/
function get_projects_by_user(){
	$output  = '';	
	if(isset($_POST['action']) && strcmp($_POST['action'], 'get_projects_by_user') == 0){
		if(isset($_POST['user_id']) && strlen($_POST['user_id'])>0){
			$today = date('Ymd');
			$args =  array( 
	            'ignore_sticky_posts' 	=> true, 
	            'post_type'           	=> 'project',
	            'order'              	=> 'ASC',
	            'meta_key'				=> 'project_expire_date_project',
				'orderby'				=> 'meta_value',
	            'posts_per_page'		=> -1, 
	            'author' 				=> $_POST['user_id'],
	            'meta_query' => array(
					array(
				        'key'		=> 'project_expire_date_project',
				        'compare'	=> '>',
				        'value'		=> $today,
				    )
			    ),
	            // check if date expire is > current date
			);   
			        	
		 	$loop = new WP_Query( $args ); 
			$count = 1 ;

			// <span class="select" id="selectrecipient">Kristjan Ada</span> 
			$output_select = '';
			$output_span = '';

			//$output_select .= '<select class="styled simple" name="project" required="required" >';
			if ($loop->have_posts()) {  $count = 0; 
				while ($loop->have_posts())	{  $loop->the_post(); $count++; 
					$project_id = get_the_ID(); 
					$select_var = '';
					if($count == 1){
						$first_option = get_the_title($project_id); 	
						//$output_span  .= "<span class='select' id='selectproject'></span> ";
						//$select_var = "selected='selected'";
					}
					
					$output_select .= '<option value="'.$project_id.'" >'. get_the_title($project_id).'</option>';
				}
			}else {
				//$output_span  .= "<span class='select' id='selectproject'>No projects for this user</span> ";
				$output_select .= "<option value='0' disabled='disabled' selected='selected'>No projects for this user</option>";
				$first_option = 'No projects for this user'; 
			}
			$output_select .= "</select>";

			$output =   $output_select;

		}else{
			$output = "";
		}
    }else {
   	 	$output = "";
	}	

	echo json_encode( array('message' => $output, 'first_option' => $first_option ));
   	die();

}
add_action( 'wp_ajax_get_projects_by_user', 'get_projects_by_user' );
add_action( 'wp_ajax_nopriv_get_projects_by_user', 'get_projects_by_user' );