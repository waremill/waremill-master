<?php 

/*
* $other_user_id = is the id for the user where the email will be sent 
*/

// $email_address_notification = get_field('email_address_notification', 'options');

/********************************************* Send Email User Received a New Message *********************************************/
/* 
*  Function  : send_email_new_message
*  Parameters: id user to send message 
*  Return    : true / false (email send or not )
*/
function send_email_new_message($other_user_id){

	$subject = "";
	$content_message = "";
	$user = wp_get_current_user(); 
	$user_ID = $user->ID;
	$user_role = $user->roles[0];
	$other_user = get_user_by('ID', $other_user_id );
    $notify_other_user = get_field('notify_new_message', 'user_'.$other_user_id );

    if($notify_other_user == true){ // accept notifications

        if(strcmp($user_role, "customer") == 0){
            $subject         = get_field('email_subject_contractor', 'options');
            $content_message = get_field('email_content_new_message_contractor', 'options');
            $content_message = str_replace('{customer}',  $user->user_firstname.' '. $user->user_lastname  , $content_message);
            
        }elseif(strcmp($user_role, "contractor") == 0) {
            $subject         = get_field('email_subject_customer', 'options');
            $content_message = get_field('email_content_new_message_customer', 'options');
            $content_message = str_replace('{contractor}', $user->user_firstname.' '. $user->user_lastname , $content_message); 
        }

        // replace specific strings
        $content_message = str_replace('{name}', $other_user->user_firstname.' '. $other_user->user_lastname, $content_message);

        $message = get_basic_template_email( $content_message );

        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.get_bloginfo('name').' <'.  get_option( 'admin_email' ) .'>';

        $mail = wp_mail( $other_user->user_email, $subject, $message, $headers );
        if( $mail ){
            return true;
        }else{
            return false;
        }
    }else{
        return true; 
    }
}	

/********************************************* Send Email User Received a New Bid *********************************************/
/* 
*  Function  : send_email_new_bid
*  Parameters: id user to send message 
*  Return    : true / false (email send or not )
*/
function send_email_new_bid($customer_id, $project_id){
	$user 				= wp_get_current_user(); 
	$subject 			= get_field('subject_bid', 'options');
	$content_message 	= get_field('email_content_new_bid_customer', 'options');
	$other_user        = get_user_by('ID', $customer_id );

    $notify_a_new_bid  = get_field('notify_a_new_bid', 'user'. $customer_id);
    if($notify_other_user == true){
    	
    	$content_message   = str_replace('{name}', $other_user->user_firstname.' '. $other_user->user_lastname, $content_message);
    	$content_message   = str_replace('{project}', get_the_title($project_id) , $content_message) ;
        $content_message   = str_replace('{contractor}', $user->user_firstname.' '. $user->user_lastname , $content_message); 

    	$message = get_basic_template_email($content_message); 

    	$headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';

        $mail = wp_mail( $other_user->user_email, $subject, $message, $headers );
        if( $mail ){
            return true;
        }else{
            return false;
        }
    }else{
        return true; 
    }
}


/********************************************* Send Email User Get Hired *********************************************/
/* 
*  Function  : send_email_hire
*  Parameters: id user to send message, project id 
*  Return    : true / false (email send or not )
*/
function send_email_hire($customer_id, $project_id){
	$user 				= wp_get_current_user(); 
	$subject 			= get_field('subject_hire', 'options');
	$content_message 	= get_field('email_content_hired_contractor', 'options');
	$other_user 		= get_user_by('ID', $customer_id );

    $notify_get_hired   = get_field('notify_get_hired', 'user_'.$other_user );
    if($notify_get_hired == true){
        $content_message    = str_replace('{name}', $other_user->user_firstname.' '. $other_user->user_lastname, $content_message);
        $content_message    = str_replace('{project_name}', get_the_title($project_id) , $content_message) ;

        $project_deadline   = get_field('project_delivey_deadline_project', $project_id);
        $date               = new DateTime($project_deadlin);  
        $insert_date        = $date->format('d/m/Y');
        $content_message    = str_replace('{project_deadline}', $insert_date, $content_message);

        $quantity_project   = get_field('quantity_project', $project_id);
        $content_message    = str_replace('{project_quantity}', $quantity_project , $content_message);
        $content_message    = str_replace('{project_link}', '<a href="'.get_permalink($project_id).'">'.get_the_title($project_id).'</a>', $content_message); 
        $content_message    = str_replace('{customer}', $user->user_firstname.' '. $user->user_lastname , $content_message); 

        $message            = get_basic_template_email($content_message); 

        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';

        $mail = wp_mail( $other_user->user_email, $subject, $message, $headers );
        if( $mail ){
            return true;
        }else{
            return false;
        }
    }else{
        return true; 
    }
}

/********************************************* Send Email Customer - when update the settings  *********************************************/
/* 
*  Function  : send_email_customer_settings
*  Parameters: new password
*  Return    : true / false (email send or not )
*/
function send_email_customer_settings($u_new_password ){
	$user 				= wp_get_current_user(); 
	$subject 			= get_field('subject_edits', 'options');
	$content_message 	= get_field('email_settings_updated_customer', 'options');

	$content_message    = str_replace('{name}', $user->user_firstname .' ' . $user->user_lastname  , $content_message);
    $content_message    = str_replace('{first_name}', $user->user_firstname, $content_message);
    $content_message    = str_replace('{last_name}',  $user->user_lastname , $content_message);
    $content_message    = str_replace('{email}', $user->user_email, $content_message);
   
    if(!empty($user->description)){
    	$content_message    = str_replace('{company}', $user->description, $content_message);
    }else{
    	$content_message    = str_replace('{company}', '-', $content_message);
    }

    if(strlen($u_new_password) > 0 ){
    	$content_message    = str_replace('{password}', $u_new_password, $content_message);
    }else{
    	$content_message    = str_replace('{password}', 'Your password has not been changed.', $content_message);
    }
    
    $message = get_basic_template_email($content_message);

	$headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';

    $mail = wp_mail( $user->user_email, $subject, $message, $headers );
    if( $mail ){
        return true;
    }else{
        return false;
    }

}


/********************************************* Create basic template for eamils  *********************************************/
/* 
*  Function  : get_basic_template_email
*  Parameters: middle content (html)
*  Return    : html
*/
function get_basic_template_email($content_message){

	$message = '';
    $message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $message .= '<html>';
    $message .= '<head>';
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
    $message .= '<title>'.get_bloginfo('name') . '</title>';
    $message .= '</head>';
    $message .= '<body style="font-family: \'Arial\', sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;   margin: 50px 20px; padding: 0px; font-size: 14px; color: #2a200c; " topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">';
       	 	$message .= '<table style="width: 100%;  "><tr><td>';
        	    $message  .= $content_message;
            $message .= "<td><tr></table>"; 
        $message .= "</body>";
    $message .= "</html>";

    return $message;

}


/********************************************* Cronjob  *********************************************/
/* 
*  Function  : Cronjob for projects and for news / posts 
*  Parameters: middle content (html)
*  Return    : 
*/

//Cronjob  => projects => test 
function  news_with_new_projects_test_action() {
    news_with_new_projects();
} add_action('news_with_new_projects_test_action', 'news_with_new_projects_test_action');


//Cronjob  => projects 
function  news_with_new_projects_action() {
    news_with_new_projects();
} add_action('news_with_new_projects_action', 'news_with_new_projects_action');


//Cronjob  => posts 
function  news_with_new_posts_action() {
    news_with_posts();
} add_action('news_with_new_posts_action', 'news_with_new_posts_action');


/********************************************* Cronjob  => projects = only for users with this option checked  *********************************************/
/* 
*  Function  : news_with_new_projects // select all the users with a company associated  and settings -> notify new procurement (checked)
*  Parameters: -
*  Return    : -
*/
function news_with_new_projects(){
    // loop throught users
    $role = array('customer', 'contractor'); 
    $args = array(
        'role__in' => $role,
        'meta_query' => array(
            'relation'    => "AND",
            array(
                'key'     => 'associated_company',
                'value'   => '',
                'compare' => '!='
            ),
            array(
                'key'       => 'notify_new_procurement',
                'compare'   => '=',
                'value'     => '1',
            )
        )
    );

    $user_query = new WP_User_Query( $args );
    //var_dump($user_query);
    if ( ! empty( $user_query->results ) ) {
        foreach ( $user_query->results as $user ) {
            // get array with services;
            $user_id = $user->ID;
            $associated_company = get_field('associated_company', 'user_'.$user_id );

            if(!empty($associated_company)){
                $array_services = array();
                $terms = get_the_terms( $associated_company, 'service' );
                if ( $terms && ! is_wp_error( $terms ) ) { 
                    foreach ( $terms as $term ) {
                        $array_services[] = $term->term_id;
                    }
                }

                if(!empty($array_services)){
                    //check if service list is empty 
                    template_new_with_project($array_services, $user);
                }
            }
        }
    }  
    wp_reset_postdata();  
   
}

/* 
*  Function  : template_new_with_project // select new projects on his interes => send email 
*  Parameters: -
*  Return    : true / false (email send / not send )
*/
function template_new_with_project($array_services, $user ){

    $user_id    = $user->ID;
    $user_email = $user->user_email;

    $how_many_projects = get_field('how_many_projects', 'options');
    if(empty($how_many_projects)){
        $posts_per_page = 7; 
    }else{
        $posts_per_page = $how_many_projects; 
    }

    $today = date('Ymd');
    $args =  array( 
        'ignore_sticky_posts'   => true, 
        'post_type'             => 'project',
        'order'                 => 'ASC',
        'meta_key'              => 'project_expire_date_project',
        'orderby'               => 'meta_value',
        'posts_per_page'        => $posts_per_page ,
        'meta_query' => array(
            'relation'      => "AND",
            array(
                'key'       => 'project_expire_date_project',
                'compare'   => '>',
                'value'     => $today,
            ),
            array(
                'key'       => 'project_author',
                'compare'   => '!=',
                'value'     => '',
            )
        ),
    );   

    $args['tax_query'][] = array(
        array(
            'taxonomy' => 'service',
            'field'    => 'term_id',
            'terms'    =>  $array_services,
        )
    );

    //send email only if we have projects
    $loop = new WP_Query( $args ); 
    if ($loop->have_posts()) { 
        $template_email = "";
        $template_email .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $template_email .= '<html>';
        $template_email .= '<head>';
            $template_email .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
            $template_email .= '<title>'.get_bloginfo('name') . '</title>';
            $template_email .= '<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">';
        $template_email .= '</head>';
        $template_email .= '<body style="font-family: \'Lato\', sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;   margin: 0px; padding: 0px; font-size: 14px; color: #2a200c; " topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">';
            $template_email .= '<table style="width: 100%; ">';
                $template_email .= '<tr>';
                    $template_email .= '<td>';
                       $template_email .= '<table style="width: 100%; background: #2cade2; padding: 20px;">';
                            $template_email .= '<tr>';
                                $template_email .= '<td>';
                                    $template_email .= '<table style="width: 800px; margin: 0 auto; background: #2cade2; display: table;">';
                                        $template_email .= '<tr>';
                                            $template_email .= '<td>';
                                                $image_png = get_field('logo_png', 'options');
                                                $template_email .= '<a href="'.site_url().'" title="">';
                                                    $template_email .= '<img src="'.$image_png['url'].'"  style="width: 200px;">';
                                                $template_email .= '</a>';
                                            $template_email .= '</td>';
                                            $template_email .= '<td>';
                                                $template_email .= '<p style="color: #fff;font-size: 12px; text-align: right; margin: 5px 0; line-height: 16px; ">Notification</p>';
                                                $template_email .= '<p style="color: #fff;font-size: 12px; text-align: right; margin: 5px 0; line-height: 16px; ">'.date('d/m/Y').'</p>';
                                            $template_email .= '</td>';
                                        $template_email .= '</tr>';
                                    $template_email .= '</table>';
                                $template_email .= '</td>';
                           $template_email .= '</tr>';
                        $template_email .= '</table>';
                   
                        $template_email .= '<table style="width: 100%; background: #f3f3f3; padding: 20px;">';
                            $template_email .= '<tr>';
                                $template_email .= '<td>';
                                    $template_email .= '<table style="width: 800px; margin: 0 auto; background: #fff;  border: 1px solid #dfdfdf; padding: 20px; display: table; border-bottom: none;">';
                                        $template_email .= '<tr>';
                                            $template_email .= '<td colspan="2" style="border-bottom: 1px solid #dfdfdf; border-collapse: collapse;">';
                                                $intro = get_field('text_after_hi_news', 'options');
                                                $template_email .= '<p style="font-size: 12px; line-height: 16px; margin-bottom: 30px; " >Hello '.$user->user_firstname.' '. $user->user_lastname.', '.$intro.' </p> ';
                                            $template_email .= '</td>';
                                        $template_email .= '</tr>';

                                    while ($loop->have_posts())  {  $loop->the_post();  
                                        $project_id = get_the_ID();
                                        $template_email .= '<tr>';
                                            $template_email .= '<td style="width: 15%; vertical-align: top; border-bottom: 1px solid #dfdfdf; border-collapse: collapse; " >';
                                                $template_email .= '<p style="margin-top: 0px; font-size: 12px; line-height: 20px;  margin-top: 7px;"><b>Created:</b><br/>'.get_the_date('d.m.Y').'</p>'; 
                                                
                                                $date = get_field('project_expire_date_project', $project_id);
                                                $date = new DateTime($date);
                                                $template_email .= '<p style="margin-top: 0px; font-size: 12px; line-height: 20px;"><b>Expires:</b><br/>'. $date->format('d.m.Y').'</p>'; 
                                               
                                            $template_email .= '</td>';
                                            $template_email .= '<td style="width: 85%; vertical-align: top; border-bottom: 1px solid #dfdfdf;  border-collapse: collapse; ">';
                                                $template_email .= '<p style="font-size: 13px; line-height: 22px; margin: 4px 0; margin-top: 7px;"><a href="'.get_permalink($project_id).'" title="" style="color: #2cade2; "><b>'.get_the_title($project_id ).'</b></a></p>';
                                                    
                                                    $content = strip_tags(get_the_content($project_id)) ; 
                                                    $short_desc = wp_trim_words($content, 20, '').'...';

                                                $template_email .= '<p style="font-size: 12px; line-height: 19px; margin: 6px 0;">'.$short_desc.'</p>';


                                                $terms = get_the_terms( $project_id, 'service' );
                                                if ( $terms && ! is_wp_error( $terms ) ) : 
                                                    $draught_links = array();
                                                    foreach ( $terms as $term ) {
                                                        $draught_links[] = $term->name;
                                                    }
                                                    $on_draught = join( ", ", $draught_links );  
                                                    $template_email .= '<p style="font-size: 12px; line-height: 19px;  margin-top: 5px;margin-bottom: 0px;"><b>Services:</b> '.$on_draught.'</p>';
                                                endif; 
                                              
                                                $country_project = get_field('country_project', $project_id); 
                                                if(!empty($country_project)){ 
                                                    $template_email .= '<p style="font-size: 12px; line-height: 19px; margin: 5px 0 10px 0;"><b>Country:</b> '.$country_project.'</p>';
                                                }
                                            $template_email .= '</td>';
                                        $template_email .= '</tr>';
                                    } 

                                    $template_email .= '</table>';
                                    $template_email .= '<table style="width: 800px; margin: 0 auto; background: #373737; color: #fff; border: 1px solid #dfdfdf; padding: 20px; display: table; border-top: none;">';
                                        $template_email .= '<tr>';
                                            $template_email .= '<td>';
                                                $template_email .= '<p style="text-align: center; font-size: 12px; line-height: 20px; margin: 0px; color: #fff;">Copyright 2017 Waremill. All rights reserved</p>';
                                                $footer_content_news = get_field('footer_content_news', 'options');
                                                $template_email .= '<p style="text-align: center; font-size: 10px; line-height: 20px; margin: 5px 0; color: #fff;">'.$footer_content_news.'</p>';
                                            $template_email .= '</td>';
                                        $template_email .= '</tr>';
                                    $template_email .= '</table>';
                                $template_email .= '</td>';
                            $template_email .= '</tr>';
                        $template_email .= '</table>'; 

                    $template_email .= '</td>';
                $template_email .= '</tr>';
            $template_email .= '</table>';
        $template_email .= '</body>';
        $template_email .= '</html>';

        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';
        $subject   = get_field('subject_news', 'options');

        $mail = wp_mail( $user_email, $subject, $template_email , $headers );
        if( $mail ){
            return true;
        }else{
            return false;
        }

    }
    wp_reset_postdata(); 
    return false;
}




/********************************************* Cronjob  => news = only for users with this option checked  *********************************************/
/* 
*  Function  : news_with_posts // select all the users with a company associated  and settings -> notify new forum posts (checked)
*  Parameters: -
*  Return    : -
*/
function news_with_posts(){
    // loop throught users
    $role = array('customer', 'contractor'); 
    $args = array(
        'role__in' => $role,
        'meta_query' => array(
            'relation'    => "AND",
            array(
                'key'     => 'associated_company',
                'value'   => '',
                'compare' => '!='
            ),
            array(
                'key'       => 'notify_new_forum_posts',
                'compare'   => '=',
                'value'     => '1',
            )
        )
    );

    $user_query = new WP_User_Query( $args );
    if ( ! empty( $user_query->results ) ) {
        foreach ( $user_query->results as $user ) {
            $user_id = $user->ID;
            $array_services =  get_interesed_categories($user_id);  // get all category when this user published a post     
            if(!empty($array_services) && sizeof($array_services) >0 ){
                template_news_with_posts($array_services, $user);
            }
        }
    }  
    wp_reset_postdata(); 
}


/* 
*  Function  : get_interesed_categories // select all the categories where the user published a post
*  Parameters: user id
*  Return    : array with id cat
*/
function get_interesed_categories($user_ID){
    $args =  array( 
       'ignore_sticky_posts'    => true, 
        'post_type'             => 'post',
        'order'                 => 'DESC',
        'author'                => $user_ID,
        'posts_per_page'        => -1
    );   

    $category_interesed = array();
    $loop = new WP_Query( $args ); 
    if ($loop->have_posts()) { 
        while ($loop->have_posts())  {  $loop->the_post();  
            $post_id = get_the_ID();   
            $terms = get_the_terms( $post_id , 'category' );

            if ( $terms && ! is_wp_error( $terms ) ) {
                $draught_links = array();
                foreach ( $terms as $term ) { $term_link = get_term_link( $term );  
                    $category_interesed[] = $term->term_id ; //$term->name;
                }
            } 
       }
    }
    $result = array_unique($category_interesed);
    return  $result; 
}


/* 
*  Function  : template_news_with_posts // loop in posts according to users categories and send email 
*  Parameters: list for categories (with ids) and user id
*  Return    : true / false (email send / not send )
*/
function template_news_with_posts($array_category, $user ){
    $user_id    = $user->ID;
    $user_email = $user->user_email;

    $how_many_posts_news = get_field('how_many_posts_news', 'options');
    if(empty($how_many_posts_news)){
        $posts_per_page = 7; 
    }else{
        $posts_per_page = $how_many_posts_news; 
    }

    $today = date('Ymd');
    $args =  array( 
        'ignore_sticky_posts'   => true, 
        'post_type'             => 'post',
        'order'                 => 'DESC',
        'author'                => '-'. $user_id ,
        'orderby'               => 'date',
        'posts_per_page'        => $posts_per_page ,
        'category__in'          => $array_category
    );   

    //send email only if we have projects
    $loop = new WP_Query( $args ); 
    if ($loop->have_posts()) { 
        $template_email = "";
        $template_email .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $template_email .= '<html>';
        $template_email .= '<head>';
            $template_email .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
            $template_email .= '<title>'.get_bloginfo('name') . '</title>';
            $template_email .= '<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">';
        $template_email .= '</head>';
       
        $template_email .= '<body style="font-family: \'Lato\', sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;   margin: 0px; padding: 0px; font-size: 14px; color: #2a200c; " topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">';
            $template_email .= '<table style="width: 100%; ">';
            $template_email .= '<tr>';
                $template_email .= '<td>';
                   $template_email .= '<table style="width: 100%; background: #2cade2; padding: 20px;">';
                        $template_email .= '<tr>';
                            $template_email .= '<td>';
                                $template_email .= '<table style="width: 800px; margin: 0 auto; background: #2cade2; display: table;">';
                                    $template_email .= '<tr>';
                                        $template_email .= '<td>';
                                            $image_png = get_field('logo_png', 'options');
                                            $template_email .= '<a href="'.site_url().'" title="">';
                                                $template_email .= '<img src="'.$image_png['url'].'"  style="width: 200px;">';
                                            $template_email .= '</a>';
                                        $template_email .= '</td>';
                                        $template_email .= '<td>';
                                            $template_email .= '<p style="color: #fff;font-size: 12px; text-align: right; margin: 5px 0; line-height: 16px; ">Notification</p>';
                                            $template_email .= '<p style="color: #fff;font-size: 12px; text-align: right; margin: 5px 0; line-height: 16px; ">'.date('d/m/Y').'</p>';
                                        $template_email .= '</td>';
                                    $template_email .= '</tr>';
                                $template_email .= '</table>';
                            $template_email .= '</td>';
                        $template_email .= '</tr>';
                    $template_email .= '</table>';
               
                    $template_email .= '<table style="width: 100%; background: #f3f3f3; padding: 20px;">';
                        $template_email .= '<tr>';
                            $template_email .= '<td>';
                                $template_email .= '<table style="width: 800px; margin: 0 auto; background: #fff;  border: 1px solid #dfdfdf; padding: 20px; display: table; border-bottom: none;">';
                                    $template_email .= '<tr>';
                                        $template_email .= '<td style="border-bottom: 1px solid #dfdfdf; border-collapse: collapse;">';
                                            $text_after_hi_posts = get_field('text_after_hi_posts', 'options');
                                            $template_email .= '<p style="font-size: 12px; line-height: 16px; margin-bottom: 30px; " >Hello '.$user->user_firstname.' '. $user->user_lastname.', '.$text_after_hi_posts.'.</p> ';
                                        $template_email .= '</td>';
                                    $template_email .= ' </tr>';
                                   
                                    while ($loop->have_posts())  {  $loop->the_post();  
                                        $post_id = get_the_ID();    
                                        $template_email .= '<tr>';
                                            $template_email .= '<td style=" vertical-align: top; border-bottom: 1px solid #dfdfdf; border-collapse: collapse; " >';
                                                $template_email .= '<p style="font-size: 13px; line-height: 22px; margin: 4px 0; margin-top: 7px;"><a href="'.get_permalink($post_id).'" title="" style="color: #2cade2; "><b>'.get_the_title($post_id).'</b></a></p>';
                                                $auth = get_post($project_id); // gets author from post 
                                                $authid = $auth->post_author;
                                                $user_author = get_user_by('ID', $authid);
                                                //var_dump($user_author);
                                                $author_complete_name = ucwords($user_author->user_firstname).' '.ucwords($user_author->user_lastname); //$user_author->user_email;
                                                $template_email .= '<p style="font-size: 12px; line-height: 19px; margin: 5px 0 0 0;"><b>Date:</b> '.get_the_date('d/m/Y').'  |  <b>Author:</b> '. $author_complete_name .'</p>';
                                                $template_email .= '<p style="font-size: 12px; line-height: 19px; margin: 6px 0;">'.get_the_excerpt().'</p>';
                                                $terms = get_the_terms( $post_id , 'category' );
                                                if ( $terms && ! is_wp_error( $terms ) ) {
                                                    $draught_links = array();
                                                    foreach ( $terms as $term ) { $term_link = get_term_link( $term );  
                                                        $draught_links[] = $term->name ; //$term->name;
                                                    }
                                                    $on_draught = join( ", ", $draught_links );
                                                    $template_email .= '<p style="font-size: 12px; line-height: 19px; margin: 5px 0 10px 0;"><b>Category:</b> '. $on_draught .'</p>';
                                                } 
                                               
                                           $template_email .= ' </td>';
                                        $template_email .= '</tr>';
                                    }

                                $template_email .= '</table>';
                                $template_email .= '<table style="width: 800px; margin: 0 auto; background: #373737; color: #fff; border: 1px solid #dfdfdf; padding: 20px; display: table; border-top: none;">';
                                     $template_email .= '<tr>';
                                        $template_email .= '<td>';
                                            $template_email .= '<p style="text-align: center; font-size: 12px; line-height: 20px; margin: 0px; color: #fff;">Copyright 2017 Waremill. All rights reserved</p>';
                                            $footer_content_posts = get_field('footer_content_posts', 'options');
                                            $template_email .= '<p style="text-align: center; font-size: 10px; line-height: 20px; margin: 5px 0; color: #fff;">'.$footer_content_posts.'</p>';
                                        $template_email .= '</td>';
                                    $template_email .= '</tr>';
                                $template_email .= '</table>';
                            $template_email .= '</td>';
                        $template_email .= '</tr>';
                    $template_email .= '</table> ';

                $template_email .= '</td>';
            $template_email .= '</tr>';
        $template_email .= '</table>';
        $template_email .= '</body>';
 
         
        $template_email .= '</html>';

        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';
        $subject   = get_field('subject_posts', 'options');

        $mail = wp_mail( $user_email, $subject, $template_email , $headers );
        if( $mail ){
            return true;
        }else{
            return false;
        }

    }
    wp_reset_postdata(); 
    return false;
}


?>