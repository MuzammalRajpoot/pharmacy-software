<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Csettings extends CI_Controller {
	
	function __construct() {
      parent::__construct();
	  $this->load->library('lsettings');
	  $this->load->library('auth');
	  $this->load->library('session');
	  $this->load->model('Settings');
	  $this->auth->check_admin_auth();
	  $this->template->current_menu = 'settings';

	   if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
    }
	
	public function index()
	{
		$data=array('title'=> display('add_new_bank'));
        $content = $this->parser->parse('settings/new_bank',$data,true);
		$this->template->full_admin_html_view($content);
	}
	#================Add new bank==============#
	public function add_new_bank()
	{ 
		$data = array(
			'bank_id'		=>	$this->auth->generator(10),
			'bank_name'		=>	$this->input->post('bank_name'),
			'status'		=>1
		
		);
		$invoice_id = $this->Settings->bank_entry( $data );
		$this->session->set_userdata(array('message'=>display('successfully_added')));
		redirect(base_url('Csettings/bank_list'));exit;
	}
	#==============Bank list============#
	public function bank_list()
	{
        $content = $this->lsettings->bank_list( );
		$this->template->full_admin_html_view($content);
	}
	#=============Bank edit==============#
	public function edit_bank($bank_id)
	{
        $content = $this->lsettings->bank_show_by_id($bank_id);
		$this->template->full_admin_html_view($content);
	}
	#============Update Bank=============#
	public function update_bank($bank_id)
	{
        $content = $this->lsettings->bank_update_by_id($bank_id);
        $this->session->set_userdata(array('message'=>display('successfully_updated')));
        redirect('Csettings/bank_list');
	}

	#==============Table list============#
	public function table_list()
	{
        $content = $this->lsettings->table_list( );
		$this->template->full_admin_html_view($content);
	}
	#===========Table Create============#
	public function table_create()
	{
			$data=array('title'=>display('add_new_account'));
			$content = $this->parser->parse('settings/table_create',$data,true);
			$this->template->full_admin_html_view($content);
	} 
	#===========Table edit============#
	public function table_edit($account_id)
	{

			$table_data = $this->Settings->retrive_table_data($account_id);
			$data=array(
				'title'=> display('table_edit'),
				'account_name'=>$table_data[0]['account_name'],
				'account_id'=>$table_data[0]['account_id'],
				);
			$content = $this->parser->parse('settings/table_edit',$data,true);
			$this->template->full_admin_html_view($content);
	} 

	#===========Table update============#
	public function update_account_data()
	{
		$account_id = $this->input->post('account_id');
		$data['account_name'] = $this->input->post('account_name');
		$table_data = $this->Settings->update_table_data($data,$account_id);
		
		$content = $this->lsettings->table_list( );
		$this->template->full_admin_html_view($content);
	}
	#==============Create account data============#
	public function create_account_data()
	{
		$id_generator=$this->auth->generator(10);
		$this->Settings->table_create($id_generator);
		redirect(base_url('Csettings/table_list'));exit;
	}
}