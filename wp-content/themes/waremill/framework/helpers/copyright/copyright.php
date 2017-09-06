<?php
/*
 * Description of Copyright
 *
 * @example : $vc->copyright(get_field('copyright_year','options'),get_field('copyright_text','options'),true)
 * @author : Cristian ANTAL
 * 
 */
class vc_copyright extends vc_helper {
	public function init(){
		if(!$this->arg2){
			$this->arg2=false;
		}
		if($this->arg0==date('Y')){
			$return = 'Copyright &copy; '.$this->arg0.' '.$this->arg1;
		}else{
			$return = 'Copyright &copy; '.$this->arg0.' - '.date('Y').' '.$this->arg1;
		}
		if($this->arg2){
			echo $return;
		}else{
			return $return;
		}
	}
}
