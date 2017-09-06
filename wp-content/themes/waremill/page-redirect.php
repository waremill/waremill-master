<?php /*Template Name: Temporal Redirect FB*/ 

 if(check_user_login() == true ){ 
 	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	$check_field_user_first_login 	= get_field('first_login', 'user_'. $user_ID); 
	associate_draft();  
 	if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_ID), "true") == 0) { // first login => redirect page switch role
        $pg_id = get_field('page_for_switching_roles', 'options')->ID;
        $link =  get_permalink($pg_id); 
        header('Location: '.  $link);  exit();

    }else{   
		if(check_user_customer() == true){ 
			$client_dashboard = get_field('client_dashboard', 'options')->ID; 
			$client_dashboard_link =  get_permalink($client_dashboard); 
			 //wp_redirect( $client_dashboard_link ); 
				header('Location: '. $client_dashboard_link);  exit();
			
		} else if(check_user_contractor() == true ) { 

			$contractor_dashboard = get_field('contractor_dashboard', 'options')->ID; 
			$contractor_dashboard_link =  get_permalink($contractor_dashboard); 
			//wp_redirect( $contractor_dashboard_link ); 
				header('Location: '.  $contractor_dashboard_link);  exit();
		}else{ 
		   // to homepage 
			$site_url = get_site_url() ;  
			  //wp_redirect( $site_url ); 
				header('Location: '.  $site_url);  exit();
		} 
	}
} 
exit(); ?>