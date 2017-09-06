<?php get_header(); ?>
<?php // $project_id = get_the_ID(); ?>
<?php 
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
 	$project_id = get_the_ID();
 	
 	unset($_SESSION['new_email_from']);
 	$contractor_user = get_field('associated_user', $project_id);
 	//$contractor_user_email = $contractor_user['user_email'];
 	$_SESSION['new_email_from'] = $contractor_user['ID'];

 	//var_dump($_SESSION['new_email_from']);
 	//var_dump($contractor_user_id);
?>
<?php if ( ! is_user_logged_in() || ( is_user_logged_in() && ( check_role_current_user('customer' ) == true   || check_role_current_user('administrator' ) == true  //|| 
	) ) || ( is_user_logged_in() && check_role_current_user('contractor' ) == true && check_associated_user($project_id, $user_ID) == true ) ) { // user logged and with customer role ?>
	<div class="company-profile-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<?php if ( !is_user_logged_in()  ) {
						$not_logged = true;
					} ?>

					<div <?php if($not_logged == true ){ echo 'class="content left"'; } else { echo 'class="full-content"'; } // || ($not_logged == false && check_role_current_user('customer' ) == true )  ?> >
						<div class="white-box margintitle">
							<?php $company_name = get_field('company_name', $project_id); ?>
							<?php if(!empty($company_name)){ ?>
								<h1><?php echo $company_name; ?></h1>
							<?php }else{ ?>
								<h1><?php echo get_the_title(); ?></h1>
							<?php } ?>
							
							<?php if(is_user_logged_in()){  ?>
								<?php $company_id = get_field('associated_company', 'user_'.$user_ID); ?>
								<?php if($project_id == $company_id &&  check_role_current_user('contractor' ) == true && check_associated_user($project_id, $user_ID) == true  ){ // if you are this company ?>
									<div class="absview profileedit">
										<a href="<?php echo get_permalink(get_field('contractor_settings_page', 'options')->ID) ; ?>" title="" class="button button-view"><?php _e('Edit Profile', THEME_TEXT_DOMAIN); ?> <i class="fa fa-pencil" aria-hidden="true"></i></a>
									</div>
								<?php }else if(  check_role_current_user('customer' ) == true && check_associated_user($project_id, $user_ID) == false  ){  ?>
									<?php 	
											//$_SESSION['new_email_from'] = ;
									 ?>
									<div class="absview profileedit">
										<a href="<?php echo get_permalink(get_field('customer_create_new_messages', 'options')->ID) ; ?>" title="" class="button button-view"><?php _e('Contact Contractor', THEME_TEXT_DOMAIN); ?> <i class="fa fa-envelope" aria-hidden="true"></i></a>
									</div>
								<?php } ?>
							<?php } ?>


							<div class="products-listing">
								<div class="product-line">
									<div class="image">
										<?php $logo = get_field('logo', $project_id); ?>
										<?php if(!empty($logo)){ ?>
											<img src="<?php echo $logo['sizes']['logo_company']; ?>" alt="">
										<?php } else { ?>
											<img src="img/no-logo-company.jpg" alt="">
										<?php } ?>
									</div>
									<?php $additional_text = get_field('additional_text', $project_id); ?>
									<?php if(!empty($additional_text)){ ?>
										<div class="text-content">
											<?php echo $additional_text; ?>
										</div>
									<?php } ?>
								</div>
							</div>

							<div class="row small-gutter">
								<?php if($not_logged != true){?>
									<div class="cell x6">
										<h2><?php _e('Additional Information', THEME_TEXT_DOMAIN); ?></h2>
										<div class="company-details-wrapper">
											<?php 
											$terms = get_the_terms( get_the_ID(), 'industry' );
											if ( $terms && ! is_wp_error( $terms ) ) : 
											    $draught_links = array();
											    foreach ( $terms as $term ) {
											        $draught_links[] = $term->name;
											    }
											    $on_draught = join( ", ", $draught_links );  ?>
											 	<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Materials:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo esc_html( $on_draught ); ?></div>
												</div>
											<?php endif; ?>

											<?php 
											$terms = get_the_terms( get_the_ID(), 'service' );
											if ( $terms && ! is_wp_error( $terms ) ) : 
											    $draught_links = array();
											    foreach ( $terms as $term ) {
											        $draught_links[] = $term->name;
											    }
											    $on_draught = join( ", ", $draught_links );  ?>
											 	<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Services:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo esc_html( $on_draught ); ?></div>
												</div>
											<?php endif; ?>

											<?php /*$skills_and_know = get_field('skills_and_know­how', $project_id); ?>
											<?php if(!empty($skills_and_know)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Skills and Know­How', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $skills_and_know; ?></div>
												</div>
											<?php } */ ?>

											<?php $company_address = get_field('company_address', $project_id); ?>
											<?php if(!empty($company_address)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Company Address:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $company_address; ?></div>
												</div>
											<?php } ?>


											<?php $workshop_address = get_field('workshop_address', $project_id); ?>
											<?php if(!empty($workshop_address)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Workshop Address:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $workshop_address; ?></div>
												</div>
											<?php } ?>
											<?php $machinery_pack = get_field('list_of_machines', $project_id); ?>
											<?php if(!empty($machinery_pack)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('List of machines:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $machinery_pack; ?></div>
												</div>
											<?php } ?>


											<?php $company_list_of_machines = get_field('company_list_of_machines', $project_id); ?>
											<?php if(!empty($company_list_of_machines)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Company list of machines:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $company_list_of_machines; ?></div>
												</div>
											<?php } ?>

											<?php $vat_number = get_field('vat_number', $project_id); ?>
											<?php if(!empty($vat_number) && $not_logged != true ){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('VAT Number:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $vat_number; ?></div>
												</div>
											<?php } ?>

											<?php $company_registration_number = get_field('company_registration_number', $project_id); ?>
											<?php if(!empty($company_registration_number) && $not_logged != true ){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Company Registration Number:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $company_registration_number; ?></div>
												</div>
											<?php } ?>

											<?php $established_in = get_field('established_in', $project_id); ?>
											<?php if(!empty($established_in)){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Established In:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $established_in; ?></div>
												</div>
											<?php } ?>

											<?php /*$no_of_employees = get_field('no_of_employees', $project_id); ?>
											<?php if(!empty($no_of_employees) && $not_logged != true ){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('No. of Employees:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8"><?php echo $no_of_employees; ?></div>
												</div>
											<?php } */ ?>

											<?php $box = get_field('certificates', $project_id); ?>
											<?php	if (!empty($box ) && sizeof($box) > 0){ ?>
												<div class="row small-gutter">
													<div class="cell x4"><b><?php _e('Companies Certificates:', THEME_TEXT_DOMAIN); ?></b></div>
													<div class="cell x8">
														<ul class="simplelist">
														<?php foreach ($box as $box1) {  ?>		
															<?php $certificate_name = $box1['certificate_name']; ?>
															<?php //var_dump($file); ?>	
															<?php if(!empty($certificate_name) ){ ?>		
																<li>
																	<i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo $certificate_name; ?>
																</li>
															<?php } ?>
														<?php } ?>
														</ul>
													</div>
												</div>
											<?php } ?>
											
										</div>
									</div>
								<?php } ?>
								<div class="cell x6">
									<h2><?php _e('Contact', THEME_TEXT_DOMAIN); ?></h2>
									<div class="company-contact-details">
										

										<div class="row small-gutter">
											<div class="cell x5"><div class="icon"><i class="fa fa-user"></i></div><b><?php _e('Name:', THEME_TEXT_DOMAIN); ?></b></div>
											<div class="cell x7"><?php echo $contractor_user['user_firstname'].' ' .$contractor_user['user_lastname'] ; ?></div>
										</div>
										
										<?php $phone_number = get_field('phone_number', $project_id); ?>
										<?php if(!empty($phone_number)){ ?>
											<div class="row small-gutter">
												<div class="cell x5"><div class="icon"><i class="fa fa-phone"></i></div><b><?php _e('Phone:', THEME_TEXT_DOMAIN); ?></b></div>
												<div class="cell x7 simplelist" ><a href="tel:<?php echo phonenr($phone_number); ?>" title=""><?php echo $phone_number; ?></a></div>
											</div>
										<?php } ?>

										<?php $company_phone_number = get_field('company_phone_number', $project_id);?>
										<?php if(!empty($company_phone_number)){ ?>
											<div class="row small-gutter">
												<div class="cell x5"><div class="icon"><i class="fa fa-phone"></i></div><b><?php _e('Company Phone:', THEME_TEXT_DOMAIN); ?></b></div>
												<div class="cell x7 simplelist" ><a href="tel:<?php echo phonenr($company_phone_number); ?>" title=""><?php echo $company_phone_number; ?></a></div>
											</div>
										<?php } ?>

										<?php /* $email_office = get_field('email_office', $project_id); ?>
										<?php if(!empty($email_office)){ ?>
										<div class="row small-gutter">
											<div class="cell x4"><div class="icon"><i class="fa fa-envelope"></i></div><b><?php _e('Email:', THEME_TEXT_DOMAIN); ?></b></div>
											<div class="cell x8 simplelist"><a href="mailto:<?php echo $email_office; ?>" title=""><?php echo $email_office; ?></a></div>
										</div>
										<?php }*/ ?>

										<?php $webpage = get_field('webpage', $project_id); ?>
										<?php if(!empty($webpage)){ ?>
										<div class="row small-gutter">
											<div class="cell x5"><div class="icon"><i class="fa fa-link" aria-hidden="true"></i></div><b><?php _e('Website:', THEME_TEXT_DOMAIN); ?></b></div>
											<div class="cell x7 simplelist"><a href="<?php if(strpos($webpage, 'http://') == false || strpos($webpage, 'www.')){ echo 'http://'.$webpage; } else { echo $webpage; } ?>" title="" target="_blank"><?php if(strpos($webpage, 'http://') == false || strpos($webpage, 'www.')){ echo 'http://'.$webpage; } else { echo $webpage; }  ?></a></div>
										</div>
										<?php } ?>
										<?php $country = get_field('country', $project_id); ?>
										<?php if(!empty($country)){ ?>
										<div class="row small-gutter">
											<div class="cell x5"><div class="icon"><i class="fa fa-globe" aria-hidden="true"></i></div><b><?php _e('Country:', THEME_TEXT_DOMAIN); ?></b></div>
											<div class="cell x7"><?php echo $country; ?></div>
										</div>
										<?php } ?>

										<?php $city = get_field('city', $project_id); ?>
										<?php if(!empty($city)){ ?>
											<div class="row small-gutter">
												<div class="cell x5"><div class="icon"><i class="fa fa-map-marker"></i></div><b><?php _e('City:', THEME_TEXT_DOMAIN); ?></b></div>
												<div class="cell x7"><?php echo $city; ?></div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<?php $box = get_field('portofolio_with_previous_products', $project_id); ?>
						<?php	if ($box){ ?>
							<div class="white-box portofolio-carousel-wrapper">
								<h2><?php _e('Company Portofolio', THEME_TEXT_DOMAIN); ?></h2>
								<div class="<?php if(sizeof($box) > 1 ){ echo 'portofolio-carousel'; }else{ echo 'portofolio-carousel2 '; } ?>">
									<?php $count_item = 0; ?>
									<?php foreach ($box as $box1) {  $count_item++; ?>					
										<?php $box2 = $box1['images'];?>
										<?php if (!empty($box2)){ ?>
											<div class="portofolio-carousel-item">
												<?php foreach ($box2 as $box3) {  ?>	
													<?php $file = $box3['file']; ?>
													<?php if(!empty($file)){ ?>
														<div class="image">
															<a href="<?php echo $file['sizes']['large']; ?>" style="background-image: url(<?php echo $file['sizes']['portofolio']; ?>);" class="fancybox_item" rel="group-<?php echo $count_item;?>"></a>
														</div>
													<?php } ?>
												<?php } ?>
												<?php $description = $box1['description']; ?>
												<?php if(!empty($description)){ ?>
													<div class="text-content"><p><?php echo $description; ?></p></div>
												<?php } ?>
											</div>
										<?php } ?>
										

									<?php } ?>
								</div>
							</div>
						<?php } ?>
						
						<?php 
								$location = get_field('location_google_maps', $project_id);
								if( !empty($location) ):
							?>
							<div class="white-box company-map">
							<h2><?php _e('Company Location', THEME_TEXT_DOMAIN); ?></h2>
								<div class="acf-map">
									<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
								</div>
							</div>
						<?php endif; ?>
						<?php /*
						<div class="pagination">
							<a href="" title=""><i class="fa fa-caret-left"></i></a>
						</div>
						<?php */?>

						<?php if($not_logged == true ) {  // || ($not_logged == false && check_role_current_user('customer' ) == true )?>
						</div>
						<div class="sidebar-wrapper right">
							<?php include_once("parts/company-sidebar.php"); ?>
						</div>
						<?php }else { ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } if( is_user_logged_in() && check_role_current_user('contractor' ) == true && check_associated_user($project_id, $user_ID) == false ) { // customer and not the author  ?>
	<div class="default-page">
		<div class="main-content">
			<div class="container">
				<div class="content-wrapper">
					<div class="full-content">
						<?php $txt = get_field('contractor_need_to_switch_accounts', 'options'); ?>
						<?php if(!empty($txt)){ ?>
							<?php echo $txt; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } /*else  if ( !is_user_logged_in() ){ ?>
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
<?php } */ ?>
<?php get_footer();