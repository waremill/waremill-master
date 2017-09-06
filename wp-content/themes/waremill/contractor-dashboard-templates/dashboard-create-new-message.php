<?php /*Template Name: Contractor - Dashboard - Create New Message*/
get_header(); ?>
<?php  
	$user = wp_get_current_user(); 
	$user_ID = $user->ID; ?>
<?php if ( is_user_logged_in() &&  check_role_current_user('contractor' ) == true ) { // user logged and is part for discution ?>
		<?php $page_id = get_the_ID(); ?>
		
		<?php $associated_company_object = get_field('associated_company', 'user_'.$user_ID ); // return ID ?>

		<?php 
			$project_id_selected 	= $_SESSION['id_project']; 
			$author_id_selected 	= $_SESSION['id_author']; ?>

		<div class="dashboard-page">
			<div class="main-content">
				<div class="container">
					<div class="wrappload-send-messages">
						<div class="wrappload">
							<div class="loader largeloader">
								<img src="img/loading.gif" alt="">
							</div>
							<div class="contentloader messages-wrapper nopaddhere"> 
								<form action="" id="new_message" class="reply-form new-message-form" name="new_message"  method="POST" enctype="multipart/form-data" >
									<div class="messages-header">
										<div class="text-content">
											<div class="row">								
												<div class="cell x6 headinfo">
													<p><?php _e('Contractor: ', THEME_TEXT_DOMAIN); ?> <strong><?php echo ucwords($user->user_firstname).' '.ucwords($user->user_lastname); //echo $user->user_email;?></strong></p>
													<p><?php _e('Name of company: ', THEME_TEXT_DOMAIN); ?><strong><?php if(!empty($associated_company_object)){ echo get_field('company_name', $associated_company_object); } ?></strong></p>
												</div>
												<div class="cell x6 headinfo align-right">
													<div class="wrappitemforms">
														<div class="leftlabel">
															<p><span><?php _e('Customer: ', THEME_TEXT_DOMAIN); ?></span></p>
														</div>
														<div class="select-block ">
															<?php //var_dump($_SESSION['new_email_from']); ?>
															<select class="styled simple" name="recipient" required="required" id="populate_customer_by_user">
																<option disabled="disabled" value="0" selected="selected"><?php _e('Please select a customer', THEME_TEXT_DOMAIN); ?></option>
																<?php if(isset($_SESSION['new_email_from']) && strlen($_SESSION['new_email_from']) > 0 ){   ?>
																	<?php echo get_all_customers_selected($_SESSION['new_email_from'], array('contractor', 'customer')); ?>
																<?php } else { ?>
																	<?php echo get_all_customers_selected($author_id_selected, array('contractor', 'customer')); ?>
																<?php } ?>
															</select>
														</div>
													</div>


													<div class="wrappitemforms">
														<div class="leftlabel">
															<p><img src="img/bx_loader.gif" alt="" id="waitloadproj"><span><?php _e('Projects:', THEME_TEXT_DOMAIN); ?></span></p> 
														</div>
														<div class="select-block " >
															<select class="styled simple" name="project" required="required"  id="populate_projects_by_user">
																<?php if(isset($project_id_selected) && strcmp($project_id_selected, $project_id)==0) { ?>
																	<?php  
																	  		$today = date('Ymd');
																			$args =  array( 
																                'ignore_sticky_posts' 	=> true, 
																                'post_type'           	=> 'project',
																                'order'              	=> 'ASC',
																                //'meta_key'				=> 'project_expire_date_project',
																				'orderby'				=> 'title',
																                'posts_per_page'		=> -1, 
																                //'author' 				=> $_SESSION['id_author'],
																                /*'meta_query' => array(
																					array(
																				        'key'		=> 'project_expire_date_project',
																				        'compare'	=> '>=',
																				        'value'		=> $today,
																				    )
																			    ),*/
																			);   

																		 	$loop = new WP_Query( $args ); 
																 			$count = 1 ;
																 			if ($loop->have_posts()) {  ?>
																 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
																 				<?php $project_id = get_the_ID(); ?>
																 					<option value="<?php echo $project_id; ?>" <?php if(isset($project_id_selected) && strcmp($project_id_selected, $project_id)==0) { echo "selected='selected'"; }?>><?php echo get_the_title($project_id); ?></option>
																 				<?php }	?>
																		<?php }	?>
																		<?php wp_reset_postdata(); ?>	
																<?php } else { ?>
																	<option value="0" disabled="disabled" selected="selected"><?php _e('Please select a project', THEME_TEXT_DOMAIN); ?></option>
																<?php } ?>
																
															  	<?php /* 
															  		$today = date('Ymd');
																	$args =  array( 
														                'ignore_sticky_posts' 	=> true, 
														                'post_type'           	=> 'project',
														                'order'              	=> 'ASC',
														                //'meta_key'				=> 'project_expire_date_project',
																		'orderby'				=> 'title',
														                'posts_per_page'		=> -1, 
														                //'author' 				=> $_SESSION['id_author'],
														                /*'meta_query' => array(
																			array(
																		        'key'		=> 'project_expire_date_project',
																		        'compare'	=> '>=',
																		        'value'		=> $today,
																		    )
																	    ),*//*
																	);   

																 	$loop = new WP_Query( $args ); 
														 			$count = 1 ;
														 			if ($loop->have_posts()) {  ?>
														 				<?php  while ($loop->have_posts())	{  $loop->the_post(); ?>
														 				<?php $project_id = get_the_ID(); ?>
														 					<option value="<?php echo $project_id; ?>" <?php if(isset($project_id_selected) && strcmp($project_id_selected, $project_id)==0) { echo "selected='selected'"; }?>><?php echo get_the_title($project_id); ?></option>
														 				<?php }	?>
																<?php }	?>
																<?php wp_reset_postdata(); */ ?>	
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>						
									</div>
									<div class="sep"></div>

									<div class="messages-wrapper white-box">
										<div id="messages" class="no-messages">
											<i class="fa fa-paperclip"></i>
											<p><?php _e('This message board is empty.', THEME_TEXT_DOMAIN); ?></p>
										</div>
										<div class="input-block file-upload-wrapper">
											<textarea name="message" palceholder="Your message here" required="required"><?php if(isset($_SESSION['new_message']) && strlen($_SESSION['new_message']) > 0 ) { echo $_SESSION['new_message']; }?></textarea>
										</div>
										
										<div class="selected-file">
											<i class="fa fa-paperclip"></i>
											<input type="file" id="uploadFile" name="file" placeholder="Choose File" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,  application/pdf, image/*" />
										</div>
										

										<div class="floating-objects">
											
											<input type="hidden" value="<?php echo wp_create_nonce('submit-new-message'); ?>" name="submit-new-message">
											<input type="hidden" value="new_message" name="action">
											<div class="submitarea normalbutton">
												<input type="submit" class="button left" value="<?php _e('Send', THEME_TEXT_DOMAIN); ?>"> 
												<i class="fa fa-angle-right"></i>
											</div>
										</div>
										<div class="answer_replay"></div>
													
									</div>	

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	
<?php } else { ?>
	
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<?php $login_page = get_field('login_but_without_access_on_this_page', 'options'); ?>
						<?php if(!empty($login_page)){ ?>
							<?php echo $login_page; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>
 

<?php get_footer();