<?php 
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
?>
<div class="sidebar">
	<div class="widget">
		<div class="wrappload">
			<div class="loader">
				<img src="img/loading.gif" alt="">
			</div>
			<div class="contentloader">
				<form  action="" id="contact_customer_form" class="contact_customer_form" name="contact_customer_form"  method="POST" enctype="multipart/form-data" >
					<div class="filter-box">
						<div class="filter-header"><h4><?php _e('Contact Contractor', THEME_TEXT_DOMAIN); ?></h4></div>
						<div class="inner-content">
							<div class="input-block-wrapper">
								<label><?php _e('Your name (required)', THEME_TEXT_DOMAIN); ?></label>
								<div class="input-block">
									<input type="text" name="contact_contractor_name" required="required" value="<?php echo $user->display_name; ?>">
								</div>
								<label><?php _e('Your email (required)', THEME_TEXT_DOMAIN); ?></label>
								<div class="input-block">
									<input type="email" name="contact_contractor_email" required="required" value="<?php echo $user->user_email; ?>">
								</div>
								<label><?php _e('Message (required)', THEME_TEXT_DOMAIN); ?></label>
								<div class="input-block">
									<textarea name="contact_contractor_message" required="required" minlength="50"></textarea>
								</div>
							</div>
							<?php /*
							<button type="submit"><?php _e('Send', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></button>
							<?php */ ?>
							<div class="floating-objects">
								<input type="hidden" name="company_id" value="<?php echo get_the_ID(); ?>">

								<input type="hidden" value="<?php echo wp_create_nonce('submit-contact-customer-logged'); ?>" name="submit-contact-customer-logged">
								<input type="hidden" value="contact_customer" name="action">
								<div class="submitarea fullbutton">
									<input type="submit" class="button left" value="<?php _e('Send', THEME_TEXT_DOMAIN); ?>"> 
									<i class="fa fa-angle-right"></i>
								</div>
							</div>
							<div class="answer_contact_customer"></div>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>