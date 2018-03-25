<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lsearch {

	//Medicine search form
	public function medicine_search_form(){
		$CI =& get_instance();
		$data = array(
				'title' => display('medicine_search'),
				'search_result' => ''
			);
		$search_form = $CI->parser->parse('search/medicine_search',$data,true);
		return $search_form;
	}
	//Customer search form
	public function customer_search_form(){
		$CI =& get_instance();
		$data = array(
				'title' => display('customer_search'),
				'search_result' => ''
			);
		$search_form = $CI->parser->parse('search/customer_search',$data,true);
		return $search_form;
	}

	//Invoice search form
	public function invoice_search_form(){
		$CI =& get_instance();
		$data = array(
				'title' => display('invoice_search'),
				'search_result' => ''
			);
		$search_form = $CI->parser->parse('search/invoice_search',$data,true);
		return $search_form;
	}	

	//Purchase search form
	public function purchase_search_form(){
		$CI =& get_instance();
		$data = array(
				'title' => display('purchase_search'),
				'search_result' => ''
			);
		$search_form = $CI->parser->parse('search/purchase_search',$data,true);
		return $search_form;
	}
}