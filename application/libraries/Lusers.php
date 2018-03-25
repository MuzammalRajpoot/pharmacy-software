<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lusers {

	#==============user list================#
	public function user_list()
	{
		$CI =& get_instance();
		$CI->load->model('Userm');
		$user_list = $CI->Userm->user_list();
		$i=0;
		if(!empty($user_list)){	
			foreach($user_list as $k=>$v){$i++;
			   $user_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('user_list'),
				'user_list' => $user_list,
			);
		$userList = $CI->parser->parse('users/user',$data,true);
		return $userList;
	}
	#=============User Search item===============#
	public function user_search_item($user_id)
	{
		$CI =& get_instance();
		$CI->load->model('Userm');
		$user_list = $CI->Userm->user_search_item($user_id);
		$i=0;
		foreach($user_list as $k=>$v){$i++;
           $user_list[$k]['sl']=$i;
		}
		$data = array(
				'title' => display('user_search_item'),
				'user_list' => $user_list
			);
		$userList = $CI->parser->parse('users/user',$data,true);
		return $userList;
	}
	#==============User add form===========#
	public function user_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('Userm');
		$data = array(
				'title' => display('add_user'),
			);
		$userForm = $CI->parser->parse('users/add_user_form',$data,true);
		return $userForm;
	}
	#================Insert user==========#
	public function insert_user($data)
	{
		$CI =& get_instance();
		$CI->load->model('Userm');
        $CI->Userm->user_entry($data);
		return true;
	}
	#===============User edit form==============#
	public function user_edit_data($user_id)
	{
		$CI =& get_instance();
		$CI->load->model('Userm');
		$user_detail = $CI->Userm->retrieve_user_editdata($user_id);
		$data=array(
			'user_id' 		=> $user_detail[0]['user_id'],
			'first_name' 	=> $user_detail[0]['first_name'],
			'last_name' 	=> $user_detail[0]['last_name'],
			'username' 		=> $user_detail[0]['username'],
			'password' 		=> $user_detail[0]['password'],
			'status' 		=> $user_detail[0]['status']
			);
	
		$companyList = $CI->parser->parse('users/edit_users_form',$data,true);
		return $companyList;
	}
}
?>