<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lweb_setting {
	//Sub Category Add
	public function setting_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('Web_settings');
		$setting_detail = $CI->Web_settings->retrieve_setting_editdata();
		$language = $CI->Web_settings->languages();
		$data = array(
			'title' 	=> display('update_setting'),
			'logo' 		=> $setting_detail[0]['logo'],
			'invoice_logo' 		=> $setting_detail[0]['invoice_logo'],
			'currency'	=> $setting_detail[0]['currency'],
			'currency_position'	=> $setting_detail[0]['currency_position'],
			'favicon'	=> $setting_detail[0]['favicon'],
			'footer_text'	=> $setting_detail[0]['footer_text'],
			'language'	=> $language,
			'rtr'	=> $setting_detail[0]['rtr'],
			'captcha'			=> $setting_detail[0]['captcha'],
			'site_key'		=> $setting_detail[0]['site_key'],
			'secret_key'	=> $setting_detail[0]['secret_key'],
		);
		$setting = $CI->parser->parse('web_setting/web_setting',$data,true);
		return $setting;
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