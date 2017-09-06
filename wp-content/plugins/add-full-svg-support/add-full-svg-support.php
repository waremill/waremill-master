<?php
/**
 * Plugin Name: Add Full SVG Support
 * Plugin URI: http://www.jenskuerschner.de/svg-images-with-png-fallback-in-wordpress/
 * Description: Upload SVG files to your WordPress and use them anywhere you want via shortcode. Include a fallback image and also add style-information.
 * Version: 1.1.5
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


// Include admin options page
if (is_admin()) include 'svg-options.php';


// Add functionality to upload SVG files in WordPress
function svg_upload ($svg_mime) {
    $svg_mime['svg'] = 'image/svg+xml';
    return $svg_mime;
}
add_filter( 'upload_mimes', 'svg_upload' );
// Workaround by @blobfolio to make it work with recent WordPress security enhancements
function ignore_upload_ext($checked, $file, $filename, $mimes){
	//we only need to worry if WP failed the first pass
	if(!$checked['type']){
		//rebuild the type info
		$wp_filetype = wp_check_filetype( $filename, $mimes );
		$ext = $wp_filetype['ext'];
		$type = $wp_filetype['type'];
		$proper_filename = $filename;
		//preserve failure for non-svg images
		if($type && 0 === strpos($type, 'image/') && $ext !== 'svg'){
			$ext = $type = false;
		}
		//everything else gets an OK, so e.g. we've disabled the error-prone finfo-related checks WP just went through. whether or not the upload will be allowed depends on the <code>upload_mimes</code>, etc.
		$checked = compact('ext','type','proper_filename');
	}
	return $checked;
}
add_filter('wp_check_filetype_and_ext', 'ignore_upload_ext', 10, 4);


// Add a shortcode to implement them with a fallback solution in any frontend editor
function generate_svg_code($atts) { 
    $svga = shortcode_atts( array(
    'width' => '0',
    'height' => '0',
    'svg_path' => '',
    'alt_path' => '',
    'alt' => '',
    'style' => '',
    ), $atts ); 
    
    // get size of the svg
    $checkoptions = get_option("add_full_svg_support_option_name");
    if (isset($checkoptions["asf"]) and $checkoptions["asf"] != 1) {
        if ($svga['width'] == "" or $svga['width']  == "0" or $svga['height'] == "" or $svga['height'] == "0") {
            if ($file = @fopen(htmlentities($svga['svg_path'], ENT_QUOTES), 'rb')) {
                $svgfile = simplexml_load_file(htmlentities($svga['svg_path'], ENT_QUOTES));
                if (substr($svgfile[width],-2) == "px") {
                    $svgwidth = (int)substr($svgfile[width],0,-2);
                } else {
                    $svgwidth = (int)$svgfile[width];
                }
                if (!is_numeric($svgwidth) or $svgwidth > 99999) $svgwidth = "0";
                if ($svgwidth != "" and $svgwidth != "0") $svga['width'] = $svgwidth."px";
                if (substr($svgfile[height],-2) == "px") {
                    $svgheight = (int)substr($svgfile[height],0,-2);
                } else {
                    $svgheight = (int)$svgfile[height];
                }
                if (!is_numeric($svgheight) or $svgheight > 99999) $svgheight = "0";
                if ($svgheight != "" and $svgheight != "0") $svga['height'] = $svgheight."px";
            }
        }
    }
    
    return '<svg xmlns="http://www.w3.org/2000/svg" style="'.htmlentities($svga['style'], ENT_QUOTES).'" width="'.htmlentities($svga['width'], ENT_QUOTES).'" height="'.htmlentities($svga['height'], ENT_QUOTES).'"><image xlink:href="'.htmlentities($svga['svg_path'], ENT_QUOTES).'" src="'.htmlentities($svga['alt_path'], ENT_QUOTES).'" width="'.htmlentities($svga['width'], ENT_QUOTES).'" height="'.htmlentities($svga['height'], ENT_QUOTES).'" alt="'.htmlentities($svga['alt'], ENT_QUOTES).'"  /></svg>';
}
add_shortcode('do-svg', 'generate_svg_code');


// Try to replace every images between wp_head and wp_footer with svg
function svg_callback($buffer) {
    // img-tag(0), style(1), src(2), alt(3), width(4), height(5), title(6)
    $img_pattern = '/<img\s*(?:style\s*\=\s*[\'\"](.*?)[\'\"].*?\s*|src\s*\=\s*[\'\"](.*?)[\'\"].*?\s*|alt\s*\=\s*[\'\"](.*?)[\'\"].*?\s*|width\s*\=\s*[\'\"](.*?)[\'\"].*?\s*|height\s*\=\s*[\'\"](.*?)[\'\"].*?\s*|title\s*\=\s*[\'\"](.*?)[\'\"].*?\s*)+.*?>/si';
    preg_match_all($img_pattern, $buffer, $img_details);
    foreach ($img_details[0] as $key => $content) {
        $svg_style_from_img = $img_details[1][$key];
        $svg_src_from_img = $img_details[2][$key];
        $svg_alt_from_img = $img_details[3][$key];
        $svg_width_from_img = $img_details[4][$key];
        $svg_height_from_img = $img_details[5][$key];
        $svg_src_from_img_new = preg_replace('"\.(bmp|gif|jpg|jpeg|JPG|JPEG|png|ico|webp)$"', '.svg', $svg_src_from_img);
        $img_svg = generate_svg_code(array('width' => $svg_width_from_img,'height' => $svg_height_from_img, 'svg_path' => $svg_src_from_img_new, 'alt_path' => $svg_src_from_img, 'alt' => $svg_alt_from_img, 'style' => $svg_style_from_img));
        if ($file = @fopen($svg_src_from_img_new, 'rb')) {
            $buffer = (str_replace($content, $img_svg, $buffer));
        }
    }    
    return $buffer;
}
$checkoptions = get_option("add_full_svg_support_option_name");
if (isset($checkoptions["acai"]) and $checkoptions["acai"] == 1) {
    function svg_buffer_start() { ob_start("svg_callback"); }
    function svg_buffer_end() { ob_end_flush(); }
    add_action('wp_head', 'svg_buffer_start');
    add_action('wp_footer', 'svg_buffer_end');
}

?>