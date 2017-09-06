<?php /*Template Name: Contractor - Change Password */
get_header(); ?>
<?php if ( is_user_logged_in() && check_role_current_user('contractor' ) == true  ) { // user logged and with customer role ?>
	
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<h1><?php _e('Change Password', THEME_TEXT_DOMAIN); ?><?php //echo get_the_title(); ?></h1>
						<div class="sep"></div>
						<div class="formbox formbox2">
							<div class="inner-content ">
								<div class="wrappload">
									<div class="loader">
										<img src="img/loading.gif" alt="">
									</div>
									<div class="contentloader">
										<form  action="" class="changepassword_form" name="changepassword_form2"  method="POST" enctype="multipart/form-data" >
											<?php if(get_user_register_type() == 3){ // for users that have this ?>
												<div class="input-block-wrapper">
													<label><?php _e('Password *', THEME_TEXT_DOMAIN); ?></label>
													<input type="password" name="contractor_password" required="required">
												</div>
												 
												<div class="input-block-wrapper">
													<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
													<input type="password" name="contractor_new_password">
												</div>
												 
												<div class="input-block-wrapper">
													<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
													<input type="password" name="contractor_new_password2">
												</div>

											<?php } else { ?>

												<div class="input-block-wrapper">
													<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
													<input type="password" name="contractor_new_password">
												</div>
												

												<div class="input-block-wrapper">
													<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
													<input type="password" name="contractor_new_password2">
												</div>
												
											<?php } ?>

											<input type="hidden" name="user_type" value="<?php echo get_user_register_type(); ?>">
											<input type="hidden" value="<?php echo wp_create_nonce('submit-change-password'); ?>" name="submit-change-password">
											<input type="hidden" value="change_password_form" name="action">
											
											<div class="submitarea">
												<input type="submit" class="button" value="<?php _e('Save Changes', THEME_TEXT_DOMAIN); ?>"> 
												<i class="fa fa-angle-right"></i>
											</div>
											<div class="changepassword_resp"></div>
										</form>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } else { ?>
	<?php if( !is_user_logged_in()){ ?>
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
	<?php } else { // login_but_without_access_on_this_page  ?>
		<div class="default-page">
			<div class="main-content">
				<div class="container">
					<div class="content-wrapper">
						<div class="full-content">
							<?php $txt = get_field('login_but_without_access_on_this_page', 'options'); ?>
							<?php if(!empty($txt)){ ?>
								<?php echo $txt; ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>

<?php get_footer();