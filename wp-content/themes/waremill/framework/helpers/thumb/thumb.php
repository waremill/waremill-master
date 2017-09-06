<?php
/*
 * @name vc_thumb
 * @type helper
 * @author Anton Iulian-Leonard
 * @email leo@verycreative.info
 */
class vc_thumb extends vc_helper {

    protected $_files = array(
        'phpthumb/ThumbLib.inc.php' => null,
        'phpthumb/ThumbBase.inc.php' => null,
        'phpthumb/PhpThumb.inc.php' => null,
        'phpthumb/GdThumb.inc.php' => null,
        'phpthumb/thumb_plugins/gd_reflection.inc.php' => null,
        'phpthumb/thumb_plugins/gd_watermark.inc.php' => null
    );
    protected $_thumb;
    
    protected $_cache;
    
    protected $_cache_dir;
    
    protected $_cache_url;
    
    public function __construct(VC $vc) {
        parent::__construct($vc);
        
        $this->_cache_dir = get_template_directory() . '/vc_cache';
        if( !file_exists($this->_cache_dir) ) {
            mkdir($this->_cache_dir, 0777);
        }
        $this->_cache_url = get_stylesheet_directory_uri() . '/vc_cache';
    }
    public function setCacheParams($path, $url) {
        if( !file_exists($path) ) {
            return $this;
        }
        
        $this->_cache_dir = $path . '/vc_cache';
        if( !file_exists($this->_cache_dir) ) {
            mkdir($this->_cache_dir, 0777);
        }
        $this->_cache_url = $url;
        
        return $this;
    }
    private function fromUrlTODir($path) {
         if (function_exists('wp_upload_dir')) {
            $upload_dir = wp_upload_dir();
            $path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $path);
        }
        return $path;
    }
    public function init() {
        if( !$this->arg0 ) {
            throw new Exception('VC FRAMEWORK: first parameter must be a image file path');
        }
        $this->arg0 = $this->fromUrlTODir($this->arg0);
        
        if( file_exists($this->arg0) ) {
            $size = getimagesize($this->arg0);
            $info = pathinfo($this->arg0);
            $width = (int) ($this->_arg1 ? $this->arg1 : $size[0]);
            $height = (int) ($this->arg2 ? $this->arg2 : $size[1]);
            $quality = (int) ($this->arg4 ? $this->arg4 : 80);
            $crop = $this->arg3 ? 'crop' : 'nocrop';
            $this->_cache = $info['filename'] . '-' . md5($info['dirname'] . $width . $height . $quality . $crop) . '.' . $info['extension'];
            if( !file_exists($this->_cache_dir . '/' . $this->_cache) ) {
                if( !class_exists('PhpThumbFactory') ) {
                    require_once $this->_path . '/' . $this->_vc_name . '/phpthumb/ThumbLib.inc.php';
                }
                $this->_thumb = PhpThumbFactory::create($this->arg0, array('resizeUp' => true, 'jpegQuality' => $quality, 'preserveAlpha' => true, "preserveTransparency" => true));
                if( $this->arg3 ) {
                    $this->_thumb->adaptiveResize($width,$height);
                }
                else {
                    $this->_thumb->resize($width, $height);
                }
            }
        }
        return $this;
    }
    public function wattermark($path = '', $position = 'right bottom', $margin = '10 10') {
        if( $this->_thumb ) {
            if( !$path && function_exists('get_field') ) {
                $wattermark = get_field('wattaremark', 'options');
                $path = $wattermark['url'];
            }
            $path = $this->fromUrlTODir($path);
            if( file_exists($path) ) {
                list($width, $height) = getimagesize($path);
                list($xpoz, $ypoz) = explode(' ', $position);
                $margins = explode(' ', $margin); 
                if($this->_thumb->newDimensions['newWidth'] < $width || $this->_thumb->newDimensions['newHeight'] < $height) {
                    $info = pathinfo($path);
                    $cache = 'watermark_' . $info['filename'] . '-' .md5($info['dirname'] . ((int) $this->_thumb->newDimensions['newWidth'] / 2) . ((int) $this->_thumb->newDimensions['newHeight'] / 2)) . '.' . $info['extension'];
                    if( !file_exists($this->_cache_dir . '/' .$cache) ) {
                        $wattermark = PhpThumbFactory::create($path, array('resizeUp' => true, 'jpegQuality' => 80, 'preserveAlpha' => true, "preserveTransparency" => true));
                        $wattermark->resize((int) $this->_thumb->newDimensions['newWidth'] / 2, (int) $this->_thumb->newDimensions['newHeight'] / 2);
                        $wattermark->save($this->_cache_dir . '/' .$cache);
                    }
                    $path = $this->_cache_dir . '/' .$cache;
                    list($width, $height) = getimagesize($path);
                }
                
                switch($xpoz) {
                    case 'center': 
                        $x = abs(((int) $this->_thumb->newDimensions['newWidth'] - (int) $width) / 2);
                        break;
                    case 'left':
                        $x = is_numeric($margins[0]) ? $margins[0] : 0;
                        break;
                    case 'right':
                        $x = (int) $this->_thumb->newDimensions['newWidth'] - (int) $width - (is_numeric($margins[0]) ? $margins[0] : 0);
                        break;
                    default:
                        $x = 0;
                }
                switch($ypoz) {
                    case 'center': 
                        $y = abs(((int) $this->_thumb->newDimensions['newHeight'] - (int) $height) / 2);
                        break;
                    case 'top':
                        $y = is_numeric($margins[1]) ? $margins[1] : 0;
                        break;
                    case 'bottom':
                        $y = (int) $this->_thumb->newDimensions['newHeight'] - (int) $height - (is_numeric($margins[1]) ? $margins[1] : 0);
                        break;
                    default:
                        $y = 0; 
                }
                $this->_thumb->createWatermark($path, $x, $y);
            }
        }
        return $this;
    }
    public function generate($echo  = true) {
        if(!$this->_thumb) {
            return $this->_cache ? $this->_cache_url . '/' . $this->_cache : '';
        }
        $this->_thumb->save($this->_cache_dir . '/' . $this->_cache);
        
        if( $echo ) {
            echo '<img src="' . $this->_cache_url . '/' . $this->_cache . '" alt="Very Creative Thumb" />';
            return;
        }
        return $this->_cache_url . '/' . $this->_cache;
    } 
    public function __toString() {
        
        return $this->generate(false);
    }
    function clear() {
        if( $this->_cache ) {
            unlink($this->_cache_dir . '/' . $this->_cache);
            return true;
        }
        array_map('unlink', glob($this->_cache_dir .'/*'));
        return true;
    }
}