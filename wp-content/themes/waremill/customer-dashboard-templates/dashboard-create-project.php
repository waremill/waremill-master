<?php /*Template Name: Customer - Dashboard - Create Project*/
get_header();   ?>
<?php // var_dump($_SESSION['create-project-hp']); ?>
<?php if ( (is_user_logged_in() && check_role_current_user('customer' ) == true) || ( !is_user_logged_in() && isset($_SESSION['create-project-hp']) && $_SESSION['create-project-hp'] == true )) { // user logged and with customer role ?>
<?php $homepage = get_option('homepage', 'options')->ID; ?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<h1><?php _e('Create new project', THEME_TEXT_DOMAIN); ?></h1>
				<div class="sep"></div>
				<div class="new-project-form">
					<div class="wrappload">
						<div class="loader">
							<img src="img/loading.gif" alt="">
						</div>
						<div class="contentloader">
							<form action="" id="customer_createproject_form" name="customer_createproject_form"  method="POST" enctype="multipart/form-data" >
								<div class="input-block" data-tip="Please enter a project name here.">
									<label><?php _e('Project Name *', THEME_TEXT_DOMAIN); ?></label>
									<input type="text" name="new_project_customer"  required="required" >
								</div>
								<div class="row noowhidden3">
								<?php  $terms = get_terms( 
											array(
											    'taxonomy' => 'industry',
											    'hide_empty' => false,
											) 
										);
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
										<div class="cell x6">
											<div class="input-block-wrapper">
												<label><?php _e('Material *', THEME_TEXT_DOMAIN); ?></label>
												<div class="input-block" data-tip="Select a material.">
													<i class="fa fa-check-circle"></i>
													<div class="select-block">
														<select name="new_project_industries[]" multiple="multiple" placeholder="Please select a material." class="SlectBox" required="required">
															<?php foreach ( $terms as $term ) { ?>
															    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="wrappselectitems"></div><?php // do not remove this div ?>

											</div>
										</div>
									<?php } ?>
									
									<?php  $terms = get_terms( 
											array(
											    'taxonomy' => 'service',
											    'hide_empty' => false,
											) 
										);
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>	
										<div class="cell x6">
											<div class="input-block-wrapper">
												<label><?php _e('Service *', THEME_TEXT_DOMAIN); ?></label>
												<div class="input-block" data-tip="Select a service.">
													<i class="fa fa-check-circle"></i>
													<div class="select-block">
														<select name="new_project_services[]" multiple="multiple" placeholder="Please select a service." class="SlectBox" required="required">
															<?php foreach ( $terms as $term ) { ?>
															 	<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="wrappselectitems"></div><?php // do not remove this div ?>
											</div>
										</div>
									<?php } ?>
								</div>
								
								<div class="input-block" data-tip="Write a description.">
									<label><?php _e('Description *', THEME_TEXT_DOMAIN); ?>
										<span class="smalltext"><?php _e('(min. 100 characters)', THEME_TEXT_DOMAIN); ?></span>
									</label>
									<textarea name="new_project_description" minlength="100" required="required"></textarea>
								</div>
								<div class="row visible">
									<div class="cell x6">
										<div class="input-block" data-tip="Write a color name.">
											<label><?php _e('Color *', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="new_project_color" required="required" >
										</div>
									</div>
									<div class="cell x3">
										<div class="input-block" data-tip="Select expiring date.">
											<label><?php _e('Project Expire date *', THEME_TEXT_DOMAIN); ?></label>
											<div class="floating-objects">
												<a href="#" class="button grey small left" id="rfq-expire-date" data-date-format="dd.mm.yyyy" data-date="<?php echo date('d.m.Y'); ?>"><?php _e('Calendar', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></a>
												<input type="hidden" id="date_expire" name="new_project_expire_date" value="<?php echo date('d.n.Y'); ?>">
												<div id="expire-date-input" class="text-input"><?php echo date('d.n.Y'); ?></div>
											</div>
										</div>
									</div>
									<div class="cell x3">
										<div class="input-block" data-tip="Select deadline.">										
											<label><?php _e('Project delivery deadline *', THEME_TEXT_DOMAIN); ?></label>
											<a href="#" class="button grey small left" id="rfq-delivery-deadline" data-date-format="dd.mm.yyyy" data-date="<?php echo date('d.m.Y'); ?>"><?php _e('Calendar', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
											<input type="hidden" id="date_deadline" name="new_project_deadline_date" value="<?php echo date('d.n.Y'); ?>">
											<div id="delivery-deadline-input" class="text-input"><?php echo date('d.n.Y'); ?></div>
										</div>
									</div>
								</div>
								<div class="row visible">
									<div class="cell x6">
										<div class="input-block" data-tip="Write quantity.">										
											<label><?php _e('Quantity *', THEME_TEXT_DOMAIN); ?></label>
											<input type="number" min="1" max="1000000" name="new_project_quantity"  required="required"  >
										</div>
									</div>
									<div class="cell x6">
										<div class="input-block" data-tip="Write anual quantity.">										
											<label><?php _e('Annual quantity (optional)', THEME_TEXT_DOMAIN); ?></label>
											<input type="number"  min="1" max="1000000" name="new_project_anual_quantity"  >
										</div>
									</div>
								</div>
								<div class="row visible">
									<div class="cell x6">
										<div class="input-block" data-tip="Select country.">
											<label><?php _e('Country *', THEME_TEXT_DOMAIN); ?></label>
											<div class="select-block largeselect">
												<select name="new_project_country" class="styled" required="required">
												  	<?php /*<option value="0" selected="selected" disabled="disabled"> </option>*/?>
												  	<?php get_template_part( 'parts/list', 'countries'); ?> 
												</select>
											</div>
										</div>
									</div>
									<div class="cell x6">
										<div class="input-block" data-tip="Set target price.">										
											<label><?php _e('Target price (optional)', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="new_project_target_price" >
										</div>
									</div>
								</div>
								<div class="input-block file-upload-wrapper" data-tip="Select files.">
									<label><?php _e('Upload files (max. 10 MB)', THEME_TEXT_DOMAIN); ?>
										<span class="smalltext"><?php _e('Accept file types .doc/.docx, .xls/.xlsx, .ppt/.pptx, .pdf, .png/.jpg/.jpeg/.gif', THEME_TEXT_DOMAIN); ?></span>
									</label>
									<div class="file-btn-holder">
										<div class="fileUpload button">
										    <span><?php _e('Upload', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></span>
										    <input id="uploadBtn" type="file" name="new_project_files[]" multiple="multiple" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,  application/pdf, image/*" class="upload" />
										</div>
										<?php /*<input id="uploadFile" placeholder="Choose File" disabled="disabled" name="new_project_files" />*/?>
										<span id="flname" class="largetext"><?php _e('Choose File', THEME_TEXT_DOMAIN); ?></span>
									</div>								
								</div>

								<div class="input-block" data-tip="Check NDA to be safe.">
									<div class="checkbox-input">
										<label><input type="checkbox" name="new_project_protect"> <?php _e('Protect your ideas and designs with NDA', THEME_TEXT_DOMAIN); ?></label>
									</div>
								</div>

								<?php if( ( !is_user_logged_in() && isset($_SESSION['create-project-hp']) && $_SESSION['create-project-hp'] == true ) ) { ?>
									<input type="hidden" value="<?php echo wp_create_nonce('submit-addprojectcustomer-notregister'); ?>" name="submit-addprojectcustomer-notregister">
								<?php } else { ?>
									<input type="hidden" value="<?php echo wp_create_nonce('submit-addprojectcustomer'); ?>" name="submit-addprojectcustomer">
								<?php } ?>
								<input type="hidden" value="addproject_customer" name="action">
								<div class="responsecheck_addproject_customer"></div>
								<div class="submitarea smalltop smallbutton">
									<input type="submit" class="button" value="<?php _e('Create Project', THEME_TEXT_DOMAIN); ?>"> 
									<i class="fa fa-angle-right"></i>
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