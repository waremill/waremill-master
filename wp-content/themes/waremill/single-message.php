<?php get_header(); ?>
<?php  
	$user = wp_get_current_user(); 
	$user_ID = $user->ID; ?>
<?php if ( is_user_logged_in() && (check_role_current_user('contractor' ) == true || check_role_current_user('customer' ) == true) ) { // user logged and is part for discution ?>
	<?php $page_id = get_the_ID(); ?>
	<?php $customer 	= get_field('customer');  //var_dump($customer); ?>
	<?php $contractor 	= get_field('contractor'); ?>
	<?php $associated_company_object = get_field('associated_company', 'user_'.$contractor['ID']); // return ID ?>
	<?php $project 		= get_field('project'); ?>
	<?php //var_dump( $associated_company_object); ?>
	
	<?php // mark all messages as read ?>

	<?php if( ($user_ID == $contractor['ID'] && check_role_current_user('contractor' ) == true ) || ( $user_ID == $customer['ID'] && check_role_current_user('customer' ) == true ) ){  ?>
		<div class="dashboard-page">
			<div class="main-content">
				<div class="container">
					<div class="messages-header">
						<div class="text-content">
							<div class="row">								
								<div class="cell x6 headinfo">
									<p><?php _e('Contractor: ', THEME_TEXT_DOMAIN); ?> <strong><?php echo ucwords($contractor['user_firstname']).' '. ucwords($contractor['user_lastname']);//echo $contractor['user_email'];?></strong></p>
									<p><?php _e('Name of company: ', THEME_TEXT_DOMAIN); ?><strong><a href="<?php echo get_permalink( $associated_company_object); ?>" title=""><?php if(!empty($associated_company_object)){ echo get_field('company_name', $associated_company_object); } ?></a></strong></p>
								</div>
								<div class="cell x6 headinfo align-right">
									<p><?php _e('Customer: ', THEME_TEXT_DOMAIN); ?> <strong><?php echo ucwords($customer['user_firstname']).' '. ucwords($customer['user_lastname']);//echo $customer['user_email'];?></strong></p>
									<p><?php _e('Project: ', THEME_TEXT_DOMAIN); ?><strong><a href="<?php echo get_permalink($project->ID); ?>" title=""><?php if(!empty($project)){ echo get_the_title($project->ID); } ?></a></strong></p>
								</div>
							</div>
						</div>						
					</div>
					<div class="sep"></div>

					<div class="messages-wrapper white-box">
					
						<?php $messages = get_field('discussion'); ?>
						<?php if(!empty($messages)){ ?>
							<div class="wrappload-messages">
								<div class="wrappload ">
									<div class="loader largeloader ">
										<img src="img/loading.gif" alt="">
									</div>
									<div class="contentloader "> 
										<div  class="wrappmessages">
											<?php $size_messages = sizeof($messages);   ?> 
											<?php if($size_messages > 5 ){ ?>
												<div class="centered-content">
													<a href="#" title="" class="view-older" data-start="<?php echo $size_messages - 5 ; ?>" data-message="<?php echo $page_id; ?>"><?php _e('Older messages', THEME_TEXT_DOMAIN); ?> <i class="fa fa-angle-up"></i></a>
												</div>
											<?php } ?>
											<div id="messages">
												<?php if($size_messages > 5 ){ ?>
													<?php $messages_part = array_slice($messages, $size_messages - 5 , 5 ); ?>
												<?php } else { ?>
													<?php $messages_part = $messages; ?>
												<?php } ?>
												<?php foreach ($messages_part as $key => $single_message) { ?>
													<?php 
														$user_message 		= $single_message['user']; 
													 	$user_message_id 	= $user_message['ID'];
													 	$date 				= $single_message['date']; 
													 	$date 				= new DateTime($date);
													 	$file 				= $single_message['file'];
													 	$read 				= $single_message['read']; 
													?>
													<div class="message-item <?php if($user_message_id == $user_ID) { echo "me "; } if( $read == false && $user_message_id != $user_ID) { echo " new"; } ?>" data-order="<?php echo $size_messages - 4 + $key;  ?>">
														<div class="author">
															<div class="icon"><i class="fa fa-user"></i></div>
															<h4><?php echo ucwords($user_message['user_firstname']); //$rest = substr($user_message['user_email'], 0, 6).'...'; echo $rest; ?></h4>
															<p><?php echo $date->format('d/m/Y'); // Y-m-d H:i:s ?></p>
															<p><?php echo $date->format('H:i:s'); ?></p>
														</div>
														<div class="message-content-wrapper">
															<div class="message-content">
																<?php $text = $single_message['message']; ?>
																<?php if(!empty($text)){ ?>
																	<?php echo $text; ?>
																<?php } ?>
															</div>
															<?php if(!empty($file)){ ?>
																<div class="file-attachment">
																	<a href="<?php echo $file['url'];?>" target="_blank" title="">
																		<i class="fa fa-paperclip"></i><?php echo $file['filename']; ?>
																	</a>
																</div>
															<?php } ?>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>

						<?php } else { ?>
							<div class="no-messages">
								<i class="fa fa-paperclip"></i>
								<p><?php _e('This message board is empty.', THEME_TEXT_DOMAIN); ?></p>
							</div>
						<?php } ?>
						
						<div class="wrappload-send-messages">
							<div class="wrappload">
								<div class="loader largeloader">
									<img src="img/loading.gif" alt="">
								</div>
								<div class="contentloader"> 
									<form action="" id="replay_message" class="reply-form" name="replay_message"  method="POST" enctype="multipart/form-data" >
										<div class="input-block file-upload-wrapper">
											<textarea name="message_new" palceholder="Your message here" required="required"></textarea>
										</div>
										
										<div class="selected-file">
											<i class="fa fa-paperclip"></i>
											<input type="file" id="uploadFile" name="file" placeholder="Choose File" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,  application/pdf, image/*" />
										</div>
										

										<div class="floating-objects">
											<input type="hidden" name="id_message" id="msg" value="<?php echo $page_id; ?>">
											<input type="hidden" value="<?php echo wp_create_nonce('submit-message'); ?>" name="submit-message">
											<input type="hidden" value="replay_message" name="action">
											<div class="submitarea normalbutton">
												<input type="submit" class="button left" value="<?php _e('Send', THEME_TEXT_DOMAIN); ?>"> 
												<i class="fa fa-angle-right"></i>
											</div>
										</div>
										<div class="answer_replay"></div>
									</form>
								</div>
							</div>
						</div>

						
					</div>

					<?php $customer_messages 	= get_field('customer_messages', 'options')->ID; ?>
					<?php $contractor_messages 	= get_field('contractor_messages', 'options')->ID; ?>

					<div class="pagination">
						<?php if ( is_user_logged_in() && check_role_current_user('customer') == true ) { ?>
							<a href="<?php echo get_permalink($customer_messages); ?>" title=""><i class="fa fa-caret-left"></i></a>
						<?php } else if ( is_user_logged_in() &&  check_role_current_user('contractor') == true ){ ?>
							<a href="<?php echo get_permalink($contractor_messages); ?>" title=""><i class="fa fa-caret-left"></i></a>
						<?php } ?>
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
							<?php if(check_role_current_user('contractor' ) == true){ ?>
								<?php $login_page = get_field('customer_need_to_switch_accounts', 'options'); ?>
								<?php if(!empty($login_page)){ ?>
									<?php echo $login_page; ?>
								<?php } ?>
							<?php } else if(check_role_current_user('customer' ) == true ){ ?>
								<?php $login_page = get_field('contractor_need_to_switch_accounts', 'options'); ?>
								<?php if(!empty($login_page)){ ?>
									<?php echo $login_page; ?>
								<?php } ?>
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
						<?php $login_page = get_field('login_but_without_access_on_this_page', 'options'); ?>
						<?php if(!empty($login_page)){ ?>
							<?php echo $login_page; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } ?>

<?php get_footer(); 