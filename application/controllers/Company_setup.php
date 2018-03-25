<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company_setup extends CI_Controller {
	
	public $company_id;
	function __construct() {
      parent::__construct(); 
		$this->load->library('auth');
		$this->load->library('lcompany');
		$this->load->library('session');
		$this->load->model('Companies');
		$this->auth->check_admin_auth();
		$this->template->current_menu = 'settings';

		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
    }
    #==============Company page load===========#
	public function index()
	{
		$content = $this->lcompany->company_add_form();
		$this->template->full_admin_html_view($content);
	}
	#===============Company Search Item===========#
	public function company_search_item()
	{	
		$company_id = $this->input->post('company_id');
        $content = $this->lcompany->company_search_item($company_id);
		$this->template->full_admin_html_view($content);
	}
	#================Manage Company==============#
	public function manage_company()
	{
		$config = array();
		$config["base_url"] = base_url()."Company_setup/manage_company";
		$config["total_rows"] = $this->Companies->count_company();	  
		$config["per_page"] = 15;
		$config["uri_segment"] = 3;	
		$config['full_tag_open'] = '<div id="pagination">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$limit = $config["per_page"];
	    $links = $this->pagination->create_links();
        $content = $this->lcompany->company_list($limit,$page,$links);
		$this->template->full_admin_html_view($content);
	}

	#===============Company update form================#
	public function company_update_form($company_id)
	{	
		$content = $this->lcompany->company_edit_data($company_id);
		$this->template->full_admin_html_view($content);
	}
	#===============Company update===================#
	public function company_update()
	{
		$company_id  = $this->input->post('company_id');
		$data=array(
			'company_id' 	=> $company_id,
			'company_name'  => $this->input->post('company_name'),
			'email' 		=> $this->input->post('email'),
			'address' 		=> $this->input->post('address'),
			'mobile' 		=> $this->input->post('mobile'),
			'website' 		=> $this->input->post('website'),
			'status' 	    => 1
			);
		$this->Companies->update_company($data,$company_id);
		$this->session->set_userdata(array('message'=>display('successfully_updated')));
		redirect(base_url('Company_setup/manage_company'));
	}	
}