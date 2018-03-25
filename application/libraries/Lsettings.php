<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lsettings {
	#===============Bank list============#
	public function bank_list()
	{
		$CI =& get_instance();
		$CI->load->model('Settings');
		$bank_list = $CI->Settings->get_bank_list( );
		$i=0;
		if(!empty($bank_list)){		
			foreach($bank_list as $k=>$v){$i++;
			   $bank_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('bank_list'),
				'bank_list' => $bank_list
			);
		$bankList = $CI->parser->parse('settings/bank',$data,true);
		return $bankList;
	}
	#=============Bank show by id=======#
	public function bank_show_by_id($bank_id){
		$CI =& get_instance();
		$CI->load->model('Settings');
		$bank_list = $CI->Settings->get_bank_by_id($bank_id);
		$data = array(
				'title' => display('bank_update'),
				'bank_list' => $bank_list
			);
		$bankList = $CI->parser->parse('settings/edit_bank',$data,true);
		return $bankList;
	}
	#=============Bank Update by id=======#
	public function bank_update_by_id($bank_id){
		$CI =& get_instance();
		$CI->load->model('Settings');
		$bank_list = $CI->Settings->bank_update_by_id($bank_id);
		return true;
	}
	#============Table List=============#
	public function table_list()
	{
		$CI =& get_instance();
		$CI->load->model('Settings');
		$bank_list = $CI->Settings->table_list();
		$i=0;
		if(!empty($bank_list)){		
			foreach($bank_list as $k=>$v){$i++;
			   $bank_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('account_list'),
				'table_list' => $bank_list
			);
		$bankList = $CI->parser->parse('settings/table_list',$data,true);
		return $bankList;
	}
}
?>