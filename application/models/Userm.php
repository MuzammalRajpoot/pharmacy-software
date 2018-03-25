<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Userm extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	#============Count Company=============#
	public function count_user()
	{
		return $this->db->count_all("users");
	}
	#=============User List=============#
	public function user_list()
	{
		$this->db->select('users.*,user_login.*');
		$this->db->from('user_login');
		$this->db->join('users', 'users.user_id = user_login.user_id'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	#==============User search list==============#
	public function user_search_item($user_id)
	{
		$this->db->select('users.*,user_login.user_type');
		$this->db->from('user_login');
		$this->db->join('users', 'users.user_id = user_login.user_id'); 
		$this->db->where('users.user_id',$user_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	#============Insert user to database========#
	public function user_entry($data)
	{
		$users= array(
				'first_name' => $data['first_name'], 
				'last_name' => $data['last_name'],
				'status' => $data['status'],
			);
		$this->db->insert('users',$users);


		$user_login = array(
				'username' => $data['email'], 
				'password' => $data['password'], 
				'user_type' => $data['user_type'], 
				'status' => $data['status'], 
			);

		$this->db->insert('user_login',$user_login);
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('status',1);

		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_product[] = array('label'=>$row->first_name,'value'=>$row->user_id);
		}
		$cache_file = './my-assets/js/admin_js/json/user.json';
		$productList = json_encode($json_product);
		file_put_contents($cache_file,$productList);
	}
	#==============User edit data===============#
	public function retrieve_user_editdata($user_id)
	{

		$this->db->select('users.*,user_login.*');
		$this->db->from('user_login');
		$this->db->join('users', 'users.user_id = user_login.user_id'); 
		$this->db->where('users.user_id',$user_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	#==============Update company==================#
	public function update_user($user_id)
	{

		$data=array(
			'first_name'  	=> $this->input->post('first_name'),
			'last_name' 	=> $this->input->post('last_name'),
			'status' 	    => $this->input->post('status')
			);

		$this->db->where('user_id',$user_id);
		$this->db->update('users',$data);

		$user_login = array(
			'username' 		=> $this->input->post('username'),
			'password' 		=> md5("gef".$this->input->post('password')),
		);
		$this->db->where('user_id',$user_id);
		$this->db->update('user_login',$user_login);

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('status',1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_product[] = array('label'=>$row->first_name,'value'=>$row->user_id);
		}
		$cache_file = './my-assets/js/admin_js/json/user.json';
		$productList = json_encode($json_product);
		file_put_contents($cache_file,$productList);
		return true;
	}
	#===========Delete user item========#
	public function delete_user($user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->delete('users'); 

		$this->db->where('user_id',$user_id);
		$this->db->delete('user_login');

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('status',1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_product[] = array('label'=>$row->first_name,'value'=>$row->user_id);
		}
		$cache_file = './my-assets/js/admin_js/json/user.json';
		$productList = json_encode($json_product);
		file_put_contents($cache_file,$productList);
		return true;
	}
}