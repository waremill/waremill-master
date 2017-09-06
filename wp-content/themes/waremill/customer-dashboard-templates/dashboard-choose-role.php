<?php /*Template Name: Customer - Dashboard - User Role*/
get_header(); ?>
<div class="dashboard-page">
	<div class="main-content">
		<div class="container">
			<div class="centered-content">
				<?php if (have_posts()) : while (have_posts()) : the_post();?>
					<?php the_content(); ?>
				<?php endwhile; endif; ?>
			</div>

			<?php $box = get_field('options'); ?>
			<?php	if ($box){ ?>
				<div class="row user-roles">
				<?php foreach ($box as $box1) {  ?>
					<?php $short_description = $box1['short_description']; ?>
					<?php $user_role = $box1['user_role']; ?>	
					<?php $image = $box1['image']; ?>	
					<?php if(!empty( $short_description) && !empty($user_role) && !empty($image)){ ?>			
						<div class="cell x6">
							<div class="white-box user-role">
								<div class="icon"><img src="<?php echo $image['sizes']['type']; ?>" alt=""></div>
								<?php echo $short_description; ?>
								<?php 
									switch ($user_role) {
 									   	case "customer":
 									   		?><a href="" title="" class="button">Hire</a><?php
 									     	break;
    									case "contractor":
    										?><a href="" title="" class="button">Work</a><?php
    										break;
 									} ?>
								
							</div>
						</div>
					<?php } ?>
						
					
				<?php } ?>
				</div>
			<?php } ?>

			<?php /*
			<div class="row user-roles">
				<div class="cell x6">
					<div class="white-box user-role">
						<div class="icon"><img src="img/user-customer-icon.png" alt=""></div>
						<h2>I want to hire a contractor.</h2>
						<p>Find, collaborate with, and pay an expert.</p>

						<a href="" title="" class="button">Hire</a>
					</div>
				</div>

				<div class="cell x6">
					<div class="white-box user-role">
						<div class="icon"><img src="img/user-contractor-icon.png" alt=""></div>
						<h2>I'm looking for work.</h2>
						<p>Find freelance projects and grow your business.</p>

						<a href="" title="" class="button">Work</a>
					</div>
				</div>
			</div>
			<?php */ ?>
		</div>
	</div>
</div>
<?php get_footer();