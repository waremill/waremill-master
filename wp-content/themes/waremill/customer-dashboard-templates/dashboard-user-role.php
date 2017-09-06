<?php /*Template Name: User Role*/

// chck if is old user => redirect to settings page
if ( is_user_logged_in() && ( check_role_current_user('contractor' ) == true)    ) { 
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;

	$type_of_account = get_field('type_of_account', 'user_'.$user_ID);
	if(strcmp($type_of_account, "old") == 0){
		$fist_login = "field_58a6bf22a7e69";
	    update_field(  $fist_login, "0",  "user_".$user_ID );
	    update_user_meta($user_ID, 'user_first_login', 'false'); 

		$stg = get_field('contractor_settings_page', 'options')->ID;
		$stg_link = get_permalink($stg); 

		$_SESSION['notification'] =  true;
		header('Location: '.  $stg_link);  exit();
	}
}

get_header(); ?>
<?php if ( is_user_logged_in() && ( check_role_current_user('customer' ) == true || check_role_current_user('contractor' ) == true)) { // user logged and with customer role ?>
	<?php 
		$user = wp_get_current_user(); 
		$user_ID = $user->ID;

		//var_dump($user); 
		$user_roles = get_user_roles_by_user_id($user_ID); 

		// is_user_in_role($user_id, $role)

		// access only for users that have first login 

		$check_field_user_first_login 	= get_field('first_login', 'user_'. $user_ID); 
		$check_meta_user_first_login 	= get_user_meta_first_login($user_ID) ;
		
		//var_dump($check_field_user_first_login);
		//var_dump($check_meta_user_first_login);

		associate_draft();

		if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_ID), "true") == 0) { ?>
			<?php
				$fist_login = "field_58a6bf22a7e69";
                update_field(  $fist_login, "0",  "user_".$user_ID );
                update_user_meta($user_ID, 'user_first_login', 'false'); 
			?>
				<div class="dashboard-page">
					<div class="main-content">
						<div class="container">
							<div class="wrappload">
								<div class="loader">
									<img src="img/loading.gif" alt="">
								</div>
								<div class="contentloader">
									<?php /*<form action="" id="change_user_role" name="change_user_role"  method="POST" enctype="multipart/form-data" >*/?>
										<div class="centered-content">
											<?php if (have_posts()) : while (have_posts()) : the_post();?>
												<?php the_content(); ?>
											<?php endwhile; endif; ?>

											<h2 class="userrole"><?php _e('Current Role:', THEME_TEXT_DOMAIN); ?> <span id="userrl"><?php echo $user_roles[0]; ?></span></h2>
										</div>

										<?php $box = get_field('options'); ?>
										<?php	if ($box){ ?>
											<div class="row user-roles">
											<?php foreach ($box as $box1) {  ?>
												<?php $short_description = $box1['short_description']; ?>
												<?php $user_role = $box1['user_role']; ?>	
												<?php $image = $box1['image']; ?>	
												<?php if(!empty( $short_description) && !empty($user_role) && !empty($image)){ ?>			
													<div class="cell x6 boxuser" data-role="<?php echo $user_role; ?>">
														<div class="white-box user-role <?php if(is_user_in_role($user_ID, $user_role)==true){ echo "active"; }?>">
															<div class="userwrapp">
																<div class="icon"><img src="<?php echo $image['sizes']['type']; ?>" alt=""></div>
																<h2 class="userrole userrole-<?php echo $user_role; ?>"><span><?php echo $user_role; ?></span></h2>
																<?php echo $short_description; ?>
																<?php 
																	switch ($user_role) {
								 									   	case "customer":
								 									   		//if(is_user_in_role($user_ID, $user_role)==false){ 
								 									   			?>
								 									   			<?php /*<input type="submit" class="button switch_role" value="<?php _e('Hire', THEME_TEXT_DOMAIN); ?>">*/ ?>
								 									   			<a href="#"  class="button switch_role" data-role="customer"><?php _e('Hire', THEME_TEXT_DOMAIN); ?></a>
								 									   			<?php
								 									   		//}
								 									     	break;
								    									case "contractor":
								    										//if(is_user_in_role($user_ID, $user_role)==false){ 
								    											?>
								    											<a href="#"  class="button switch_role" data-role="contractor"><?php _e('Work', THEME_TEXT_DOMAIN); ?></a>
								 									   			<?php /*<input type="submit" class="button switch_role" value="<?php _e('Work', THEME_TEXT_DOMAIN); ?>">
								    											<?php */
								    										//}
								    										break;
								 									} ?>
															</div>
														</div>
													</div>
												<?php } ?>
													
												
											<?php } ?>
											</div>
										<?php } ?>
										<div class="answerswitch"></div>
										<div class="skipparea">
											<div class="pagination "> 
												<?php 
												
												switch ($user_roles[0]) {  
												   	case "customer": 
												   		$client_dashboard = get_field('client_dashboard', 'options')->ID; 
												   		if(!empty($client_dashboard)){ ?>
												   			<a href="<?php echo get_permalink($client_dashboard); ?>" title="" class="skipbutton"><?php _e('Skip This ', THEME_TEXT_DOMAIN); ?> <i class="fa fa-caret-right" aria-hidden="true"></i></a><?php
												   		}
												     	break;
													case "contractor":
														$contractor_dashboard = get_field('contractor_dashboard', 'options')->ID;
														if(!empty($contractor_dashboard)){ 
															?>
															<a href="<?php echo get_permalink($contractor_dashboard); ?>" title="" class="skipbutton"><?php _e('Skip This ', THEME_TEXT_DOMAIN); ?> <i class="fa fa-caret-right" aria-hidden="true"></i></a><?php
														}
														break;
													} ?>
											</div>
										</div>
										<?php /*<input type="hidden" value="<?php echo wp_create_nonce('submit-changerole'); ?>" name="submit-changerole">
										<input type="hidden" value="changerole" name="action">
									</form>*/?>
									

								</div>
							</div>

							<?php /*
							<div class="row user-roles">
								<div class="cell x6">
									<div class="white-box user-role">
										<div class="icon"><img src="img/user-customer-icon.png" alt=""></div>
										<h2>I want to hire a contractor.</h2>
										<p>Find, collaborate with, and pay an expert.</p>

										<a href="" title="" class="button">Hire</a>
									</div>
								</div>

								<div class="cell x6">
									<div class="white-box user-role">
										<div class="icon"><img src="img/user-contractor-icon.png" alt=""></div>
										<h2>I'm looking for work.</h2>
										<p>Find freelance projects and grow your business.</p>

										<a href="" title="" class="button">Work</a>
									</div>
								</div>
							</div>
							<?php */ ?>
						</div>
					</div>
				</div>

	<?php }else{ ?>

		<div class="default-page">
			<div class="main-content">
				<div class="container">
					<div class="content-wrapper">
						<div class="full-content">
							<?php $role_page_first_login = get_field('role_page_first_login', 'options'); ?>
							<?php if(!empty($role_page_first_login)){ ?>
								<?php echo $role_page_first_login; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>

<?php } else { ?>
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						
						<?php $txt = get_field('no_access_pages_customer_and_contractor', 'options'); ?>
						<?php if(!empty($txt)){ ?>
							<?php echo $txt; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer();