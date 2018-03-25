<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Csupplier extends CI_Controller {
	
	public $supplier_id;
	function __construct() {
      parent::__construct(); 
		$this->load->library('auth');
		$this->load->library('lsupplier');
		$this->load->library('session');
		$this->load->model('Suppliers');
		$this->auth->check_admin_auth();
		$this->template->current_menu = 'supplier';
    }
	public function index()
	{
		$content = $this->lsupplier->supplier_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Supplier Search Item
	public function supplier_search_item()
	{	
		$supplier_id = $this->input->post('supplier_id');			
        $content = $this->lsupplier->supplier_search_item($supplier_id);
        
		$this->template->full_admin_html_view($content);
	}
	//Product Add Form
	public function manage_supplier()
	{
        $content = $this->lsupplier->supplier_list();
		$this->template->full_admin_html_view($content);
	}
	//Insert Product and uload
	public function insert_supplier()
	{
		$data=array(
			'supplier_id' 	=> $this->auth->generator(20),
			'supplier_name' => $this->input->post('supplier_name'),
			'address' 		=> $this->input->post('address'),
			'mobile' 		=> $this->input->post('mobile'),
			'details' 		=> $this->input->post('details'),
			'status' 				=> 1
			);
		$supplier = $this->lsupplier->insert_supplier($data);
		if ($supplier == TRUE) {
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			if(isset($_POST['add-supplier'])){
				redirect(base_url('Csupplier/manage_supplier'));
				exit;
			}elseif(isset($_POST['add-supplier-another'])){
				redirect(base_url('Csupplier'));
				exit;
			}
		}else{
			$this->session->set_userdata(array('error_message'=>display('already_exists')));
			if(isset($_POST['add-supplier'])){
				redirect(base_url('Csupplier/manage_supplier'));
				exit;
			}elseif(isset($_POST['add-supplier-another'])){
				redirect(base_url('Csupplier'));
				exit;
			}
		}
	}
	//Supplier Update Form
	public function supplier_update_form($supplier_id)
	{	
		$content = $this->lsupplier->supplier_edit_data($supplier_id);
	
		$this->template->full_admin_html_view($content);
	}
	// Supplier Update
	public function supplier_update()
	{
		$supplier_id  = $this->input->post('supplier_id');
		$data=array(
			'supplier_name' => $this->input->post('supplier_name'),
			'address' 		=> $this->input->post('address'),
			'mobile' 		=> $this->input->post('mobile'),
			'details' 		=> $this->input->post('details')
			);
		$this->Suppliers->update_supplier($data,$supplier_id);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Csupplier/manage_supplier'));
		exit;
	}
	// Supplier Delete from System
	public function supplier_delete()
	{	
		$supplier_id =  $_POST['supplier_id'];
		$this->Suppliers->delete_supplier($supplier_id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		return true;	
	}
	// Supplier details findings !!!!!!!!!!!!!! Inactive Now !!!!!!!!!!!!
	public function supplier_details($supplier_id)
	{	
		$content = $this->lsupplier->supplier_detail_data($supplier_id);
		$this->supplier_id=$supplier_id;
		$this->template->full_admin_html_view($content);
	}
//Supplier Ledger Book
	public function	supplier_ledger($supplier_id)
	{
		$content = $this->lsupplier->supplier_ledger($supplier_id);
		$this->supplier_id=$supplier_id;
		$this->template->full_admin_html_view($content);
	
	}
// Supplier wise sales report details
	public function supplier_sales_details($supplier_id)
	{	
		$content = $this->lsupplier->supplier_sales_details($supplier_id);
		$this->supplier_id=$supplier_id;
		$this->template->full_admin_html_view($content);
	}
	
// Supplier wise sales report summary
	public function supplier_sales_summary($supplier_id)
	{	
		$content = $this->lsupplier->supplier_sales_summary($supplier_id);
		$this->supplier_id=$supplier_id;
		$this->template->full_admin_html_view($content);
	}

// Actual Ledger based on sales & deposited amount
	public function sales_payment_actual($supplier_id)
	{	$limit=300;
		$start_record=0;
		$links="";
		$content = $this->lsupplier->sales_payment_actual($supplier_id,$limit,$start_record,$links);
		$this->supplier_id=$supplier_id;
		$this->template->full_admin_html_view($content);
	}
}