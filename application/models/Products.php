<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//Count Product
	public function count_product()
	{
		return $this->db->count_all("product_information");
	}
	//Product List
	public function product_list()
	{
		$query=$this->db->select('supplier_information.*,product_information.*')
				->from('product_information')
				->join('supplier_information', 'product_information.supplier_id = supplier_information.supplier_id','left')
				->order_by('product_information.supplier_id','desc')
				->order_by('product_information.product_id','desc')
				->limit('500')
				->get();
		if ($query->num_rows() > 0) {
		 	return $query->result_array();	
		}
		return false;

	}
	//Product tax list
	public function retrieve_product_tax(){
		$result = $this->db->select('*')
                    ->from('tax_information')
                    ->get()
                    ->result();

        return $result;
	}
	//Tax selected item
	public function tax_selected_item($tax_id){
		$result = $this->db->select('*')
                    ->from('tax_information')
                    ->where('tax_id',$tax_id)
                    ->get()
                    ->result();

        return $result;
	}

	//Product generator id check 
	public function product_id_check($product_id){
		$query=$this->db->select('*')
				->from('product_information')
				->where('product_id',$product_id)
				->get();
		if ($query->num_rows() > 0) {
		 	return true;	
		}else{
			return false;
		}
	}
	//Count Product
	public function product_entry($data)
	{
		
		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where('status',1);
		$this->db->where('product_model',$data['product_model']);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return FALSE;
		}else{
			$this->db->insert('product_information',$data);
			$this->db->select('*');
			$this->db->from('product_information');
			$this->db->where('status',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->product_name."-(".$row->product_model.")",'value'=>$row->product_id);
			}
			$cache_file = './my-assets/js/admin_js/json/product.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
			return TRUE;
		}
	}
	//Retrieve Product Edit Data
	public function retrieve_product_editdata($product_id)
	{
		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where('product_id',$product_id);
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
	public function update_product($data,$product_id)
	{

		$this->db->where('product_id',$product_id);
		$this->db->update('product_information',$data); 

	

		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where('status',1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$json_product[] = array('label'=>$row->product_name."-(".$row->product_model.")",'value'=>$row->product_id);
		}
		$cache_file = './my-assets/js/admin_js/json/product.json';
		$productList = json_encode($json_product);
		file_put_contents($cache_file,$productList);
		return true;
	}
	// Delete Product Item
	public function delete_product($product_id)
	{

		#### Check product is using on system or not##########
		# If this product is used any calculation you can't delete this product.
		# but if not used you can delete it from the system.
		$this->db->select('product_id');
		$this->db->from('product_purchase_details');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		$affected_row=$this->db->affected_rows();

		if($affected_row == 0) {
			$this->db->where('product_id',$product_id);
			$this->db->delete('product_information'); 
			$this->session->set_userdata(array('message'=>display('successfully_delete')));

			$this->db->select('*');
			$this->db->from('product_information');
			$this->db->where('status',1);
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$json_product[] = array('label'=>$row->product_name."-(".$row->product_model.")",'value'=>$row->product_id);
			}
			$cache_file = './my-assets/js/admin_js/json/product.json';
			$productList = json_encode($json_product);
			file_put_contents($cache_file,$productList);
			return true;
		}else{
			$this->session->set_userdata(array('message'=>display('you_cant_delete_this_product')));
			return false;
		}	
	}
	//Product By Search 
	public function product_search_item($product_id)
	{

		$this->db->select('supplier_information.*,product_information.*');
		$this->db->from('product_information');
		$this->db->join('supplier_information', 'product_information.supplier_id = supplier_information.supplier_id','left');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}	
	
	//Duplicate Entry Checking 
	public function product_model_search($product_model)
	{
		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where('product_model',$product_model);
		$query = $this->db->get();
		return $this->db->affected_rows();
	}	
	//Product Details
	public function product_details_info($product_id)
	{
		$this->db->select('*');
		$this->db->from('product_information');
		$this->db->where('product_id',$product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Product Purchase Report
	public function product_purchase_info($product_id)
	{
		$this->db->select('a.*,b.*,c.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('product_purchase_details b','b.purchase_id = a.purchase_id');
		$this->db->join('supplier_information c','c.supplier_id = a.supplier_id');
		$this->db->where('b.product_id',$product_id);
		$this->db->order_by('a.purchase_id','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	// Invoice Data for specific data
	public function invoice_data($product_id)
	{
		$this->db->select('a.*,b.*,c.customer_name');
		$this->db->from('invoice a');
		$this->db->join('invoice_details b','b.invoice_id = a.invoice_id');
		$this->db->join('customer_information c','c.customer_id = a.customer_id');
		$this->db->where('b.product_id',$product_id);
		$this->db->order_by('a.invoice_id','asc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
	public function previous_stock_data($product_id,$startdate)
	{
		$startdate.=" 00:00:00";
		
		$this->db->select('date,sum(quantity) as quantity');
		$this->db->from('product_report');
		$this->db->where('product_id',$product_id);
		$this->db->where('date <=',$startdate);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	
	}
// Invoice Data for specific data
	public function invoice_data_supplier_rate($product_id,$startdate,$enddate)
	{


		$startdate.=" 00:00:00";
		$enddate.=" 00:00:00";
		
		$this->db->select('date,sum(quantity) as quantity,rate,-rate*sum(quantity) as total_price,account');
		$this->db->from('product_report');
		$this->db->where('product_id',$product_id);
		$this->db->where('date >=',$startdate);
		$this->db->where('date <=',$enddate);
		$this->db->group_by('date','account');
		$this->db->order_by('date','asc');
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();	
		}
		return false;
	}
}