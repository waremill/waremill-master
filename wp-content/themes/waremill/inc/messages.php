<?php 
/********************************************* Return the total nr of unread messages *********************************************/
function get_total_nr_unread_messages(){
	$user 			= wp_get_current_user(); 
	$user_ID 		= $user->ID;
	$current_role 	= $user->roles[0];
	
	$args =  array( 
        'ignore_sticky_posts' 	=> true, 
        'post_type'           	=> 'message',
        'order'              	=> 'DESC',
        'posts_per_page'		=> -1,
        'meta_query' => array(
			array(
		        'key'		=> $current_role,
		        'compare'	=> '=',
		        'value'		=> $user_ID,
		    )
	    ),
	);   

	$args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
 	$loop = new WP_Query( $args ); 
 	$count = 0;
	
	if ($loop->have_posts()) { 
	 	while ($loop->have_posts())	{  
	 		$loop->the_post(); 
	 		$page_id = get_the_ID(); ; 
	 		$box = get_field('discussion', $page_id);
			if ($box){
				foreach ($box as $key => $box1) { 
					$user_message = $box1['user']['ID']; //var_dump($user_message);
					if($user_message != $user_ID )	{
						$user_not_read = $box1['read'];
						if($user_not_read == false){
							$count ++;
							//echo $key;

						}
						
					}
			 	} 
			} 
	 	}
	}
	
	wp_reset_postdata();	
	return $count;

}

/********************************************* Load more messages *********************************************/
function get_old_messages(){
	$ok_success = false; 
	$content 	= '';

	$user = wp_get_current_user(); 
	$user_ID = $user->ID; 

	if(isset($_POST['action']) && strlen($_POST['action']) > 0 ){
		$message 	= $_POST['page']; 
		$start 		= $_POST['start']; 

		if(isset($message) && strlen($message) > 0 && isset($start) && strlen($start) > 0){
			$customer 	= get_field('customer', $message);
			$contractor 	= get_field('contractor', $message);

			// check if current users is part of conversation
			if( $user_ID == $contractor['ID'] || $user_ID == $customer['ID'] ){ 
			
				$content 		= get_messages_interval($start, $message); 
				$ok_success 	= true;
				$content_nr 	= get_number_messages_interval($start, $message); 
			}else{
				$_POST['message'] = "<p class='ferror fcenter'>Error: You are not part of this conversation.</p>";
			}

		}else{
			$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		}
	}else {
		$_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	echo json_encode(array('message' => $_POST['message'] , 'done' => $ok_success, 'content' => $content, 'content_nr' => $content_nr ));
   	die();
}
add_action( 'wp_ajax_get_old_messages', 'get_old_messages' );
add_action( 'wp_ajax_nopriv_get_old_messages', 'get_old_messages' );

/********************************************* HTML messages *********************************************/
function get_messages_interval($start, $id_message){ ob_start(); 
	$user = wp_get_current_user(); 
	$user_ID = $user->ID; 

	$messages = get_field('discussion', $id_message);
	$size_messages = sizeof($messages); 
	$messages_part = array();

	if(intval($start) < 5 ){
		$val_start = intval($start);
		$messages_part = array_slice($messages, 0, $val_start);  
	}else{
		$val_start 	= intval($start) - 5 ;
		$messages_part = array_slice($messages, $val_start, 5);  
	}
	
	$count=0;
	foreach ($messages_part as $key => $single_message) {  

			$user_message 		= $single_message['user']; 
		 	$user_message_id 	= $user_message['ID'];
		 	$date 				= $single_message['date']; 
			$date 				= new DateTime($date);

		 	$file 				= $single_message['file'];
		 	$read 				= $single_message['read']; 
		?>
		<div class="message-item <?php if($user_message_id == $user_ID) { echo "me "; } if( $read == false && $user_message_id != $user_ID) { echo " new"; } ?>" data-order="<?php echo intval($start) - 4 + $key;  //echo $size_messages - intval($start) + $count;  $count++; ?>">
			<div class="author">
				<div class="icon"><i class="fa fa-user"></i></div>
				<h4><?php echo ucwords($user_message['user_firstname']); //$rest = substr($user_message['user_email'], 0, 6).'...'; echo $rest; ?></h4>
				<p><?php echo $date->format('j M Y'); // Y-m-d H:i:s ?></p>
				<p><?php echo $date->format('H:i:s'); ?></p>
			</div>
			<div class="message-content-wrapper">
				<div class="message-content">
					<?php $text = $single_message['message']; ?> 
					<?php if(!empty($text)){ ?>
						<?php echo $text; ?>
					<?php } ?>
				</div>
				<?php if(!empty($file)){ ?>
					<div class="file-attachment">
						<a href="<?php echo $file['url'];?>" target="_blank" title="">
							<i class="fa fa-paperclip"></i><?php echo $file['filename']; ?>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>

	<?php } ?>
<?php 
	$messages_info = ob_get_clean(); 
	return $messages_info; 								
}

/********************************************* number messages *********************************************/
function get_number_messages_interval($start, $id_message){
	$messages 		= get_field('discussion', $id_message);
	
	if(intval($start) < 5 && intval($start) > 1){
		$val_start = intval($start);
	}if(intval($start) == 1){
		$val_start = 0;
	} else {
		$val_start 	= intval($start) - 5 ;
	}

	return $val_start; 
}

/********************************************* Replay / Send Messages *********************************************/
function replay_message(){

	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	
	$ok_success = false; 
	$create_new_message = '';
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');


	if(isset($user_ID)){
		if (isset($_POST['submit-message']) ) {
	        if(wp_verify_nonce($_POST['submit-message'], 'submit-message') == 1) { 
	     		
	        	$message_id 	= $_POST['id_message'];
	        	$file 			= $_FILES['file'];

	        	$customer 		= get_field('customer', $message_id);
				$contractor 	= get_field('contractor', $message_id);

	        	// check valid text and valid file
	        	if( $user_ID == $contractor['ID'] || $user_ID == $customer['ID'] ){ 
		        	$valid = true;
		        	if(isset($message_id) && strlen($message_id) > 0 ){
		        		if(isset($file) && !empty($file['name'])){
		        			if($file['size'] < 5242880 ){
		        				$extensions = array('jpg','png', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif');
		        				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		        				if(!in_array($ext, $extensions) ) {
								    $valid = false;
								    $_POST['message_alt'] = "<p class='ferror fcenter'>Error: Invalid file type. We accept: .doc/.docx, .xls/.xlsx, .ppt/.pptx, .pdf, .png/.jpg/.jpeg/.gif</p>";
								}else{
									$valid = true;
								}

		        			}else{
		        				$valid = false;
		        				$_POST['message_alt'] = "<p class='ferror fcenter'>Error: The file size is to big. Max. 5MB</p>";
		        			}
		        		}else{
		        			$valid = true;
		        		}

		        	}else {
		        		$valid = false;
		        		$_POST['message_alt'] = "<p class='ferror fcenter'>Error: Please add a message.</p>";
		        	}
		        	
		        	if($valid == true){

						$discussion = array();
						$discussion = get_field('discussion', $message_id);

						$text 	= $_POST['message_new'];

						$date_now = current_time('YmdHis');
						$date = DateTime::createFromFormat('YmdHis',  $date_now);
		                $date = $date->format('YmdHis');

		                $new_message = array();
		                $attachment_id = '';
		              	if(isset($file) && !empty($file['name'])){
		                	$attachment_id = media_handle_upload('file',  $message_id);
		                	$new_message = array(
								'user' 		=> $user_ID,
								'message'	=> $text,
								'date'		=> $date, 
								'file'		=> $attachment_id
							);	
		                }else{
		                	
							$new_message = array(
								'user' 		=> $user_ID,
								'message'	=> $text,
								'date'		=> $date, 
								'file'		=> ''
							);
		                }
						
						$field_discussion = "field_58948f819d48c";
						$discussion[] = $new_message;

						update_field($field_discussion, $discussion, $message_id);

						// update field last date 
						$field_last_date = "field_58a6d15260cef";
						update_field($field_last_date, $date, $message_id);

						//var_dump($discussion);
						$count_order = sizeof($discussion); 

						$create_new_message = '';
						$date = DateTime::createFromFormat('YmdHis',  $date_now);

						$create_new_message .= '<div class="message-item  me"  data-order="'. $count_order .'">';
							$create_new_message .= '<div class="author">';
								$create_new_message .= '<div class="icon"><i class="fa fa-user"></i></div>';
								$rest = substr( $user->user_email , 0, 6).'...'; 
								$create_new_message .= '<h4>'.$rest.'</h4>';
								$create_new_message .= '<p>'.$date->format('j M Y').'</p>';
								$create_new_message .= '<p>'.$date->format('H:i:s').'</p>';
							$create_new_message .= '</div>';
							$create_new_message .= '<div class="message-content-wrapper">';
								$create_new_message .= '<div class="message-content"><p>'. $text.'</p></div>';
								if(!empty($attachment_id)){ 
									$att_url = wp_get_attachment_url($attachment_id); 
									$create_new_message .= '<div class="file-attachment">';
										$create_new_message .= '<a href="'.$att_url.'" target="_blank" title="">';
											$create_new_message .= '<i class="fa fa-paperclip"></i>'. $file['name']; 
										$create_new_message .= '</a>';
									$create_new_message .= '</div>';
								}
							$create_new_message .= '</div>';
						$create_new_message .= '</div>';


						//$create_new_message =  create_single_message( $count_order , $user['user_email'] , $date_now, $message, $attachment_id , $file['name'] );
						//send email for the user
						
						if( intval($user_ID) == intval($contractor['ID'])  ){ 
							
						 	$return_email = send_email_new_message($customer['ID'] );
						}elseif( intval($user_ID) == intval($customer['ID']) ) {
							$return_email =  send_email_new_message($contractor['ID']);
						 	
						}

						if($return_email == true){
							$ok_success = true;	
							$_POST['message_alt'] = "<p class='fsuccess fcenter'>Success: Your message have been sent.</p>";
						}else{
							$ok_success = true;	
							$_POST['message_alt'] = "<p class='fsuccess fcenter'>Success: Your message have been sent but the user did not received the email notification.</p>";
						}
					}
		      
	        	}else{
					$_POST['message_alt'] = "<p class='ferror fcenter'>Error: You are not part of this conversation.</p>";
				}
	        	
	        }else {
				$_POST['message_alt'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
			}
	    }else {
			$_POST['message_alt'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		}

	}else{
		$_POST['message_alt'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	
	$output = array(
		'message_alt' 	=> $_POST['message_alt'],
		'done'			=> $ok_success,
		'content_new'	=> $create_new_message 
	);

	unset($_SESSION['id_project']);
	unset($_SESSION['id_author']);

	unset($_SESSION['new_name']);
	unset($_SESSION['new_email']);
	unset($_SESSION['new_message']);
	unset($_SESSION['project_id']);
	unset($_SESSION['undone_message']);

   	echo json_encode(array('message_alt' => $_POST['message_alt'], 'done' => $ok_success, 'content_new'	=> $create_new_message  ));
   	die();
}
add_action( 'wp_ajax_replay_message', 'replay_message' );
add_action( 'wp_ajax_nopriv_replay_message', 'replay_message' );


function get_project_name_from_message($discussion_id){
	$post_message = get_field('project', $discussion_id);
	$post_message_id = $post_message->ID;
	$post_message_title = get_the_title($post_message_id); 
	return $post_message_title;
}

function get_last_message($discussion_id){
	$box = get_field('discussion', $discussion_id ); 
	$last_row = end($box);

	return $last_row;
}


/********************************************* Mark as read the messages from current page  *********************************************/ 
function update_as_read_specific_message(){

	$nr = 0 ;
	$nr = get_total_nr_unread_messages(); 
	$count_mark = 0; 
	
	if(isset($_POST['action']) && strlen($_POST['action']) > 0 && strcmp($_POST['action'], "update_as_read_specific_message") == 0){
		if(isset($_POST['id']) && strlen($_POST['id']) > 0 ){
			$id_msg = $_POST['id'];
			$post_msg = get_post_type($id_msg);
			if(strcmp($post_msg, "message") == 0 ){
				//echo $post_msg;
				$user 		= wp_get_current_user(); 
				$user_ID 	= $user->ID;
				$user_email = $user->user_email;

				$customer 	= get_field('customer', $id_msg);
				$customer_email = $customer['user_email'];
				
				$contractor = get_field('contractor', $id_msg); 
				$contract_email = $contractor['user_email'];

				$opuse_email = '';
				if(strcmp($user_email, $customer_email) == 0){
					$opuse_email = $contract_email;
				}else if(strcmp($user_email, $contract_email) == 0){
					$opuse_email = $customer_email;
				}

				$box = get_field('discussion', $id_msg); 

				if( have_rows('discussion' , $id_msg) ) {
					$i = 0;
					while( have_rows('discussion' , $id_msg) ) {
						the_row();
						$i++;
						$user_message_obj = get_sub_field('user');
						$user_message = $user_message_obj['user_email'];
						if(strcmp($user_message, $opuse_email) == 0){ // get only the messages  that aren't from the  current user 
							
							$read = get_sub_field('read');
							if($read == false){ // get only the messages that are unread from this post
								update_sub_field('read', 1,  $id_msg);		
								$count_mark++;
							}
						}
					}
					
				}
			}
		}
	}

	$nr = $nr - $count_mark;
	echo json_encode(array('no' => $nr  ));
   	die();
}
add_action( 'wp_ajax_update_as_read_specific_message', 'update_as_read_specific_message' );
add_action( 'wp_ajax_nopriv_update_as_read_specific_message', 'update_as_read_specific_message' );


/********************************************* check if there are new messages for current user in a specific discution  *********************************************/ 

function check_new_messages($message_id){
	$total = 0;

	$user 		= wp_get_current_user(); 
	$user_ID 	= $user->ID;
	$user_email = $user->user_email;

	$customer 	= get_field('customer', $message_id);
	$customer_email = $customer['user_email'];
	
	$contractor = get_field('contractor', $message_id); 
	$contract_email = $contractor['user_email'];

	$opuse_email = '';
	if(strcmp($user_email, $customer_email) == 0){
		$opuse_email = $contract_email;
	}else if(strcmp($user_email, $contract_email) == 0){
		$opuse_email = $customer_email;
	}

	if( have_rows('discussion' , $message_id) ) {
		while( have_rows('discussion' , $message_id) ) {
			the_row();
			$user_message_obj = get_sub_field('user');
			$user_message = $user_message_obj['user_email'];
			if(strcmp($user_message, $opuse_email) == 0){ // get only the messages  that aren't from the  current user 
				
				$read = get_sub_field('read');
				if($read == false){ // get only the messages that are unread from this post
					$total++;
				}
			}
		}
	}

	if($total > 0 ){
		return true;
	}else {
		return false; 
	}

}


/********************************************* update number of unread messages - in menu  *********************************************/ 
function update_no_message(){
	$nr = 0;
	if(isset($_POST['action']) && strlen($_POST['action']) > 0 && strcmp($_POST['action'], "update_no_message") == 0){
		$nr = get_total_nr_unread_messages();

		// check if is customer to update the bids : get_bidders_unread
		$nr_bids = get_bidders_unread_total();

	}else{
		$nr  = 0; 
	}
	echo json_encode(array('nr' => $nr , 'bids' => $nr_bids ));
   	die();
}
add_action( 'wp_ajax_update_no_message', 'update_no_message' );
add_action( 'wp_ajax_nopriv_update_no_message', 'update_no_message' );



/********************************************* Create New message  *********************************************/ 
function new_message(){
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	$array_errors 	= array();
	$array_success 	= array();
	$ok_done 		= false; 

	if(isset($user_ID)){
		if (isset($_POST['submit-new-message']) ) {
	        if(wp_verify_nonce($_POST['submit-new-message'], 'submit-new-message') == 1) { 
	        	
	        	$valid = true;
	        	unset($_POST['submit-new-message']);
		    	unset($_POST['action']);


		    	foreach ($_POST as $key => $value)	{
					if( (strcmp($key, 'message') == 0 || strcmp($key, 'recipient') == 0 || strcmp($key, "project") == 0 ) && strlen($key) < 0){
						$array_errors['message'] = "<p class='ferror fcenter'>Error: You can not leave ".$key." empty!</p>";
					}
		    	}

		    	if(isset($file) && !empty($file['name'])){
        			if($file['size'] < 5242880 ){
        				$extensions = array('jpg','png', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'gif');
        				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        				if(!in_array($ext, $extensions) ) {
						    $valid = false;
						    $array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid file type. We accept: .doc/.docx, .xls/.xlsx, .ppt/.pptx, .pdf, .png/.jpg/.jpeg/.gif</p>";
						}else{
							$valid = true;
						}
        			}else{
        				$valid = false;
        				$array_errors['message'] = "<p class='ferror fcenter'>Error: The file size is to big. Max. 5MB</p>";
        			}
        		}else{
        			$valid = true;
        		}

		    	if(sizeof($array_errors) == 0 && $valid == true){ // no errors
		    		$project 		= $_POST['project'];
		    		$recipient		= $_POST['recipient'];
		    		
		    		$current_user 	= $user ; 
		    		$current_role 	= $user->roles[0];
		    		$message 		= $_POST['message'];

		    		// var_dump($current_role);
		    		// check if project author is the current user !!! 
		    		if(strcmp($current_role, "contractor") == 0){
		    			$validate_project_user = check_project_autor($project , $recipient	);
		    		}else{
		    			$validate_project_user = check_project_autor($project , $user->ID);
		    		}
		    		
		    		$validate_project_bidder = check_bid($project, $user->ID); 
		    		if($validate_project_user == true || $validate_project_bidder == true){ // check if is author or a bidder 
		    			// check if we have already a discutions with those 2 users and this project 
			    		
		    			if($recipient == $user->ID){
		    				$array_errors['message'] = "<p class='ferror fcenter'>Error: You can't sent message to you.</p>";
		    			}else {
		    				$check_messages = check_customer_contractor_project_message($user->ID, $recipient, $project, $current_role);
				    		if( sizeof($check_messages) == 0 ){
				    			$content_retrun = add_new_message($project, $recipient, $current_user, $current_role, $message);
				    			if($content_retrun == null){
				    				$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
				    			}else{ // ok = success, message added
				    				$array_success['notice']= "<p class='fsuccess fcenter'>Success: Your message has been sent. Please wait to reload this page.</p>";

									$return_email = send_email_new_message($recipient );
									
									if($return_email == true){
										$ok_success = true;	
										$_POST['message_alt'] = "<p class='fsuccess fcenter'>Success: Your message have been sent.</p>";
									}else{
										$ok_success = true;	
										$_POST['message_alt'] = "<p class='fsuccess fcenter'>Success: Your message have been sent but the user did not received the email notification.</p>";
									}

				    				$array_success['link'] = get_permalink($content_retrun['id']);
				    				$array_success['content_add'] = $content_retrun['content_add'];
				    				$ok_done = true;

				    			}

				    		}else{
				    			$array_errors['message'] = "<p class='ferror fcenter'>Error: You have an open discution about this project with this user. Please search in your inbox. </p>";
				    		}
		    			}
		    		}else{
		    			$array_errors['message'] = "<p class='ferror fcenter'>Error: The customer is not the owner of this project. </p>";
		    		}
		    		
		    	}
	        }
	    }
	}

	unset($_SESSION['id_project']);
	unset($_SESSION['id_author']);

	unset($_SESSION['new_name']);
	unset($_SESSION['new_email']);
	unset($_SESSION['new_message']);
	unset($_SESSION['project_id']);
	
	echo json_encode( array('message' => $array_errors, 'success' => $array_success, 'done' => $ok_done ) );
   	die();
}
add_action( 'wp_ajax_new_message', 'new_message' );
add_action( 'wp_ajax_nopriv_new_message', 'new_message' );

/*
*	$project = project id
*	$recipient = user ID 
*	$current_user = user (object)
*	$current_role = string with current role of user
* 	$message = string 
*/

function add_new_message($project, $recipient, $current_user, $current_role, $message){
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	// Create post object

	$user_recipient = get_user_by( 'ID', $recipient );
	$user_recipient = get_user_by( 'ID', $recipient );
	$new_message = 0 ;
	$create_new_message = '';

	$field_customer 	= 'field_58948f479d48a';
	$field_contractor 	= 'field_58948f6b9d48b';
	$field_project		= 'field_5894903aa7fb9';

	if(strcmp($current_role, "customer") == 0){

		$name = get_the_title($project).' – '.$current_user->user_email.' – '.$user_recipient->user_email;
		$name_2 = get_the_title($project).' – '.$current_user->user_email.' – '.$user_recipient->user_email.''.date('YmdHis');

		$my_post = array(
			'post_type'		=> 	'message',
		    'post_title' 	=>  $name,
		    'post_name' 	=> 	md5($name_2),
		    'post_status' 	=> 'publish',
		);

		$new_message = wp_insert_post( $my_post );	

		update_field($field_customer, $current_user->ID, $new_message);
		update_field($field_contractor, $recipient, $new_message);
		update_field($field_project, $project, $new_message);

	}else if(strcmp($current_role, "contractor") == 0){
		$name = get_the_title($project).' – '.$user_recipient->user_email.' – '. $current_user->user_email;
		$name_2 = get_the_title($project).' – '.$user_recipient->user_email.' – '. $current_user->user_email.''.date('YmdHis');

		$my_post = array(
			'post_type'		=> 'message',
		    'post_title' 	=> $name,
		    'post_name' 	=> md5($name_2),
		    'post_status' 	=> 'publish',
		);

		$new_message = wp_insert_post( $my_post );	

		update_field($field_contractor, $current_user->ID, $new_message);
		update_field($field_customer, $recipient, $new_message);
		update_field($field_project, $project, $new_message);
	}


		$date_now = current_time('YmdHis');
		$date = DateTime::createFromFormat('YmdHis',  $date_now);
        $date = $date->format('YmdHis');
        $file 		= $_FILES['file'];

		// update repeater
        $new_message_array = array();
        $attachment_id = '';
      	if(isset($file) && !empty($file['name'])){
        	$attachment_id = media_handle_upload('file',  $new_message);
        	
        	$new_message_array = array(
				'user' 		=> $current_user->ID,
				'message'	=> $message,
				'date'		=> $date, 
				'file'		=> $attachment_id
			);	

        }else{

			$new_message_array = array(
				'user' 		=> $current_user->ID,
				'message'	=> $message,
				'date'		=> $date, 
				'file'		=> ''
			);
        }

        $discussion 		= array();
		$discussion 		= get_field('discussion', $new_message);
		$discussion[] 		= $new_message_array;
		$field_discussion   = "field_58948f819d48c";
		update_field($field_discussion, $discussion, $new_message);


		$field_last_date = "field_58a6d15260cef";
		update_field($field_last_date, $date, $new_message);

		$count_order = sizeof($discussion); 

		$date = DateTime::createFromFormat('YmdHis',  $date_now);

		$create_new_message .= '<div class="message-item  me"  data-order="'. $count_order .'">';
			$create_new_message .= '<div class="author">';
				$create_new_message .= '<div class="icon"><i class="fa fa-user"></i></div>';
				$rest = substr( $current_user->user_email , 0, 6).'...'; 
				$create_new_message .= '<h4>'.$rest.'</h4>';
				$create_new_message .= '<p>'.$date->format('j M Y').'</p>';
				$create_new_message .= '<p>'.$date->format('H:i:s').'</p>';
			$create_new_message .= '</div>';
			$create_new_message .= '<div class="message-content-wrapper">';
				$create_new_message .= '<div class="message-content"><p>'. $message.'</p></div>';
				if(!empty($attachment_id)){ 
					$att_url = wp_get_attachment_url($attachment_id); 
					$create_new_message .= '<div class="file-attachment">';
						$create_new_message .= '<a href="'.$att_url.'" target="_blank" title="">';
							$create_new_message .= '<i class="fa fa-paperclip"></i>'. $file['name']; 
						$create_new_message .= '</a>';
					$create_new_message .= '</div>';
				}
			$create_new_message .= '</div>';
		$create_new_message .= '</div>';

	$output_array = array(); 

	if($new_message != 0){
		$output_array = array(
			'id' 			=> $new_message,
			'content_add' 	=> $create_new_message
		);
	}else{
		$output_array = null; 
	}
	
	return $output_array;

}

/*
*	$customer_id 	= user#1 id
*	$contractor_id 	= user#2 id
*	$project_id 	= project id
*	$current_role = string with current role of user
* 	
*/

function check_customer_contractor_project_message($customer_id, $contractor_id, $project_id, $current_role){

	$count  = 0;
	//var_dump($current_role);

	if(strcmp( $current_role , "customer") == 0){ 
		$args_11 =  array( 
	        'ignore_sticky_posts' 	=> true, 
	        'post_type'           	=> 'message',
	        'order'              	=> 'DESC',
	        'posts_per_page'		=> -1,
	        'meta_query' 			=> array(
	        	'relation' => 'AND',
		        array(
		            'relation' 			=> 'AND',
		        	array(
					    array(
					        'key'		=> 'contractor',
					        'compare'	=> 'LIKE',
					        'value'		=> $contractor_id,
					    )
			        ),
			       	array(
			            'relation'		=> 'OR',
						array(
					        'key'		=> 'customer',
					        'compare'	=> 'LIKE',
					        'value'		=> $customer_id,
					    )
			        )
		        ),
		        array(
		            'key'		=> 'project',
					'compare'	=> '=',
					'value'		=> $project_id, 
		        )
		    ),
		);
	
	} else if(strcmp( $current_role , "contractor") == 0){ 
		$args_11 =  array( 
	        'ignore_sticky_posts' 	=> true, 
	        'post_type'           	=> 'message',
	        'order'              	=> 'DESC',
	        'posts_per_page'		=> -1,
	        'meta_query' 			=> array(
	        	'relation' => 'AND',
		        array(
		            'relation' 			=> 'AND',
		        	array(
					    array(
					        'key'		=> 'contractor',
					        'compare'	=> 'LIKE',
					        'value'		=> $customer_id,
					    )
			        ),
			       	array(
			            'relation'		=> 'OR',
						array(
					        'key'		=> 'customer',
					        'compare'	=> 'LIKE',
					        'value'		=> $contractor_id,
					    )
			        )
		        ),
		        array(
		            'key'		=> 'project',
					'compare'	=> '=',
					'value'		=> $project_id, 
		        )
		    ),
		);
	}
	
	$loop = new WP_Query( $args_11 ); 
	//var_dump($loop);
	$count = $loop->posts; //$loop->post_count;

	//var_dump($count);

	wp_reset_postdata();
	  
	return $count;
}


function contact_customer(){
	$ok_done = false; 
	// unset($_SESSION['id_project']);
	// unset($_SESSION['id_author']);

	// unset($_SESSION['new_name']);
	// unset($_SESSION['new_email']);
	// unset($_SESSION['new_message']);
	// unset($_SESSION['project_id']);

	if (isset($_POST['submit-contact-customer']) ) {
	   if(wp_verify_nonce($_POST['submit-contact-customer'], 'submit-contact-customer') == 1) { 
			
	   		unset($_POST['submit-contact-customer']);
		    unset($_POST['action']);

			foreach ($_POST as $key => $value)	{
				if( strcmp($key, 'contact_contractor_name') == 0  && strlen($value) < 0){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: You can not leave name empty!</p>";
				}
				if( strcmp($key, 'contact_contractor_email') == 0  && strlen($value) < 0){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: You can not leave email empty!</p>";
				}else if( strcmp($key, 'contact_contractor_email') == 0  && strlen($value) > 0 && validEmail($value)==false ){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid email address!</p>";
				}

				if( strcmp($key, 'contact_contractor_message') == 0  && strlen($value) < 0){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: You can not leave message empty!</p>";
				}else if( strcmp($key, 'contact_contractor_message') == 0  && strlen($value) > 0 && strlen($value) < 50 ){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: Your message should have at least 50 characters!</p>";
				}

				if( strcmp($key, 'company_id') == 0 && strlen($value) < 0){
					$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later!</p>";
				}
	    	}

	    	if(sizeof($array_errors) == 0 ){ // no errors
	    		$_SESSION['new_name'] 	= $_POST['contact_contractor_name'];
	    		$_SESSION['new_email'] 	= $_POST['contact_contractor_email'];
	    		$_SESSION['new_message']  = $_POST['contact_contractor_message'];
 
	    		$associated_user 		= get_field('associated_user', $_POST['company_id']);
	    		$associated_user_email  = $associated_user['user_email']; 
	    		$_SESSION['new_email_from'] = $associated_user_email;
	    		$_SESSION['undone_message'] = true; 

	    		
 	    		$ok_done = true;
	    	}
	   }
	}
	
	echo json_encode( array('message' => $array_errors, 'done' => $ok_done ) );
   	die();
}
add_action( 'wp_ajax_contact_customer', 'contact_customer' );
add_action( 'wp_ajax_nopriv_contact_customer', 'contact_customer' );


?>