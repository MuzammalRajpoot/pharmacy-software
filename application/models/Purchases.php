<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchases extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//Count purchase
	public function count_purchase()
	{
		$this->db->select('a.*,b.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('supplier_information b','b.supplier_id = a.supplier_id');
		return $query=$this->db->get()->num_rows();
	}
	//purchase List
	public function purchase_list()
	{
		$this->db->select('a.*,b.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('supplier_information b','b.supplier_id = a.supplier_id');
		$this->db->order_by('a.purchase_date','desc');
		$this->db->order_by('purchase_id','desc');
		$this->db->limit('500');
		$query = $this->db->get();
		
		$last_query =  $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//Select All Supplier List
	public function select_all_supplier()
	{
		$query = $this->db->select('*')
					->from('supplier_information')
					->where('status','1')
					->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}

	//purchase Search  List
	public function purchase_by_search($supplier_id)
	{
		$this->db->select('a.*,b.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('supplier_information b','b.supplier_id = a.supplier_id');
		$this->db->where('b.supplier_id',$supplier_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Count purchase
	public function purchase_entry()
	{
		$purchase_id = date('YmdHis');
		
		$p_id = $this->input->post('product_id');
		$supplier_id=$this->input->post('supplier_id');

		//supplier & product id relation ship checker.
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_id =$p_id[$i];
			$value=$this->product_supplier_check($product_id,$supplier_id);
			if($value==0){
			  	$this->session->set_userdata(array('message'=>"Product and Supplier did not match!"));
			  	redirect(base_url('cpurchase'));
			  	exit();
			}
		}
		
		$data=array(
			'purchase_id'			=>	$purchase_id,
			'chalan_no'				=>	$this->input->post('chalan_no'),
			'supplier_id'			=>	$this->input->post('supplier_id'),
			'grand_total_amount'	=>	$this->input->post('grand_total_price'),
			'purchase_date'			=>	$this->input->post('purchase_date'),
			'purchase_details'		=>	$this->input->post('purchase_details'),
			'status'				=>	1
		);
		$this->db->insert('product_purchase',$data);
		
		$ledger=array(
			'transaction_id'		=>	$purchase_id,
			'chalan_no'				=>	$this->input->post('chalan_no'),
			'supplier_id'			=>	$this->input->post('supplier_id'),
			'amount'				=>	$this->input->post('grand_total_price'),
			'date'					=>	$this->input->post('purchase_date'),
			'description'			=>	$this->input->post('purchase_details'),
			'status'				=>	1
		);
		$this->db->insert('supplier_ledger',$ledger);
			
		$rate = $this->input->post('product_rate');
		$quantity = $this->input->post('product_quantity');
		$t_price = $this->input->post('total_price');
		
		for ($i=0, $n=count($p_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$total_price = $t_price[$i];
			
			$data1 = array(
				'purchase_detail_id'	=>	$this->generator(15),
				'purchase_id'		=>	$purchase_id,
				'product_id'		=>	$product_id,
				'quantity'			=>	$product_quantity,
				'rate'				=>	$product_rate,
				'total_amount'		=>	$total_price,
				'status'			=>	1
			);

			if(!empty($quantity))
			{
				$this->db->insert('product_purchase_details',$data1);
			}
		}
		return true;
	}
	//Retrieve purchase Edit Data
	public function retrieve_purchase_editdata($purchase_id)
	{
		$this->db->select('a.*,b.*,c.product_id,c.product_name,c.product_model,d.supplier_id,d.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('product_purchase_details b','b.purchase_id =a.purchase_id');
		$this->db->join('product_information c','c.product_id =b.product_id');
		$this->db->join('supplier_information d','d.supplier_id = a.supplier_id');
		$this->db->where('a.purchase_id',$purchase_id);
		$this->db->order_by('a.purchase_details','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
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
	//Update Categories
	public function update_purchase()
	{
		$purchase_id  = $this->input->post('purchase_id');
		$data=array(
			'chalan_no'			=>	$this->input->post('chalan_no'),
			'supplier_id'			=>	$this->input->post('supplier_id'),
			'grand_total_amount'		=>	$this->input->post('grand_total_price'),
			'purchase_date'			=>	$this->input->post('purchase_date'),
			'purchase_details'		=>	$this->input->post('purchase_details')
		);
		if($purchase_id!='')
		{
			$this->db->where('purchase_id',$purchase_id);
			$this->db->update('product_purchase',$data); 
			
		}
		
		$rate = $this->input->post('product_rate');
		$p_id = $this->input->post('product_id');
		$quantity = $this->input->post('product_quantity');
		$t_price = $this->input->post('total_price');
		$purchase_d_id = $this->input->post('purchase_detail_id');
		
		for ($i=0, $n=count($purchase_d_id); $i < $n; $i++) {
			$product_quantity = $quantity[$i];
			$product_rate = $rate[$i];
			$product_id = $p_id[$i];
			$total_price = $t_price[$i];
			$purchase_detail_id = $purchase_d_id[$i];
			
			$data1 = array(
				'product_id'		=>	$product_id,
				'quantity'			=>	$product_quantity,
				'rate'				=>	$product_rate,
				'total_amount'		=>	$total_price
			);
			if(!empty($quantity))
			{
				$this->db->where('purchase_detail_id',$purchase_detail_id);
				$this->db->update('product_purchase_details',$data1); 
			}
		}
		return true;
	}
	// Delete purchase Item
	public function delete_purchase($purchase_id)
	{
		//Delete product_purchase table
		$this->db->where('purchase_id',$purchase_id);
		$this->db->delete('product_purchase'); 
		//Delete product_purchase_details table
		$this->db->where('purchase_id',$purchase_id);
		$this->db->delete('product_purchase_details');
		return true;
	}
	public function purchase_search_list($cat_id,$company_id)
	{
		$this->db->select('a.*,b.sub_category_name,c.category_name');
		$this->db->from('purchases a');
		$this->db->join('purchase_sub_category b','b.sub_category_id = a.sub_category_id');
		$this->db->join('purchase_category c','c.category_id = b.category_id');
		$this->db->where('a.sister_company_id',$company_id);
		$this->db->where('c.category_id',$cat_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	//Retrieve purchase_details_data
	public function purchase_details_data($purchase_id)
	{
		$this->db->select('a.*,b.*,c.*,e.purchase_details,d.product_id,d.product_name,d.product_model');
		$this->db->from('product_purchase a');
		$this->db->join('supplier_information b','b.supplier_id = a.supplier_id');
		$this->db->join('product_purchase_details c','c.purchase_id = a.purchase_id');
		$this->db->join('product_information d','d.product_id = c.product_id');
		$this->db->join('product_purchase e','e.purchase_id = c.purchase_id');
		$this->db->where('a.purchase_id',$purchase_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	
	//This function will check the product & supplier relationship.
	public function product_supplier_check($product_id,$supplier_id)
	{
	 $this->db->select('*');
	  $this->db->from('product_information');
	  $this->db->where('product_id',$product_id);
	  $this->db->where('supplier_id',$supplier_id);	
	  $query = $this->db->get();
		if ($query->num_rows() > 0) {
			return true;	
		}
		return 0;
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