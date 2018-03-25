<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Csearch extends CI_Controller {
	
	public $company_id;
	function __construct() {
      parent::__construct(); 
		$this->load->library('auth');
		$this->auth->check_admin_auth();
		$this->load->library('session');
		$this->load->library('lsearch');
		$this->load->model('Searchs');
		$this->load->model('Web_settings');
		
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
    }
    #===========Company page load===========#
	public function index()
	{
		$content = $this->lsearch->medicine_search_form();
		$this->template->full_admin_html_view($content);
	}

    #===========Medicine page load=========#
	public function medicine()
	{
		$content = $this->lsearch->medicine_search_form();
		$this->template->full_admin_html_view($content);
	}

	#===========Medicine search============#
	public function medicine_search()
	{
		$keyword = $this->input->post('what_you_search');
		$search_result = $this->Searchs->medicine_search($keyword);

		if(!empty($search_result)){
			$i=1;
			foreach($search_result as $k=>$v){$i++;
			   $search_result[$k]['sl']=$i;
			}
		}

		$data = array(
				'title' => display('medicine_search'),
				'search_result' => $search_result
			);
		$content = $this->parser->parse('search/medicine_search',$data,true);
		$this->template->full_admin_html_view($content);
	}

	#===========Customer page load=========#
	public function customer()
	{
		$content = $this->lsearch->customer_search_form();
		$this->template->full_admin_html_view($content);
	}

	#===========Customer search============#
	public function customer_search()
	{
		$keyword = $this->input->post('what_you_search');
		$search_result = $this->Searchs->customer_search($keyword);

		if(!empty($search_result)){
			$i=1;
			foreach($search_result as $k=>$v){$i++;
			   $search_result[$k]['sl']=$i;
			}
		}

		$data = array(
				'title' =>display('customer_search'),
				'search_result' => $search_result
			);
		$content = $this->parser->parse('search/customer_search',$data,true);
		$this->template->full_admin_html_view($content);
	}

	#===========Inoice page load=========#
	public function invoice()
	{
		$content = $this->lsearch->invoice_search_form();
		$this->template->full_admin_html_view($content);
	}

	#===========Invoice search============#
	public function invoice_search()
	{
		$keyword = $this->input->post('what_you_search');
		$search_result = $this->Searchs->invoice_search($keyword);

		if(!empty($search_result)){
			$i=1;
			foreach($search_result as $k=>$v){$i++;
			   $search_result[$k]['sl']=$i;
			}
		}
		$currency_details = $this->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' =>display('invoice_search'),
				'search_result' => $search_result,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$content = $this->parser->parse('search/invoice_search',$data,true);
		$this->template->full_admin_html_view($content);
	}
	

	#===========Purchase page load=========#
	public function purchase()
	{
		$content = $this->lsearch->purchase_search_form();
		$this->template->full_admin_html_view($content);
	}

	#===========Purchase search============#
	public function purchase_search()
	{
		$keyword = $this->input->post('what_you_search');
		$search_result = $this->Searchs->purchase_search($keyword);

		if(!empty($search_result)){
			$i=1;
			foreach($search_result as $k=>$v){$i++;
			   $search_result[$k]['sl']=$i;
			}
		}
		$currency_details = $this->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' =>display('purchase_search'),
				'search_result' => $search_result,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$content = $this->parser->parse('search/purchase_search',$data,true);
		$this->template->full_admin_html_view($content);
	}
}