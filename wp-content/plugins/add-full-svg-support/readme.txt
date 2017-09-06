=== Add Full SVG Support ===
Contributors: jekuer
Author: jekuer
Tags: SVG, Style, Support, Vector, Upload, Input, Fallback, PNG, Image, Shortcode, SEO
Donate link: http://www.jenskuerschner.de/svg-images-with-png-fallback-in-wordpress/
Requires at least: 3.0.1
Tested up to: 4.7.2
Stable tag: 1.1.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
  
Upload SVG files to your WordPress and use them anywhere you want via shortcode. Include a fallback image and also add style-information.



== Description ==
This plugin adds some SVG functionality to your WordPress and uses a SVG technique that's based on an idea by Alexey Ten. This way of including SVG images into HTML seems to be the one with the best combination of performance and compatibility at the moment.
  
List of Features:  
  
* Upload SVG files to your media library
* Use SVG files via shortcode in your posts or pages
* Set the style via CSS
* Define a fallback image (usually PNG) for your SVG
* Add alt-information for SEO
* Automatically change all regular images to svg images
  
Example:
[do-svg style="margin:30px" width="148px" height="130px" svg_path="http://www.blogurl.de/images/image.svg" alt_path="http://www.blogurl.de/images/image.png" alt="My Other Image"]




== Installation ==
1. Upload the folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Place the shortcode in your templates.
  
Shortcode-Example:
[do-svg width="50px" height="30px" style="margin:30px" svg_path="wp-content/uploads/image.svg" alt_path="wp-content/uploads/image.png" alt="My Image"]



== Frequently Asked Questions ==
= Do I always have to add the dimensions of the image (widht, height)? =
You don\'t have to, but we recommend it for performance, SEO and functionality issues.
  
= How can I create SVG files? =
If you are looking for a SVG editor and don’t have tons of money for Adobe Indesign, check out Inkscape (free) or CorelDraw (cheap).
  
= Why did you write this plugin? =
All further information and background story: http://www.jenskuerschner.de/svg-images-with-png-fallback-in-wordpress/

= The image does not show up?! =
This usually happens if your server does not allow fopen or if the svg file does not contain any size information. You can workaround this issue by adding height and width information to the shortcode?

= Normal images do not get changed?! =
Is there a svg file with the exact same name (except the file extension) in the exact same folder?
Did you input the <img> tag manually and used single quotation marks? If yes, change it to double.
Does you server allow fopen? If not, the auto-change will not work, because the engine cannot check if the svg file exists.
Did you specify any size information (width; height)? If not, do so! Most SVG files do not contain those information.




== Changelog ==
= 1.1.5 =
* Added workaround to make svg upload work with latest WordPress security enhancements

= 1.1.3 =
* Minor bug fixes

= 1.1.2 =
* Minor bug fixes

= 1.1.1 =
* Minor bug fixes

= 1.1.0 =
* (added) Functionality to auto-change regular image tags with the svg code
* (added) Functionality to try to get svg size from the file if no size is specified

= 1.0.0 =
* Initial release.