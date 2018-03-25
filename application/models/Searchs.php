<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Searchs extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	
	#============Medicine search============#
	public function medicine_search($keyword)
	{
		$this->db->select("a.*,(SUM(b.Purchase) - SUM(b.sell)) AS total_stock");
		$this->db->from('product_information a');
		$this->db->join('stock_history b','b.product_id = a.product_id','left');
		$this->db->group_by('a.product_id');
		$this->db->or_like(
			array(
				'a.product_name' => $keyword,
				'a.product_model' => $keyword,
				'a.generic_name' => $keyword,
				'a.product_location' => $keyword,
				'a.box_size' => $keyword,
				));
		$this->db->order_by('a.product_id','desc');
		$query = $this->db->get();

		return $stok_report = $query->result_array();
	}

	#============Customer search============#
	public function customer_search($keyword)
	{
		$this->db->select("*");
		$this->db->from('customer_information');
		$this->db->or_like(
			array(
				'customer_name' => $keyword,
				'customer_address' => $keyword,
				'customer_mobile' => $keyword,
				'customer_email' => $keyword,
				));
		$query = $this->db->get();

		return $stok_report = $query->result_array();
	}

	#============Invoice search============#
	public function invoice_search($keyword)
	{
		$this->db->select("a.*,b.invoice,b.date,b.customer_id");
		$this->db->from('invoice_details a');
		$this->db->join('invoice b','b.invoice_id = a.invoice_id','left');
		$this->db->or_like(
			array(
				'b.invoice' 	=> $keyword,
				'b.date' 		=> $keyword,
				'a.quantity' 	=> $keyword,
				'a.rate' 		=> $keyword,
				'a.total_price'=> $keyword,
				'a.discount'	=> $keyword,
				'a.tax'		=> $keyword,
				'a.due_amount'=> $keyword,
				));
		$query = $this->db->get();

		return $stok_report = $query->result_array();
	}	

	#============Purchase search============#
	public function purchase_search($keyword)
	{

		$this->db->select('a.*,b.supplier_name');
		$this->db->from('product_purchase a');
		$this->db->join('supplier_information b','b.supplier_id = a.supplier_id');

		$this->db->or_like(
			array(
				'purchase_id' 		=> $keyword,
				'chalan_no' 		=> $keyword,
				'b.supplier_id' 	=> $keyword,
				'grand_total_amount'=> $keyword,
				'purchase_date'		=> $keyword,
				'b.supplier_name'	=> $keyword,
				));

		$this->db->order_by('a.purchase_date','desc');
		$this->db->order_by('purchase_id','desc');
		$query = $this->db->get();

		return $stok_report = $query->result_array();
	}
}