<?php /*Template Name: Contractor - Dashboard - Settings*/
get_header(); 
$this_page_id = get_the_ID(); ?>
<?php if ( is_user_logged_in() && check_role_current_user('contractor' ) == true) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
		$user_ID = $user->ID;
		$company_id = get_field('associated_company', 'user_'.$user_ID);

		//var_dump($_SESSION['notification'] );


	 ?>
	<?php if(!empty($company_id)){ ?>
		<div class="dashboard-page">
			<?php if(isset($_SESSION['notification'])){ ?>
				<?php $notification = get_field('notification_old_contractor_update_the_account', 'options'); ?>
				<?php if(!empty($notification)){ ?>
					<div class="greenarea">
						<div class="container">
							<?php echo $notification; ?>
						</div>
					</div>	
				<?php } ?>
			<?php } ?>
			<div class="main-content">
				<div class="container">
					<h1><?php _e('Settings', THEME_TEXT_DOMAIN); ?></h1>
					<div class="sep"></div>
					<div class="contractor-registration-form">

						<div class="wrappload">
							<div class="loader largeloader"> 
								<img src="img/loading.gif" alt="">
							</div>
							<div class="contentloader"> 
								<form action="" class="update_contractor_settings_form" name="update_contractor_settings_form"  method="POST" enctype="multipart/form-data" >
									<div class="row tab-items" >
										<div class="cell x3 column_menu">
											
											<div class="wrapptables">
												<ul class="tabs">
													<li class="tab-link current" data-tab="tab-1"><?php echo get_field('main_info', $this_page_id); ?></li>
													<li class="tab-link" data-tab="tab-2"><?php echo get_field('company_info', $this_page_id); ?></li>
													<li class="tab-link" data-tab="tab-3"><?php echo get_field('logo', $this_page_id); ?></li>
													<li class="tab-link" data-tab="tab-4"><?php echo get_field('portfolio', $this_page_id); ?></li>
													<li class="tab-link" data-tab="tab-5"><?php echo get_field('password_settings', $this_page_id); ?></li>
													<li class="tab-link" data-tab="tab-6"><?php echo get_field('notification_settings', $this_page_id); ?></li>
												</ul>
											</div>
											<div class="wrappbuttons">
												<a href="<?php echo get_permalink($company_id); ?>" title="" target="_blank" class="button green  button-view"><?php _e('VIEW PROFILE', THEME_TEXT_DOMAIN); ?></a>
												<div class="row  centered-content">
													<div class="cell x12 no-float">
														<input type="hidden" name="user_type" value="<?php echo get_user_register_type(); ?>">
														<input type="hidden" value="<?php echo wp_create_nonce('submit-form'); ?>" name="submit-form">
														<input type="hidden" value="user_update_settings_contractor" name="action">
														
														<div class="submitarea smalltop">
															<input type="submit" class="button" value="<?php _e('Save Changes', THEME_TEXT_DOMAIN); ?>"> 
															<i class="fa fa-angle-right"></i>
														</div>
														<?php /*<button type="submit" class="button">Save Changes<i class="fa fa-angle-right"></i></button>*/?>
													</div>
												</div>
												<div class="cell x12 answform"><div class="responsecheck_update_settings_contractor"></div></div>
											</div>

										</div>
										<div class="cell x9 column_fields">
											
											<div id="tab-1" class="tab-content current">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('main_info', $this_page_id); ?></h3>
												</div>

												<div class="wrappformfields">
													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('First Name *', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="first_name" value="<?php echo $user->user_firstname; ?>" required="required">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('Last Name *', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="last_name" value="<?php echo $user->user_lastname;?>" required="required">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('Email *', THEME_TEXT_DOMAIN); ?></label>
															<input type="email" name="email" value="<?php echo $user->user_email; ?>" required="required">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your phone number.">
															<label><?php _e('Phone Number', THEME_TEXT_DOMAIN); ?></label>
															<input type="tel" name="contact_person_phone_number" value="<?php $phone_number = get_field('phone_number', $company_id); if(!empty($phone_number)){ echo $phone_number; } ?>">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your webpage.">
															<label><?php _e('Webpage', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="website" value="<?php $webpage = get_field('webpage', $company_id); if(!empty($webpage)){ echo $webpage; } ?>">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your city.">
															<label><?php _e('City *', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="city" value="<?php $city = get_field('city', $company_id); if(!empty($city)){ echo $city; } ?>" required="required">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your country.">
															<label><?php _e('Country *', THEME_TEXT_DOMAIN); ?></label>
															<div class="select-block largeselect">
																<select name="country" class="styled" required="required">
																  	<?php /*<option value="0" selected="selected" disabled="disabled"> </option>*/?>
																  	<?php $cnt = get_field('country', $company_id); ?>
																  	<?php if(!empty($cnt)){ ?>
																  		<?php echo get_selected_country($cnt); ?>
																  	<?php } else{ ?>
																  		<?php get_template_part( 'parts/list', 'countries'); ?> 
																  	<?php } ?>
																</select>
															</div>
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your workshop address.">
															<label><?php _e('Workshop Address', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="workshop_address" value="<?php $workshop_address = get_field('workshop_address', $company_id); if(!empty($workshop_address)){ echo $workshop_address; } ?>">
														</div>
													</div>

													<div class="cell x12">
														<div class="input-block" data-tip="Please write your list of machines.">
															<label><?php _e('List of machines', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="list_of_machines" value="<?php $list_of_machines = get_field('list_of_machines', $company_id ); if(!empty( $list_of_machines)) { echo  $list_of_machines; }?>">
														</div>
													</div>

													<?php  
														$terms_list = get_the_terms( $company_id, 'service' );
														$draught_links = array(); 
														$draught_links2 = array();
														if ( $terms_list && ! is_wp_error( $terms_list ) ) {
															foreach ( $terms_list as $term ) {
															    $draught_links[] = $term->term_id;
															    $draught_links2[]["id"] = $term->term_id;
															}
														}  ?>
														<div class="cell x6">
															<div class="input-block">	
																<div class="input-block-wrapper">
																	<label><?php _e('Services *', THEME_TEXT_DOMAIN); ?></label>
																	<?php  $terms = get_terms( 
																		array(
																		    'taxonomy' => 'service',
																		    'hide_empty' => false,
																		) 
																	); 
																	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>
																	<div class="input-block" data-tip="Please select one or more services.">
																		<i class="fa fa-check-circle"></i>
																		<div class="select-block">
																			<select name="service[]" multiple="multiple" placeholder="Please select one or more services." class="SlectBox" required="required">
																				<?php foreach ( $terms as $term ) { $count_positions++; ?>
																				    <option value="<?php echo $term->term_id; ?>" <?php if(in_array($term->term_id, $draught_links)){ 
																				    	echo "selected='selected'"; 
																				    	$key = array_search($term->term_id, $draught_links);
																				    	$draught_links2[$key]['index'] = $count_positions;
																				    	$draught_links2[$key]['name'] = $term->name;	
																				    } ?>><?php echo $term->name; ?></option>
																				<?php } ?>
																			</select>
																		</div>
																	</div>
																	<div class="wrappselectitems"><?php //var_dump($draught_links2); ?>
																		<?php foreach($draught_links2 as $item2){ ?>
																			<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
																				<span><?php echo $item2['name']; ?></span>
																				<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
																			</div> 
																		<?php } ?>

																	</div><?php // do not remove this div ?>
																	<?php } ?>
																</div>
															</div>
														</div>

													<?php  
														$terms_list = get_the_terms( $company_id, 'industry' );
														$draught_links = array(); 
														$draught_links2 = array();
														if ( $terms_list && ! is_wp_error( $terms_list ) ) {
															foreach ( $terms_list as $term ) {
															    $draught_links[] = $term->term_id;
															    $draught_links2[]["id"] = $term->term_id;
															}
														}  ?>
														<div class="cell x6">
															<div class="input-block">
																<div class="input-block-wrapper">
																	<label><?php _e('Material *', THEME_TEXT_DOMAIN); ?></label>
																	<?php  $terms = get_terms( 
																		array(
																		    'taxonomy' => 'industry',
																		    'hide_empty' => false,
																		) 
																	); 
																	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>
																	<div class="input-block" data-tip="Please select one or more materials.">
																		<i class="fa fa-check-circle"></i> 
																		<div class="select-block">
																			<select name="industry[]" multiple="multiple" placeholder="Please select one or more materials." class="SlectBox" required="required">
																				<?php foreach ( $terms as $term ) { $count_positions++; ?>
																				    <option value="<?php echo $term->term_id; ?>" <?php if(in_array($term->term_id, $draught_links)){ 
																				    	echo "selected='selected'"; 
																				    	$key = array_search($term->term_id, $draught_links);
																				    	$draught_links2[$key]['index'] = $count_positions;
																				    	$draught_links2[$key]['name'] = $term->name;	
																				    } ?>><?php echo $term->name; ?></option>
																				<?php } ?>
																			</select>
																		</div>
																	</div>
																	<div class="wrappselectitems"><?php //var_dump($draught_links2); ?>
																		<?php foreach($draught_links2 as $item2){ ?>
																			<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
																				<span><?php echo $item2['name']; ?></span>
																				<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
																			</div> 
																		<?php } ?>

																	</div><?php // do not remove this div ?>
																	<?php } ?>
																</div>
															</div>
														</div>

														<div class="cell x12">
															<div class="input-block" data-tip="Please write additional info.">
																<label><?php _e('Additional information of you and your company. *', THEME_TEXT_DOMAIN); ?></label>
																<p class="smalltext"><?php _e('Between 100-100000 characters', THEME_TEXT_DOMAIN); ?></p>
																<textarea maxlength="100000" minlength="100" name="additional_text" required="required"><?php $additional_text = get_field('additional_text', $company_id); if(!empty($additional_text)){ echo strip_tags($additional_text); } ?></textarea>
															</div>
														</div>
												</div>
											</div>

											
											<div id="tab-2" class="tab-content">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('company_info', $this_page_id); ?></h3>
												</div>
												<div class="wrappformfields">
													<div class="cell x6">
														<div class="input-block">
															<label><?php _e('Company Name', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="company_name" value="<?php $company_name = get_field('company_name', $company_id);  if(!empty($company_name)) { echo $company_name; } ?>" >
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write your VAT number.">
															<label><?php _e('VAT Number', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="vat_number" value="<?php $vat_number = get_field('vat_number', $company_id); if(!empty($vat_number)) { echo $vat_number; } ?>">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write you company registration number.">
															<label><?php _e('Company Registration Number', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="company_registration_number" value="<?php $crnr = get_field('company_registration_number', $company_id); if(!empty($crnr)){ echo $crnr; } ?>">
														</div>
													</div>
													
													<div class="cell x6">
														<div class="input-block" data-tip="Please write where are you established in.">
															<label><?php _e('Established In', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="established_in" value="<?php $ei = get_field('established_in', $company_id); if(!empty($ei)){ echo $ei; } ?>">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write the company address.">
															<label><?php _e('Company Address', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="company_address" value="<?php $ca = get_field('company_address', $company_id); if(!empty($ca)){ echo $ca; } ?>">
														</div>
													</div>

													<div class="cell x6">
														<div class="input-block" data-tip="Please write the company phone number.">
															<label><?php _e('Company Phone Number', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="company_phone_number" value="<?php $canr = get_field('company_phone_number', $company_id); if(!empty($canr)){ echo $canr; } ?>">
														</div>
													</div>

													<div class="cell x12">
														<div class="input-block" data-tip="Please write the company list of machines.">
															<label><?php _e('Company List of Machines', THEME_TEXT_DOMAIN); ?></label>
															<input type="text" name="company_list_of_machines" value="<?php $canrm = get_field('company_list_of_machines', $company_id); if(!empty($canrm)){ echo $canrm; } ?>">
														</div>
													</div>

													<div class="cell x12 ">
														<div class="input-block">
															<label><?php _e('Certificates', THEME_TEXT_DOMAIN); ?></label>
															<div class="input-block portofolio-item" data-tip="Please write some of your certificates.">
																<div class="wrapp_new_items placehere" data-item="certificates">
																	<div class="row small-gutter">
																		<div class="cell x12">
																			<input type="text" name="certificates[][name]" placeholder="Certificate Name *">
																		</div>
																		
																	</div>
																</div>
															
																<div class="input-block mtop20">
																	<div class="row">
																		<div class="cell x4">
																			<a href="#" title="" class="button additems" data-item="certificates"><?php _e('Add more', THEME_TEXT_DOMAIN); ?></a>
																		</div>
																	</div>
																</div>

																<?php $box = get_field('certificates', $company_id); ?>
																<?php	if ($box){ $count_items = -1; ?>
																	<div class="areacertificates">
																	<?php foreach ($box as $box1) { $count_items++; ?>
																		<?php $certf_name = $box1['certificate_name'];?>	
																		<?php if(!empty($certf_name) ){ ?>	
																			<div class="singlecertficate" data-item="<?php echo $count_items; ?>">
																				<a href="#" title="" class="removebutton removecertificate"  data-item="<?php echo $count_items; ?>">
																					<i class="fa fa-times-circle-o" aria-hidden="true"></i>
																				</a>
																				<div class="certificate_details">
																					<p><i class="fa fa-file-text-o" aria-hidden="true"></i><?php echo $certf_name; ?></p>
																				</div>
																				<input type="hidden" name="contractor_old_certificates[<?php echo $count_items; ?>][name]" value="<?php echo $certf_name; ?>">
																			</div>
																			
																		<?php } ?>
																	<?php } ?>
																	</div>
																<?php } ?>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div id="tab-3" class="tab-content">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('logo', $this_page_id);  ?></h3>
												</div>
												<div class="wrappformfields">
													<div class="cell x6">
														<div class="input-block portofolio-item" data-tip="Please upload some of your previous projects.">
															<label><?php _e('Upload logo or profile picture', THEME_TEXT_DOMAIN); ?></label>
															<p class="smalltext"><?php _e('Accept file types: .png/.jpg/.jpeg/.gif; Max. size: 5MB', THEME_TEXT_DOMAIN); ?></p>
															<div class="row small-gutter">
																<div class="cell x8">
																	<?php /*<input id="uploadFile" disabled="disabled" type="text" name="" placeholder="Choose file">*/?>
																</div>
																<div class="cell x4">
																	<div class="file-btn-holder">
																		<div class="fileUpload button">
																		    <span><?php _e('Upload', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></span>
																		    <input id="uploadBtn3" name="logo" type="file" class="upload" accept="image/*" />
																		</div>
																		<?php /* <input id="uploadFile" placeholder="Choose File" disabled="disabled" /> */?>
																	</div>
																</div>
																<div class="cell x12">
																	<span class="filesname"></span> 														
																</div>
															</div>
														</div>
													</div>	
													<?php $logo = get_field('logo', $company_id); ?>
													<?php if(!empty($logo)){ ?>
														<div class="cell x6 toright"> 
															<div class="uploaded-logo" data-item="1">
																<a href="#" title="" class="removebutton removegall" data-item="1">
																	<i class="fa fa-times-circle-o" aria-hidden="true"></i>
																</a>
																<div class="logoarea">
																	<img src="<?php echo $logo['sizes']['logo_company']; ?>" alt="">
																	<input type="hidden" name="contractor_logo" value="<?php echo $logo['ID']; ?>">
																</div>
															</div>
														</div>
													<?php }else{ ?>
														<div class="cell x6 "><div class="nlg"><p><?php _e('NO LOGO AVAILABLE', THEME_TEXT_DOMAIN); ?></p></div></div>

													<?php } ?>

												</div>
											</div>

											<div id="tab-4" class="tab-content">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('portfolio', $this_page_id);  ?></h3>
												</div>
												<div class="wrappformfields">
													<div class="cell x12">
														<div class="input-block visible">
															<div class="input-block portofolio-item" data-tip="Please upload max. 3 images for your product (with description).">
																<label><?php _e('Portofolio with previous products', THEME_TEXT_DOMAIN); ?></label>
																<p class="smalltext"><?php _e('Accept file types: .png/.jpg/.jpeg/.gif; Max. size: 20MB / gallery', THEME_TEXT_DOMAIN); ?></p>
																<div class="wrapp_new_items placehere" data-item="portofolio">
																	<div class="row small-gutter" >
																		<div class="cell x9 portfdesc">
																			<input type="text" name="portofolio[][name]" placeholder="Add product description *">
																		</div>
																		<div class="cell x3 portfbutton">
																			<div class="file-btn-holder">
																				<div class="fileUpload button">
																				    <span><?php _e('Upload', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></span>
																				    <input  type="file" class="upload" name="portofolio[][images0][]" multiple="multiple" accept="image/*"  />
																				</div>
																				
																			</div>
																		</div>
																		<div class="cell x12">
																			<span class="filesname"></span> <?php // for add files name ?>
																		</div>
																	</div>
																</div>

																<div class="input-block "> <?php // mtop20 ?>
																	<div class="row">
																		<div class="cell x4">
																			<a href="#" title="" class="button additems" data-item="portofolio"><?php _e('Add more', THEME_TEXT_DOMAIN); ?></a>
																		</div>
																	</div>
																</div>

																<?php $box = get_field('portofolio_with_previous_products', $company_id); ?>
																<?php	if ($box){ ?>
																	<div class="uploaded-products">
																		<?php $count_item = -1; ?>
																		<?php foreach ($box as $box1) {  $count_item++; ?>					
																			<?php $box2 = $box1['images'];?>
																			<?php if (!empty($box2)){ ?>
																				<div class="individualgall" data-item="<?php echo $count_item; ?>">
																					<a href="#" title="" class="removebutton removegall" data-item="<?php echo $count_item; ?>">
																						<i class="fa fa-times-circle-o" aria-hidden="true"></i>
																					</a>
																					<div class="wrapps">
																						<div class="row small-gutter">
																						<?php $cnt = -1; ?>
																						<?php foreach ($box2 as $box3) { $cnt++;  ?>	
																							<?php $file = $box3['file']; ?>
																							<?php if(!empty($file)){ ?>
																							<div class="cell x3">
																								<img src="<?php echo $file['sizes']['smallimg']; ?>" alt="">
																								<input type="hidden" name="contractor_old_portofolio[<?php echo $count_item; ?>][id<?php echo $count_item; ?>][<?php echo $cnt; ?>]" value="<?php echo $file['ID']; ?>">
																							</div>
																							<?php } ?>
																						<?php } ?>
																						<?php $description = $box1['description']; ?>
																						</div>
																						<?php if(!empty($description)){ ?>
																							<p class="vsmall"><?php echo $description; ?></p>
																							<input type="hidden" name="contractor_old_portofolio[<?php echo $count_item; ?>][name]" value="<?php echo $description; ?>">
																						<?php } ?>
																					</div>

																				</div>
																			<?php } ?>
																		<?php } ?>
																	</div> 
																<?php } ?>

															</div>
														</div>
													</div>	
												</div>
											</div>

											<div id="tab-5" class="tab-content">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('password_settings', $this_page_id);  ?></h3>
												</div>
												<div class="wrappformfields">
													<?php if(get_user_register_type() == 3){ // for users that have this ?>
														<div class="cell x12">
															<div class="input-block">
																<label><?php _e('Password *', THEME_TEXT_DOMAIN); ?></label>
																<input type="password" name="contractor_password" required="required">
															</div>
														</div>
														
														<div class="cell x4">
															<div class="input-block">
																<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
																<input type="password" name="contractor_new_password">
															</div>
														</div>

														<div class="cell x4">
															<div class="input-block">
																<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
																<input type="password" name="contractor_new_password2">
															</div>
														</div>
													<?php } else { ?>
														<div class="cell x6">
															<div class="input-block">
																<label><?php _e('New Password', THEME_TEXT_DOMAIN); ?></label>
																<input type="password" name="contractor_new_password">
															</div>
														</div>

														<div class="cell x6">
															<div class="input-block">
																<label><?php _e('Confirm Password', THEME_TEXT_DOMAIN); ?></label>
																<input type="password" name="contractor_new_password2">
															</div>
														</div>
													<?php } ?>
												</div>
											</div>

											<div id="tab-6" class="tab-content">
												<div class="cell x12 input-block title-form-section">
													<h3><?php echo get_field('notification_settings', $this_page_id);  ?></h3>
												</div>
												<div class="wrappformfields">
													<div class="cell x12">
														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_procurement" <?php $notify_new_procurement = get_field('notify_new_procurement', 'user_'.$user_ID ); if($notify_new_procurement==true){  echo "checked='checked'"; } // $user_ID ?> > <?php echo get_field('label_for_new_procurement', 'options'); ?></label>
															</div>
														</div>

														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_message" <?php $notify_new_message = get_field('notify_new_message', 'user_'.$user_ID ); if($notify_new_message==true){  echo "checked='checked'"; }  ?> > <?php echo get_field('label_for_new_message', 'options'); ?></label>
															</div>
														</div>		

														<div class="input-block" >
															<div class="checkbox-input">
																<label><input type="checkbox" name="user_new_hired" <?php $notify_get_hired = get_field('notify_get_hired', 'user_'.$user_ID ); if($notify_get_hired==true){ echo "checked='checked'"; }  ?>> <?php echo get_field('label_for_get_hired_for_a_project', 'options'); ?></label>
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

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="hidesection duplicatearea">
			
			<div class="duplicate" data-item="portofolio">
				<div class="row small-gutter" >
					<a href="#" title="" class="removenewadd removenewportfolio" data-item="portofolio"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
					<div class="cell x9 portfdesc marginitem">
						<input type="text" name="portofolio[][name]" placeholder="Add product description *">
					</div>
					<div class="cell x3 portfbutton">
						<div class="file-btn-holder">
							<div class="fileUpload button">
							    <span>Upload <i class="fa fa-angle-right"></i></span>
							    <input  type="file" class="upload" name="portofolio[][images][]" multiple="multiple" accept="image/*"  />
							</div> 
							
						</div>
					</div>
					<div class="cell x12">
						<span class="filesname"></span> <?php // for add files name ?>
					</div>
				</div>
			</div>	

			<div class="duplicate" data-item="certificates">
				<div class="row small-gutter">
					<a href="#" title="" class="removenewadd removenewcertificates" data-item="certificates"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
					<div class="cell x12">
						<input type="text" name="certificates[][name]" placeholder="Certificate Name *">
					</div>
					
				</div>
			</div>
					
		</div>
	<?php }else{ ?>
		<?php $something_went_wrong = get_field('something_went_wrong','options'); ?>
		<?php if(!empty($something_went_wrong)){ ?>
			<div class="default-page">
				<div class="main-content">
					<div class="container">
						<div class="content-wrapper">
							<div class="full-content">
								<?php echo $something_went_wrong; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
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
<?php unset($_SESSION['notification']); ?>
<?php get_footer();