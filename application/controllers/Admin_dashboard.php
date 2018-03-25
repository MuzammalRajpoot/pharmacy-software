<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_dashboard extends CI_Controller {
	
	function __construct() {
      	parent::__construct();
	  	$this->template->current_menu = 'home';
	  	$this->load->model('Web_settings');
    }

    public function index(){
    	$CI =& get_instance();
		$CI->load->library('lreport');
		$CI->load->library('occational');
		if (!$this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
		}
		$this->auth->check_admin_auth();

	  	$CI->load->model('Customers');
	    $CI->load->model('Products');
	    $CI->load->model('Suppliers');
	    $CI->load->model('Invoices');
	    $CI->load->model('Purchases');
	    $CI->load->model('Reports');
	    $CI->load->model('Accounts');
	    $CI->load->model('Web_settings');
	   
	    $total_customer = $CI->Customers->count_customer();
	    $total_product = $CI->Products->count_product();
	    $total_suppliers = $CI->Suppliers->count_supplier();
	    $total_sales = $CI->Invoices->count_invoice();
	    $total_purchase = $CI->Purchases->count_purchase();

	    $this->Accounts->accounts_summary(1);
      	$total_expese=$this->Accounts->sub_total;
      	
      	$monthly_sales_report = $CI->Reports->monthly_sales_report();

	    $sales_report = $CI->Reports->todays_total_sales_report();	
	
		$total_profit = ($sales_report[0]['total_sale'] - $sales_report[0]['total_supplier_rate']);
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();

	    $data = array(
	    	'title' => display('dashboard'), 
	    	'total_customer' => $total_customer,
	    	'total_product' => $total_product,
	    	'total_suppliers' => $total_suppliers,
	    	'total_sales' => $total_sales,
	    	'total_purchase' => $total_purchase,
	    	'purchase_amount' => $sales_report[0]['total_supplier_rate'],
	    	'sales_amount' => $sales_report[0]['total_sale'],
	    	'total_expese' => $total_expese,
	    	'total_profit' => $total_profit,
	    	'monthly_sales_report' => $monthly_sales_report,
	    	'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],
	    	);

		$content = $CI->parser->parse('include/admin_home',$data,true);
		$this->template->full_admin_html_view($content);
		
    }
    //Today All Report
	public function all_report()
	{

		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }

		$CI =& get_instance();
		$CI->load->library('lreport');
		if (!$this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
		}
		$this->auth->check_admin_auth();

		$user_type=$this->session->userdata('user_type');
		
		if ($user_type == 1) {
			$content = $CI->lreport->retrieve_all_reports();
			$this->template->full_admin_html_view($content);
		}elseif ($user_type == 2) {
			$CI->load->library('linvoice');
			$content = $CI->linvoice->invoice_add_form();
			$this->template->full_admin_html_view($content);
		}
	}
	#==============Todays_sales_report============#
	public function todays_sales_report()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();
		$content = $CI->lreport->todays_sales_report();
		$this->template->full_admin_html_view($content);
	}
	#================todays_purchase_report========#
	public function todays_purchase_report()
	{

		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();
		$content = $CI->lreport->todays_purchase_report();
		$this->template->full_admin_html_view($content);
	}
	#=============Total profit report===================#
	public function total_profit_report(){
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$CI->load->library('lreport');
		$this->auth->check_admin_auth();
		$content = $CI->lreport->total_profit_report();
		$this->template->full_admin_html_view($content);
	}
	#============Date wise sales report==============#
	public function retrieve_dateWise_SalesReports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$from_date = $this->input->post('from_date');		
		$to_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_SalesReports($from_date,$to_date);
		$this->template->full_admin_html_view($content);
	}	
	#==============Date wise purchase report=============#
	public function retrieve_dateWise_PurchaseReports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_PurchaseReports($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#==============Product sales report date wise===========#
	public function product_sales_reports_date_wise()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');	
        $content = $CI->lreport->get_products_report_sales_view();
		$this->template->full_admin_html_view($content);
	}
	#==============Date wise purchase report=============#
	public function retrieve_dateWise_profit_report()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$start_date = $this->input->post('from_date');		
		$end_date = $this->input->post('to_date');	
        $content = $CI->lreport->retrieve_dateWise_profit_report($start_date,$end_date);
		$this->template->full_admin_html_view($content);
	}
	#==============Product sales search reports============#
	public function product_sales_search_reports()
	{
		if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('lreport');
		$from_date = $this->input->post('from_date');		
		$to_date = $this->input->post('to_date');	
        $content = $CI->lreport->get_products_search_report( $from_date,$to_date );
		$this->template->full_admin_html_view($content);
	}
	#============User login=========#
	public function login()
	{	
		if ($this->auth->is_logged() )
		{
			$this->output->set_header("Location: ".base_url().'Admin_dashboard', TRUE, 302);
		}
		$data['title'] = display('admin_login_area');
        $content = $this->parser->parse('user/admin_login_form',$data,true);
		$this->template->full_admin_html_view($content);
	}
	#==============Valid user check=======#
	public function do_login()
	{
		$error = '';
		$setting_detail = $this->Web_settings->retrieve_setting_editdata(); 

		if ($setting_detail[0]['captcha'] == 0 && $setting_detail[0]['secret_key'] != null && $setting_detail[0]['site_key'] != null) {

			$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
			$this->form_validation->set_message('validate_captcha', 'Please check the the captcha form');

			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_userdata(array('error_message'=>display('please_enter_valid_captcha')));
				$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
			}else{
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				if ( $username == '' || $password == '' || $this->auth->login($username, $password) === FALSE ){
					$error = display('wrong_username_or_password');
				}
				if ( $error != '' ){
					$this->session->set_userdata(array('error_message'=>$error));
					$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
				}else{
					$this->output->set_header("Location: ".base_url(), TRUE, 302);
		        }
			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if ( $username == '' || $password == '' || $this->auth->login($username, $password) === FALSE ){
				$error = display('wrong_username_or_password');
			}
			if ( $error != '' ){
				$this->session->set_userdata(array('error_message'=>$error));
				$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
			}else{
				$this->output->set_header("Location: ".base_url(), TRUE, 302);
	        }
		}
	}

	//Valid captcha check
	function validate_captcha() { 
	  	$captcha = $this->input->post('g-recaptcha-response'); 
	  	// $response = file_get_contents("//www.google.com/recaptcha/api/siteverify?secret=6LdiKhsUAAAAABH4BQCIvBar7Oqe-2LwDKxMSX-t&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']); 

		$url = "www.google.com/recaptcha/api/siteverify?secret=6LdiKhsUAAAAABH4BQCIvBar7Oqe-2LwDKxMSX-t&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR'];
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
		if (curl_errno($ch)) {
		  echo curl_error($ch);
		  echo "\n<br />";
		  $contents = '';
		} else {
		  curl_close($ch);
		}
	 	if ($contents . 'success' == false) { return FALSE; } else { return TRUE; } 
	}

	#===============Logout=======#
	public function logout()
	{	
		if ($this->auth->logout())
		$this->output->set_header("Location: ".base_url().'Admin_dashboard/login', TRUE, 302);
	}
	#=============Edit Profile======#
	public function edit_profile()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->library('luser');
		$content = $CI->luser->edit_profile_form();
		$this->template->full_admin_html_view($content);
	}
	#=============Update Profile========#
	public function update_profile()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Users');
		$this->Users->profile_update();
		$this->session->set_userdata(array('message'=> display('successfully_updated')));
		redirect(base_url('Admin_dashboard/edit_profile'));
	}
	#=============Change Password=========# 
	public function change_password_form()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$content = $CI->parser->parse('user/change_password',array('title'=>display('change_password')),true);
		$this->template->full_admin_html_view($content);
	}
	#============Change Password===========#
	public function change_password()
	{	
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Users'); 

		$error = '';
		$email = $this->input->post('email');
		$old_password = $this->input->post('old_password');
		$new_password = $this->input->post('password');
		$repassword = $this->input->post('repassword');
		if ( $email == '' || $old_password == '' || $new_password == '')
		{
			$error = display('blank_field_does_not_accept');
		}else if($email != $this->session->userdata('user_email')){
			$error = display('you_put_wrong_email_address');
		}else if(strlen($new_password)<6 ){
			$error = display('new_password_at_least_six_character');
		}else if($new_password != $repassword ){
			$error = display('password_and_repassword_does_not_match');
		}else if($CI->Users->change_password($email,$old_password,$new_password) === FALSE ){
			$error = display('you_are_not_authorised_person');
		}
		if ( $error != '' )
		{
			$this->session->set_userdata(array('error_message'=>$error));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
		}else{
			$this->session->set_userdata(array('message'=>display('successfully_changed_password')));
			$this->output->set_header("Location: ".base_url().'Admin_dashboard/change_password_form', TRUE, 302);
        }
	}
}