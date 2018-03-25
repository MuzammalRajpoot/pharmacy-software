<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//BANK LIST
	public function get_bank_list()
	{
		$this->db->select('*');
		$this->db->from('bank_add');
		$this->db->where('status',1);
		$this->db->limit('500');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Get bank by id
	public function get_bank_by_id($bank_id)
	{
		$this->db->select('*');
		$this->db->from('bank_add');
		$this->db->where('bank_id',$bank_id);
		$this->db->where('status',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Bank updaet by id
	public function bank_update_by_id($bank_id)
	{
		$data['bank_name']=$this->input->post('bank_name');
		$this->db->where('bank_id',$bank_id);
		$this->db->update('bank_add',$data); 
		return true;
	}
	//Table list
	public function table_list()
	{
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->limit('500');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//COUNT PRODUCT
	public function bank_entry( $data )
	{
		$this->db->insert('bank_add',$data);
	}
	//Table create
	public function table_create($account_id)
	{
		$account_name=$this->input->post('account_name');
		$status=$this->input->post('account_type');

		$account_exists = $this->db->select('*')
								->from('accounts')
								->where('account_name',$account_name)
								->get()
								->num_rows();
		if ($account_exists > 0) {
			$this->session->set_userdata(array('error_message'=>display('account_already_exists')));
			return true;
		}else{
			if($status==1){$account_table_name="outflow_".$account_id;}else{$account_table_name="inflow_".$account_id ;}
		
			$data = array(
					'account_id'		=>	$this->auth->generator(10),
					'account_name'		=>	$account_name,
					'account_table_name'=>	$account_table_name,
					'status'			=>	$status
				
				);
			
			$this->db->insert('accounts',$data);
			$sql="CREATE TABLE IF NOT EXISTS ".$account_table_name." (
					`transection_id` varchar(200) NOT NULL,
					`tracing_id` varchar(200) NOT NULL,
					`payment_type` varchar(10) NOT NULL,
					`date` datetime NOT NULL,
					`amount` int(10) NOT NULL,
					`description` varchar(255) NOT NULL,
					`status` int(5) NOT NULL,
					 PRIMARY KEY (`transection_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1";
			$this->db->query($sql);
			$this->session->set_userdata(array('message'=>display('successfully_created')));
		}
	}
	//Retrive table data by id
	public function retrive_table_data($account_id){
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('account_id',$account_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Update table data by id
	public function update_table_data($account_name,$account_id){

		$account_exists = $this->db->select('*')
								->from('accounts')
								->where('account_name',$account_name['account_name'])
								->get()
								->num_rows();
		if ($account_exists > 0) {
			$this->session->set_userdata(array('error_message'=>display('account_already_exists')));
			return false;
		}else{
			$this->db->where('account_id',$account_id);
			$this->db->update('accounts',$account_name);
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
			return true;
		}
	}
}