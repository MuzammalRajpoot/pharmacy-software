<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoices extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth');
		$this->load->library('lcustomer');
		$this->load->library('session');
		$this->load->model('Customers');
		$this->auth->check_admin_auth();
	}
	//Count invoice
	public function count_invoice()
	{
		return $this->db->count_all("invoice");
	}
	//invoice List
	public function invoice_list()
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->order_by('a.invoice','desc');
		$this->db->limit('500');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//invoice Search Item
	public function search_inovoice_item($customer_id)
	{
		$this->db->select('a.*,b.customer_name');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->where('b.customer_id',$customer_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//POS invoice entry
	public function pos_invoice_setup($product_model){
		$product_information = $this->db->select('*')
						->from('product_information')
						->where('product_model',$product_model)
						->get()
						->row();
						
		if (isset($product_information->product_id)) {

			$this->db->select('SUM(a.quantity) as total_purchase');
			$this->db->from('product_purchase_details a');
			$this->db->where('a.product_id',$product_information->product_id);
			$total_purchase = $this->db->get()->row();

			$this->db->select('SUM(b.quantity) as total_sale');
			$this->db->from('invoice_details b');
			$this->db->where('b.product_id',$product_information->product_id);
			$total_sale = $this->db->get()->row();

			
			$data2 = (object)array(
				'total_product' => ($total_purchase->total_purchase - $total_sale->total_sale), 
				'supplier_price' => $product_information->supplier_price, 
				'price' => $product_information->price, 
				'supplier_id' => $product_information->supplier_id, 
				'tax' => $product_information->tax, 
				'product_id' => $product_information->product_id, 
				'product_name' => $product_information->product_name, 
				);

			return $data2;
		}else{
			return false;
		}
	}
	//POS customer setup
	public function pos_customer_setup(){
		return $this->db->select('*')
						->from('customer_information')
						->where('customer_name','Walking Customer')
						->get()
						->row();
	}

	//Count invoice
	public function invoice_entry()
	{
		$invoice_id = $this->generator(10);
		$invoice_id = strtoupper($invoice_id);
		
		$product_id = $this->input->post('product_id');
		if ($product_id == null) {
			$this->session->set_userdata(array('error_message'=>display('please_select_product')));
			redirect('Cinvoice/pos_invoice');
		}

		if (($this->input->post('customer_name_others') == null) && ($this->input->post('customer_id') == null )) {
			$this->session->set_userdata(array('error_message'=>display('please_select_customer')));
			redirect(base_url().'Cinvoice');
		}

		
		//Customer data Existence Check.
		$paid_amount=$this->input->post('paid_amount');
		if(($this->input->post('customer_name_others') != null) && ($paid_amount == null )){

			$customer_id=$this->auth->generator(15);
		  	//Customer  basic information adding.
			$data=array(
				'customer_id' 	=> $customer_id,
				'customer_name' => $this->input->post('customer_name_others'),
				'customer_address' 		=>$this->input->post('customer_name_others_address'),
				'customer_mobile' 		=> "",
				'customer_email' 		=> "",
				'status' 				=> 2
				);
		
			$this->Customers->customer_entry($data);
		  	//Previous balance adding -> Sending to customer model to adjust the data.
			$this->Customers->previous_balance_add(0,$customer_id);
		}
		else{
			$customer_id=$this->input->post('customer_id');
		}

		//Paid ammount 
		if(($this->input->post('customer_name_others') != null) && ($paid_amount != null )){

			$customer_id=$this->auth->generator(15);
		  	//Customer  basic information adding.
			$data=array(
				'customer_id' 	=> $customer_id,
				'customer_name' => $this->input->post('customer_name_others'),
				'customer_address' 		=>$this->input->post('customer_name_others_address'),
				'customer_mobile' 		=> "",
				'customer_email' 		=> "",
				'status' 				=> 1
				);
		
			$this->Customers->customer_entry($data);
		  	//Previous balance adding -> Sending to customer model to adjust the data.
			$this->Customers->previous_balance_add(0,$customer_id);

			//Full or partial Payment record.
			if($this->input->post('paid_amount') > 0)
			{
				//Insert to customer_ledger Table 
				$data2 = array(
					'transaction_id'	=>	$this->auth->generator(15),
					'customer_id'		=>	$customer_id,
					'receipt_no'		=>	$this->auth->generator(10),
					'date'				=>	$this->input->post('invoice_date'),
					'amount'			=>	$this->input->post('paid_amount'),
					'payment_type'		=>	1,
					'description'		=>	'ITP',
					'status'			=>	1
				);
				$this->db->insert('customer_ledger',$data2);


				// Inserting for Accounts adjustment.
				############ default table :: customer_payment :: inflow_92mizdldrv #################
				//Insert to customer_ledger Table 
				$account_table="inflow_92mizdldrv";
				$account_adjustment = array(
					'transection_id'	=>	$this->auth->generator(15),
					'tracing_id'		=>	$customer_id,
					'date'				=>	$this->input->post('invoice_date'),
					'amount'			=>	$this->input->post('paid_amount'),
					'payment_type'		=>	1,
					'description'		=>	'ITP',
					'status'			=>	1
				);
				$this->db->insert($account_table,$account_adjustment);
			}
		}

		//Data inserting into invoice table
		$data=array(
			'invoice_id'		=>	$invoice_id,
			'customer_id'		=>	$customer_id,
			'date'				=>	$this->input->post('invoice_date'),
			'total_amount'		=>	$this->input->post('grand_total_price'),
			'invoice'			=>	$this->number_generator(),
			'status'			=>	1
		);
		$this->db->insert('invoice',$data);
		
		
		//Insert to customer_ledger Table 
		$data2 = array(
			'transaction_id'	=>	$this->generator(15),
			'customer_id'		=>	$customer_id,
			'invoice_no'		=>	$invoice_id,
			'date'				=>	$this->input->post('invoice_date'),
			'amount'			=>	$this->input->post('grand_total_price'),
			'status'			=>	1
		);
		$this->db->insert('customer_ledger',$data2);

		//Insert payment method
		$payment_method = $this->input->post('payment_method');
		$card_no = $this->input->post('card_no');
		$bank_name = $this->input->post('bank_name');
		if ($card_no != null) {
			$data3 = array(
			'payment_type'	=>	$payment_method,
			'card_no'			=>	$card_no,
			'bank_name'			=>	$bank_name,
			'price'				=>	$this->input->post('grand_total_price'),
			);
			$this->db->insert('payment_method',$data3);
		}

		
		$rate = $this->input->post('product_rate');
		$p_id = $this->input->post('product_id');
		$total_amount = $this->input->post('total_price');
		$discount = $this->input->post('discount');

		$available_quantity = $this->input->post('available_quantity');
		$quantity = $this->input->post('product_quantity');

		$result = array();
		foreach($quantity as $k => $v)
		{
		    if($v > $available_quantity[$k])
		    {
		       $this->session->set_userdata(array('error_message'=>display('you_can_not_buy_greater_than_available_quantity')));
		       redirect('Cinvoice');
		    }
		}

		for ($i=0, $n=count($quantity); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$discount_rate = $discount[$i];
			$total_price = $total_amount[$i];
			$supplier_rate=$this->supplier_rate($product_id);
			
			$data1 = array(
				'invoice_details_id'	=>	$this->generator(15),
				'invoice_id'			=>	$invoice_id,
				'product_id'			=>	$product_id,
				'quantity'				=>	$product_quantity,
				'rate'					=>	$product_rate,
				'discount'           	=>	$discount_rate,
				'tax'           		=>	$this->input->post('total_tax'),
				'paid_amount'           		=>	$this->input->post('paid_amount'),
				'due_amount'           	=>	$this->input->post('due_amount'),
				'supplier_rate'         =>	$supplier_rate[0]['supplier_price'],
				'total_price'           =>	$total_price,
				'status'				=>	1
			);
			
			if(!empty($quantity))
			{
				$this->db->insert('invoice_details',$data1);

			}
		}
		return $invoice_id;
	}
	//Get Supplier rate of a product
	public function supplier_rate($product_id)
	{
		$this->db->select('supplier_price');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id)); 
		$query = $this->db->get();
		return $query->result_array();
	
	}
	//Retrieve invoice Edit Data
	public function retrieve_invoice_editdata($invoice_id)
	{
		$this->db->select('a.*,b.customer_name,c.*,c.tax as total_tax,c.product_id,d.product_name,d.product_model,d.tax');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->join('invoice_details c','c.invoice_id = a.invoice_id');
		$this->db->join('product_information d','d.product_id = c.product_id');
		$this->db->where('a.invoice_id',$invoice_id);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//update_invoice
	public function update_invoice()
	{
		$invoice_id = $this->input->post('invoice_id');

		$data=array(
			'customer_id'		=>	$this->input->post('customer_id'),
			'date'				=>	$this->input->post('invoice_date'),
			'total_amount'		=>	$this->input->post('grand_total_price')
		);
		$data2 = array(
			'customer_id'		=>	$this->input->post('customer_id'),
			'invoice_no'		=>	$invoice_id,
			'date'				=>	$this->input->post('invoice_date'),
			'amount'			=>	$this->input->post('grand_total_price')
		);
		
		if($invoice_id!='')
		{
			$this->db->where('invoice_id',$invoice_id);
			$this->db->update('invoice',$data); 
			
			//Update Another table
			$this->db->where('invoice_no',$invoice_id);
			$this->db->update('customer_ledger',$data2); 
		}

		$invoice_d_id = $this->input->post('invoice_details_id');
		$rate = $this->input->post('product_rate');
		$p_id = $this->input->post('product_id');
		$invoice_id = $this->input->post('invoice_id');
		$quantity = $this->input->post('product_quantity');
		$total_amount = $this->input->post('total_price');
		$discount_rate = $this->input->post('discount');
		
		for ($i=0, $n=count($invoice_d_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$invoice_detail_id = $invoice_d_id[$i];
			$total_price = $total_amount[$i];
			$discount = $discount_rate[$i];
			
			$data1 = array(
				'invoice_id'		=>	$invoice_id,
				'product_id'		=>	$product_id,
				'quantity'			=>	$product_quantity,
				'rate'				=>	$product_rate,
				'discount'			=>	$discount,
				'total_price'		=>	$total_price,
				'tax'           		=>	$this->input->post('total_tax'),
				'paid_amount'           =>	$this->input->post('paid_amount'),
				'due_amount'           	=>	$this->input->post('due_amount'),
			);
			if(!empty($quantity))
			{
				$this->db->where('invoice_details_id',$invoice_detail_id);
				$this->db->update('invoice_details',$data1); 
			}
		}
		return $invoice_id;
	}
	//Retrieve invoice_html_data
	public function retrieve_invoice_html_data($invoice_id)
	{
		$this->db->select('a.*,b.*,c.*,d.product_id,d.product_name,d.product_details,d.product_model');
		$this->db->from('invoice a');
		$this->db->join('customer_information b','b.customer_id = a.customer_id');
		$this->db->join('invoice_details c','c.invoice_id = a.invoice_id');
		$this->db->join('product_information d','d.product_id = c.product_id');
		$this->db->where('a.invoice_id',$invoice_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Delete invoice Item
	public function retrieve_product_data($product_id)
	{
		$this->db->select('supplier_price,price,supplier_id,tax');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id,'status' => 1)); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	//Retrieve company Edit Data
	public function retrieve_company()
	{
		$this->db->select('*');
		$this->db->from('company_information');
		$this->db->limit('1');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Delete invoice Item
	public function delete_invoice($invoice_id)
	{	
		//Delete Invoice table
		$this->db->where('invoice_id',$invoice_id);
		$this->db->delete('invoice'); 
		//Delete invoice_details table
		$this->db->where('invoice_id',$invoice_id);
		$this->db->delete('invoice_details'); 
		return true;
	}
	public function invoice_search_list($cat_id,$company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('invoices a');
		$this->db->join('invoice_sub_category b','b.sub_category_id = a.sub_category_id');
		$this->db->join('invoice_category c','c.category_id = b.category_id');
		$this->db->where('a.sister_company_id',$company_id);
		$this->db->where('c.category_id',$cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// GET TOTAL PURCHASE PRODUCT
	public function get_total_purchase_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_purchase');
		$this->db->from('product_purchase_details');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// GET TOTAL SALES PRODUCT
	public function get_total_sales_item($product_id)
	{
		$this->db->select('SUM(quantity) as total_sale');
		$this->db->from('invoice_details');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//Get total product
	public function get_total_product($product_id){
		$this->db->select('SUM(a.quantity) as total_purchase');
		$this->db->from('product_purchase_details a');
		$this->db->where('a.product_id',$product_id);
		$total_purchase = $this->db->get()->row();

		$this->db->select('SUM(b.quantity) as total_sale');
		$this->db->from('invoice_details b');
		$this->db->where('b.product_id',$product_id);
		$total_sale = $this->db->get()->row();

		$this->db->select('supplier_price,price,supplier_id,tax');
		$this->db->from('product_information');
		$this->db->where(array('product_id' => $product_id,'status' => 1)); 
		$product_information = $this->db->get()->row();

		$data2 = array(
			'total_product' => ($total_purchase->total_purchase - $total_sale->total_sale), 
			'supplier_price' => $product_information->supplier_price, 
			'price' => $product_information->price, 
			'supplier_id' => $product_information->supplier_id, 
			'tax' => $product_information->tax, 
			);

		return $data2;
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
	//NUMBER GENERATOR
	public function number_generator()
	{
		$this->db->select_max('invoice', 'invoice_no');
		$query = $this->db->get('invoice');	
		$result = $query->result_array();	
		$invoice_no = $result[0]['invoice_no'];
		if ($invoice_no !='') {
			$invoice_no = $invoice_no + 1;	
		}else{
			$invoice_no = 1000;
		}
		return $invoice_no;		
	}

}