<?php
get_header(); ?>
<?php 
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
 	$project_id = get_the_ID();
 	
?>
<?php if ( is_user_logged_in() && ( check_role_current_user('contractor' ) == true   || check_role_current_user('administrator' ) == true || 
			(check_role_current_user('customer' ) == true && check_project_autor($project_id, $user_ID) == true ) ) ) { // user logged and with customer role ?>
	<?php 
	 	
 		$auth = get_post($project_id); // gets author from post 
		$authid = $auth->post_author; // gets author id for the post
	?>
	<div class="dashboard-page">
		<div class="main-content">
			<div class="container">
				<?php 
					$date 		= get_field('project_expire_date_project', $project_id);
					$date 		= new DateTime($date);
					$intdate	= intval($date->format('Ymd'));
					$today 		= intval(date('Ymd')); 

					$archive = false; // active
					if($today >= $intdate){
						$archive = true; // archive
					} ?>
				 
				<?php if($archive==false) { ?>
					<h1><?php _e('Active Project', THEME_TEXT_DOMAIN); ?></h1>
				<?php } else { ?>
					<h1><?php _e('Archived Project', THEME_TEXT_DOMAIN); ?></h1>
				<?php } ?>
				<div class="sep"></div>

				<div class="project-listing">
					<div class="project-item white-box simple <?php if($archive==true) { echo 'grey'; } ?>">
						<div class="left-details">
							<?php echo get_bidders_no($project_id); ?>
							<?php echo get_creation_date($project_id); ?>
							<?php if($archive==true) { ?>
								<?php echo get_expiration_date_archive($project_id); ?>
							<?php } else { ?>
								<?php echo get_expiration_date($project_id);?>
							<?php } ?>
						</div>

						<div class="text-content">
							<div class="project-header">
								<h2 class="left"><div class="icon"><i class="fa fa-file-text-o"></i></div><?php echo get_the_title(); ?></h2>
								<div class="project-options right">
									<?php $box = get_field('files_project', $project_id); ?>
									<?php	if ($box){ ?>
										<div class="has-at left">
											<i class="fa fa-paperclip"></i>
											<?php _e('The project containers attachments', THEME_TEXT_DOMAIN); ?>
										</div>
									<?php } ?>
									<?php // check if is active & if current user is the author for this project ?>
									<?php $id_author = get_the_author_meta('ID');?>
									<?php if($user_ID  == $id_author && check_role_current_user('customer' ) && $archive==false){ ?>
										<a href="<?php echo get_permalink(get_field('customer_project_edit_page_individual', 'options')->ID).'?id='.$project_id; ?>" class="edit-project left">
											<i class="fa fa-pencil-square-o"></i>
											<p class="smalllabel"><?php _e('Edit', THEME_TEXT_DOMAIN); ?></p>
										</a>
									<?php } ?>
								</div>
							</div>
							<div class="floating-objects">
								<div class="description">
									<h6><?php _e('Description:', THEME_TEXT_DOMAIN); ?></h6>
									<?php if (have_posts()) : while (have_posts()) : the_post();?>
		    							<?php the_content(); ?>
       		 						<?php endwhile; endif; ?>
								</div>
								<?php $country_project = get_field('country_project', $project_id); ?>
								<?php if(!empty($country_project)){?>
									<div class="countries">
										<h6><?php _e('Country:', THEME_TEXT_DOMAIN); ?></h6>
										<p><?php echo $country_project; ?></p>
									</div>
								<?php } ?>
							</div>

							<div class="hidden-content" style="display: block;">
								<?php echo get_project_inf($project_id); ?>
							</div>
						</div>

						<div class="clear"></div>
						<?php if($archive==false) { 
								// if is logged as contractor ? 
							if ( is_user_logged_in() && check_role_current_user('contractor' ) == true && $authid != $user_ID) { ?>
							  	<?php // check if this user didn't bit already for this project ?>
						  		<?php $validation = check_bid($project_id, $user_ID); ?>
						  		<?php if($validation == true){ ?>
						  			<div class="bids-listing"> 
						  				<div class="no-bids">
											<i class="fa fa-smile-o" aria-hidden="true"></i>
											<p><?php _e('You have already bided  to this project. <br/> This is your bid:', THEME_TEXT_DOMAIN); ?></p>
											<?php // display the bid ?>
											<?php $current_bid = get_current_bid($project_id, $user_ID); ?>
											<?php $bid_id = $current_bid->ID; ?>
											<?php 
												//$bid_id = get_the_ID(); 
												$bidder = get_field('bidder', $bid_id); 
												$bidder_id = $bidder['ID'];
												$customer_hired = get_field('customer_hired', $project_id);  

												$bid_choose = false; 
												if(!empty($customer_hired) && $customer_hired['ID'] == $bidder_id  ){
													$bid_choose = true;
												}else {
													$bid_choose = false;
												}

												$date 		= get_field('project_expire_date_project', $project_id);
												$date 		= new DateTime($date);
												$intdate	= intval($date->format('Ymd'));
												$today 		= intval(date('Ymd')); 
											?>

											<div class="bid-item white-box">
												<div class="image">
													<?php if($bid_choose == true) {  ?>
														<i class="fa fa-check" aria-hidden="true"></i>
													<?php } ?>
													<?php // get avatar - contractor ?>
													<?php 
														$bidder_company = get_field('associated_company', 'user_'.$bidder_id);
														$bidder_company_id = $bidder_company->ID;
														$image = get_field('logo', $bidder_company_id);			 
														if(!empty($image)){   ?>
															<img src="<?php echo $image['sizes']['vsmall-logo']; ?>" alt="<?php echo $image['alt']; ?>" />
													<?php } else { ?>
														<img src="img/no-logo-company.jpg" alt="">
													<?php } ?>
												</div>
												<div class="text-content">
													<h4><?php echo ucwords($bidder['user_firstname'].' '.$bidder['user_lastname'] ); //$bidder['user_email']; ?></h4>
													<p><?php _e('Added: ', THEME_TEXT_DOMAIN); ?> <span class="spacespan"><?php echo get_the_time('G:i:s', $bid_id); ?></span><span class="spacespan"><?php echo get_the_time( 'd/m/Y', $bid_id); ?></span></p>
													<div class="view-more"><?php _e('Bid details', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-down"></i></div>
												</div>
												<div class="clear"></div>
												<div class="hidden-content">
													<div class="left-content">
														<h5><b><?php _e('Notes:', THEME_TEXT_DOMAIN); ?></b></h5>
														<?php $notes = get_field('notes', $bid_id); ?>
														<?php if(!empty($notes)){ ?>
															<?php echo $notes; ?>
														<?php } ?>
														<div class="row floatbit questionbox">
															<div class="cell x4">
																<div class="floating-objects">
																	<?php $_SESSION['id_project'] = $project_id; ?>
																	<?php $_SESSION['id_author'] = $authid; ?>
																	<?php /*
																	<button class="button green left" type="submit">Bid Project <i class="fa fa-angle-right"></i></button>*/?>
																	<?php $question = get_field('contractor_create_new_messages', 'options'); ?>
																	<?php if(!empty($question)){ ?>
																		<a href="<?php echo get_permalink($question->ID); ?>" title="" class="button left"><?php _e('ASK A QUESTION', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
																	<?php } ?>
																</div>
															</div>
														</div>
													</div>
													<div class="right-content">
														<h5><b><?php _e('Price per item:', THEME_TEXT_DOMAIN); ?></b></h5>
														<p><?php $price = get_field('price_per_item', $bid_id); if(!empty($price)) { echo $price; } ?></p>

														<h5><b><?php _e('Delivery date:', THEME_TEXT_DOMAIN); ?></b></h5>
														<p><?php $date_dev = get_field('delivery_date', $bid_id); $date = new DateTime($date_dev); echo $date->format('d/m/Y'); ?></p>
													</div>

												</div>

											</div>

										</div>		
						  			</div>
						  		<?php } else { ?>
									<div class="bids-listing">
										<h3><?php _e('Bid Project', THEME_TEXT_DOMAIN); ?></h3>
										<div class="wrappload">
											<div class="loader">
												<img src="img/loading.gif" alt="">
											</div>
											<div class="contentloader">
												<form action="" class="bidding_form" name="bidding_form"  method="POST" enctype="multipart/form-data" >
													<div class="row">
														<div class="cell x4">
															<div class="input-block mbottom20">
																<label><?php _e('Price per item *', THEME_TEXT_DOMAIN); ?></label>
																<input type="text" name="price"  required="required">
															</div>
															<div class="input-block">
																<label><?php _e('Delivery date *', THEME_TEXT_DOMAIN); ?></label>
																<input type="text" name="delivery_date" class="devdate" data-date="<?php echo date('d/m/Y');?>" data-format="dd/mm/yyyy" required="required" value="<?php echo date('d/m/Y'); ?>"> 
															</div>
														</div>
														<div class="cell x8">
															<div class="input-block">
																<label><?php _e('Notes', THEME_TEXT_DOMAIN); ?></label>
																<textarea name="notes" ></textarea>
															</div>
														</div>
													</div>
													<div class="row floatbit">
														<div class="cell x4">
															<div class="floating-objects">
																<?php $_SESSION['id_project'] = $project_id; ?>
																<?php $_SESSION['id_author'] = $authid; ?>
																<input type="hidden" name="project" value="<?php echo get_the_ID(); ?>">
																<input type="hidden" value="<?php echo wp_create_nonce('submit-bidding'); ?>" name="submit-bidding">
																<input type="hidden" value="bid_project" name="action">
																<div class="submitarea smalltop">
																	<input type="submit" class="button green left" value="<?php _e('Bid Project', THEME_TEXT_DOMAIN); ?>"> 
																	<i class="fa fa-angle-right"></i>
																</div>
																<?php /*
																<button class="button green left" type="submit">Bid Project <i class="fa fa-angle-right"></i></button>*/?>
																<?php $question = get_field('contractor_create_new_messages', 'options'); ?>
																<?php if(!empty($question)){ ?>
																	<a href="<?php echo get_permalink($question->ID); ?>" title="" class="button left"><?php _e('ASK A QUESTION', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-right"></i></a>
																<?php } ?>
															</div>
														</div>
													</div>
													<div class="responsecheck_bidding"></div>
												</form>
											</div>
										</div>
									</div>
								<?php } ?>

							<?php } else if ( is_user_logged_in() && check_role_current_user('contractor' ) == true && $authid == $user_ID) { ?>
								<div class="bids-listing">
							  		<div class="no-bids">
										<i class="fa fa-smile-o" aria-hidden="true"></i>
										<p><?php _e('You are the owner of this project.', THEME_TEXT_DOMAIN); ?><br/><?php _e('Switch the roles to see the list of bids.', THEME_TEXT_DOMAIN); ?></p>
									</div>
								</div>
							<?php }else if(is_user_logged_in() && check_role_current_user('customer' ) == true && $authid == $user_ID){ ?>
									<?php $_SESSION['id_project'] 	= $project_id; ?>
									<?php// $_SESSION['id_author'] 	= $authid; ?>
									<div class="clear"></div>
									<div class="bids-listing">
										<h3><?php _e('Project Bids', THEME_TEXT_DOMAIN); ?></h3>
										<?php $nr = get_bidders_number($project_id); ?>
										
										<?php if($nr > 0){ ?>
											<?php echo get_list_bids($project_id); ?>
										<?php }else{ ?>
											<div class="no-bids">
												<i class="fa fa-frown-o"></i>
												<p><?php _e('This project has no bidders.', THEME_TEXT_DOMAIN); ?></p>
											</div>
										<?php } ?>
									</div>
							<?php } ?>

						<?php } else { ?>
							<div class="bids-listing">
								
								<div class="no-bids">
									<?php 
										$customer_hired = get_field('customer_hired', $project_id);  
										if(!empty($customer_hired)){ 
											if($customer_hired['ID'] == $user_ID ){
												$user_customer = get_user_by('ID', $authid); ?>
												<i class="fa fa-smile-o" aria-hidden="true"></i>
												<p><?php  _e('This project has expired.', THEME_TEXT_DOMAIN); 
													echo "<br/>";
														_e('You have been hired. Please contact the customer: ', THEME_TEXT_DOMAIN);	
													echo  ucwords($user_customer->user_firstname.' '.$user_customer->user_lastname); //$user_customer->user_email;
												?>
												</p>
										<?php } ?>
									<?php }else { ?>
											<i class="fa fa-frown-o"></i>
											<p><?php  _e('This project has expired.', THEME_TEXT_DOMAIN); ?></p>
									<?php  } ?>

									

								</div>


								<?php if(is_user_logged_in() && check_role_current_user('customer' ) == true && $authid == $user_ID){ ?>
									<div class="clear"></div>
									<div class="bids-listing ">
									<?php // lists bids
										$nr = get_bidders_number($project_id); 
										if($nr > 0){  
											echo get_list_bids($project_id); 
										} else { ?>
											<div class="no-bids">
												<?php if($archive==false) { ?>
													<i class="fa fa-frown-o"></i>
												<?php } ?>
												<p><?php _e('This project has no bidders.', THEME_TEXT_DOMAIN); ?></p>
											</div>
									<?php } ?>
									</div>
								<?php } ?>
							</div>

						<?php } ?>
							
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } if( is_user_logged_in() && check_role_current_user('customer' ) == true && check_project_autor($project_id, $user_ID) == false ) { // customer and not the author  ?>
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

<?php } else  if ( !is_user_logged_in() ){ ?>
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