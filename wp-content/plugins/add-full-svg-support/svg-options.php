<?php
/**
 * This File is part of the Plugin 'Add Full SVG Support' (http://www.jenskuerschner.de/svg-images-with-png-fallback-in-wordpress/)
 * Upload SVG files to your WordPress and use them anywhere you want via shortcode. Include a fallback image and also add style-information.
 *
 * Author: Jens K&uuml;rschner
 * Author URI: http://www.jenskuerschner.de
 *
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*  2016 Jens K&uuml;rschner  (email : mail@jenskuerschner.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("No script kiddies please!");


class AddFullSVGSupportSettingsPage {
    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page() {
        add_options_page(
            'Add Full SVG Support Settings', 
            'SVG Support Settings', 
            'manage_options', 
            'add_full_svg_support-setting-admin', 
            array( $this, 'add_full_svg_support_settings_page' )
        );
    }
    
    public function add_full_svg_support_settings_page() {
        $this->options = get_option( 'add_full_svg_support_option_name' );
        ?>
        <div class="wrap">
            <h2>Add Full SVG Support Settings</h2>           
            <form method="post" action="options.php">
            <?php
                settings_fields( 'add_full_svg_support_option_group' ); 
                ?><p>&nbsp;</p><?php
                do_settings_sections( 'add_full_svg_support-setting-admin' );
                ?><p><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p><?php
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {        
        register_setting(
            'add_full_svg_support_option_group',
            'add_full_svg_support_option_name',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'main_settings',
            'Core Settings', 
            array( $this, 'print_section_info' ),
            'add_full_svg_support-setting-admin'
        );  

        add_settings_field(
            'acai',
            'Auto-Change All Images',
            array( $this, 'acai_callback' ),
            'add_full_svg_support-setting-admin', 
            'main_settings'           
        );      

        add_settings_field(
            'asf', 
            'Auto-Size Fallback', 
            array( $this, 'asf_callback' ), 
            'add_full_svg_support-setting-admin', 
            'main_settings'
        );      
    }

    public function sanitize( $input ) {
        $new_input = array();
        if( isset( $input['acai'] ) )
            $new_input['acai'] = absint( $input['acai'] );
        if( isset( $input['asf'] ) )
            $new_input['asf'] = absint( $input['asf'] );
        return $new_input;
    }

    public function print_section_info() {
        print '<p>Control some of the core functionalities of the plugin.</p>';
    }

    public function acai_callback() {
        $html = '<p><input type="checkbox" id="acai" name="add_full_svg_support_option_name[acai]" value="1"' . checked(1, $this->options['acai'], false ) . '/>';
        $html .= '<label for="acai">Check this if you want to automatically change all images on your page to svg-images.</label></p>';
        $html .= '<p>&nbsp;</p><p><em>(Images will be changed if there is a svg file with the exact same name in the exact same folder on your server. You can improve compatibility by giving all of your images width and height values (also recommended for SEO issues). Please make sure that your theme has set wp_head and wp_footer correctly!)<br /><b>(I do not recommend to activate this, because it is a huge performance killer! It might also overload your server in some rare cases.</b> However, if you love the convenient way, feel free to use it.)</em></p><p>&nbsp;</p><hr><p>&nbsp;</p><p>&nbsp;</p>';
        echo $html;
    }

    public function asf_callback() {   
        $html = '<p><input type="checkbox" id="asf" name="add_full_svg_support_option_name[asf]" value="1"' . checked(1, $this->options['asf'], false ) . '/>';
        $html .= '<label for="asf">Check this if you want to <u>disable</u> the functionality to automatically get the size of the svg-file.</label></p>';
        $html .= '<p>&nbsp;</p><p><em>(You can use this function if you experience any strange behaviour that is related to the size of your images. Generally, I recommend to give all of your image files (including the SVG shortcode) width and height values. This is not only good for compatibility, but also SEO reasons!)<br />(The auto-size function of this plugin is also more of a fallback solution, since most svg images do not contain size-information!)</em></p><p>&nbsp;</p><hr><p>&nbsp;</p><p>&nbsp;</p>';
        echo $html;
    }
}

if (is_admin()) $add_full_svg_support_settings_page = new AddFullSVGSupportSettingsPage();



?>