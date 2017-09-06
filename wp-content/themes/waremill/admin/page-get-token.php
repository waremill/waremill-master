<?php /*Template Name: Get Token */
get_header(); ?>
<?php if ( is_user_logged_in() && (check_role_current_user('administrator' ) == true ) ){ // || check_role_current_user('contractor' ) == true)  ) { // user logged and with customer role ?>
	
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="full-content">
					<h1><?php echo get_the_title(); ?></h1>
					<div class="sep"></div>
					
					
					<div class="wrappload">
						<div class="loader largeloader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader"> 
							<form action="" class="generate_token_form" name="generate_token_form"  method="POST" enctype="multipart/form-data" >
								<div class="row input-block-wrapper ">
									<div class="cell x6">
										<label><?php _e('First Name', THEME_TEXT_DOMAIN); ?></label>
										<div class="input-block">
								       		<input type="text" name="user_first_name" required="required">
								       </div>
								    </div>
							    	<div class="cell x6">
										<label><?php _e('Last Name', THEME_TEXT_DOMAIN); ?></label>
										<div class="input-block">
								       		<input type="text" name="user_last_name" required="required">
								       </div>
								    </div>

								    <div class="cell x12">
								    	<label><?php _e('Email Address', THEME_TEXT_DOMAIN); ?></label>
										<div class="input-block">
								       		<input type="email" name="user_email" required="required">
								       </div>
								    </div>
							    </div>

							    <div class="row  centered-content">
									<div class="cell x3 no-float">
										<input type="hidden" value="<?php echo wp_create_nonce('submit_form_token'); ?>" name="submit_form_token">
										<input type="hidden" value="generate_token" name="action">
										<div class="submitarea smalltop">
											<input type="submit" class="button" value="<?php _e('Generate', THEME_TEXT_DOMAIN); ?>"> 
											<i class="fa fa-angle-right"></i>
										</div>
									</div>
								</div>
								<div class="responsecheck_generate"></div>

						    </form>
						   	<div class="outputmd5">
						   		<p><?php _e('Token', THEME_TEXT_DOMAIN); ?></p>
						   		<div id="outputtoken"></div>
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