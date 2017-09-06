<?php

/*
 * Description of Social Share
 *
 * @example : $vc->share('facebook','http://verycreative.eu') sau $vc->share('facebook') sau $vc->share('twitter','http://verycreative.eu','Very Creative Agency')
 * Daca aveti nevoie fara echo $vc->share->get('facebook')
 * @author Cristian ANTAL
 * 
 */

class vc_share extends vc_helper {
	public function init(){
		echo $this->get($this->arg0,$this->arg1,$this->arg2);
	}
	public function get($network,$url="",$text=""){
		if(!$url){
			$url = $this->url();
		}
		if($text){
			$text = urlencode($text);
		}
		$url = urlencode($url);
		if($network=="facebook"){
			return $this->facebook($url);
		}elseif($network=="twitter"){
			return $this->twitter($url,$text);
		}elseif($network=="linkedin"){
			return $this->linkedin($url,$text);
		}elseif($network=="google"){
			return $this->google($url);
		}
	}
	private function url() {
	    $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
	    $url .= '://' . $_SERVER['SERVER_NAME'];
	    $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
	    $url .= $_SERVER['REQUEST_URI'];
	    return $url;
	}
	private function post_id(){
		return get_the_ID();
	}
	private function find_image_id($post_id) {
    if (!$img_id = get_post_thumbnail_id ($post_id)) {
	        $attachments = get_children(array(
	            'post_parent' => $post_id,
	            'post_type' => 'attachment',
	            'numberposts' => 1,
	            'post_mime_type' => 'image'
	        ));
	        if (is_array($attachments)) foreach ($attachments as $a){
	            $img_id = $a->ID;
	        }
	    }
	    if ($img_id){
	        return $img_id;
	    }
	    return false;
	}
	private function find_img_src($post_id) {
	    if (!$img = $this->find_image_id($post_id)){
	    	$post = get_post($post_id);
	        if ($img = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches)){
	            $img = $matches[1][0];
	        }
	    }
	    if (is_int($img)) {
	        $img = wp_get_attachment_image_src($img);
	        $img = $img[0];
	    }
	    if(!$img){
	    	$img = get_field('placeholder','options');
	    }
	    return $img;
	}
	private function img($img=""){
		if(!$img){
			return $this->find_img_src();
		}else{
			return $img;
		}
	}
	private function facebook($url){
		return "https://www.facebook.com/sharer/sharer.php?u=$url";
	}
	private function twitter($url,$text=""){
		return "https://twitter.com/intent/tweet?text=$text%20$url";
	}
	private function linkedin($url,$text=""){
		return "https://www.linkedin.com/shareArticle?mini=true&url=$url&title=$text";
	}
	private function google($url){
		return "https://plus.google.com/share?url=$url";
	}
	private function pinterest($url,$text="",$img=""){
		return "https://pinterest.com/pin/create/button/?url=$url&media=$img&description=$text";
	}
}