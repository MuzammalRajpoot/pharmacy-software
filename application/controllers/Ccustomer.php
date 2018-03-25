<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ccustomer extends CI_Controller {
	public $menu;
	function __construct() {
      parent::__construct();
		$this->load->library('auth');
		$this->load->library('lcustomer');
		$this->load->library('session');
		$this->load->model('Customers');
		$this->auth->check_admin_auth();
		$this->template->current_menu = 'customer';
	  
    }
	//Default loading for Customer System.
	public function index()
	{
	//Calling Customer add form which will be loaded by help of "lcustomer,located in library folder"
		$content = $this->lcustomer->customer_add_form();
	//Here ,0 means array position 0 will be active class
		$this->template->full_admin_html_view($content);
	}
	//customer_search_item
	public function customer_search_item()
	{
		$customer_id = $this->input->post('customer_id');			
		$content = $this->lcustomer->customer_search_item($customer_id);
		$this->template->full_admin_html_view($content);
	}
	//Manage customer
	public function manage_customer()
	{
		$this->load->model('Customers');
		
		$content = $this->lcustomer->customer_list();
		$this->template->full_admin_html_view($content);;
	}
	//Product Add Form
	public function credit_customer()
	{
		$this->load->model('Customers');
		
		$content = $this->lcustomer->credit_customer_list();
		$this->template->full_admin_html_view($content);;
	}
	//Paid Customer list. The customer who will pay 100%.
	public function paid_customer()
	{
		$this->load->model('Customers');
		$content = $this->lcustomer->paid_customer_list();
		$this->template->full_admin_html_view($content);;
	}
	
	
	//Insert Product and upload
	public function insert_customer()
	{
		$customer_id=$this->auth->generator(15);

	  	//Customer  basic information adding.
		$data=array(
			'customer_id' 			=> $customer_id,
			'customer_name' 		=> $this->input->post('customer_name'),
			'customer_address' 		=> $this->input->post('address'),
			'customer_mobile' 		=> $this->input->post('mobile'),
			'customer_email' 		=> $this->input->post('email'),
			'status' 				=> 2
			);
		$result=$this->Customers->customer_entry($data);
		if ($result == TRUE) {
			//Previous balance adding -> Sending to customer model to adjust the data.
			$this->Customers->previous_balance_add($this->input->post('previous_balance'),$customer_id);
							
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			if(isset($_POST['add-customer'])){
				redirect(base_url('Ccustomer/manage_customer'));
				exit;
			}elseif(isset($_POST['add-customer-another'])){
				redirect(base_url('Ccustomer'));
				exit;
			}
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			redirect(base_url('Ccustomer'));
		}
	}
	//customer Update Form
	public function customer_update_form($customer_id)
	{	
		$content = $this->lcustomer->customer_edit_data($customer_id);
		$this->menu=array('label'=> 'Edit Customer', 'url' => 'Ccustomer');
		$this->template->full_admin_html_view($content);
	}
			
	//Customer Ledger
	public function customer_ledger($customer_id)
	{	
		$content = $this->lcustomer->customer_ledger_data($customer_id);
		$this->menu=array('label'=> 'Customer Ledger', 'url' => 'Ccustomer');
		$this->template->full_admin_html_view($content);
	}
	
	//Customer Final Ledger
	public function customerledger($customer_id)
	{	
		$content = $this->lcustomer->customerledger_data($customer_id);
		$this->menu=array('label'=> 'Customer Ledger', 'url' => 'Ccustomer');
		$this->template->full_admin_html_view($content);
	}	
	//Customer Previous Balance
	public function previous_balance_form()
	{	
		$content = $this->lcustomer->previous_balance_form();
		$this->template->full_admin_html_view($content);
	}
	// customer Update
	public function customer_update()
	{
		$this->load->model('Customers');
		$customer_id  = $this->input->post('customer_id');
		$data=array(
			'customer_name' => $this->input->post('customer_name'),
			'customer_address' 		=> $this->input->post('address'),
			'customer_mobile' 		=> $this->input->post('mobile'),
			'customer_email' 		=> $this->input->post('email')
			);
		$this->Customers->update_customer($data,$customer_id);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Ccustomer'));
		exit;
	}
	// product_delete
	public function customer_delete()
	{	
		$this->load->model('Customers');
		$customer_id =  $_POST['customer_id'];
		$this->Customers->delete_customer($customer_id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		return true;
	}			
}