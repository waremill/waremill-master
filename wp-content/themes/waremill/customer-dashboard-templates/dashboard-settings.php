<?php /*Template Name: Customer- Dashboard - Settings*/
get_header();
$this_page_id = get_the_ID();  ?>
<?php if ( is_user_logged_in() && check_role_current_user('customer' ) == true) { // user logged and with customer role ?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<h1><?php _e('Settings', THEME_TEXT_DOMAIN); ?></h1>
				<div class="sep"></div>
				<?php $user = wp_get_current_user(); 
					 $user_ID = $user->ID;
				 ?>
				<div class="contractor-registration-form">
					<div class="wrappload">
						<div class="loader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader">
							<form  action="" class="update_customer_form" name="update_customer_form"  method="POST" enctype="multipart/form-data" >
								<div class="row tab-items" >
									<div class="cell x3 column_menu">
										
										<div class="wrapptables">
											<ul class="tabs">
												<li class="tab-link current" data-tab="tab-1"><?php echo get_field('main_info', $this_page_id); ?></li>
												<li class="tab-link" data-tab="tab-2"><?php echo get_field('password_settings', $this_page_id); ?></li>
												<li class="tab-link" data-tab="tab-3"><?php echo get_field('notification_settings', $this_page_id); ?></li>
											</ul>
										</div>
										<div class="wrappbuttons">
											<div class="row centered-content">
												<div class="cell x12">
													<input type="hidden" name="user_type" value="<?php echo get_user_register_type(); ?>">
													<input type="hidden" value="<?php echo wp_create_nonce('submit-updatecustomer'); ?>" name="submit-updatecustomer">
													<input type="hidden" value="user_update_customer" name="action">
													
													<div class="submitarea smalltop">
														<input type="submit" class="button" value="<?php _e('Save Changes', THEME_TEXT_DOMAIN); ?>"> 
														<i class="fa fa-angle-right"></i>
													</div>
													<div class="responsecheck_update_customer"></div>
												</div>
											</div>
										</div>

									</div>
									<div class="cell x9 column_fields columns_customer">
										
										<div id="tab-1" class="tab-content current">
											<div class="cell x12 input-block title-form-section">
												<h3><?php echo get_field('main_info', $this_page_id); ?></h3>
											</div>

											<div class="wrappformfields">
												<div class="cell x6">
													<div class="input-block">
														<label><?php _e('First Name *', THEME_TEXT_DOMAIN); ?></label>
														<input type="text" name="update_first_name_customer" value="<?php echo $user->user_firstname; ?>" required="required">
													</div>
												</div>
												<div class="cell x6">
													<div class="input-block">
														<label><?php _e('Family Name *', THEME_TEXT_DOMAIN); ?></label>
														<input type="text" name="update_last_name_customer" value="<?php echo $user->user_lastname;?>" required="required">
													</div>
												</div>
												<div class="cell x6">
													<div class="input-block">
														<label><?php _e('Company Name', THEME_TEXT_DOMAIN); ?></label>
														<input type="text" name="update_company_customer"  value="<?php echo $user->description; ?>" >
													</div>
												</div>
												<div class="cell x6">
													<div class="input-block">
														<label><?php _e('Email *', THEME_TEXT_DOMAIN); ?></label>
														<input type="email" name="update_email_customer" value="<?php echo $user->user_email; ?>" required="required">
													</div>
												</div>
											</div> 
										</div>

										<div id="tab-2" class="tab-content">
											<div class="cell x12 input-block title-form-section">
												<h3><?php echo get_field('password_settings', $this_page_id); ?></h3>
											</div>

											<div class="wrappformfields">
												<?php if(get_user_register_type() == 3){ // for users that have this ?>
													<div class="cell x4">
														<div class="input-block">
															<label><?php _e('Password *', THEME_TEXT_DOMAIN); ?></label>
															<input type="password" name="update_old_password_customer" value="" required="required">
														</div>
													</div>
													
													<div class="cell x4">
														<div class="input-block">
															<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
															<input type="password" name="update_new_password_customer" value="">
														</div>
													</div>

													<div class="cell x4">
														<div class="input-block">
															<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
															<input type="password" name="update_new_password_customer_2" value="">
														</div>
													</div>
												<?php } else { ?>
													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
															<input type="password" name="update_new_password_customer" value="">
														</div>
													</div>
													
													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
															<input type="password" name="update_new_password_customer_2" value="">
														</div>
													</div>

												<?php } ?>
											</div>
										</div>

										<div id="tab-3" class="tab-content">
											<div class="cell x12 input-block title-form-section">
												<h3><?php echo get_field('notification_settings', $this_page_id); ?></h3>
											</div>

											<div class="wrappformfields">
												<div class="cell x12">
														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_bid" <?php $notify_new_procurement = get_field('notify_a_new_bid', 'user_'.$user_ID ); if($notify_new_procurement==true){  echo "checked='checked'"; } // $user_ID ?> > <?php echo get_field('label_for_new_bid', 'options'); ?></label>
															</div>
														</div>

														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_message" <?php $notify_new_message = get_field('notify_new_message', 'user_'.$user_ID ); if($notify_new_message==true){  echo "checked='checked'"; }  ?> > <?php echo get_field('label_for_new_message', 'options'); ?></label>
															</div>
														</div>		

														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_forum_posts" <?php $notify_new_forum_posts = get_field('notify_new_forum_posts', 'user_'.$user_ID ); if($notify_new_forum_posts==true){ echo "checked='checked'";  }  ?>> <?php echo get_field('label_for_new_forum_posts', 'options'); ?></label>
															</div>
														</div>

														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_newsletter" <?php $waremills_newsletter = get_field('waremills_newsletter', 'user_'.$user_ID ); if($waremills_newsletter==true){ echo "checked='checked'";  } ?>> <?php echo get_field('label_for_newsletter', 'options'); ?></label>
															</div>
														</div>		

												</div>
											</div>
										</div>

									</div>
								</div>
							</form>



							<?php /*
							<br/><br/><br/><br/><br/><br/><br/>====================================<br/><br/><br/><br/><br/><br/><br/>
							<form  action="" class="update_customer_form" name="update_customer_form"  method="POST" enctype="multipart/form-data" >
								<div class="row visible">
									<div class="cell x6">
										<div class="input-block">
											<label><?php _e('First Name', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="update_first_name_customer" value="<?php echo $user->user_firstname; ?>" required="required">
										</div>
									</div>
									<div class="cell x6">
										<div class="input-block">
											<label><?php _e('Family Name', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="update_last_name_customer" value="<?php echo $user->user_lastname;?>" required="required">
										</div>
									</div>
									<div class="cell x6">
										<div class="input-block">
											<label><?php _e('Company Name', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="update_company_customer"  value="<?php echo $user->description; ?>" >
										</div>
									</div>
									<div class="cell x6">
										<div class="input-block">
											<label><?php _e('Email', THEME_TEXT_DOMAIN); ?></label>
											<input type="email" name="update_email_customer" value="<?php echo $user->user_email; ?>" required="required">
										</div>
									</div>
									
									<?php if(get_user_register_type() == 3){ // for users that have this ?>
										<div class="cell x4">
											<div class="input-block">
												<label><?php _e('Password *', THEME_TEXT_DOMAIN); ?></label>
												<input type="password" name="update_old_password_customer" value="" required="required">
											</div>
										</div>
										
										<div class="cell x4">
											<div class="input-block">
												<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
												<input type="password" name="update_new_password_customer" value="">
											</div>
										</div>

										<div class="cell x4">
											<div class="input-block">
												<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
												<input type="password" name="update_new_password_customer_2" value="">
											</div>
										</div>
									<?php } else { ?>
										<div class="cell x6">
											<div class="input-block">
												<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
												<input type="password" name="update_new_password_customer" value="">
											</div>
										</div>
										
										<div class="cell x6">
											<div class="input-block">
												<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
												<input type="password" name="update_new_password_customer_2" value="">
											</div>
										</div>

									<?php } ?>
								</div> 


								<div class="row centered-content">
									<div class="x4 no-float">
										<input type="hidden" name="user_type" value="<?php echo get_user_register_type(); ?>">
										<input type="hidden" value="<?php echo wp_create_nonce('submit-updatecustomer'); ?>" name="submit-updatecustomer">
										<input type="hidden" value="user_update_customer" name="action">
										<div class="responsecheck_update_customer"></div>
										<div class="submitarea smalltop">
											<input type="submit" class="button" value="<?php _e('Save Changes', THEME_TEXT_DOMAIN); ?>"> 
											<i class="fa fa-angle-right"></i>
										</div>
									</div>
								</div>


							</form>
							<?php */ ?>
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