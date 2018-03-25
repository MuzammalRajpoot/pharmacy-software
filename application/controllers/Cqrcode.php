<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cqrcode extends CI_Controller {

	function __construct() 
    {
	    parent::__construct();
	    $CI =& get_instance();
	    $this->auth->check_admin_auth();
	    $this->template->current_menu = 'Cproduct';
		$this->load->library('ciqrcode');
    }
    //QR-Code Generator
	public function qrgenerator($product_id){
		$CI =& get_instance();
	    $this->auth->check_admin_auth();
	    $this->load->model('Products');
	    $this->load->model('Invoices');

		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']     = ''; //string, the default is application/cache/
		$config['errorlog']     = ''; //string, the default is application/logs/
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = '1024'; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);
		//Create QR code image create

		$params['data']  = $product_id;
		$params['level'] = 'H';
		$params['size'] = 10;
		$image_name = $product_id.'.png';
		$params['savename'] = FCPATH.'my-assets/image/qr/'.$image_name;
		$this->ciqrcode->generate($params);

		$product_info = $this->Products->retrieve_product_editdata($product_id);
		$company_info = $CI->Invoices->retrieve_company();
		$data = array(
				'title' 	 => display('print_qrcode'),
				'product_name' => $product_info[0]['product_name'],
				'product_model' => $product_info[0]['product_model'],
				'price' => $product_info[0]['price'],
				'product_details' => $product_info[0]['product_details'],
				'qr_image' => $image_name,
				'company_name' => $company_info[0]['company_name'],
			);

		$content = $CI->parser->parse('product/barcode_print_page',$data,true);
	
		$this->template->full_admin_html_view($content);
	}
}
?>