<?php
/**
 * Description of Image Helper
 *
 * @example : $vc->img(get_field('logo','options'),'full',get_bloginfo('description'))
 * @author Cristian ANTAL
 * 
 */
class vc_img extends vc_helper {
	public function init(){
		$this->print_img($this->arg0,$this->arg1,$this->arg2,$this->arg3);
	}
	public function get_url($image="",$size="full"){
		if(!$image){
			$image=get_field('image_placeholder','options');
		}
		if(!$size){
			$size = 'full';
		}

		if(!$image){
			global $_wp_additional_image_sizes;
			return isset( $_wp_additional_image_sizes[$size] ) ? 'http://placehold.it/'.$_wp_additional_image_sizes[$size]['width'].'x'.$_wp_additional_image_sizes[$size]['height'] : 'http://placehold.it/1900x1200';
		}

		if(is_numeric($image)){
			return $this->resizeimg($image,$size);
		}elseif($size=="full"){
			return $image['url'];
		}elseif(is_array($image['sizes'])){
			if(array_key_exists($size, $image['sizes'])){
				return $image['sizes'][$size];
			}else{
				return $image['url'];
			}
		}else{
			return $image['url'];
		}
	}
	public function check($image="",$size="full"){
		if($this->get_url($image,$size)){
			return true;
		}else{
			return false;
		}
	}
	public function the_img($image,$size="full"){
		echo $this->get_url($image,$size);
	}
	public function print_img($image="",$size="full",$title="image",$class=""){
		echo '<img src="'.$this->get_url($image,$size).'"'. ($title ? ' alt="'.$title.'"' : ' alt="image"') . ($class ? ' class="'.$class.'"' : '') .'>';
	}
	public function featured_img($id="",$size="full"){
		if(!$id){
			$id = get_the_ID();
		}

		if(has_post_thumbnail($id)){
			return $this->resizeimg(get_post_thumbnail_id( $id ) , $size);
		}else{
			global $_wp_additional_image_sizes;
			return isset( $_wp_additional_image_sizes[$size] ) ? 'http://placehold.it/'.$_wp_additional_image_sizes[$size]['width'].'x'.$_wp_additional_image_sizes[$size]['height'] : 'http://placehold.it/1900x1200';	
		}
		
	}
	public function the_featured_img($id="",$size="full"){
		echo $this->featured_img($id,$size);
	}
	public function print_featured_img($id="",$size="full",$title="",$class=""){
		echo '<img src="'.$this->featured_img($id,$size).'"'. ($title ? ' alt="'.$title.'"' : ' alt="image"') . ($class ? ' class="'.$class.'"' : '') .'>';
	}
	public function resizeimg($image,$size){		
		$res=wp_get_attachment_image_src($image,$size);
		return $res[0];
	}
}
