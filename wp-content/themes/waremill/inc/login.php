<?php 

/********************************************* Login form basic for wordpress  *********************************************/
function redirect_login_page() {
    $login_page  = home_url( '/login/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','redirect_login_page');

function login_failed() {
    $login_page  = home_url( '/login/' );
    wp_redirect( $login_page . '?login=failed' );
    exit;
}
add_action( 'wp_login_failed', 'login_failed' );
 
function verify_username_password( $user, $username, $password ) {
    $login_page  = home_url( '/login/' );
    if( $username == "" || $password == "" ) {
        wp_redirect( $login_page . "?login=empty" );
        exit;
    }
}
add_filter( 'authenticate', 'verify_username_password', 1, 3);

function logout_page() {
    $login_page  = home_url( '/login/' );
    wp_redirect( $login_page . "?login=false" );
    exit;
}
add_action('wp_logout','logout_page');


function wpsites_loginout_menu_link( $menu ) {
    $loginout = wp_loginout($_SERVER['REQUEST_URI'], false );
    $menu .= $loginout;
    return $menu;
}
add_filter( 'wp_nav_menu_secondary_items','wpsites_loginout_menu_link' );



function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
      show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'remove_admin_bar');


function cubiq_registration_redirect ($errors, $sanitized_user_login, $user_email) {
    // don't lose your time with spammers, redirect them to a success page
    if ( !isset($_POST['email_login']) || $_POST['email_login'] !== '' ) {
        wp_redirect( home_url('/register/') . '?action=register&success=1' );
        exit;
    }

    if ( !empty( $errors->errors) ) {
        if ( isset( $errors->errors['username_exists'] ) ) {
            wp_redirect( home_url('/login/') . '?action=register&failed=username_exists' );
        } else if ( isset( $errors->errors['email_exists'] ) ) {
            wp_redirect( home_url('/login/') . '?action=register&failed=email_exists' );
        } else if ( isset( $errors->errors['empty_username'] ) || isset( $errors->errors['empty_email'] ) ) {
            wp_redirect( home_url('/register/') . '?action=register&failed=empty' );
        } else if ( !empty( $errors->errors ) ) {
            wp_redirect( home_url('/register/') . '?action=register&failed=generic' );
        }
        exit;
    }
    return $errors; 
}
add_filter('registration_errors', 'cubiq_registration_redirect', 10, 3);

/********************************************* End Login form *********************************************/



/********************************************* Register form *********************************************/
function register_user_form(){
    if (isset($_POST['submit-register']) ) {
        if(wp_verify_nonce($_POST['submit-register'], 'submit-register') == 1) { 

            $register_first_name    = $_POST['register_first_name'];
            $register_family_name   = $_POST['register_family_name'];
            $register_company_name  = $_POST['register_company_name'];
            $register_email         = $_POST['register_email'];
            $register_password      = $_POST['register_password'];
            $register_password2     = $_POST['register_password2'];

            if(isset( $register_first_name ) && strlen($register_first_name ) >0 && 
                isset( $register_family_name ) && strlen( $register_family_name ) > 0 && 
                isset( $register_email ) && strlen($register_email) > 0 && 
                isset( $register_password ) && strlen( $register_password ) > 0 && 
                isset( $register_password2 ) && strlen( $register_password2 ) > 0 ) {
                if(validEmail($register_email) == true){
                    
                    if(email_exists($register_email) == true) {

                        $_POST['message'] = "<p class='ferror fcenter'>Error: The email address is already used.</p>";

                    }else{
                         if(strcmp($register_password, $register_password2) == 0){
                            
                            if(strlen($register_password) > 5 && strlen($register_password2) > 5){
                                
                                // check if email is allready registred 
                                $userdata = array(
                                    'user_login'    =>  $register_email, 
                                    'user_url'      =>  '',
                                    'user_pass'     =>  $register_password2,   
                                    'first_name'    =>  $register_first_name,
                                    'last_name'     =>  $register_family_name,
                                    'user_email'    =>  $register_email,
                                    'nickname'      =>  $register_email ,
                                    'role'          => 'customer'
                                ); 

                                // upload company name in decription field 
                                if(isset($register_company_name) && strlen($register_company_name) > 0){
                                    $userdata['description'] = $register_company_name;
                                }

                                // Create User
                                $user_id = wp_insert_user( $userdata );

                                // Add user meta - false => user not ative 
                                add_user_meta( $user_id, 'user_active', 'false'); 

                                // After register => field & meta update => first login is not here                                         
                                add_user_meta( $user_id, 'user_first_login', 'true');
                                $fist_login = "field_58a6bf22a7e69";
                                update_field(  $fist_login, "1",  "user_".$user_id );

                                // Generate has for activate account 
                                $field_has  = "field_5878c8010a907";
                                $code       = md5( $register_email .''. $register_first_name .''. $register_family_name.''.date('ymdhms')); 
                                update_field(  $field_has, $code,  "user_".$user_id );

                                // Upload field with link for activation 
                                $field_activation = "field_5878dbaf5cc5f";
                                $link_activate =  get_activation_link($user_id, $code); 
                                update_field( $field_activation, $link_activate,  "user_".$user_id );

                                $field_inactive = "field_58be7e89a983f"; 
                                update_field( $field_inactive, "0",  "user_".$user_id );


                                // Send email to activate account
                                if( sendEmailNewUser($register_email, $register_first_name, $register_family_name, $register_company_name, $user_id, $code ) == false){
                                    $_POST['message'] = "<p class='error'>Error: The account was created but the notification message wasn't sent. Please contact us.</p>"; 
                                }else{
                                    $_POST['message'] = "<p class='fsuccess fcenter'>Success: Your account needs confirmation. Please check your email address.</p>";
                                }
                            }else{
                                $_POST['message'] = "<p class='ferror fcenter'>Error: The passwords should contain more than 5 characters.</p>";
                            }
                        }else{
                            $_POST['message'] = "<p class='ferror fcenter'>Error: The passwords do not match.</p>";
                        }
                    }
                }else{
                    $_POST['message'] = "<p class='ferror fcenter'>Error: Invalid email address.</p>";
                }
            }else{
                $_POST['message'] = "<p class='ferror fcenter'>Error: Please check all the fields.</p>";
            }
        }else {
            $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
        }
    }else {
        $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
    }
   echo json_encode(array('message' => $_POST['message'] ));
   die();
}
add_action( 'wp_ajax_register_user_form', 'register_user_form' );
add_action( 'wp_ajax_nopriv_register_user_form', 'register_user_form' );

/********************************************* End Register form *********************************************/

/********************************************* Get link for activate account *********************************************/
/* 
*  Function  : get_activation_link
*  Parameters: user_id, hash for activate account
*  Return    : complet link (with page url) 
*/
function get_activation_link($user_id, $code ){
    $my_account = get_field('account_validation_page', 'options');
    $link_activate = get_permalink($my_account->ID) ."?id=".$user_id."&limit=".$code; 
    return $link_activate;
}

/********************************************* Send Email Register Form *********************************************/
/* 
*  Function  : sendEmailNewUser
*  Parameters: email, first_name, last_name, company, user_id, hash for generate the link 
*  Return    : boolean (email sent)
*/
function sendEmailNewUser($register_email, $register_first_name, $register_family_name, $register_company_name, $user_id, $code ){

    $subject            = get_field('subject_create_new_account', 'options');   // subject
    $content_message    = get_field('create_new_account', 'options');           // template content
    $link_activate      = get_activation_link($user_id, $code );                // generate activation linke 
    $content_message    = str_replace('{name}', $register_first_name ." " . $register_family_name, $content_message);
    $content_message    = str_replace('{link}', "<a href='".$link_activate."'>activation link</a>" , $content_message);
    $content_message    = str_replace('{first_name}', $register_first_name, $content_message);
    $content_message    = str_replace('{last_name}', $register_family_name, $content_message);
    $content_message    = str_replace('{email}', $register_email, $content_message);
    $content_message    = str_replace('{username}', $register_email, $content_message);

    if(isset( $register_company_name) && strlen($register_company_name) > 0){
        $content_message    = str_replace('{company}', $register_company_name, $content_message);
    }else{
        $content_message    = str_replace('{company}', '-', $content_message);
    }

    $message = get_basic_template_email($content_message);
    $address_from = get_bloginfo('admin_email'); 
    $headers[] = 'From: '.get_bloginfo('name').' <'. $address_from .'>';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    $mail  = wp_mail($register_email,  $subject  , $message, $headers);   // email for admin
   
    if (!$mail)  {
        return false;
    }else{
        return true;
    }  
}

/********************************************* Validate hash with user_id *********************************************/
/* 
*  Function  : check_id_with_hash => check if user match with a hash
*  Parameters: user_id, hash
*  Return    : boolean
*/
function check_id_with_hash($id, $hash){
    $hash_is = get_field('hash', 'user_'.$id);
    if(!empty($hash_is)){
        if(strcmp($hash_is, $hash) == 0){
            return true; 
        }else{
            return false; 
        }
    }else {
        return false; 
    }
}

/********************************************* Check if user is customer *********************************************/
/* 
*  Function  : change_role_tempmember_to_client => check if user is customer by user_id
*  Parameters: user_id
*  Return    : boolean
*/
function change_role_tempmember_to_client($id){

    $user_id = wp_update_user( array( 'ID' => $id, 'role' => 'customer' ) );
    if ( is_wp_error( $user_id ) ) {  // There was an error, probably that user doesn't exist.  
       return false; 
    } else { // Success!
        return true; 
    }       
    return false; 
}

/********************************************* Get meta user_active *********************************************/
/* 
*  Function  : get_user_meta_active => get meta value from user_active field
*  Parameters: user_id
*  Return    : string
*/
function get_user_meta_active($id){
    $meta_val = '';
    $meta_val = get_user_meta( $id, 'user_active', true ); 
    return $meta_val;
}

/********************************************* Get meta user_first_login *********************************************/
/* 
*  Function  : get_user_meta_first_login => get meta value from user_first_login field
*  Parameters: user_id
*  Return    : string
*/
function get_user_meta_first_login($id){
    $meta_val = '';
    $meta_val = get_user_meta( $id, 'user_first_login', true ); 
    return $meta_val;
}

/********************************************* Restrict access to a specific user role (?) *********************************************/
add_filter( 'authenticate', 'myplugin_auth_signon', 30, 3 );
function myplugin_auth_signon( $user, $username, $password ) {
    $restrict_role = 'member';   
    $user_role = $user->roles;
    //var_dump($user_role);
    if (in_array($restrict_role, (array)$user_role) && strcmp(get_user_meta_active( $user->ID), 'false') == 0 ) {    
        $user = new WP_Error('error', __( 'Your account was not approved yet. Please check your email address or contact us.'));
    }
    return $user;
}  

/********************************************* Get user_roles *********************************************/
/* 
*  Function  : get_user_roles_by_user_id => get user roles
*  Parameters: user_id
*  Return    : array
*/
function get_user_roles_by_user_id( $user_id ) {
    $user = get_userdata( $user_id );
    return empty( $user ) ? array() : $user->roles;
}


/********************************************* Check role for a user *********************************************/
/* 
*  Function  : is_user_in_role => check if a user has a role
*  Parameters: user_id, role to check
*  Return    : boolean
*/
function is_user_in_role( $user_id, $role  ) {
    return in_array( $role, get_user_roles_by_user_id( $user_id ) );
}

/********************************************* Check role for a current_user *********************************************/
/* 
*  Function  : check_role_current_user => check if a current user has a role
*  Parameters: role to check
*  Return    : boolean
*/
function check_role_current_user($role){
    $user = wp_get_current_user();
    if ( in_array( $role, (array) $user->roles ) ) {
        return true;
    }
    return false; 
}

/********************************************* Login form *********************************************/
function login_user_form(){
    $ok_redirect = false; 
    $link_redirect = '';
    if (isset($_POST['submit-login']) || isset($_POST['submit-login-page']) ) {
        if(wp_verify_nonce($_POST['submit-login'], 'submit-login') == 1 || wp_verify_nonce($_POST['submit-login-page'], 'submit-login-page') == 1) { 

            $user_info      = $_POST['login_email'];
            $user_password  = $_POST['login_password'];

            if ( username_exists($user_info) == false  ){ 
                if(email_exists($user_info) == false) {
                    $_POST['message'] = "<p class='ferror fcenter'>Error: This user does not exist.</p>";
                    $ok_redirect = false; 
                    $ok_exist = false; 
                }else{
                    $ok_exist = true; 
                }
            }else {
                $ok_exist = true; 
            }

            if($ok_exist == true){
                $user = get_user_by( 'login', $user_info );
                //  $check = wp_authenticate_username_password( NULL, $user_info , $user_password );

                // check if account is valid 
                if(strcmp(get_user_meta_active( $user->ID ), "false") == 0){ 
                    $_POST['message'] = "<p class='ferror fcenter'>Error: Your account is not valid.</p>";
                }else{
                    if ( $user && !wp_check_password( $user_password, $user->data->user_pass, $user->ID) ){ // username not match with password
                        $_POST['message'] = "<p class='ferror fcenter'>Error: Username and password do not match.</p>";
                        $ok_redirect = false; 

                    }else { // username + password match 

                        if(strcmp(get_user_meta_active( $user->ID ), "false") == 0){
                            $_POST['message'] = "<p class='ferror fcenter'>Error: Sorry, but your account has not been validated.</p>";
                            $ok_redirect = false;   
                        }else{
                            $my_account = get_field('my_account', 'options'); 
                            $creds = array(
                                'user_login'    => $user_info,
                                'user_password' => $user_password
                            );
                         
                            $user = wp_signon( $creds, false ); // login 

                            if ( is_wp_error( $user ) ) {
                                $_POST['message'] = "<p class='ferror fcenter'>Error: " . $user->get_error_message() ."</p>";
                                $ok_redirect = false; 
                            } else {
                                // redirect  
                                // check if user create project in this  section and associate the project with him
                                associate_draft();

                                if(check_user_customer_param($user) == true){

                                    $check_field_user_first_login   = get_field('first_login', 'user_'.  $user->ID); 
                                    if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login( $user->ID), "true") == 0) { // first login => redirect page switch role
                                        $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                    }else{                                                                                                               // not first login => redirect page dashboard         
                                        $pg_id = get_field('client_dashboard', 'options')->ID;
                                    }

                                    $link_redirect = get_permalink( $pg_id );
                                }else if(check_user_contractor_param($user) == true){

                                    $check_field_user_first_login   = get_field('first_login', 'user_'.  $user->ID); 
                                    if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login( $user->ID), "true") == 0) { // first login => redirect page switch role
                                        $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                    }else{   
                                        $pg_id = get_field('contractor_dashboard', 'options')->ID;
                                    }

                                    $link_redirect = get_permalink( $pg_id );
                                }else{
                                    $link_redirect = user_admin_url();
                                }
                            
                                $_POST['message'] = "<p class='fsuccess fcenter'>Success: You will be redirected immediately to your control panel.</p>";
                                $ok_redirect = true; 
                            }
                        }
                    }
                }
            }

        }else {
            $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
            $ok_redirect = false; 
        }
    }else {
        $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
        $ok_redirect = false; 
    }

   echo json_encode(array('message' => $_POST['message'], 'redirect' => $ok_redirect , 'link' => $link_redirect ));
   die();
}
add_action( 'wp_ajax_login_user_form', 'login_user_form' );
add_action( 'wp_ajax_nopriv_login_user_form', 'login_user_form' );

/********************************************* Lost Password form *********************************************/
function forgot_user_form(){
   
     if (isset($_POST['submit-forgot']) ) {
        if(wp_verify_nonce($_POST['submit-forgot'], 'submit-forgot') == 1) { 
            $forgot_email = $_POST["forgot_email"];  
            if(isset($forgot_email) && validEmail($forgot_email)) {
                $user = get_user_by( 'email', $forgot_email);
                if(strcmp(get_user_meta_active( $user->ID ), "false") == 0){
                    $_POST['message'] = "<p class='ferror fcenter'>Error: Your account is not valid.</p>";
                }else{
                    if ( ! empty( $user ) ) {
                        $random_password = wp_generate_password( 15, false );
                        $update_user = wp_update_user( array (
                                'ID' => $user->ID, 
                                'user_pass' => $random_password
                            )
                        );
                        if( $update_user ) {
                            
                            $page_login_id = get_field('login_page_redirect', 'options')->ID;
                            $link = get_permalink($page_login_id); 

                            $subject = get_field('subject_reset_password', 'options');
                            $content_message    = get_field('reset_password', 'options');
                            $content_message    = str_replace('{name}', $user->first_name.' '. $user->user_lastname, $content_message);
                            $content_message    = str_replace('{password}', $random_password, $content_message);
                            $content_message    = str_replace('{link}', '<a href="'.$link.'">Login</a>', $content_message);

                            $message = get_basic_template_email($content_message);

                            $headers[] = 'MIME-Version: 1.0' . "\r\n";
                            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers[] = "X-Mailer: PHP \r\n";
                            $headers[] = 'From: '.get_bloginfo('name').' <'. get_option( 'admin_email' ) .'>';

                            $mail = wp_mail( $forgot_email, $subject, $message, $headers );
                            if( $mail ){
                                $_POST['message'] = "<p class='fsuccess fcenter'>Success: Please check your e-mail to see your new password.</p>";
                            }else{
                                $_POST['message'] = "<p class='ferror fcenter'>Error: Something went wrong on updating your account. Please try again later.</p>"; 
                            }
                             
                        } else {
                            $_POST['message'] = "<p class='ferror fcenter'>Error: Something went wrong on updating your account. Please try again later.</p>"; 
                        }
                    }else{
                        $_POST['message'] = "<p class='ferror fcenter'>Error: Invalid email address.</p>";
                    }
                }
               
            }else{
                $_POST['message'] = "<p class='ferror fcenter'>Error: Invalid email address.</p>";
            } 
        }else {
            $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
        }
    }else {
        $_POST['message'] = "<p class='ferror fcenter'>Error: Please try again later.</p>";
    }

    echo json_encode(array('message' => $_POST['message']));
    die();
}
add_action( 'wp_ajax_forgot_user_form', 'forgot_user_form' );
add_action( 'wp_ajax_nopriv_forgot_user_form', 'forgot_user_form' );

/********************************************* Add columns dashboard roles and meta *********************************************/
add_action( 'show_user_profile', 'display_user_active' );
add_action( 'edit_user_profile', 'display_user_active' );

function display_user_active( $user ) { ?>
    <h3>User Meta Fields</h3>
    <table class="form-table">
        <tr>
            <th><label>Active</label></th>
            <td><input type="text" value="<?php echo get_user_meta( $user->ID, 'user_active', true ); ?>" class="regular-text" readonly=readonly /></td>
        </tr>
    </table>
     <table class="form-table">
        <tr>
            <th><label>First Login</label></th>
            <td><input type="text" value="<?php echo get_user_meta( $user->ID, 'user_first_login', true ); ?>" class="regular-text" readonly=readonly /></td>
        </tr>
    </table>
    <?php
}

/********************************************* Add columns to User panel list page *********************************************/
function add_user_columns($column) {
    $column['active'] = 'Account';
    $column['user_role'] = 'User Roles';

    return $column;
}
add_filter( 'manage_users_columns', 'add_user_columns' );

/********************************************* Add the data *********************************************/
function add_user_column_data( $val, $column_name, $user_id ) {
    $user = get_userdata($user_id);

    switch ($column_name) {
        case 'active' :
            return  get_user_meta($user_id, 'user_active', true ); //$user->active;
            break;
        case 'first_login' :
            return  get_user_meta($user_id, 'user_first_login', true ); //$user->user_first_login;
            break;
        case 'user_role' :
            $user_info = get_userdata( $user_id );        
            return   implode(', ', $user_info->roles); //$user->active;
            break;
        default:
    }
    return;
}
add_filter( 'manage_users_custom_column', 'add_user_column_data', 10, 3 );



/********************************************* Login & Register with Google + *********************************************/
function login_user_google(){
    
    if(isset($_GET['action']) && strcmp($_GET['action'], "login_user_google")==0){
        
        $google_first_name  = $_GET['name'];
        $google_last_name   = $_GET['last'];
        $google_email       = $_GET['email'];
        $google_type        = $_GET['type'];
        $google_user_id     = $_GET['user_id'];
        $ok_redirect        = false; 
        $link_redirect      = '';

        if(isset($google_first_name) && strlen($google_first_name) > 0 && 
            isset($google_last_name) && strlen($google_last_name) > 0 && 
                isset($google_email) && validEmail($google_email) == true ){

                if(isset($google_type ) && strlen($google_type )>0){

                    switch ($google_type) {
                        case 'register':
                            if ( username_exists($google_email) == false  && email_exists($google_email) == false  ) {
                                $random_password = wp_generate_password( 15, false );
                                $userdata = array(
                                    'user_login'    =>  $google_email, 
                                    'user_url'      =>  'https://plus.google.com/'.$google_user_id ,
                                    'user_pass'     =>  $random_password ,  
                                    'first_name'    =>  $google_first_name,
                                    'last_name'     =>  $google_last_name,
                                    'user_email'    =>  $google_email,
                                    'nickname'      =>  $google_email ,
                                    'role'          => 'customer'
                                ); 

                               
                                /*--------------  Create User  --------------*/
                                $user_id = wp_insert_user( $userdata );
                                add_user_meta( $user_id, 'user_active', 'true'); 
                                if ( ! is_wp_error( $user_id ) ) {
                                    $user = get_user_by( 'ID', $user_id );

                                    wp_clear_auth_cookie();
                                    wp_set_current_user ( $user_id );
                                    wp_set_auth_cookie  (  $user_id );
                                    

                                    // after register => field & meta update => first login is not here                                        
                                    add_user_meta( $user_id, 'user_first_login', 'true');
                                    $fist_login = "field_58a6bf22a7e69";
                                    update_field(  $fist_login, "1",  "user_".$user_id );

                                    // redirect 
                                    // check if is first login 

                                    $field_inactive = "field_58be7e89a983f"; 
                                    update_field( $field_inactive, "0",  "user_".$user_id );


                                    // ============================************=======================
                                     // check if user create project in this  section and associate the project with him
                                    associate_draft();    
                                    if(check_user_customer_param($user) == true){

                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{   
                                            $pg_id = get_field('client_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );
                                    }else if(check_user_contractor_param($user) == true){


                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{   
                                            $pg_id = get_field('contractor_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );

                                    }else{
                                        $link_redirect = user_admin_url();
                                    }
                                
                                    $_POST['message'] = "<p class='fsuccess fcenter'>Success: You will be redirected immediately to your control panel.</p>";
                                    $ok_redirect = true; 

                                }else{
                                    $_POST['message'] = "<p class='ferror fcenter'>Error: The account wasn't created .</p>";
                                    $ok_redirect = false; 
                                }
                                break;
                            } // else = login
                            /*else{

                                    $_POST['message'] = "<p class='ferror fcenter'>Error: This user exist. Please use the login form.</p>";
                                    $ok_redirect = false; 
                               
                            }*/
                           
                        case 'login':
                            // check if is an account with this username or email address
                            if ( username_exists($google_email) == true   ) { //  && email_exists($google_email) == true ====> do not use this roule .. people may change the email address from settings!
                                $user = get_user_by( 'login', $google_email); // check by username ==> people may change the emails address
                                if(strcmp(get_user_meta_active( $user->ID ), "false") == 0){  // check if account is valid 
                                    $_POST['message'] = "<p class='ferror fcenter'>Error: Your account is not valid.</p>";
                                    $ok_redirect = false; 
                                }else{ // acount valid 
                                    
                                    /*
                                    $my_account = get_field('my_account', 'options'); 
                                    $creds = array(
                                        'user_login'    => $google_email,
                                        'user_password' => $user_password
                                    );
                                 
                                    $user = wp_signon( $creds, false );
                                    */

                                    wp_clear_auth_cookie();
                                    wp_set_current_user ( $user->ID );
                                    wp_set_auth_cookie  ( $user->ID );
                                    /*
                                    if ( is_wp_error( $user ) ) {
                                        $_POST['message'] = "<p class='ferror fcenter'>Error: " . $user->get_error_message() ."</p>";
                                        $ok_redirect = false; 
                                    } else { */

                                     // check if user create project in this  section and associate the project with him
                                    associate_draft();    

                                    // redirect    ============================************=======================
                                    if(check_user_customer_param($user) == true){

                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{   
                                            $pg_id = get_field('client_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );


                                    }else if(check_user_contractor_param($user) == true){


                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{   
                                            $pg_id = get_field('contractor_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );

                                    }else{
                                        $link_redirect = user_admin_url();
                                    }
                                
                                    $_POST['message'] = "<p class='fsuccess fcenter'>Success: You will be redirected immediately to your control panel.</p>";
                                    $ok_redirect = true; 
                                    //}

                                }
                            }else{

                                // if user not exist => create user 
                                
                                $random_password = wp_generate_password( 15, false );
                                $userdata = array(
                                    'user_login'    =>  $google_email, 
                                    'user_url'      =>  'https://plus.google.com/'.$google_user_id ,
                                    'user_pass'     =>  $random_password ,  
                                    'first_name'    =>  $google_first_name,
                                    'last_name'     =>  $google_last_name,
                                    'user_email'    =>  $google_email,
                                    'nickname'      =>  $google_email ,
                                    'role'          => 'customer'
                                ); 

                               
                                /*--------------  Create User  --------------*/
                                $user_id = wp_insert_user( $userdata );
                                add_user_meta( $user_id, 'user_active', 'true'); 
                                if ( ! is_wp_error( $user_id ) ) {
                                    $user = get_user_by( 'ID', $user_id );

                                    wp_clear_auth_cookie();
                                    wp_set_current_user ( $user_id );
                                    wp_set_auth_cookie  (  $user_id );

                                      // after register => field & meta update => first login is not here                                        
                                    add_user_meta( $user_id, 'user_first_login', 'true');
                                    $fist_login = "field_58a6bf22a7e69";
                                    update_field(  $fist_login, "1",  "user_".$user_id );

                                     $field_inactive = "field_58be7e89a983f"; 
                                    update_field( $field_inactive, "0",  "user_".$user_id );
                                    // redirect   ============================************=======================
                                    // check if user create project in this  section and associate the project with him
                                    associate_draft();    
                                    if(check_user_customer_param($user) == true){

                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{   
                                            $pg_id = get_field('client_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );

                                    }else if(check_user_contractor_param($user) == true){


                                        $check_field_user_first_login   = get_field('first_login', 'user_'. $user_id); 
                                        if( strcmp($check_field_user_first_login , "1" ) == 0 && strcmp(get_user_meta_first_login($user_id), "true") == 0) { // first login => redirect page switch role
                                            $pg_id = get_field('page_for_switching_roles', 'options')->ID;
                                        }else{ 
                                            $pg_id = get_field('contractor_dashboard', 'options')->ID;
                                        }
                                        $link_redirect = get_permalink( $pg_id );

                                    }else{
                                        $link_redirect = user_admin_url();
                                    }
                                
                                    $_POST['message'] = "<p class='fsuccess fcenter'>Success: You will be redirected immediately to your control panel.</p>";
                                    $ok_redirect = true; 

                                }else{
                                    $_POST['message'] = "<p class='ferror fcenter'>Error: The account wasn't created .</p>";
                                    $ok_redirect = false; 
                                }

                                
                                //$_POST['message'] = "<p class='ferror fcenter'>Error: This user does not exist. Please use the register form.</p>";
                                //$ok_redirect = false; 
                            }
                            break;
                        default:
                            $_POST['message'] = "<p class='ferror fcenter'>Error: Something went wrong authentication. Please try again later.</p>";
                            $ok_redirect = false; 
                            break;
                    }
                }else{
                    $_POST['message'] = "<p class='ferror fcenter'>Error: Something went wrong authentication. Please try again later.</p>";
                    $ok_redirect = false; 
                }
        }else{
            $_POST['message'] = "<p class='ferror fcenter'>Error: Something went wrong authentication. Please try again later.</p>";
            $ok_redirect = false; 
        }
    }
    echo json_encode(array('message' => $_POST['message'] , 'redirect' => $ok_redirect , 'link' => $link_redirect ));
    die();
}
add_action( 'wp_ajax_login_user_google', 'login_user_google' );
add_action( 'wp_ajax_nopriv_login_user_google', 'login_user_google' );


/********************************************* Get email by user role  *********************************************/
/* 
*  Function  : type_of_menu => check user role and get the menu 
*  Parameters: 
*  Return    : menu (with echo)
*/
function type_of_menu(){
    if(check_user_customer() == true ){ 
        wp_nav_menu( array( 'theme_location' => 'header-menu-customer','container'=>false,'menu_class' => 'home-header-menu' ) ); 
    }else if(check_user_contractor() == true ){
        wp_nav_menu( array( 'theme_location' => 'header-menu-contractor','container'=>false,'menu_class' => 'home-header-menu' ) ); 
    }
}

/********************************************* Get user type of register  *********************************************/
/* 
*  Function  : get_user_register_type => check user register type by url field
*  Parameters: 
*  Return    : int ( 1 - facebook, 2 - google, 3 - normal )
*/
function get_user_register_type(){
    $user = wp_get_current_user(); 
    $userid = get_current_user_id();  

    $user_url = $user->user_url;

    if(strpos( $user_url , 'facebook') !== false){
        return 1;
    }else if( strpos( $user_url , 'plus.google') !== false ){
        return 2;
    }else{
        return 3;
    }
}



?>