<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Creport extends CI_Controller {
	
	function __construct() {
     	parent::__construct();
      	$CI =& get_instance();
      	$CI->load->model('Web_settings');
	  	$this->template->current_menu = 'report';
    }
	public function index()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');	
		$today = date('Y-m-d');

		$product_id = $this->input->post('product_id')?$this->input->post('product_id'):"";	
		$date=$this->input->post('stock_date')?$this->input->post('stock_date'):$today;
		$limit=15;
		$start_record=($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$date=($this->uri->segment(3)) ? $this->uri->segment(3) : $date;
		//,$date,$limit,$page
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');	
		$link=$this->pagination($limit,"Creport/index/$date");	
        $content = $CI->lreport->stock_report_single_item($product_id,$date,$limit,$start_record,$link);
        
		$this->template->full_admin_html_view($content);
	}
	#===============Report paggination=============#
	public function pagination($per_page,$page)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$product_id=$this->input->post('product_id');	
		
		$config = array();
		$config["base_url"] = base_url().$page;
		$config["total_rows"] = $this->Reports->product_counter($product_id);	  
		$config["per_page"] = $per_page;
		$config["uri_segment"] = 4;	
        $config["num_links"] = 5; 
        /* This Application Must Be Used With BootStrap 3 * */
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";



		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$limit = $config["per_page"];
	    return $links = $this->pagination->create_links();	
	}
	
}