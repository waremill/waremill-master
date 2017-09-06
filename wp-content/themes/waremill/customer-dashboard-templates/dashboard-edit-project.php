<?php /*Template Name: Customer - Dashboard - Edit Project*/
get_header(); ?>
<?php $project_id = 0; ?>
<?php $project_id = $_GET['id'];?>
<?php if ( is_user_logged_in() && check_role_current_user('customer' ) == true ) { // user logged and with customer role ?>
	<?php $user = wp_get_current_user(); 
	 	$user_ID = $user->ID;
 	?>
 	<?php // check post with user author ?>
 	<?php $checkproject = check_project_autor($project_id, $user_ID); ?>
 	<?php if( isset($project_id) && $checkproject == true ){ ?>
		<?php $homepage = get_option('homepage', 'options')->ID; ?>

		<?php // check if project is not expired => you will not be able to edit this post ?>
		<?php 

		$date 		= get_field('project_expire_date_project', $project_id);
		$date 		= new DateTime($date);
		$intdate	= intval($date->format('Ymd'));
		$today 		= intval(date('Ymd')); 

		$archive = false; // active
		if($today >= $intdate){
			$archive = true; // archive
		} 
		?>

		<?php if($archive == false) { ?>
			<div class="dashboard-page">
				<div class="main-content">

					<div class="container">
						<h1><?php _e('Edit Project: ', THEME_TEXT_DOMAIN); ?><?php echo get_the_title($project_id); ?></h1>
						<div class="sep"></div>
						<div class="new-project-form">
							<div class="wrappload">
								<div class="loader">
									<img src="img/loading.gif" alt="">
								</div>
								<div class="contentloader">
									<form action="" id="customer_editproject_form" name="customer_editproject_form"  method="POST" enctype="multipart/form-data" >
										<div class="input-block" data-tip="Please enter a project name here.">
											<label><?php _e('Project Name *', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="new_project_customer"  required="required" value="<?php echo get_the_title($project_id); ?>">
										</div>
										<div class="row noowhidden3">
									<?php  
										$terms_list = get_the_terms( $project_id, 'industry' );
										$draught_links = array(); 
										$draught_links2 = array();
										if ( $terms_list && ! is_wp_error( $terms_list ) ) {
											foreach ( $terms_list as $term ) {
											    $draught_links[] = $term->term_id;
											    $draught_links2[]["id"] = $term->term_id;
											}
										}  ?>
										<?php  $terms = get_terms( 
												array(
												    'taxonomy' => 'industry',
												    'hide_empty' => false,
												) 
											); 
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1; ?>
											<div class="cell x6">
												<div class="input-block-wrapper">
													<label><?php _e('Material *', THEME_TEXT_DOMAIN); ?></label>
													<div class="input-block" data-tip="Select a material.">
														<i class="fa fa-check-circle"></i>
														<div class="select-block">
															<select name="new_project_industries[]" multiple="multiple" placeholder="Please select a material." class="SlectBox" required="required">
																<?php foreach ( $terms as $term ) { $count_positions++; ?>
																    <option value="<?php echo $term->term_id; ?>" <?php if(in_array($term->term_id, $draught_links)){ 
																    	echo "selected='selected'"; 
																    	$key = array_search($term->term_id, $draught_links);
																    	$draught_links2[$key]['index'] = $count_positions;
																    	$draught_links2[$key]['name'] = $term->name;	
																    } ?>>
																    	<?php echo $term->name; ?>
																    </option>
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
												</div>
											</div>
										<?php } ?>
									<?php /*======================================================================================*/?>		
									<?php  
										$terms_list = get_the_terms( $project_id, 'service' );
										$draught_links = array(); 
										$draught_links2 = array();
										if ( $terms_list && ! is_wp_error( $terms_list ) ) {
											foreach ( $terms_list as $term ) {
											    $draught_links[] = $term->term_id;
											    $draught_links2[]["id"] = $term->term_id;
											}
										}  ?>

											<?php  $terms = get_terms( 
													array(
													    'taxonomy' => 'service',
													    'hide_empty' => false,
													) 
												);
											if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ $count_positions = -1;?>	
												<div class="cell x6">
													<div class="input-block-wrapper">
														<label><?php _e('Service *', THEME_TEXT_DOMAIN); ?></label>
														<div class="input-block" data-tip="Select a service.">
															<i class="fa fa-check-circle"></i>
															<div class="select-block">
																<select name="new_project_services[]" multiple="multiple" placeholder="Please select a service." class="SlectBox" required="required">
																	<?php foreach ( $terms as $term ) { $count_positions++; ?>
																	 	<option value="<?php echo $term->term_id; ?>"  <?php if(in_array($term->term_id, $draught_links)){ 
																    	echo "selected='selected'"; 
																    	$key = array_search($term->term_id, $draught_links);
																    	$draught_links2[$key]['index'] = $count_positions;
																    	$draught_links2[$key]['name'] = $term->name;	
																    } ?>><?php echo $term->name; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="wrappselectitems">
															<?php foreach($draught_links2 as $item2){ ?>
																<div class="selected-option" data-item="<?php echo  $item2['index']; ?>">
																	<span><?php echo $item2['name']; ?></span>
																	<a href="#" title="" class="removesummo" data-item="<?php echo  $item2['index']; ?>"><i class="fa fa-times"></i></a>
																</div> 
															<?php } ?>
														</div><?php // do not remove this div ?>
													</div>
												</div>
											<?php } ?>
										</div>
										<?php /*
										<div class="input-block" data-tip="Select a material.">
											<label><?php _e('Material *', THEME_TEXT_DOMAIN); ?></label>
											<input type="text" name="new_project_material"  required="required" value="<?php $mt = get_field('material_project', $project_id); if(!empty($mt)) { echo $mt; }?>" >
										</div>
										<?php */ ?>
										<div class="input-block" data-tip="Write a description.">
											<label><?php _e('Description *', THEME_TEXT_DOMAIN); ?>
												<span class="smalltext"><?php _e('(min. 100 characters)', THEME_TEXT_DOMAIN); ?></span>
											</label>
											<textarea name="new_project_description" minlength="100" required="required"><?php 
												$content_post = get_post($project_id);
												$content = $content_post->post_content;
												$content = apply_filters('the_content', $content);
												$content = str_replace(']]>', ']]&gt;', $content);
												echo strip_tags($content);
											?></textarea>
										</div>
										<div class="row visible">
											<div class="cell x6">
												<div class="input-block" data-tip="Write a color name.">
													<label><?php _e('Color *', THEME_TEXT_DOMAIN); ?></label>
													<input type="text" name="new_project_color" required="required" value="<?php $cl = get_field('color_project', $project_id); if(!empty($cl)){ echo $cl; } ?>">
												</div>
											</div>
											<div class="cell x3">
												<div class="input-block" data-tip="Select expiring date.">
													<?php 
														$dexp = get_field('project_expire_date_project',  $project_id); 
														if(!empty($dexp)){
															$date = DateTime::createFromFormat('Ymd',  $dexp );
		                        							$date = $date->format('d.n.Y');
														}else{
															$date = date('d.n.Y');
														}
													?>		
													<label><?php _e('Project Expire date *', THEME_TEXT_DOMAIN); ?></label>
													<div class="floating-objects">
														<a href="#" class="button grey small left" id="rfq-expire-date" data-date-format="dd.mm.yyyy" data-date="<?php  echo $date;  ?>"><?php _e('Calendar', THEME_TEXT_DOMAIN); ?><i class="fa fa-angle-right"></i></a>
														<input type="hidden" id="date_expire" name="new_project_expire_date" value="<?php  echo $date;  ?>">
														<div id="expire-date-input" class="text-input"><?php  echo $date;  ?></div>
													</div>
												</div>
											</div>
											<div class="cell x3">
												<div class="input-block" data-tip="Select deadline.">	
													<?php 
														$dexp = get_field('project_delivey_deadline_project',  $project_id); 
														if(!empty($dexp)){
															$date = DateTime::createFromFormat('Ymd',  $dexp );
		                        							$date = $date->format('d.n.Y');
														}else{
															$date = date('d.n.Y');
														}
													?>									
													<label><?php _e('Project delivery deadline *', THEME_TEXT_DOMAIN); ?></label>
													<a href="#" class="button grey small left" id="rfq-delivery-deadline" data-date-format="dd.mm.yyyy" data-date="<?php echo $date; ?>"><?php _e('Calendar', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
													<input type="hidden" id="date_deadline" name="new_project_deadline_date" value="<?php echo $date; ?>">
													<div id="delivery-deadline-input" class="text-input"><?php echo $date; ?></div>
												</div>
											</div>
										</div>
										<div class="row visible">
											<div class="cell x6">
												<div class="input-block" data-tip="Write quantity.">										
													<label><?php _e('Quantity *', THEME_TEXT_DOMAIN); ?></label>
													<input type="number" min="1" max="1000000" name="new_project_quantity"  required="required" value="<?php $qnt = get_field('quantity_project', $project_id); if(!empty($qnt)){ echo $qnt; } ?>" >
												</div>
											</div>
											<div class="cell x6">
												<div class="input-block" data-tip="Write anual quantity.">										
													<label><?php _e('Annual quantity (optional)', THEME_TEXT_DOMAIN); ?></label>
													<input type="number"  min="1" max="1000000" name="new_project_anual_quantity" value="<?php $qnta = get_field('annual_quantity_project', $project_id); if(!empty($qnta)) { echo $qnta; } ?>" >
												</div>
											</div>
										</div>
										<div class="row visible">
											<div class="cell x6">
												<div class="input-block " data-tip="Select country.">
													<label><?php _e('Country *', THEME_TEXT_DOMAIN); ?></label>
													<div class="select-block largeselect">
														<select name="new_project_country" class="styled" required="required">
														  	<?php /*<option value="0" selected="selected" disabled="disabled"> </option>*/?>
														  	<?php $cnt = get_field('country_project', $project_id); ?>
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
												<div class="input-block" data-tip="Set target price.">										
													<label><?php _e('Target price (optional)', THEME_TEXT_DOMAIN); ?></label>
													<input type="text" name="new_project_target_price" value="<?php $tgp = get_field('target_price_project', $project_id); if(!empty($tgp)) { echo $tgp; } ?>">
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
										<?php $box = get_field('files_project', $project_id); ?>
										<?php	if ($box){ ?>
											<div class="input-block proj_files">
											<?php foreach ($box as $box1) {  ?>
												<?php $file_item = $box1['file']; ?>
												<?php $file_path = $box1['file_path'];?>					
												<?php if( !empty($file_path) && !empty($file_item) ) { ?>
													<div class="proj_file" data-item="<?php echo $file_item['ID'];?>">
														<i class="fa fa-file-text-o" aria-hidden="true"></i> 
														<div class="proj_file_name">
															<a href="<?php echo $file_path; ?>" title="<?php echo $file_item['filename']; ?>" target="_blank"><?php echo $file_item['filename']; ?></a>
															<input type="hidden" name="update_old_images[]" value="<?php echo $file_item['ID']; ?>">
														</div>
														<div class="proj_file_remove">
															<a href="#" title="" data-id="<?php echo $file_item['ID'];?>" class="removefile"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
														</div>
													</div>
												<?php } ?>	
											<?php } ?>
											</div>
										<?php } ?>							
										

										<div class="input-block" data-tip="Check NDA to be safe.">
											<div class="checkbox-input">
												<label><input type="checkbox" name="new_project_protect" <?php $protect = get_field('protect_project', $project_id);   if(!empty($protect) && $protect == true) { echo "checked='checked'"; } ?>> <?php _e('Protect your ideas and designs with NDA', THEME_TEXT_DOMAIN); ?></label>
											</div>
										</div>
										<input type="hidden" name="project" value="<?php echo $project_id ; ?>">
										<input type="hidden" value="<?php echo wp_create_nonce('submit-editprojectcustomer'); ?>" name="submit-editprojectcustomer">
										<input type="hidden" value="editproject_customer" name="action">
										<div class="responsecheck_editproject_customer"></div>
										<div class="submitarea smalltop smallbutton">
											<input type="submit" class="button" value="<?php _e('Update Project', THEME_TEXT_DOMAIN); ?>"> 
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
			<div class="default-page dashboard-page">
				<div class="main-content">
					<div class="container">
						<div class="content-wrapper ">
							<div class="full-content">
								<div class="bids-listing">
									<div class="no-bids">
										<i class="fa fa-frown-o" aria-hidden="true"></i>
										<h2><?php _e('This project has expired and  you can\'t edit it.', THEME_TEXT_DOMAIN); ?></h2>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div> 
			</div>	
		<?php } ?>


	<?php }else{ ?>
 		<div class="default-page">
			<div class="main-content">
				<div class="container">
					<div class="content-wrapper">
						<div class="full-content">
							<?php $txt = get_field('content_page_404', 'options'); ?>
							<?php if(!empty($txt)){ ?>
								<?php echo $txt; ?>
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