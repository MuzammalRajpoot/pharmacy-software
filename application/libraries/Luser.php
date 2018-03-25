<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Luser {
	//Login....
	public function edit_profile_form()
	{
		$CI =& get_instance();
		$CI->load->model('Users');
		$edit_data = $CI->Users->profile_edit_data();	
		$data = array(
				'first_name' => $edit_data[0]['first_name'],
				'last_name' => $edit_data[0]['last_name'],
				'user_name' => $edit_data[0]['username']
			);	
		$profile_data = $CI->parser->parse('user/edit_profile',$data,true);
		return $profile_data;
	}
}
?>