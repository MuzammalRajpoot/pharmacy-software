<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cinvoice extends CI_Controller {
	
	function __construct() {
      parent::__construct();
	  
	  $this->template->current_menu = 'memo';
    }
	public function index()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$content = $CI->linvoice->invoice_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Search Inovoice Item
	public function search_inovoice_item()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('linvoice');
		
		$customer_id = $this->input->post('customer_id');
        $content = $CI->linvoice->search_inovoice_item($customer_id);
		$this->template->full_admin_html_view($content);
	}
	//Product Add Form
	public function manage_invoice()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$CI->load->model('Invoices');

        $content = $CI->linvoice->invoice_list();
		$this->template->full_admin_html_view($content);
	}
	//POS invoice page load
	public function pos_invoice(){
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$content = $CI->linvoice->pos_invoice_add_form();
		$this->template->full_admin_html_view($content);
	}

	//Insert pos invoice
	public function insert_pos_invoice()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Invoices');
		$product_model = $this->input->post('product_model');
		
		$product_details = $CI->Invoices->pos_invoice_setup($product_model);

		$tr = " ";
		if (!empty($product_details)){
			$product_id = $this->generator(5);
			$tr .= "<tr id=\"row_".$product_id."\">
						<td class=\"\">
							
							<input type=\"text\" name=\"product_name\" onkeypress=\"invoice_productList(1);\" class=\"form-control productSelection \" value='".$product_details->product_name."- (".$product_details->product_id.")"."' placeholder='".display('product_name')."' required=\"\" id=\"product_name\" >


							<input type=\"hidden\" class=\"form-control autocomplete_hidden_value product_id_".$product_id."\" name=\"product_id[]\" id=\"SchoolHiddenId\" value = \"$product_details->product_id\" id=\"product_id\"/>
							
						</td>
						<td>
                            <input type=\"number\" name=\"available_quantity[]\" id=\"\" class=\"form-control text-right available_quantity_'".$product_details->product_id."'\" value='".$product_details->total_product."' readonly=\"\" />
                        </td>
						<td class=\"text-right\">
							<input type=\"number\" name=\"product_quantity[]\" onkeyup=\"quantity_calculate('".$product_id."');\" id=\"total_qntt_".$product_id."\" class=\"form-control text-right\" value=\"1\" min=\"1\"/>
						</td>
						<td class=\"\">
							<input type=\"number\" name=\"product_rate[]\" readonly=\"\" value='".$product_details->price."' id=\"price_item_".$product_id."\" class=\"price_item1 form-control text-right\" />
						</td>
						<td class=\"\">
							<input type=\"number\" name=\"discount[]\" onkeyup=\"quantity_calculate('".$product_id."');\" id=\"discount_".$product_id."\" class=\"form-control text-right\" placeholder=\"Discount\" value =\"0.00\"/>
						</td>
						<td class=\"text-right\">
							<input class=\"total_price form-control text-right\" type=\"text\" name=\"total_price[]\" id=\"total_price_".$product_id."\" value='".$product_details->price."' tabindex=\"-1\" readonly=\"readonly\"/>
						</td>
						<td>

							<input type=\"hidden\" id=\"total_tax_".$product_id."\" class=\"total_tax_1\" value='".$product_details->tax."'/>
                            <input type=\"hidden\" id=\"all_tax_".$product_id."\" class=\" total_tax\"/>

							<button style=\"text-align: right;\" class=\"btn btn-danger\" type=\"button\" value=\"Delete\" onclick=\"deleteRow(this)\">Delete</button>
						</td>
					</tr>";
			echo $tr;
		}else{
			return false;
		}
	}

	//Insert Product and uload
	public function insert_invoice()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Invoices');
		$invoice_id = $CI->Invoices->invoice_entry();
		$this->session->set_userdata(array('message'=>display('successfully_added')));
		$this->invoice_inserted_data($invoice_id);
	}
	//invoice Update Form
	public function invoice_update_form($invoice_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$content = $CI->linvoice->invoice_edit_data($invoice_id);
		$this->template->full_admin_html_view($content);
	}
	// invoice Update
	public function invoice_update()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Invoices');
		$invoice_id = $CI->Invoices->update_invoice();
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		$this->invoice_inserted_data($invoice_id);
	}
	//Retrive right now inserted data to cretae html
	public function invoice_inserted_data($invoice_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$content = $CI->linvoice->invoice_html_data($invoice_id);		
		$this->template->full_admin_html_view($content);
	}
	//Retrive right now inserted data to cretae html
	public function pos_invoice_inserted_data($invoice_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('linvoice');
		$content = $CI->linvoice->pos_invoice_html_data($invoice_id);		
		$this->template->full_admin_html_view($content);
	}
	
	// retrieve_product_data
	public function retrieve_product_data()
	{	

		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Invoices');
		$product_id = $this->input->post('product_id');
		//$product_info = $CI->Invoices->retrieve_product_data($product_id);
		//$product_stock_check = $this->product_stock_check($product_id);

		$product_info = $CI->Invoices->get_total_product($product_id);
		echo json_encode($product_info);
	}
	// product_delete
	public function invoice_delete()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Invoices');
		$invoice_id =  $_POST['invoice_id'];
		$CI->Invoices->delete_invoice($invoice_id);
		$this->session->set_userdata(array('message'=>display('successfully_delete')));
		return true;	
	}
	//AJAX INVOICE STOCKs
	public function product_stock_check($product_id)
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Invoices');
		//$product_id =  $this->input->post('product_id');

		$purchase_stocks = $CI->Invoices->get_total_purchase_item($product_id);	
		$total_purchase = 0;		
		if(!empty($purchase_stocks)){	
			foreach($purchase_stocks as $k=>$v){
				$total_purchase = ($total_purchase + $purchase_stocks[$k]['quantity']);
			}
		}
		$sales_stocks = $CI->Invoices->get_total_sales_item($product_id);
		$total_sales = 0;	
		if(!empty($sales_stocks)){	
			foreach($sales_stocks as $k=>$v){
				$total_sales = ($total_sales + $sales_stocks[$k]['quantity']);
			}
		}
		
		$final_total = ($total_purchase - $total_sales);
		return $final_total ;
	}

	//This function is used to Generate Key
	public function generator($lenth)
	{
		$number=array("1","2","3","4","5","6","7","8","9");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,8);
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