<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cpurchase extends CI_Controller {
	
	function __construct() {
      parent::__construct();
	  
	  $this->template->current_menu = 'purchase';
    }
	public function index()
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$content = $CI->lpurchase->purchase_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Product Add Form
	public function manage_purchase()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$CI->load->model('Purchases');
        $content = $CI->lpurchase->purchase_list();
      
		$this->template->full_admin_html_view($content);
	}
	//Insert Product and uload
	public function insert_purchase()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Purchases');
		$CI->Purchases->purchase_entry();
		$this->session->set_userdata(array('message'=>display('successfully_added')));
		if(isset($_POST['add-purchase'])){
			redirect(base_url('Cpurchase/manage_purchase'));
			exit;
		}elseif(isset($_POST['add-purchase-another'])){
			redirect(base_url('Cpurchase'));
			exit;
		}
	}
	//purchase Update Form
	public function purchase_update_form($purchase_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$content = $CI->lpurchase->purchase_edit_data($purchase_id);
		$this->template->full_admin_html_view($content);
	}
	// purchase Update
	public function purchase_update()
	{
	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Purchases');
		$CI->Purchases->update_purchase();
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Cpurchase/manage_purchase'));
		exit;
	}
	// product_delete
	public function purchase_delete()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Purchases');
		$purchase_id =  $_POST['purchase_id'];
		$CI->Purchases->delete_purchase($purchase_id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		return true;
			
	}
	//Purchase item by search
	public function purchase_item_by_search()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$supplier_id = $this->input->post('supplier_id');			
        $content = $CI->lpurchase->purchase_by_search($supplier_id);
		$this->template->full_admin_html_view($content);
	}
	//Product search by supplier id
	public function product_search_by_supplier(){

		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$CI->load->model('Suppliers');
		$supplier_id = $this->input->post('supplier_id');			
        $content = $CI->Suppliers->product_search_item($supplier_id);

        if (empty($content)) {
        	echo "No Product Found !";
	    }else{
	    	// Select option created for product
	        echo "<select name=\"product_id[]\"  onChange=\"purchase_productList(1);\" class=\"productSelection js-example-basic-single form-control\" id=\"product_id\">";
	        	echo "<option value=\"0\">".display('select_one')."</option>";
	        	foreach ($content as $product) {
	    			echo "<option value=".$product['product_id'].">";
	    			echo $product['product_name']."-(".$product['product_model'].")";
	    			echo "</option>"; 
	        	}	
	        echo "</select>";
	    }
	}

	//Retrive right now inserted data to cretae html
	public function purchase_details_data($purchase_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lpurchase');
		$content = $CI->lpurchase->purchase_details_data($purchase_id);	
		$this->template->full_admin_html_view($content);
	}
}