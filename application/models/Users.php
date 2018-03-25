<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	function check_valid_user($username,$password)
	{ 	
		$password = md5("gef".$password);
        $this->db->where(array('username'=>$username,'password'=>$password,'status'=>1));
		$query = $this->db->get('user_login');
		$result =  $query->result_array();	
		if (count($result) == 1)
		{
			$user_id = $result[0]['user_id'];
			
			$this->db->select('a.*,b.*');
			$this->db->from('user_login a');
			$this->db->join('users b','b.user_id = a.user_id');
			$this->db->where('a.user_id',$user_id);
			$query = $this->db->get();
			return $query->result_array();
		}
		return false;
	}
	/*
	**User registration
	*/
	public function user_registration()
	{
		$birth_day = $this->input->post('birth_day');
		$birth_month = $this->input->post('birth_month');
		$birth_year = $this->input->post('birth_year');
		$dbo = $birth_year.'-'.$birth_month.'-'.$birth_day;
	
		$data1=array(
			'user_id'			=>	null,
			'first_name'		=>	$this->input->post('first_name'),
			'last_name'			=>	$this->input->post('last_name'),
			'gender'			=>	$this->input->post('user_sex'),
			'date_of_birth'		=>	$dbo ,
			'status'			=>	1
			);
		$this->db->insert('users',$data1);
        $insert_id = $this->db->insert_id();
		//Inset user Login table 
		
		$password = $this->input->post('password');
		$password = md5("ctgs".$password);
		
		$data = array(
			'user_id'			=>	1,//$insert_id ,
			'username'			=>	$this->input->post('username'),
			'password'		    =>	$password,
			'user_type'			=>	2,
			'security_code'		=>  '',
			'status'			=>	0
			);
		$this->db->insert('user_login',$data);
	}
	public function profile_edit_data()
	{
		$user_id = $this->session->userdata('user_id');
		$this->db->select('a.*,b.username');
		$this->db->from('users a');
		$this->db->join('user_login b','b.user_id = a.user_id');
		$this->db->where('a.user_id',$user_id);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Update Profile
	public function profile_update()
	{
		$user_id = $this->session->userdata('user_id');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$user_name = $this->input->post('user_name');
		
		return $this->db->query("UPDATE `users` AS `a`,`user_login` AS `b` SET `a`.`first_name` = '$first_name', `a`.`last_name` = '$last_name', `b`.`username` = '$user_name' WHERE `a`.`user_id` = '$user_id' AND `a`.`user_id` = `b`.`user_id`");

	}
	//Change Password
	public function change_password($email,$old_password,$new_password)
	{
		$user_name = md5("gef".$new_password);
		$password = md5("gef".$old_password);
        $this->db->where(array('username'=>$email,'password'=>$password,'status'=>1));
		$query = $this->db->get('user_login');
		$result =  $query->result_array();
		
		if (count($result) == 1)
		{	
			$this->db->set('password',$user_name);
			$this->db->where('password',$password);
			$this->db->where('username',$email);
			$this->db->update('user_login');

			return true;
		}
		return false;
	}

}