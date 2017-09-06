<?php

require_once 'framework/framework.php';
include(TEMPLATEPATH .'/inc/mailchimp_api/MailChimp.php'); 
use \DrewM\MailChimp\MailChimp;

/**
 * This is required for $_SESSION variables to work
 */
session_start();

define('THEME_TEXT_DOMAIN', 'waremill');

# General (theme setup, enqueue css/js, hide admin bar)
require_once TEMPLATEPATH . '/inc/general.php';

# Add Widgets
require_once TEMPLATEPATH. '/inc/widgets.php';

# Add Shortcodes
require_once TEMPLATEPATH. '/inc/shortcodes.php';

# Post Types
require_once TEMPLATEPATH. '/inc/custom-post-type-taxonomies.php';

# Login function
require_once TEMPLATEPATH. '/inc/login.php';

# Customer function
require_once TEMPLATEPATH. '/inc/account-customer.php';

# Contractor function
require_once TEMPLATEPATH. '/inc/account-contractor.php';

# Switch Roles
require_once TEMPLATEPATH. '/inc/switch-roles.php';

# bid function
require_once TEMPLATEPATH. '/inc/bid.php';

# Country - Selected function
require_once TEMPLATEPATH. '/inc/list-country-selected.php';

# discussion functions
require_once TEMPLATEPATH. '/inc/messages.php';

# emails functions
require_once TEMPLATEPATH. '/inc/emails.php';

# post functions
require_once TEMPLATEPATH. '/inc/post.php';

# admin function
require_once TEMPLATEPATH. '/inc/admin-functions.php';

news_with_new_projects();

function todo_notice() {
 ?>
 <div class="updated">
    <p>TODO:</p>
    <p>Remove list of users from media after import </p>
    <p>Change email for cronjobs => /inc/emails.php </p>
    <p>Change client ID for /inc/4-user-register-login.js</p>
    <p>Remove me  <?php// from echo __FILE__;?></p>
 </div>
 <?php
}
add_action( 'admin_notices', 'todo_notice' );



function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );


function enqueue_comments_reply() {
	if( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1) ) {
		wp_enqueue_script( 'comment-reply', 'wp-includes/js/comment-reply', array(), false, true );
	}
}
add_action(  'wp_enqueue_scripts', 'enqueue_comments_reply' );


function disable_comment_url($fields) { 
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','disable_comment_url');


function wpb_add_google_fonts() {
	wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i', false ); 
}

add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

// accept svg
function cc_mime_types( $mimes ){ 
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function add_localize_script()  {
?>
<script type="text/javascript">
    var jsHomeUrl = '<?php echo home_url(); ?>';
    var ajaxUrl = "<?php echo admin_url( 'admin-ajax.php' ) ?>";
</script>
<?php
}
add_action('wp_head', 'add_localize_script', 999);


// validate Email
function validEmail($email){
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            return false;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                return false;
            }
        }
    }

    return true;
}


function phonenr($args){
    $str = $args; 
    $str = preg_replace('/[^+0-9a-zA-Z]/', '', $str); 
    return $str; 
}

function validURL($url){
    // if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
    if(preg_match('/^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/', $url) ){
        return false;
    
    }else{
        return true;
    }
}


function my_acf_init() {
    $api_google_maps = get_field('api_google_maps', 'options');
    acf_update_setting('google_api_key', $api_google_maps);
}

add_action('acf/init', 'my_acf_init');


// add functionality => search by id 

function my_search_pre_get_posts($query){ 
    // Verify that we are on the search page that that this came from the event search form
    if($query->query_vars['s'] != '' && is_search()) {
        // If "s" is a positive integer, assume post id search and change the search variables
        if(absint($query->query_vars['s'])){
            // Set the post id value
            $query->set('p', $query->query_vars['s']);

            // Reset the search value
            $query->set('s', '');
        }
    }
}
// Filter the search page
add_filter('pre_get_posts', 'my_search_pre_get_posts'); 


// [bartag foo="foo-value"]
function button_func( $atts ) {
    $a = shortcode_atts( array(
        'url'   => '',
        'title' => '',
        'align' => ''
    ), $atts );

    $content = "<div class='divbutton ".$a['align']."' ><a href='".$a['url']."' title='' class='button'>".$a['title']."</a></div>";

    return $content; 
}
add_shortcode( 'button', 'button_func' );





add_filter('acf/update_value/type=date_time_picker', 'my_update_value_date_time_picker', 10, 3);

function my_update_value_date_time_picker( $value, $post_id, $field ) {
    
    return strtotime($value);
    
}


/* Comments */

function twentytwelve_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
        // Display trackbacks differently than normal comments.
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
        // Proceed with normal comments.
        global $post;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <header class="comment-meta comment-author vcard">
                <?php
                    // echo get_avatar( $comment, 44 );
                    printf( '<b class="fn">%1$s</b> %2$s',
                        get_comment_author_link(),
                        // If current post author is also comment author, make it known visually.
                        ( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
                    );
                    printf( ' on <a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                        esc_url( get_comment_link( $comment->comment_ID ) ),
                        get_comment_time( 'c' ),
                        /* translators: 1: date, 2: time */
                        sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
                    );
                ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </header><!-- .comment-meta -->
            <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
            <?php endif; ?>
            <section class="comment-content comment">
                <?php comment_text(); ?>
                <?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
            </section><!-- .comment-content -->
        </article><!-- #comment-## -->
    <?php
        break;
    endswitch; // end comment_type check
}
function alter_comment_form_fields($fields){
    
    $fields['url'] = '';  //removes website field
    return $fields;
}
add_filter('comment_form_default_fields','alter_comment_form_fields');

function my_comment_form_before() {
    ob_start();
}
add_action( 'comment_form_before', 'my_comment_form_before' );
function my_comment_form_after() {
    $html = ob_get_clean();
    $html = preg_replace(
        '/<h3 id="reply-title"(.*)>(.*)<\/h3>/',
        '<p id="reply-title"\1>\2</p>',
        $html
    );
    echo $html;
}
add_action( 'comment_form_after', 'my_comment_form_after' );
 
add_filter( 'img_caption_shortcode', 'my_img_caption_shortcode', 10, 3 );

function my_img_caption_shortcode( $empty, $attr, $content ){
    $attr = shortcode_atts( array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => ''
    ), $attr );

    if ( 1 > (int) $attr['width'] || empty( $attr['caption'] ) ) {
        return '';
    }

    if ( $attr['id'] ) {
        $attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '" ';
    }

    return '<div ' . $attr['id']
    . 'class="wp-caption ' . esc_attr( $attr['align'] ) . '" '
    . 'style="max-width: ' . ( 10 + (int) $attr['width'] ) . 'px;">'
    . do_shortcode( $content )
    . '<h3 class="wp-caption-text">' . $attr['caption'] . '</h3>'
    . '</div>';

} 

function custom_comment_form( $args = array(), $post_id = null ) {
    if ( null === $post_id )
        $post_id = get_the_ID();

    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $args = wp_parse_args( $args );
    if ( ! isset( $args['format'] ) )
        $args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

    $req      = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );
    $html5    = 'html5' === $args['format'];
    $fields   =  array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>',
        'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                    '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . $html_req  . ' /></p>',
        'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
                    '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
    );

    $required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

    $fields = apply_filters( 'comment_form_default_fields', $fields );
    $defaults = array(
        'fields'               => $fields,
        'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Your message', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea></p>',
        'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
        'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
        'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>'. ( $req ? $required_text : '' ) . '</p>',
        'comment_notes_after'  => '<p class="form-allowed-tags" id="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'class_submit'         => 'submit',
        'name_submit'          => 'submit',
        'title_reply'          => __( 'Leave a Reply' ),
        'title_reply_to'       => __( 'Leave a Reply to %s' ),
        'cancel_reply_link'    => __( 'Cancel reply' ),
        'label_submit'         => __( 'Post Comment' ),
        'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
        'format'               => 'xhtml',
    );

    $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

        if ( comments_open( $post_id ) ) : ?>
            <?php
            do_action( 'comment_form_before' );
            ?>
            <div id="respond" class="comment-respond">
                <div id="reply-title" class="comment-reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></div>
                <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
                    <?php echo $args['must_log_in']; ?>
                    <?php
                    do_action( 'comment_form_must_log_in_after' );
                    ?>
                <?php else : ?>
                    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="comment-form"<?php echo $html5 ? ' novalidate' : ''; ?>>
                        <?php
                        do_action( 'comment_form_top' );
                        ?>
                        <?php if ( is_user_logged_in() ) : ?>
                            <?php
                            echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
                            ?>
                            <?php
                            do_action( 'comment_form_logged_in_after', $commenter, $user_identity );
                            ?>
                        <?php else : ?>
                            <?php echo $args['comment_notes_before']; ?>
                            <?php
                            do_action( 'comment_form_before_fields' );
                            foreach ( (array) $args['fields'] as $name => $field ) {
                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
                            }
                            do_action( 'comment_form_after_fields' );
                            ?>
                        <?php endif; ?>
                        <?php
                        echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
                        ?>
                        <?php echo $args['comment_notes_after']; ?>

                        <?php
                        $submit_button = sprintf(
                            $args['submit_button'],
                            esc_attr( $args['name_submit'] ),
                            esc_attr( $args['id_submit'] ),
                            esc_attr( $args['class_submit'] ),
                            esc_attr( $args['label_submit'] )
                        );
                        $submit_button = apply_filters( 'comment_form_submit_button', $submit_button, $args );

                        $submit_field = sprintf(
                            $args['submit_field'],
                            $submit_button,
                            get_comment_id_fields( $post_id )
                        );
                        echo apply_filters( 'comment_form_submit_field', $submit_field, $args );
                        do_action( 'comment_form', $post_id );
                        ?>
                    </form>
                <?php endif; ?>
            </div>
            <?php
            do_action( 'comment_form_after' );
        else :
            do_action( 'comment_form_comments_closed' );
        endif;
}



/********************************************* customer: update settings -> update_notifications  *********************************************/
function update_notifications_customer(){

    $field_new_bid      = "field_58c92b1a5aae0";
    $field_new_message  = "field_58c8f10aa0328";
    $field_new_forum    = "field_58c8f13fa032a"; 
    $field_newsletter   = "field_58c8f153a032b"; 

    $user = wp_get_current_user(); 
    $user_ID = $user->ID;
    
    // user_new_bid
    if(isset($_POST['user_new_bid']) && strcmp($_POST['user_new_bid'], "on")==0){
        update_field( $field_new_bid, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_bid, "0", 'user_'.$user_ID );
    }

    // user_new_message
    if(isset($_POST['user_new_message']) && strcmp($_POST['user_new_message'], "on")==0){
        update_field( $field_new_message, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_message, "0", 'user_'.$user_ID );
    }

    // user_new_forum_posts
    if(isset($_POST['user_new_forum_posts']) && strcmp($_POST['user_new_forum_posts'], "on")==0){
        update_field( $field_new_forum, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_forum, "0", 'user_'.$user_ID );
    }

    // user_new_newsletter
    if(isset($_POST['user_new_newsletter']) && strcmp($_POST['user_new_newsletter'], "on")==0){
        update_field( $field_newsletter, "1", 'user_'.$user_ID );
 
        // add user in a specific list 
        $api_key = get_field('api_key_mailchimp', 'options'); //replace with your API key
        $list_id = get_field('list_id_mailchimp', 'options'); //replace with the list id you're adding the email to
     
        //=========================
        if(!empty($api_key) && !empty($list_id)){

            $MailChimp = new MailChimp($api_key);
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $_POST['update_email_customer'],
                'merge_fields'  => [
                        'FNAME'     => $_POST['update_first_name_customer'],
                        'LNAME'     => $_POST['update_last_name_customer']
                    ],
                'status'        => 'subscribed',
            ]);
        }

    }else{
        update_field( $field_newsletter, "0", 'user_'.$user_ID );
        // add user in a specific list 
        $api_key = get_field('api_key_mailchimp', 'options'); //replace with your API key
        $list_id = get_field('list_id_mailchimp', 'options'); //replace with the list id you're adding the email to
     
        //=========================
        if(!empty($api_key) && !empty($list_id)){
        
            $MailChimp = new MailChimp($api_key);
            $subscriber_hash = $MailChimp->subscriberHash($_POST['update_email_customer']);
            $MailChimp->delete("lists/$list_id/members/$subscriber_hash");
        }
    }
}

/********************************************* contractor: update settings -> update_notifications  *********************************************/
function update_notifications_contractor(){

    $field_new_procurement  = "field_58c8f0e3a0327";
    $field_new_message      = "field_58c8f10aa0328";
    $field_get_hired        = "field_58c8f12ba0329";
    $field_new_forum_posts  = "field_58c8f13fa032a";
    $field_newsletter       = "field_58c8f153a032b";

    $user = wp_get_current_user(); 
    $user_ID = $user->ID;   

    // user_new_procurement
    if(isset($_POST['user_new_procurement']) && strcmp($_POST['user_new_procurement'], "on")==0){
        update_field( $field_new_procurement, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_procurement, "0", 'user_'.$user_ID );
    }

    // user_new_message
    if(isset($_POST['user_new_message']) && strcmp($_POST['user_new_message'], "on")==0){
        update_field( $field_new_message, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_message, "0", 'user_'.$user_ID );
    }

    // user_new_hired
    if(isset($_POST['user_new_hired']) && strcmp($_POST['user_new_hired'], "on")==0){
        update_field( $field_get_hired, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_get_hired, "0", 'user_'.$user_ID );
    }

    // user_new_forum_posts
    if(isset($_POST['user_new_forum_posts']) && strcmp($_POST['user_new_forum_posts'], "on")==0){
        update_field( $field_new_forum_posts, "1", 'user_'.$user_ID );
    }else{
        update_field( $field_new_forum_posts, "0", 'user_'.$user_ID );
    }

    // user_new_newsletter
    if(isset($_POST['user_new_newsletter']) && strcmp($_POST['user_new_newsletter'], "on")==0){
        update_field( $field_newsletter, "1", 'user_'.$user_ID );

        // add user in a specific list 
        $api_key = get_field('api_key_mailchimp', 'options'); //replace with your API key
        $list_id = get_field('list_id_mailchimp', 'options'); //replace with the list id you're adding the email to
     
        //=========================
        if(!empty($api_key) && !empty($list_id)){
            $MailChimp = new MailChimp($api_key);
            $result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $_POST['email'],
                'merge_fields'  => [
                        'FNAME'     => $_POST['first_name'],
                        'LNAME'     => $_POST['last_name']
                    ],
                'status'        => 'subscribed',
            ]);
        }

    }else{
        update_field( $field_newsletter, "0", 'user_'.$user_ID );
        // add user in a specific list 
        $api_key = get_field('api_key_mailchimp', 'options'); //replace with your API key
        $list_id = get_field('list_id_mailchimp', 'options'); //replace with the list id you're adding the email to
     
        //=========================
        if(!empty($api_key) && !empty($list_id)){
            $MailChimp = new MailChimp($api_key);
            $subscriber_hash = $MailChimp->subscriberHash($_POST['email']);
            $MailChimp->delete("lists/$list_id/members/$subscriber_hash");
        }
    }

}