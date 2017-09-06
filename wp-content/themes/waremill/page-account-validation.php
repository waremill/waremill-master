<?php /*Template Name: Account Validation*/
defined( 'ABSPATH' ) or die( 'Direct access is forbidden!' );
get_header(); ?>
<?php $page_id = get_the_ID(); ?>
<div class="default-page">
	<div class="main-content">
		<div class="container">
			<div class="content-wrapper">
				<div class="full-content">
					<h1><?php echo get_the_title(); ?></h1>
					<div class="sep"></div>
					<?php $user_id  = $_GET['id']; ?>
					<?php $limit 	= $_GET['limit']; ?>
					<?php if(check_user_login() == false ){ // if is nobody logged?>

						<?php if(isset($user_id) && strlen($user_id) > 0 && isset($limit) && strlen($limit) > 0 ){ ?>
							<?php if(check_id_with_hash($user_id, $limit)){ // user with this hash ?>
								
								<?php // change role: from "temp_member" to "client" ?>
								<?php // check if is temp_member or client ?>
								<?php if( strcmp(get_user_meta_active($user_id), "false") == 0){ ?>
									<?php $change_role =  update_user_meta($user_id, 'user_active', 'true'); ?>
									
									<?php if( $change_role == true){ // success change role ?>
										
									<?php// update_meta_active_user($user_id); 
									//change_role_tempmember_to_client($user_id); ?>

										<?php $success_message = get_field('success_message', $page_id); ?>
										<?php if(!empty($success_message)){ ?>
											<?php echo $success_message; ?>
										<?php } ?>	

									<?php } else { // error change role ?>
										
										<?php $error_validation = get_field('error_validation', $page_id); ?>
										<?php if(!empty($error_validation)){ ?>
											<?php echo $error_validation; ?>
										<?php } ?>

									<?php } ?>

								<?php } else { ?>

									<?php $account_already_valid = get_field('account_already_valid', $page_id); ?>
									<?php if(!empty($account_already_valid)){ ?>
										<?php echo $account_already_valid; ?>
									<?php } ?>

								<?php } ?>
								
							<?php } else {  // user withouth this hash ?>

								<?php if (have_posts()) : while (have_posts()) : the_post();?>
									<?php the_content(); ?>
								<?php endwhile; endif; ?>

							<?php } ?>

						<?php }else { // link with no parameters  ?>

							<?php if (have_posts()) : while (have_posts()) : the_post();?>
								<?php the_content(); ?>
							<?php endwhile; endif; ?>

						<?php } ?>

					<?php }else{  // if is logged ?>
						<?php $current_user = wp_get_current_user(); ?>
						<?php $current_user_id = $current_user->ID; ?>
						
						<?php if(strcmp($current_user_id, $user_id) == 0 ){ // is the same user ?>
							
							<?php $account_already_valid = get_field('account_already_valid', $page_id); ?>
							<?php if(!empty($account_already_valid)){ ?>
								<?php echo $account_already_valid; ?>
							<?php } ?>

						<?php } else { // login but other user  ?>

							<?php if (have_posts()) : while (have_posts()) : the_post();?>
								<?php the_content(); ?>
							<?php endwhile; endif; ?>

						<?php } ?>
						
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();