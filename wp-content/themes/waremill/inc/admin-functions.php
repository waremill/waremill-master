<?php 

/********************************************* admin: send invitations  *********************************************/
function send_invitation(){
	$user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
	$array_errors = array();

	if(isset($user_ID)){
		if (isset($_POST['action']) && isset($_POST['id']) ) { 																			
		    if(strcmp($_POST['action'], 'send_invitation') == 0  ) { 
		    	if(check_role_current_user("administrator") == true){
	      			if(isset($_POST['id']) && strlen($_POST['id']) > 0 ){
	      				// check if is a inactive user
	      				$other_user			= get_user_by('ID', $_POST['id'] );
	      				$inactive_account 	= get_field('inactive_account', 'user_'.$_POST['id']);
	      				
	      				if($inactive_account == true){
	      					unset($_POST['action']);
			        		$ok_process = false;
			        		$subject 		= get_field('subject_send_invitation', 'options');
			        		$content_email 	= create_email_template($_POST['id']);
						    $headers[] 		= 'MIME-Version: 1.0' . "\r\n";
						    $headers[] 		= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						    $headers[] 		= "X-Mailer: PHP \r\n";
						    $headers[] 		= 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';
						    $mail 			= wp_mail( $other_user->user_email, $subject, $content_email, $headers );
						    if( $mail == true ){
						        $array_errors['message'] = "<span class='fsuccess '>Success: Email sent for: " . $other_user->user_email ." .</span>";
						    }else{
						        $array_errors['message'] = "<span class='ferror '>Error: Email not sent for: ". $other_user->user_email ." .</span>";
						    }

	      				}else{
	      					$array_errors['message'] = "<span class='ferror '>Error: This user in not inactive: ". $other_user->user_email ." .</span>";
	      				}
	      			}
	        	}else{
	        		$array_errors['message'] = "<span class='ferror '>Error: You are not a administrator.</span>";
	        	}

		    } else {
		        $array_errors['message'] = "<span class='ferror '>Error: Please try again later.</span>";
		    }	
		}else{
			$array_errors['message'] = "<span class='ferror '>Error: Please try again later.</span>";
		}
	}else{
		$array_errors['message'] = "<span class='ferror '>Error: Invalid User.</span>";
	}

    echo json_encode( $array_errors );
   	die();
}
add_action( 'wp_ajax_send_invitation', 'send_invitation' );
add_action( 'wp_ajax_nopriv_send_invitation', 'send_invitation' );


function create_email_template($user_ID){

	$activate_page 	= get_field('activate_page', 'options');
	$delete_page 	= get_field('delete_page', 'options');

	$other_user		 	= get_user_by('ID', $user_ID );
	$user_token 		= get_field('hash_inactive_account', 'user_'.$user_ID );

	$content_message 	= get_field('email_send_invitation_account', 'options');
	$content_message 	= str_replace('{name}', $other_user->user_firstname.' '. $other_user->user_lastname, $content_message);
	$content_message 	= str_replace('{link_activation}', '<a href="'.get_permalink($activate_page).'?action=keep&id='.$user_ID.'&token='.$user_token .'" title="">Activate Your Accont</a>', $content_message) ;
    $content_message 	= str_replace('{link_remove}', '<a href="'.get_permalink($delete_page).'?action=delete&id='.$user_ID.'&token='.$user_token .'" title="">Delete Your Account</a>'  , $content_message); 

	$message = '';
    $message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $message .= '<html>';
    $message .= '<head>';
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
    $message .= '<title>'.get_bloginfo('name') . '</title>';
    $message .= '</head>';
    $message .= '<body style="font-family: \'Arial\', sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;   margin: 50px 20px; padding: 0px; font-size: 14px; color: #2a200c; " topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">';
       	 	$message .= '<table style="width: 100%;  "><tr><td>';
        	    $message  .= $content_message;
            $message .= "<td><tr></table>"; 
        $message .= "</body>";
    $message .= "</html>";
   
    return $message;

}


/********************************************* admin: generate token *********************************************/
function generate_token(){

	$array_errors 	= '';
	$output 		= 0;

	if (isset($_POST['submit_form_token']) ) {
	    if(wp_verify_nonce($_POST['submit_form_token'], 'submit_form_token') == 1) { 

	    	$user_first_name = $_POST['user_first_name'];
	    	$user_last_name  = $_POST['user_last_name'];
	    	$user_email 	 = $_POST['user_email']; 

	    	$user = get_user_by('email',$user_email);
	    	if ( ! empty( $user ) ) {

	    		if(strcmp($user->first_name, $user_first_name) == 0 && strcmp($user->last_name, $user_last_name) == 0){
	    			$user_id = $user->ID; 

		    		$string_to_md5 = $user_email.''.$user_first_name.''.$user_last_name.''.$user_id ;
		    		$output = md5($string_to_md5);

		    		$array_errors = "<p class='fsuccess fcenter'>Success: The token was generated.</p>";

	    		}else {
	    			$array_errors = "<p class='ferror fcenter'>Error: Invalid user name.</p>";
	    		}

	    	}else {
	    		$array_errors = "<p class='ferror fcenter'>Error: Invalid user.</p>";
	    	}
		}else {
	        $array_errors = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$array_errors = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}
   
   echo json_encode(array('message' => $array_errors, 'output' => $output ));
   die();		

}
add_action( 'wp_ajax_generate_token', 'generate_token' );
add_action( 'wp_ajax_nopriv_generate_token', 'generate_token' );


/********************************************* admin: import data *********************************************/

function import_data(){
	$array_errors = array();
	if (isset($_POST['submit-form-import']) ) {
	    if(wp_verify_nonce($_POST['submit-form-import'], 'submit-form-import') == 1) { 

	    	$csv_file = reArrayFiles($_FILES['csv_file']);
	    	//var_dump($csv_file);

    		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');

			$attachment_id = kv_handle_attachment('csv_file', ''); 
			$att_url = wp_get_attachment_url($attachment_id);

			//var_dump($att_url);

	    	$row = 0;
			if (($handle = fopen($att_url, "r")) !== FALSE) { $count_lines = 0; 
				//echo "here";
				$array_post = array();
			    while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) { $count_lines++;
			    	//echo $count_lines; 
			        $num = count($data);
			        //echo "<p> $num fields in line $row: <br /></p>\n";
			       
			        // ID,Title,Content,Logo,Country,City,Address,Zipcode,Web,Email,"Foundation Date",Products,Lat,Long,Keywords,Person,Park,"Nr Employees"
			        //** echo "=========================\n";
			        if($row>0){
			        	$array_post[$row-1] = array(); 
			        	for ($c=0; $c < $num; $c++) {

				            switch ($c) {
							    case 0:break; 
							    case 1: // title 
							        $array_post[$row-1]['title'] 	= $data[$c]; 
							        //** echo "Title: ". $data[$c]."\n"; 
							        break; 

							    case 2: // content
							    	$array_post[$row-1]['content'] 	= $data[$c]; 
							    	//** echo "Content: ".$data[$c] ."\n"; 
							     	break; 

							    case 3: // logo

							    	$new_url = str_replace('/2016/09/', '/2017/03/', $data[$c]);
		                    		$new_url = str_replace('/2016/10/', '/2017/03/', $new_url);

							    	$array_post[$row-1]['logo'] 	= $new_url; 
							    	//** echo "Logo: ". $data[$c] . ' ----- ' . $new_url ."\n"; 
							    	break;

							    case 4: // Country
							    	$array_post[$row-1]['country'] 	= $data[$c]; 
							    	//** echo "Country: ". $data[$c]."\n";  
							    	break;

							    case 5: // city
							    	$array_post[$row-1]['city'] 	= $data[$c]; 
							    	//** echo "City: ". $data[$c] ."\n"; 
							    	break;

							    case 6: // address
							    	$array_post[$row-1]['address'] 	= $data[$c]; 
							    	//** echo "Address: ".$data[$c] ."\n"; 
							    	break;

							    case 7: // zipcode
							    	$array_post[$row-1]['zipcode'] 	= $data[$c]; 
							    	//** echo "Zip: ". $data[$c]."\n"; 
							    	break;

							    case 8: // web
							    	$array_post[$row-1]['web'] 		= $data[$c]; 
							    	//** echo "Web: ". $data[$c]."\n"; 
							    	break; 

							    case 9: // email
							    	$array_post[$row-1]['email']	= $data[$c]; 
							    	//** echo "Email: ". $data[$c]."\n";  
							    	break; 

							    //case 10: // foundation_date
							    //	$array_post[$row-1]['foundation_date'] = $data[$c]; 
							   // 	echo "Foundation "
							   // 	break; 	
							    //case 11: // foundation_date
							    	//$array_post[$row-1]['products'] = $data[$c]; 
							    	//break; 	
							    case 12: // Lat
							    	$array_post[$row-1]['lat'] 		= $data[$c]; 
							    	//** echo "Lat: ". $data[$c]."\n"; 
							    	break;

							    case 13: // Long
							    	$array_post[$row-1]['long'] 	= $data[$c]; 
							    	//** echo "Long: ". $data[$c]."\n"; 
							    	break; 

							    //case 14: // Keywords
							    	//$array_post[$row-1]['keywords'] = $data[$c]; 
							    	//break; 
							    case 15: // Person
							    	$array_post[$row-1]['person'] = $data[$c]; 
							    	//** echo "Person: ". $data[$c]."\n"; 
							    	break; 
							   // case 16: // park
							    	//$array_post[$row-1]['park'] = $data[$c]; 
							    	//break; 
							    ///case 17: // park
							    	//$array_post[$row-1]['nr_employees'] = $data[$c]; 
							    	//break; 

							    //default:
							    	//break;
							        
							}
				        }
			        }
			       // echo "\n";
			        $row++;
			        
			    }


			    fclose($handle);

			    $count_array_posts = sizeof($array_post); 
			    // create users and posts
			    for ($i=0; $i < $count_array_posts; $i++) { 

			    	if(!empty($array_post[$i]['email'])){

				    	$random_password = wp_generate_password( 15, false );
				    	$person_name = array(); 

				    	if(strlen($array_post[$i]['person'])){
				    		$person_name = explode(" ", $array_post[$i]['person'] );
				    	}/*else{
				    		$person_name[0] = $array_post[$i]['email'];
				    		$person_name[1] = $array_post[$i]['email'];
				    	}*/
				    	
						$userdata = array(
	                        'user_login'    =>  'test-'.$i.'@domain.com', // $array_post[$i]['email']; // ******************************************
	                        'user_url'      =>  '',
	                        'user_pass'     =>  $random_password,   
	                        'first_name'    =>  $person_name[0],
	                        'last_name'     =>  $person_name[1],
	                        'user_email'    =>  'test-'.$i.'@domain.com', // $array_post[$i]['email']; //*********************************************
	                        'nickname'      =>  $array_post[$i]['email'],
	                        'role'          => 'contractor'
	                    ); 

	                    // upload company name in decription field 
	                    $userdata['description'] = $array_post[$i]['title'];
	                    // Create User
	                    $user_id = wp_insert_user( $userdata );

	                   // / var_dump($user_id);

	                    //check if were problems on create user
	                    if ( ! is_wp_error( $user_id ) ) {
						    // Add user meta - false => user not ative 
		                    add_user_meta( $user_id, 'user_active', 'true'); 

		                    // After register => field & meta update => first login is not here                                         
		                    add_user_meta( $user_id, 'user_first_login', 'true');
		                    $fist_login = "field_58a6bf22a7e69";
		                    update_field(  $fist_login, "1",  "user_".$user_id );

		                    // Generate has for activate account 
		                   // $field_has  = "field_5878c8010a907";
		                   // $code       = md5( $register_email .''. $register_first_name .''. $register_family_name.''.date('ymdhms')); 
		                   // update_field(  $field_has, $code,  "user_".$user_id );

		                    // Upload field with link for activation 
		                    //$field_activation = "field_5878dbaf5cc5f";
		                    //$link_activate =  get_activation_link($user_id, $code); 
		                    //update_field( $field_activation, $link_activate,  "user_".$user_id );

		                    $field_inactive = "field_58be7e89a983f"; 
		                    update_field( $field_inactive, "1",  "user_".$user_id );    

		                    // generate token for inactive account
		                    // $string_to_md5 = $user_email.''.$user_first_name.''.$user_last_name.''.$user_id ; 

		                    $string_to_md5 = 'test-'.$i.'@domain.com' .''.$person_name[0].''.$person_name[1].''.$user_id ;
		                    //$string_to_md5 = $array_post[$i]['email'] .''.$person_name[0].''.$person_name[1].''.$user_id ;  //***********************************************************

				    		$output_hash = md5($string_to_md5);
				    		$field_hash = "field_58bebfc7560d4"; 
		                    update_field( $field_hash, $output_hash ,  "user_".$user_id );    

		                    // company ID ----> need to select 

		                    $my_post = array(
		                        'post_title'      	=> 	$array_post[$i]['title'],
		                        'post_name' 		=> 	"contractor-".$array_post[$i]['title'],
		                        'post_status'     	=> 	'draft', // draft 
		                        'post_author'   	=>  $user_id ,
		                        'post_type'       	=> 'contractor',
		                    );    

		                    $id_new_post = wp_insert_post( $my_post );
		                    // update the fields 
		                    $fields_associated_user = "field_588b5eec56820"; 
		                    update_field($fields_associated_user, $user_id, $id_new_post);

		                    $field_associated_company = "field_5889c47ba218a";
		                    update_field($field_associated_company, $id_new_post, 'user_'.$user_id);

		                    if(!empty( $array_post[$i]['title'])){
		                    	$field_company_name = "field_5889a8373161f";
		                    	update_field($field_company_name, $array_post[$i]['title'], $id_new_post);
		                    }

		                    // content 
		                    if(!empty($array_post[$i]['content'])){
		                    	$field_content = "field_5889a8b631622";
		                    	update_field($field_content, $array_post[$i]['content'], $id_new_post);
		                    }
		                    
		                    // logo
		                    if(!empty($array_post[$i]['logo'])){
		                    	$field_logo_img = "field_5889a99e31634";
			                    $field_logo_url = "field_5889a9b531635"; 

			                    // replace 2016/09 and 2016/10 

			                    $new_url = str_replace('/2016/09/', '/2017/03/', $array_post[$i]['logo']);
			                    $new_url = str_replace('/2016/10/', '/2017/03/', $array_post[$i]['logo']);

			                    $image_id = pippin_get_image_id($new_url);
			                    update_field($field_logo_img, $image_id,  $id_new_post);
			                    update_field($field_logo_url, $new_url, $id_new_post);
		                    }

		                    // country
		                    if(!empty($array_post[$i]['country'])){
		                    	$field_country = "field_5889a89c31620";
		                    	update_field($field_country, $array_post[$i]['country'], $id_new_post);
		                    }
		                    
		                    // city 
		                    if(!empty($array_post[$i]['city'])){
		                    	$field_city = "field_5889a8f431627";
		                    	update_field($field_city, $array_post[$i]['city'], $id_new_post);
		                    }
		                   
		 
		                    // address + zipcode // workshop
		                    if(!empty($array_post[$i]['address'])){
		                    	$field_address = "field_5889a8ec31626";
		                    	update_field($field_address, $array_post[$i]['address']. ' ' . $array_post[$i]['zipcode']  , $id_new_post);	
		                    }

		                   	// web
		                   	if(!empty($array_post[$i]['web'])){
		                   		$field_web = "field_5889a8e331625";
		                    	update_field($field_web, $array_post[$i]['web'], $id_new_post);
		                   	}

		                    // google maps 
		                    $field_location_google_maps	= "field_5889aa25292fc";
		                    if(!empty($array_post[$i]['lat']) && !empty($array_post[$i]['long']) && !empty($array_post[$i]['address'])){
					    		$value_location = array("address" => $array_post[$i]['address'], "lat" => $array_post[$i]['lat'], "lng" => $array_post[$i]['long'], "zoom" => 14);
								// ** update_field($field_location_google_maps, $value_location, $id_new_post);
					    	}

					    	// list_of_machines => keywords
					    	// if(!empty($array_post[$i]['keywords'])){
					    	// 	$field_list_of_machines = "field_5889a8a131621"; 
					    	// 	update_field($field_list_of_machines, $array_post[$i]['keywords'], $id_new_post);
					    	// }
					    	


						}else {
							$array_errors[] = "<span class='ferror fcenter'>Error (". $i .") on create this user : ".$array_post[$i]['email'].". Invalid user.</span><br/>";
						}
			    	}else{
			    		$array_errors[] = "<span class='ferror fcenter'>Error (". $i .") on create this user".$array_post[$i]['email'].". Invalid email.</span><br/>";
			    	}

			    }

			    $array_errors[] = "<span class='fsuccess fcenter'>Success: Process done.</span><br/>"; 

			}else{
				$array_errors[] = "<span class='ferror fcenter'>Error: Please try again later.</span><br/>";
			}
		}else {
	        $array_errors[] = "<span class='ferror fcenter'>Error: Please try again later.</span><br/>";
	    }	
	}else{
		$array_errors[] = "<span class='ferror fcenter'>Error: Please try again later.</span><br/>";
	}
   
   echo json_encode(array('message' => $array_errors, 'output' => $output ));
   die();		

}
add_action( 'wp_ajax_import_data', 'import_data' );
add_action( 'wp_ajax_nopriv_import_data', 'import_data' );


function pippin_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    return $attachment[0]; 
}


?>