<?php 


/********************************************* contractor: update settings  *********************************************/

function bid_project(){

	$array_errors = array();
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;

	if(isset($user_ID)){
		if (isset($_POST['submit-bidding']) ) {
		    if(wp_verify_nonce($_POST['submit-bidding'], 'submit-bidding') == 1) {

		    	if(check_role_current_user("contractor") == true){
			    	unset($_POST['submit-form']);
		    		unset($_POST['action']);
		    		$ok_process = false;

		    		// check in project author is not the same with the current user (same users can't bit at their projects)
		    		$array_required = array("price", "delivery_date");

			    	foreach ($_POST as $key => $value)	{
			    		if(!isset($key) || (in_array($key, $array_required) == true && strlen($value) == 0) ){
							$clear = str_replace("_", " ", $key);
							$array_errors[$key] = "<p class='ferror fcenter'>Error: You can not leave " . $clear . " empty!</p>" . sizeof($value);
						}

						if(strcmp($key, 'project') == 0 && strlen($key) < 0){
							$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
						}

						if(strcmp($key, "price") == 0 && strlen($value) > 0 && preg_match("/\d/", $value) != true){
							$array_errors['message'] = "<p class='ferror fcenter'>Error: The price is not valid.</p>";
						}

						if(strcmp($key, "delivery_date") == 0 && strlen($value) > 0){
							$dev_data = $_POST['delivery_date'];   
	                        $date = DateTime::createFromFormat('d/m/Y', $dev_data);
	                        $date = $date->format('Ymd');
	                        $today = date('Ymd');
	                        if(intval($date) < intval($today)){
	                        	$array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid delivery date.</p>";
	                        }
						}

			    	}

			    	if(sizeof($array_errors) == 0){ // no errors

			    		// insert new post in bids
			    		$project_id = $_POST['project'];
			    		$bid_number = check_bid($project_id, $user_ID);

		    			$auth = get_post($project_id); // gets author from post
						$authid = $auth->post_author; // gets author id for the post
						$auth_user = get_user_by('ID', $authid); 
						$auth_user_email = $auth_user->user_email;
			    		
			    		// check if user bid already to this project ============================================================================
			    		if($bid_number == false ){
				    		$my_post = array(
	                            'post_title'      	=> "Bid: Project - " . get_the_title($project_id )." /  Contractor - " . $user->user_email .' / Customer - ' . $auth_user_email,
	                            'post_status'     	=> 'publish',
	                           	'post_author'   	=>  $user_ID,
	                            'post_type'       	=> 'bid',
	                        );    

	                        $id_new_post = wp_insert_post( $my_post );

							if($user_ID != $authid){ 
								if( !is_wp_error($id_new_post) ){
		                        	$field_project_id 		= "field_5892eaab157c3";
			                        $field_project_owner 	= "field_5892eabf157c4";
			                        $field_bidder 			= "field_5892ead5157c5";
			                        $field_price_per_item	= "field_5892eb6fa2e62";
			                        $field_delivery_date	= "field_5892eb7ca2e63"; 
			                        $field_notes 			= "field_5892eb83a2e64";
			                        $field_read				= "field_5892fb1fe3e1a";

			                        update_field( $field_project_id, $project_id, $id_new_post );
			                        update_field( $field_read, false, $id_new_post );
			                        // get author of this project 

			                        update_field( $field_project_owner, $authid, $id_new_post );
			                        update_field( $field_bidder, $user_ID, $id_new_post );
			                        update_field( $field_price_per_item, $_POST['price'], $id_new_post);

			                        $dev_data = $_POST['delivery_date'];   
			                        $date = DateTime::createFromFormat('d/m/Y', $dev_data);
			                        $date = $date->format('Ymd');
			                        update_field( $field_delivery_date, $date,  $id_new_post );

			                        if( isset($_POST['notes']) && strlen($_POST['notes']) > 0){
			                        	update_field( $field_notes, $_POST['notes'], $id_new_post );
			                        } 


			                        // send email notification for customer

			                        // update field total bids + total bids new from project
			                        $field_total_bids 		= "field_589d8d07be82d";
			                        $field_total_bids_new 	= "field_589d8d32be82e";

			                        $total_bids_project 	= get_bidders_number($project_id);
			                        $total_bids_unread 		= get_bidders_unread($project_id);

			                        update_field($field_total_bids, $total_bids_project, $project_id);
			                        update_field($total_bids_unread, $total_bids_unread, $project_id);
 	

 									$send_email = send_email_new_bid($authid, $project_id); 
 									if($send_email == true){
 										$array_errors['done']  = true; 
			                        	$array_errors['message'] = "<p class='fsuccess fcenter'><br/>Success: Your bid was successfully sent!<br/> Please wait to update the information and reload the page.</p>";	
 									}else{
 										$array_errors['done']  = true; 
			                        	$array_errors['message'] = "<p class='fsuccess fcenter'><br/>Success: Your bid was successfully sent but the customer did not received the email confirmation!<br/> Please wait to update the information and reload the page.</p>";	
 									}

		                        }else{
		                        	$array_errors['message'] = "<p class='ferror fcenter'>Error: Problems on send the bid! Please try again later.</p>";	
		                        } 
								
		                    }else{
		                    	$array_errors['message'] = "<p class='ferror fcenter'>Error: You can't bid to your own project.</p>";	
		                    }

	                    } else {
							$array_errors['message'] = "<p class='ferror fcenter'>Error: You already bid to this project.</p>";	
						}

			    	} // errors will be listed

		    	}else{
		        	$array_errors['message'] = "<p class='ferror fcenter'>Error: Only contractors can bid projects.</p>";
		    	}	

		    }else {
		        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		    }	
		}else{
			$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		}
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	echo json_encode( array('message' => $array_errors  ));
   	die();

}
add_action( 'wp_ajax_bid_project', 'bid_project' );
add_action( 'wp_ajax_nopriv_bid_project', 'bid_project' );

/********************************************* customer: mark as read *********************************************/
function markasread(){
	$ok_done = false; 
	$total_bids_new = 0;
	if (isset($_POST['action']) && strcmp($_POST['action'], 'markasread') == 0 ) {
		if(isset($_POST['id']) && strlen($_POST['id']) > 0 && isset($_POST['project_id']) && strlen($_POST['project_id'])>0){

			$user = wp_get_current_user(); 
			$user_ID = $user->ID;

			$id_bid = $_POST['id'];
			$id_project = $_POST['project_id'];

			$user_owner = get_field('project_owner', $id_bid );
			$id_user_owner = $user_owner['ID'];
			
			// check if current user is the owner of this project
			if($id_user_owner == $user_ID){
				// update acf "read"
				$field_read = "field_5892fb1fe3e1a";
				update_field($field_read, true, $id_bid);
				$ok_done = true;	

				$total_bids = intval(get_field('total_bids', $id_project));
				$total_bids_new = get_bidders_unread_total(); //intval(get_field('total_bids_new', $id_project));
			}
		}
	}
	echo json_encode( array('done' => $ok_done, 'new_bids' => $total_bids_new));
   	die();
}
add_action( 'wp_ajax_markasread', 'markasread' );
add_action( 'wp_ajax_nopriv_markasread', 'markasread' );


/********************************************* customer: get bidders *********************************************/
/* 
*  Function  : get_bidders_no
*  Parameters: project_id
*  Return    : html with bidder nr
*/
function get_bidders_no($project_id){ ob_start(); ?>
	<?php 
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
		$count = $loop->post_count; ?>	
	<div class="no-bidders">
		<h4><?php echo $count; ?></h4>
		<span><?php if($count==1){ echo 'Bidder'; }else { echo 'Bidders'; } ?></span>
	</div>
<?php 
	wp_reset_postdata();
	$project_info = ob_get_clean(); 
	return $project_info; 			
}

/********************************************* customer: get bidders number - only *********************************************/
/* 
*  Function  : get_bidders_number
*  Parameters: project_id
*  Return    : int - nr of bids for a project
*/
function get_bidders_number($project_id){
	$count = 0;
	$args_0 =  array( 
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

 	$loop = new WP_Query( $args_0 );
	$count = $loop->post_count;
	wp_reset_postdata();
	return $count;
}


/********************************************* customer: get bidders new number = to a specific project *********************************************/
/* 
*  Function  : get_bidders_unread
*  Parameters: project_id
*  Return    : int - nr of bids for a project not read
*/
function get_bidders_unread($project_id){
	$count = 0;
	$args_0 =  array( 
        'ignore_sticky_posts' 	=> true, 
        'post_type'           	=> 'bid',
        'order'              	=> 'DESC',
        'posts_per_page'		=> -1,
        'meta_query' => array(
        	'relation'		=> 'AND',
			array(
		        'key'		=> 'project',
		        'compare'	=> '=',
		        'value'		=> $project_id,
		    ),
		    array(
		    	'key'		=>'read',
		    	'compare'	=> '=',
		    	'value'		=> false
		    )
	    ),
	);   

 	$loop = new WP_Query( $args_0 );
	$count = $loop->post_count;
	wp_reset_postdata();
	return $count;
}

/********************************************* customer: get bidders new number = for all projects *********************************************/
/* 
*  Function  : get_bidders_unread_total
*  Parameters: 
*  Return    : int - nr of bids not read
*/
function get_bidders_unread_total(){
	$count = 0;
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	$args_10 =  array( 
        'ignore_sticky_posts' 	=> true, 
        'post_type'           	=> 'bid',
        'order'              	=> 'DESC',
        'posts_per_page'		=> -1,
        'meta_query' => array(
        	'relation'		=> 'AND',
		    array(
		    	'key'		=>'read',
		    	'compare'	=> '=',
		    	'value'		=> false
		    ), 
		    array(
		    	'key'		=>'project_owner',
		    	'compare'	=> '=',
		    	'value'		=> $user_ID
		    )
	    ),
	);   

 	$loop = new WP_Query( $args_10 );
	$count = $loop->post_count;
	wp_reset_postdata();
	return $count;
}

/********************************************* BID: Check if user bid to this project  *********************************************/
/* 
*  Function  : check_bid
*  Parameters: project_id, user_id
*  Return    : boolean = check if user_id bid to a bid_id
*/
function check_bid($project_id, $user_id){
	$ok = false; 
	$count = 0;
	$args_5 =  array( 
        'ignore_sticky_posts' 	=> true, 
        'post_status' 			=> array( 'publish'),
        'post_type'           	=> 'bid',
        'order'              	=> 'DESC',
        'posts_per_page'		=> -1,
        'meta_query' => array(
        	'relation' => 'AND',   
			array(
		        'key'		=> 'project',
		        'compare'	=> '=',
		        'value'		=> $project_id,
		    ),
		    array(
		    	'key'		=> 'bidder',
		        'compare'	=> '=',
		        'value'		=> $user_id,
		    )
	    ),
	);   

 	$loop = new WP_Query( $args_5 ); 
 	$count = $loop->post_count;
 	wp_reset_postdata();
 
 	if($count > 0){
 		$ok = true;
 	}else{
 		$ok = false;
 	}
	return $ok; 
}

/********************************************* BID: Check if user bid to this project  *********************************************/
/* 
*  Function  : get_current_bid
*  Parameters: project_id, user_id
*  Return    : int = no of bids if user_id bid to a bid_id
*/
function get_current_bid($project_id, $user_id){
	$bid = null;
	$count = 0;
	$args_5 =  array( 
        'ignore_sticky_posts' 	=> true, 
        'post_type'           	=> 'bid',
        'order'              	=> 'DESC',
        'posts_per_page'		=> -1,
        'meta_query' 			=> array(
        	'relation' 			=> 'AND',   
			array(
		        'key'			=> 'project',
		        'compare'		=> '=',
		        'value'			=> $project_id,
		    ),	
		    array(
		    	'key'			=> 'bidder',
		        'compare'		=> '=',
		        'value'			=> $user_id,
		    )	
	    ),
	);   

 	$loop = new WP_Query( $args_5 ); 
 	$count = $loop->post_count;

 	//var_dump($loop->posts);
 	if($count > 0){
 		$bid = $loop->posts[0];
 	}
 	wp_reset_postdata();
	return $bid; 
}

/********************************************* customer: get list of bidders  *********************************************/
/* 
*  Function  : get_list_bids
*  Parameters: project_id
*  Return    : html (list of bids for current user )
*/
function get_list_bids($project_id){ ob_start(); ?>
	<?php $args_3 =  array( 
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

 	$loop = new WP_Query( $args_3 ); 
	if ($loop->have_posts()) {  ?>
		<div class="effectoptions wrappload">
			<div class="loader">
				<img src="img/loading.gif" alt="">
			</div>
			<div class="contentloader">
			<?php 
				while ($loop->have_posts())	{  $loop->the_post(); 
				$bid_id = get_the_ID(); 
				$bidder = get_field('bidder', $bid_id); 
				$bidder_id = $bidder['ID'];
				$customer_hired = get_field('customer_hired', $project_id);  

				$bid_choose = false; 
				if(!empty($customer_hired) && $customer_hired['ID'] == $bidder_id  ){
					$bid_choose = true;
				}else {
					$bid_choose = false;
				}

				$date 		= get_field('project_expire_date_project', $project_id);
				$date 		= new DateTime($date);
				$intdate	= intval($date->format('Ymd'));
				$today 		= intval(date('Ymd')); 

				$archive = false; // active
				if($today >= $intdate){
					$archive = true; // archive
				}

				// check if this project is done or not 
				$project_done  = false;
				if(!empty($customer_hired)){
					$project_done = true; 
				} ?>
				<div class="bid-item white-box <?php $new = get_field('read', $bid_id); if($new == false){ echo "new"; } if($bid_choose == true) { echo "bid-choose"; } ?>" data-id="<?php echo $bid_id; ?>" data-project="<?php echo $project_id; ?>">
					<div class="image">
						<?php if($bid_choose == true) {  ?>
							<i class="fa fa-check" aria-hidden="true"></i>
						<?php } ?>
						<?php // get avatar - contractor ?>
						<?php 
							$bidder_company = get_field('associated_company', 'user_'.$bidder_id);
							$bidder_company_id = $bidder_company->ID;
							$image = get_field('logo', $bidder_company_id);			 
							if(!empty($image)){   ?>
								<img src="<?php echo $image['sizes']['vsmall-logo']; ?>" alt="<?php echo $image['alt']; ?>" />
						<?php } else { ?>
							<img src="img/no-logo-company.jpg" alt="">
						<?php } ?>
					</div>
					<div class="text-content">
						<h4><?php echo ucwords($bidder['user_firstname'].' '.$bidder['user_lastname'] ); //echo $bidder['user_email']; ?></h4>
						<p><?php _e('Added: ', THEME_TEXT_DOMAIN); ?> <span class="spacespan"><?php echo get_the_time('G:i:s', $bid_id); ?></span><span class="spacespan"><?php echo get_the_time( 'd/m/Y', $bid_id); ?></span></p>
						<div class="view-more"><?php _e('Bid details', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-down"></i></div>
					</div>
					<div class="clear"></div>
					<div class="hidden-content">
						<div class="left-content">
							<h5><b><?php _e('Notes:', THEME_TEXT_DOMAIN); ?></b></h5>
							<?php $notes = get_field('notes', $bid_id); ?>
							<?php if(!empty($notes)){ ?>
								<?php echo $notes; ?>
							<?php } ?>

							<div class="floating-objects" data-bid="<?php echo $bid_id; ?>">
								<?php  if($archive == false ){ ?>
									<?php if($project_done == false  ){ ?>
										<a href="#" class="button small green left confirmation_hire_contractor" ><?php _e('Hire', THEME_TEXT_DOMAIN); ?></a>
										<div class="confirmation-hider">
											<div class="wrappremovemsg">
												<div class="wrappload">
													<div class="loader">
														<img src="img/loading.gif" alt="">
													</div>
													<div class="contentloader">
														<div class="wrappbasictext" data-id="1365"> 
															<p class="large"><?php _e('Are you sure that you want to hire this customer?', THEME_TEXT_DOMAIN); ?></p>
															<div class="wrappbuttons">
																<a href="#" title="" class="button green nohirebutton" ><?php _e('NO', THEME_TEXT_DOMAIN); ?> <i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
																<a href="#" title="" class="button red hire_contractor" data-id="<?php echo $project_id; ?>" data-user="<?php echo $bidder_id; ?>" data-bid="<?php echo $bid_id; ?>"><?php _e('YES', THEME_TEXT_DOMAIN); ?> <i class="fa fa-times-circle-o" aria-hidden="true"></i></a> 
															</div>
														</div>
														<div class="answerremoveproject" data-id="1365"></div>
													</div>
												</div>
											</div>
										</div>
										<a href="#" class="button small left contact_contractor" data-id="<?php echo $project_id; ?>" data-user="<?php echo $bidder_id; ?>"><?php _e('Contact Contractor', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
									<?php } ?>
								<?php } ?>
								
								<?php if($bid_choose == true) {  ?>
									<a href="#" class="button small left contact_contractor" data-id="<?php echo $project_id; ?>" data-user="<?php echo $bidder_id; ?>"><?php _e('Contact Contractor', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
								<?php  }  ?>
							</div>
							
						</div>
						<div class="right-content">
							<h5><b><?php _e('Price per item:', THEME_TEXT_DOMAIN); ?></b></h5>
							<p><?php $price = get_field('price_per_item', $bid_id); if(!empty($price)) { echo $price; } ?></p>

							<h5><b><?php _e('Delivery date:', THEME_TEXT_DOMAIN); ?></b></h5>
							<p><?php $date_dev = get_field('delivery_date', $bid_id); $date = new DateTime($date_dev); echo $date->format('d/m/Y'); ?></p>
						</div>
					</div>
				</div>
		
		 	<?php  }	?>
		 	<div class="redirect_answ"></div>
		 	</div>
		 </div>
	<?php 	}
	wp_reset_postdata(); 
	$project_info = ob_get_clean(); 
	return $project_info; 		
}

/********************************************* customer: check if someone is bid to a project *********************************************/
/* 
*  Function  : user_bid
*  Parameters: project_id
*  Return    : user_id that was hired
*/
function user_bid($project_id){
	$customer_hired = get_field('customer_hired', $project_id);
	if(empty($customer_hired)){
		return null;
	}else{
		return $customer_hired; 
	}
}


/********************************************* customer: redirect when press button "Contact Contractor" *********************************************/
function action_redirect_customer(){
	$array_errors 	= array();
	$user 			= wp_get_current_user(); 
	$user_ID 		= $user->ID;
	$role 			=  $user->roles[0];
 
	//$array_errors['done']  = false; 
	$ok_done = false; 
	$link_redirect = '';

	unset($_SESSION['id_project']);
	unset($_SESSION['id_author']);
	unset($_SESSION['new_name']);
	unset($_SESSION['new_email']);
	unset($_SESSION['new_message']);
	unset($_SESSION['project_id']);

	if(isset($user_ID)){
	    if(isset($_POST['action']) && strcmp($_POST['action'], 'action_redirect_customer') == 0) {
	    	// check if the  current user is the author of this project
	    	if(isset($_POST['project_id']) && strlen($_POST['project_id']) > 0 ){

	    		//$_SESSION['id_project'] = $_POST['project_id'];
	    		if(isset($_POST['user_id']) && strlen($_POST['user_id']) > 0){

	    			$check_project_author = check_project_autor($_POST['project_id'], $user_ID); 
		    		if($check_project_author == true){

		    			// check if there is a messages with the  curent user + contractor + project
		    			$validate_relation = check_customer_contractor_project_message($user_ID, $_POST['user_id'], $_POST['project_id'], $role ); 

		    			//var_dump($validate_relation); 
		    			
		    			if( sizeof($validate_relation) > 0 ){ // exist a discution
		    				if(sizeof($validate_relation) == 1){
		    					$ok_done = true;
		    					$id_discution = $validate_relation[0]->ID;
		    					$link_redirect = get_permalink($id_discution);
		    				}

		    			}else { // not exist discution => redirect new page => set session
		    				$ok_done = true;
		    				$get_contractor = get_user_by('id', $_POST['user_id']); 
		    				$_SESSION['new_email_from'] = $get_contractor->ID;
		    				$_SESSION['id_project'] 	= $_POST['project_id'];
		    				$_SESSION['project_id'] 	= $_POST['project_id'];

		    				$customer_create_new_messages = get_field('customer_create_new_messages', 'options');
		    				$link_redirect = get_permalink($customer_create_new_messages->ID);

		    			}

		    		}else{
		    			$array_errors['message'] = "<p class='ferror fcenter'>Error: You are not the author of this project.</p>";
		    		}
	    		}else{
	    			$array_errors['message'] = "<p class='ferror fcenter'>Error: Invalid Contractor.</p>";
	    		}
	    	}else {
	    		$array_errors['message'] = "<p class='ferror fcenter'>Error: Project not selected.</p>";
	    	}
 		}else {
	        $array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	    }	
	}else{
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	echo json_encode( array('message' => $array_errors, 'done' => $ok_done, 'link' => $link_redirect  ));
   	die();

}
add_action( 'wp_ajax_action_redirect_customer', 'action_redirect_customer' );
add_action( 'wp_ajax_nopriv_action_redirect_customer', 'action_redirect_customer' );

/********************************************* customer: hire contractor  *********************************************/
function action_hire_customer(){
	$ok_done = false;
	$array_errors = array();
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;

	if (isset($_POST['action']) && strcmp($_POST['action'], 'action_hire_customer') == 0 ) {
		if(isset($_POST['project_id']) && strlen($_POST['project_id']) > 0 && isset($_POST['user_id']) && strlen($_POST['user_id']) > 0 && isset($_POST['bid_id']) && strlen($_POST['bid_id']) > 0){

			// check if contractor / bid / project are correct 
			$project_id 	= $_POST['project_id'];
			$contractor_id 	= $_POST['user_id'];
			$bid_id 		= $_POST['bid_id'];
			//var_dump($_POST);
			//$check_bid_valid = check_bid_valid($project_id, $contractor_id, $bid_id );

			if (get_post_type($bid_id ) == 'bid'){
				$project = get_field('project', $bid_id)->ID;
				$contractor = get_field('bidder', $bid_id)['ID'];		
				if(intval($project) == intval($project_id) && intval($contractor) == intval($contractor_id)){
					// update field in bild 
					$field_selected = "field_589d789894bfb";
					update_field($field_selected, true, $bid_id);

					// update field in project
					$field_customer_hired = "field_589337e6bb100";
					update_field($field_customer_hired, $contractor_id, $project_id);

					//make archive project
					$field_pexdate_project = "field_587f4399e97c4";
					$today = date('Ymd');
					$date = DateTime::createFromFormat('Ymd',  $today );
		            $date = $date->format('Ymd');

					update_field($field_pexdate_project, $date, $project_id);

					// send email to contractor

					$output_email = send_email_hire($contractor_id, $project_id); 


					$ok_done = true; 
					// $bid_selected = 
					// $customer_hired = get_field('customer_hired', $project_id); 
				}else{
					$array_errors['message'] = "<p class='ferror fcenter'>Error: Project and contractor aren't match.</p>";
				}
			}else {
				$array_errors['message'] = "<p class='ferror fcenter'>Error: This is not a correct bid.</p>";
			}
		}else{
			$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
		}
	}else {
		$array_errors['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
	}

	echo json_encode( array('done' => $ok_done ));
   	die();

}
add_action( 'wp_ajax_action_hire_customer', 'action_hire_customer' );
add_action( 'wp_ajax_nopriv_action_hire_customer', 'action_hire_customer' );

/********************************************* customer: check if someone is bid to a project *********************************************/
/* 
*  Function  : check_bid_valid
*  Parameters: project_id, contractor_id, bid_id
*  Return    : boolean => check if project_id, contractor_id and bid_id match
*/
function check_bid_valid($project_id, $contractor_id, $bid_id ) {
	$project = get_field('project', $bid_id )->ID;
	$contractor = get_field('bidder', $bid_id)->ID;
	$ok = false;
	if(intval($project) == intval($project_id) && intval($contractor) == intval($contractor_id)){
		$ok = true;
	}

	return $ok; 

}

?>