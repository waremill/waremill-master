<?php
/*
Plugin Name: Display Template Name
Description: Displays the name of the template used by the currently displayed page in the admin bar or inside the pages. Plugins very useful for developing your blog.
Version: 1.7.1
Author: Aurélien Chappard
Author URI: http://www.deefuse.fr/
License: GPL
Copyright: Aurélien Chappard
*/
if( !class_exists( 'Display_template_name' ) ) {
    class Display_template_name {
        var $adminOptionsName = "DisplayTemplateNameAdminOptions";

        public function __construct() {
            load_plugin_textdomain('display-template-name', false, basename(dirname(__FILE__)).'/lang' );

        }


        //Returns an array of admin options
        function getAdminOptions() {
            $displayTemplateNameAdminOptions = array('position' => 'TL');
            $devOptions = get_option($this->adminOptionsName);
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $displayTemplateNameAdminOptions[$key] = $option;
            }
            update_option($this->adminOptionsName, $displayTemplateNameAdminOptions);
            return $displayTemplateNameAdminOptions;
        }

        function init() {
            // add special cap
            $admin_role = get_role("administrator");
            $admin_role->add_cap( 'view_template_name' );

            //get option 
            $this->getAdminOptions();
        }


        function displayTplNameenqueue_my_styles()
        {
            wp_register_style( 'displayTPLNameStylesheet', plugins_url('stylesheet.css', __FILE__) );
            wp_enqueue_style( 'displayTPLNameStylesheet' );
        }


        //Prints out the admin page
        function printAdminPage()
        {
            $devOptions = $this->getAdminOptions();
            if (isset($_POST['update_displayTemplateNamePluginSettings']))
            {
                if (isset($_POST['news_position'])) {
                    $devOptions['position'] = $_POST['news_position'];
                }

                update_option($this->adminOptionsName, $devOptions);

                ?>
                <div class="updated"><p><strong><?php _e("The position have been saved.", "display-template-name");?></strong></p></div>
                <?php
            } ?>
            <div class=wrap>
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                    <h2>Display Template Name</h2>
                    <?php
                    $themes = wp_get_theme();

                    $urlScreenShot = $themes->get_screenshot();
                    if( empty($urlScreenShot) )
                    {
                        $urlScreenShot = plugin_dir_url(__FILE__) . 'images/default.png';
                    }
                    ?>
                    <p><?php _e("Select the position of the debug box.", "display-template-name") ?></p>
                    <div id="tablePreviewDisplayTplName">

                        <label for="displatTplName_TL">
                            <div class="macPreviewDebug">
                                <img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_tl.png';?>" class="maskDisplayTpl" />
                            </div>
                            <input type="radio" id="displatTplName_TL" name="news_position" value="TL" <?php if ($devOptions['position'] == "TL") { echo 'checked="checked"'; }?> />
                            <?php _e("Top left", "display-template-name") ?>
                        </label>

                        <label for="displatTplName_TR">
                            <div class="macPreviewDebug">
                                <img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_tr.png';?>" class="maskDisplayTpl" />
                            </div>
                            <input type="radio" id="displatTplName_TR" name="news_position" value="TR" <?php if ($devOptions['position'] == "TR") {echo 'checked="checked"'; }?>/>
                            <?php _e("Top right", "display-template-name") ?>
                        </label>

                        <label for="displatTplName_BL">
                            <div class="macPreviewDebug">
                                <img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_bl.png';?>" class="maskDisplayTpl" />
                            </div>
                            <input type="radio" id="displatTplName_BL" name="news_position" value="BL" <?php if ($devOptions['position'] == "BL") {echo 'checked="checked"'; }?>/>
                            <?php _e("Bottom left", "display-template-name") ?>
                        </label>

                        <label for="displatTplName_BR">
                            <div class="macPreviewDebug">
                                <img src="<?php echo $urlScreenShot;?>" width="201" class="preview" height="150"/>
                                <img src="<?php echo plugin_dir_url(__FILE__) . 'images/masque_br.png';?>" class="maskDisplayTpl" />
                            </div>
                            <input type="radio" id="displatTplName_BR" name="news_position" value="BR" <?php if ($devOptions['position'] == "BR") {echo 'checked="checked"'; }?>/>
                            <?php _e("Bottom right", "display-template-name") ?>
                        </label>
                    </div>
                    <div class="clearBoth"></div>
                    <p><em>
                            <?php _e("If the toolbar is displayed when you visit the site, the template name will be displayed in it and the above parameter will not be considered.", "display-template-name") ?>
                        </em></p>
                    <div class="submit">
                        <input type="submit" class="button-primary" name="update_displayTemplateNamePluginSettings" value="<?php _e('Update Settings', 'display-template-name') ?>" />
                    </div>
                </form>
            </div>
            <?php
        }//End function printAdminPage()


        /**
         * Display the name on the top of the pages
         */
        function displayTheTemplateName()
        {
            // Get the actual user
            $actualUser =  wp_get_current_user();
            if($actualUser->data != null)
            {
                //
                $devOptions = $this->getAdminOptions();

                // Css position according to the showing admin bar
                if( !is_admin_bar_showing() && current_user_can('view_template_name') )
                {
                    $top = "0px";
                    switch ($devOptions['position'])
                    {

                        case 'BL' :
                            $stringCss = 'bottom:0px; left:0px;';
                            break;
                        case 'BR' :
                            $stringCss = 'bottom:0px; right:0px;';
                            break;
                        case 'TR' :
                            $stringCss = 'top:' . $top . '; right:0px;';
                            break;
                        case 'TL' :
                        default :
                            $stringCss = 'top:' . $top . '; left:0px;';
                            break;
                    }
                    ?>
                    <style type="text/css">
                        #debug-display-template-name{
                            font-size: 14px;
                            cursor: default;
                            position: fixed;
                        <?php echo $stringCss;?>
                            background: #333;
                            color: #FFF;
                            padding: 5px 20px;
                            border: 1px solid #FFF;
                            z-index: 99999
                        }
                        #debug-display-template-name h6{
                            font-size: 18px;
                            text-transform: uppercase;
                            font-weight: normal;
                            padding-bottom: 10px;
                            border-bottom:1px solid #000;
                        }
                        #debug-display-template-name a, #debug-display-template-name a:visited{
                            color: #FFF;
                            cursor: default;
                        }
                        #debug-display-template-name p{margin-bottom:10px;}
                        #debug-display-template-name ul{
                            list-style-type:disc;
                            padding-left:10px;
                            line-height:17px;
                            list-style-position:inside;
                        }
                        #debug-display-template-name ul li{
                            margin-bottom:10px;
                        }
                        #debug-display-template-name ul li  ul{
                            list-style-type:square;
                            padding-left:15px;
                        }
                        #debug-display-template-name ul li  ul li{margin-bottom:inherit;}
                    </style>
                    <?php
                    $templateInfos = $this->get_current_template();
                    $child_template = $this->get_child_templates($templateInfos);
                    ?>
                    <div id="debug-display-template-name">
                        <h6><?php _e('Current template:','display-template-name'); ?></h6>
                        <ul>
                            <li>
                                <a href="#" title="<?php echo $templateInfos; ?>" target="_blank"><?php echo basename($templateInfos); ?></a>
                                <?php if ( count($child_template > 0) ) : ?>
                                    <ul>
                                        <?php foreach ($child_template as $tpl) : ?>
                                            <li><a title="<?php echo $tpl; ?>"><?php echo basename($tpl); ?></a></li>
                                        <?php endforeach ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
            }
        }


        /**
         * Retrieve the current template name et save into a global variable current_theme_template
         */

        function var_template_include( $t )
        {
            //   $GLOBALS['current_theme_template'] = basename($t);
            $GLOBALS['current_theme_template'] = $t;

            return $t;
        }

        /**
         * Grab the global variable et return it
         */
        function get_current_template( $echo = false )
        {
            if( !isset( $GLOBALS['current_theme_template'] ) )
                return false;
            if( $echo )
                echo $GLOBALS['current_theme_template'];
            else
                return $GLOBALS['current_theme_template'];
        }

        /**
         * Récupère les tpl enfant chargé après le template principal (vie get_template_part)
         * @param $baseTemplateCalled
         * @return array
         */
        function get_child_templates($baseTemplateCalled){
            $child_include = array();
            $included_files = get_included_files();
            $stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
            $template_dir   = str_replace( '\\', '/', get_template_directory() );
            $template_de_base_loaded = FALSE;
            foreach ( $included_files as $key => $path ) {

                $path   = str_replace( '\\', '/', $path );

                if ( false === strpos( $path, $stylesheet_dir ) && false === strpos( $path, $template_dir ) )
                    unset( $included_files[$key] );

                // si c'est dans le theme
                if(!strpos($path, '/wp-content/themes/') === false) {

                    if($template_de_base_loaded){
                        array_push($child_include, $path);
                    }

                    if($path == $baseTemplateCalled){
                        $template_de_base_loaded = TRUE;
                    }


                    //array_push($child_include, $path. ' = '.$baseTemplateCalled);
                    //echo $key.' = '. $path.'</br></br>';

                }
            }
            return $child_include;
        }

        function dispayTplName_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page=display-template-name.php">' . __('Settings', 'display-template-name') . '</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        function custom_css_admin_bar(){
            echo '<style>
                    #wp-admin-bar-displayTemplateName .ab-icon:before{
                        font-family: "dashicons" !important;
                        content: "\f100" !important;
                    }

                    #wp-admin-bar-displayTemplateName .ab-icon-child:before{
                        font-family: "dashicons" !important;
                        content: "\f345" !important;
                    }
                    </style>';
        }

        function displayTplNameAdminBar()
        {
            if(!is_admin() && is_admin_bar_showing() && current_user_can('view_template_name') ){
                global $wp_admin_bar;
                $templateInfos = $this->get_current_template();
                $child_template = $this->get_child_templates($templateInfos);
                $wp_admin_bar->add_menu( array(
                    'parent' => false, // use 'false' for a root menu, or pass the ID of the parent menu
                    'id' => 'displayTemplateName', // link ID, defaults to a sanitized title value
                    'title' => '<span class="ab-icon"></span>', // link title
                    'meta' => array('title' => $templateInfos) // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
                ));
                $wp_admin_bar->add_menu(
                    array(
                        'parent' => 'displayTemplateName', // use 'false' for a root menu, or pass the ID of the parent menu
                        'id' => 'displayTemplateName-root', // link ID, defaults to a sanitized title value
                        'title' =>  basename($templateInfos),
                        'meta' => array('title' => $templateInfos) // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );

                    )
                );
                if ( count($child_template > 0) ) {
                    $i = 0;
                    foreach ($child_template as $tpl) {
                        $wp_admin_bar->add_menu( array(
                            'parent' => 'displayTemplateName', // use 'false' for a root menu, or pass the ID of the parent menu
                            'id' => 'displayTemplateName-child-'.$i, // link ID, defaults to a sanitized title value
                            'title' => '<span class="ab-icon-child"></span> '. basename($tpl),
                            'meta' => array('title' => $tpl) // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
                        ));
                        $i++;
                    }
                }




            }

        }
    }
}


if (class_exists("Display_template_name")) {
    $display_template_name_plugin = new Display_template_name();
}

//Actions and Filters
if (isset($display_template_name_plugin))
{
    register_activation_hook( __FILE__, array(&$display_template_name_plugin, 'init') );


    //Actions
    add_action( 'wp_footer', array(&$display_template_name_plugin, 'displayTheTemplateName') );
    add_action('admin_menu', 'DisplaYTemplateName_ap');
    add_action('wp_head', array(&$display_template_name_plugin, 'custom_css_admin_bar' ));
    add_action( 'admin_init', array(&$display_template_name_plugin, 'displayTplNameenqueue_my_styles') );
    add_action( 'wp_before_admin_bar_render', array(&$display_template_name_plugin, 'displayTplNameAdminBar') );
    // Filter
    add_filter( 'template_include', array(&$display_template_name_plugin,'var_template_include'), 1000 );

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", array(&$display_template_name_plugin,'dispayTplName_settings_link') );


}

//Initialize the admin panel
if (!function_exists("DisplaYTemplateName_ap")) {
    function DisplaYTemplateName_ap() {
        global $display_template_name_plugin;
        if (!isset($display_template_name_plugin)) {
            return;
        }
        if (function_exists('add_options_page')) {
            add_options_page('Display Template Name', 'Display Template Name', 'activate_plugins', basename(__FILE__), array(&$display_template_name_plugin, 'printAdminPage'));
        }
    }
}