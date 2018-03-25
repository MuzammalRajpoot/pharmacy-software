<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cdeposit extends CI_Controller {
	
	function __construct() {
      parent::__construct();
	  
	  $this->template->current_menu = 'deposit';
    }
	public function index()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('ldeposit');
		$content = $CI->ldeposit->deposit_add_form();
		$sub_menu = array(
				array('label'=> 'Add Deposit', 'url' => 'Cdeposit','class' =>'active'),
				array('label'=> 'Manage Deposit', 'url' => 'Cdeposit/manage_deposit')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	//deposit Add Form
	public function manage_deposit()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('ldeposit');
		$CI->load->model('Deposits');
		
		$config = array();
		$config["base_url"] = base_url()."Cdeposit/manage_deposit";
		$config["total_rows"] = $this->Deposits->count_deposit();	  
		$config["per_page"] = 15;
		$config["uri_segment"] = 3;	
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$limit = $config["per_page"];
	    $links = $this->pagination->create_links();
		
        $content = $CI->ldeposit->deposit_list($limit,$page,$links);
        $sub_menu = array(
				array('label'=> 'Add Deposit', 'url' => 'Cdeposit', 'class' =>'active'),
				array('label'=> 'Manage Deposit', 'url' => 'Cdeposit/manage_deposit')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	//Insert deposit and uload
	public function insert_deposit()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('ldeposit');

		$data=array(
			'deposit_id' 	=> $this->generator(15),
			'description' 	=> $this->input->post('description'),
			'amount' 		=> $this->input->post('amount'),
			'date' 			=> $this->input->post('deposit_date'),
			'status' 		=> 1
			);
		$CI->ldeposit->insert_deposit($data);
		$this->session->set_userdata(array('message'=>"Successfully Added !"));
		if(isset($_POST['add-deposit'])){
			redirect(base_url('Cdeposit/manage_deposit'));
			exit;
		}elseif(isset($_POST['add-deposit-another'])){
			redirect(base_url('Cdeposit'));
			exit;
		}
	}
	//deposit Update Form
	public function deposit_update_form($deposit_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('ldeposit');
		$content = $CI->ldeposit->deposit_edit_data($deposit_id);
		$sub_menu = array(
				array('label'=> 'Add Deposit', 'url' => 'Cdeposit'),
				array('label'=> 'Manage Deposit', 'url' => 'Cdeposit/manage_deposit'),
				array('label'=> 'Edit Deposit', 'url' => 'Cdeposit','class' =>'active')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	// deposit Update
	public function deposit_update()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Deposits');
		$deposit_id  = $this->input->post('deposit_id');
		$data=array(
			'description' 	=> $this->input->post('description'),
			'amount' 		=> $this->input->post('amount'),
			'date' 			=> $this->input->post('deposit_date'),
			'status' 		=> 1
			);
		$CI->Deposits->update_deposit($data,$deposit_id);
		$this->session->set_userdata(array('message'=>"Successfully Updated !"));
		redirect(base_url('Cdeposit'));
		exit;
	}
	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9","0");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,61);
			$rand_number=$number["$rand_value"];
		
			if(empty($con))
			{ 
			$con=$rand_number;
			}
			else
			{
			$con="$con"."$rand_number";}
		}
		return $con;
	}
	
}