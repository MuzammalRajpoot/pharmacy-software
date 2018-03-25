<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lcategory {
	//Retrieve  Customer List	
	public function category_list()
	{
		$CI =& get_instance();
		$CI->load->model('Categories');
		$category_list = $CI->Categories->category_list();  //It will get only Credit Customers
		$i=0;
		$total=0;
		if(!empty($category_list)){	
			foreach($category_list as $k=>$v){$i++;
			   $category_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('category_list'),
				'category_list' => $category_list,
				
			);
		$customerList = $CI->parser->parse('category/category',$data,true);
		return $customerList;
	}
	//Sub Category Add
	public function category_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('Categories');
		$data = array(
				'title' => display('add_category'),
			);
		$customerForm = $CI->parser->parse('category/add_category_form',$data,true);
		return $customerForm;
	}

	//customer Edit Data
	public function category_edit_data($category_id)
	{
		$CI =& get_instance();
		$CI->load->model('Categories');
		$category_detail = $CI->Categories->retrieve_category_editdata($category_id);
		$data=array(
			'category_id' 			=> $category_detail[0]['category_id'],
			'category_name' 		=> $category_detail[0]['category_name'],
			'status' 				=> $category_detail[0]['status']
			);
		$chapterList = $CI->parser->parse('category/edit_category_form',$data,true);
		return $chapterList;
	}
}
?>