<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Template {
	var $current_menu = 'home';
	// View Message
	function message_html()
	{
		$CI =& get_instance();
		$CI->load->library('parser');
		
		$message = '';
		$message_class = '';
		$html = '';
		
		if ( $CI->session->userdata('message') != '' )
		{
			$message = $CI->session->userdata('message');
			$message_class = 'alert-success';
		}
		
		if ( $CI->session->userdata('error_message') != '' )
		{
			$message = $CI->session->userdata('error_message');
			$message_class = 'alert-error';
		}else if ( $CI->session->userdata('warning_message') != '' )
		{
			$message = $CI->session->userdata('warning_message');
		}

		$data = array(
					'message' => $message,
					'message_class' => $message_class
				);

		if ( $message != '' )
			$html = $CI->parser->parse('include/message',$data,true);

		$CI->session->unset_userdata('message');
		$CI->session->unset_userdata('error_message');

		return $html;
	}
	//Admin Html View....
	public function full_admin_html_view($content){
	
		$CI =& get_instance();
		$message = $this->message_html();
		$logged_info='';
		$top_menu='';
		
		if ($CI->auth->is_admin())
		{
			$menu_template = 'include/top_menu';
			$logged_data = 'include/admin_loggedin_info';

		
			// parse menu
			$menu_data = array(
					'active' => $this->current_menu
				);
			$log_info = array(
					'email' => $CI->session->userdata('user_name'),
					'logout' => base_url().'Admin_dashboard/logout'
				); 
			$top_menu = $CI->parser->parse($menu_template,$menu_data,true);
			$logged_info = $CI->parser->parse($logged_data,$log_info,true);
		}
		$CI->load->model('Products');
		$company_info=$CI->Products->retrieve_company();
		$data = array (
				'logindata' => $logged_info,
				'mainmenu' 	=> $top_menu,
				'content' 	=> $content,
				'msg_content' => $message,
				'company_info' => $company_info
			);
		$content = $CI->parser->parse('admin_html_template',$data);
	}
}