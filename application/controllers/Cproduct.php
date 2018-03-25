<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cproduct extends CI_Controller {
	
	public $product_id;
	function __construct() {
      parent::__construct();
	  
	  $this->template->current_menu = 'product';
    }
    //Index page load
	public function index()
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lproduct');
		$content = $CI->lproduct->product_add_form();
		$this->template->full_admin_html_view($content);
	}
	//Product Add Form
	public function manage_product()
	{	
	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lproduct');
		$CI->load->model('Products');
        $content = $CI->lproduct->product_list();
		$this->template->full_admin_html_view($content);
	
	}
	//Insert Product and uload
	public function insert_product()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lproduct');


		if ($_FILES['image']['name']) {
			//Chapter chapter add start
			$config['upload_path']          = './my-assets/image/product/';
	        $config['allowed_types']        = '*';
	        $config['max_size']             = "*";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;

	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> display('Image Not Uploaded!')));
	            redirect(base_url('Cproduct'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$image_url = base_url()."my-assets/image/product/".$image['file_name'];
	        }
		}

		$price = $this->input->post('price');
		$tax_percentage = $this->input->post('tax');
		$tax = ($price * $tax_percentage)/100;

		$data=array(
			'product_id' 			=> $this->generator(8),
			'product_name' 			=> $this->input->post('product_name'),
			'generic_name' 			=> $this->input->post('generic_name'),
			'box_size' 				=> $this->input->post('box_size'),
			'expire_date' 			=> $this->input->post('expire_date'),
			'product_location' 		=> $this->input->post('product_location'),
			'supplier_id' 			=> $this->input->post('supplier_id'),
			'category_id' 			=> $this->input->post('category_id'),
			'price' 				=> $price,
			'supplier_price' 		=> $this->input->post('supplier_price'),
			'tax'					=> $tax,
			'product_model' 		=> $this->input->post('model'),
			'product_details' 		=> $this->input->post('description'),
			'image' 				=> (!empty($image_url)?$image_url:null),
			'status' 				=> 1
			);

		$result=$CI->lproduct->insert_product($data);
		if ($result == 1) {
			$this->session->set_userdata(array('message'=>display('successfully_added')));
			if(isset($_POST['add-product'])){
				redirect(base_url('Cproduct/manage_product'));
				exit;
			}elseif(isset($_POST['add-product-another'])){
				redirect(base_url('Cproduct'));
				exit;
			}
		}else{
			$this->session->set_userdata(array('error_message'=>display('product_model_already_exist')));
			redirect(base_url('Cproduct'));
		}
	}
	//Product Update Form
	public function product_update_form($product_id)
	{	
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->library('lproduct');
		$content = $CI->lproduct->product_edit_data($product_id);
		$this->template->full_admin_html_view($content);
	}
	// Product Update
	public function product_update()
	{
		$CI =& get_instance();
		$CI->auth->check_admin_auth();
		$CI->load->model('Products');

		$product_id  = $this->input->post('product_id');
		if ($_FILES['image']['name']) {
			//Chapter chapter add start
			$config['upload_path']          = './my-assets/image/product/';
	        $config['allowed_types']        = '*';
	        $config['max_size']             = "*";
	        $config['max_width']            = "*";
	        $config['max_height']           = "*";
	        $config['encrypt_name'] 		= TRUE;

	        $this->load->library('upload', $config);
	        if ( ! $this->upload->do_upload('image'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            $this->session->set_userdata(array('error_message'=> display('image_not_uploaded!')));
	            redirect(base_url('Cproduct'));
	        }
	        else
	        {
	        	$image =$this->upload->data();
	        	$old_image = $this->input->post('old_image');
	        	$image_url = base_url()."my-assets/image/product/".$image['file_name'];
	        }
		}
		$old_image = $this->input->post('old_image');
		$data=array(
			'product_id' 			=> $this->generator(8),
			'product_name' 			=> $this->input->post('product_name'),
			'supplier_id' 			=> $this->input->post('supplier_id'),
			'category_id' 			=> $this->input->post('category_id'),
			'price' 				=> $this->input->post('price'),
			'supplier_price' 		=> $this->input->post('supplier_price'),
			'product_model' 		=> $this->input->post('model'),
			'product_details' 		=> $this->input->post('description'),
			'tax' 					=> $this->input->post('tax'),
			'generic_name' 					=> $this->input->post('generic_name'),
			'box_size' 				=> $this->input->post('box_size'),
			'expire_date' 			=> $this->input->post('expire_date'),
			'product_location' 		=> $this->input->post('product_location'),

			'image' 				=> (!empty($image_url)?$image_url:$old_image),
			'status' 				=> 1
		);
		$result = $CI->Products->update_product($data,$product_id);
		if ($result == true) {
			$this->session->set_userdata(array('message'=>display('successfully_updated')));
			redirect(base_url('Cproduct/manage_product'));
		}else{
			$this->session->set_userdata(array('error_message'=>display('product_model_already_exist')));
			redirect(base_url('Cproduct/manage_product'));
		}
	}
	// product_delete
	public function product_delete()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Products');
		$product_id =  $_POST['product_id'];
		$result=$CI->Products->delete_product($product_id);
		return true;
			
	}
	//Retrieve Single Item  By Search
	public function product_by_search()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lproduct');
		$product_id = $this->input->post('product_id');		
        $content = $CI->lproduct->product_search_list($product_id);
        $sub_menu = array(
				array('label'=> 'Manage Product', 'url' => 'Cproduct', 'class' =>'active'),
				array('label'=> 'Add Product', 'url' => 'Cproduct/manage_product')
			);
		$this->template->full_admin_html_view($content,$sub_menu);
	}
	//Retrieve Single Item  By Search
	public function product_details($product_id)
	{
		$this->product_id=$product_id;
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lproduct');	
        $content = $CI->lproduct->product_details($product_id);
		$this->template->full_admin_html_view($content);
	}
	
	//Retrieve Single Item  By Search
	public function product_sales_supplier_rate($product_id=null,$startdate=null,$enddate=null)
	{
		if($startdate==null){$startdate= date('Y-m-d',strtotime('-30 days'));}
		if($enddate==null){$enddate= date('Y-m-d');}
		$product_id_input=$this->input->post('product_id');
		if(!empty($product_id_input))
			{
				$product_id=$this->input->post('product_id');
				$startdate=$this->input->post('from_date');
				$enddate=$this->input->post('to_date');
			}
		
		$this->product_id=$product_id;
		
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lproduct');	
        $content = $CI->lproduct->product_sales_supplier_rate($product_id,$startdate,$enddate);
		$this->template->full_admin_html_view($content);
	}

	//This function is used to Generate Key
	public function generator($lenth)
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Products');

		$number=array("1","2","3","4","5","6","7","8","9","0");
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
				$con="$con"."$rand_number";
			}
		}

		$result = $this->Products->product_id_check($con);

		if ($result === true) {
			$this->generator(8);
		}else{
			return $con;
		}
	}
}