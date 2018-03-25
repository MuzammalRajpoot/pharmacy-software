<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Creceipt extends CI_Controller {
	
	function __construct() {
      parent::__construct();
	  
	  $this->template->current_menu = 'receipt';
    }
	public function index()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lreceipt');
		$content = $CI->lreceipt->receipt_add_form();
		$sub_menu = array(
				array('label'=> 'Add Receipt', 'url' => 'creceipt','class' =>'active'),
				array('label'=> 'Manage Receipt', 'url' => 'creceipt/manage_receipt')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	
	//Search Receipt Item
	public function search_receipt_item()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreceipt');
		$customer_id = $this->input->post('customer_id');
        $content = $CI->lreceipt->search_receipt_item($customer_id);
        $sub_menu = array(
				array('label'=> 'Add Receipt', 'url' => 'creceipt'),
				array('label'=> 'Manage Receipt', 'url' => 'creceipt/manage_receipt','class' =>'active')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	//receipt Add Form
	public function manage_receipt()
	{			
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreceipt');
		$CI->load->model('Receipts');
		
		$config = array();
		$config["base_url"] = base_url()."creceipt/index";
		$config["total_rows"] = $this->Receipts->count_receipt();	  
		$config["per_page"] = 15;
		$config["uri_segment"] = 3;	
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$limit = $config["per_page"];
	    $links = $this->pagination->create_links();
		
        $content = $CI->lreceipt->receipt_list($limit,$page,$links);
        $sub_menu = array(
				array('label'=> 'Add receipt', 'url' => 'creceipt'),
				array('label'=> 'Manage receipt', 'url' => 'creceipt/manage_receipt','class' =>'active')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	//Insert receipt and uload
	public function insert_receipt()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Receipts');

		$data=array(
			'transaction_id' => $this->generator(20),
			'customer_id' 	 => $this->input->post('customer_id'),
			'receipt_no' 	 => strtoupper($this->generator(10)),
			'invoice_no' 	 => null,
			'description'    => $this->input->post('description'),
			'payment_type'   => $this->input->post('payment_type'),
			'cheque_no'      => $this->input->post('cheque_no'),
			'amount' 		 => $this->input->post('amount'),
			'date' 			 => $this->input->post('receipt_date'),
			'status' 		 => 1
			);
		$CI->Receipts->receipt_entry($data);
		$this->session->set_userdata(array('message'=>display('successfully_added')));
		if(isset($_POST['add-receipt'])){
			redirect(base_url('creceipt/manage_receipt'));
			exit;
		}elseif(isset($_POST['add-receipt-another'])){
			redirect(base_url('creceipt'));
			exit;
		}
	}
	//receipt Update Form
	public function receipt_update_form($receipt_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lreceipt');
		$content = $CI->lreceipt->receipt_edit_data($receipt_id);
		$sub_menu = array(
				array('label'=> 'Add Receipt', 'url' => 'creceipt'),
				array('label'=> 'Manage Receipt', 'url' => 'creceipt/manage_receipt'),
				array('label'=> 'Edit Receipt', 'url' => 'creceipt/receipt_update_form','class' =>'active')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	// receipt Update
	public function receipt_update()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Receipts');
		$receipt_no  = $this->input->post('receipt_no');
		$data=array(
			'customer_id' 	 => $this->input->post('customer_id'),
			'description'    => $this->input->post('description'),
			'amount' 		 => $this->input->post('amount'),
			'date' 			 => $this->input->post('receipt_date')
			);
		$CI->Receipts->update_receipt($receipt_no,$data);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('creceipt/manage_receipt'));
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