<?php if(check_user_login() == true ){ ?>
	<?php $s_roles = get_field('page_for_switching_roles', 'options')->ID; ?>

	<div class="user-menu right">
		<span class="button name small"><i class="fa fa-user-circle"></i><?php _e('Hi', THEME_TEXT_DOMAIN); ?>, <?php echo get_username(); ?></span>
		<div class="hidden-menu">
			<div class="hidden-menu-inner">
			<?php if(check_user_customer() == true){ ?>
			 	<?php wp_nav_menu( array( 'theme_location' => 'menu-user-customer','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
				<ul class="logout">	
					<?php $login_page = site_url(); ?>
					<li><?php echo wp_loginout($login_page, 'Logout' ); // redirect on home page ?></li>
				</ul>
				<a href="#" title="" class="button grey small switchrole" data-role="contractor" data-page="<?php if ( !is_404() && !is_category() && !is_tag() && !is_day() && !is_month() && !is_year() && !is_search() && !is_category() && !is_tag() && !is_day() && !is_month() && !is_year() && !is_author() && !is_search() )   { echo get_the_ID(); }  ?>"><?php _e('Switch to Contractor', THEME_TEXT_DOMAIN); ?><span id="opuse-role"></span>
				</a>
			
			<?php } else if(check_user_contractor() == true ) { ?>
				<?php wp_nav_menu( array( 'theme_location' => 'menu-user-contractor','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
				<ul class="logout">	
					<?php $login_page = site_url(); ?>
					<li><?php echo wp_loginout($login_page, 'Logout' ); // redirect on home page ?></li>
				</ul>
				<a href="#" title="" class="button grey small switchrole" data-role="customer" data-page="<?php if ( !is_404() && !is_category() && !is_tag() && !is_day() && !is_month() && !is_year() && !is_search() && !is_category() && !is_tag() && !is_day() && !is_month() && !is_year() && !is_author() && !is_search() )   { echo get_the_ID(); } ?>"><?php _e('Switch to Customer', THEME_TEXT_DOMAIN); ?><span id="opuse-role"></span>
				</a>
			<?php } else { ?>
				<ul>
					<li><?php echo wp_loginout($login_page, 'Logout' ); // redirect on home page ?></li>
				</ul>
			<?php } ?>
			</div>
		</div>
	</div>
 
<?php } ?>

<nav class="navigation-menu right">
	<?php if(check_user_login() == false ){ ?>
		<?php wp_nav_menu( array( 'theme_location' => 'header-menu','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
	<?php }else { ?>
		<?php /*if(check_user_customer() == true || check_user_contractor() == true ){ ?>
			<?php wp_nav_menu( array( 'theme_location' => 'header-menu-users','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
		<?php }*/ ?>
		<?php type_of_menu(); ?>	
	<?php } ?>


	<?php if(check_user_login() == false ){ ?>
		<?php wp_nav_menu( array( 'theme_location' => 'menu-login','container'=>false,'menu_class' => 'home-header-menu' ) ); ?>
	<?php } ?>
</nav>
<a href="#mobile-navigation-menu" title="" class="show-menu right"><i class="fa fa-bars"></i></a>
