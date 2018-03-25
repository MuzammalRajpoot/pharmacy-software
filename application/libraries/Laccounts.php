<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Laccounts
 {
	
	public function get_daily_closing_view()
	{
		$CI =& get_instance();
		$CI->load->model('Accounts');
		$CI->load->model('Settings');
		
				
		$draw_data = array(
				'title' => display('accounts'),
			);
			
	/*
		$draw_view = $CI->parser->parse('accounts/drawing_form',$draw_data,true);
		
		$transaction_data = array(
				'title' => 'Accounts'
			);
		$transaction_view = $CI->parser->parse('accounts/expence_form',$transaction_data,true);
		
		$bank_list = $CI->banks->get_bank_list( );
		$bank_data = array(
				'title' => 'Accounts',
				'bank_list' => $bank_list
			);
		$bank_view = $CI->parser->parse('settings/banking_form',$bank_data,true);
		
		
		//Get Daily Drawing Amount			 
		$closing_amount =$CI->settings->get_last_closing_amount();		
		$last_closing_amount = 0;
		if(!empty($closing_amount)){
				$last_closing_amount = $closing_amount[0]['amount'];
		}
		
		//Get Daily Drawing Amount			 
		$draw_amount =$CI->settings->get_todays_draw_amount();		
		$total_draw_amount = 0;
		if(!empty($draw_amount)){
				$total_draw_amount = $draw_amount[0]['total_draw_amount'];
		}
		
		//Get Daily expence Amount			 
		$expense_amount =$CI->settings->get_todays_expense_amount();		
		$total_expense_amount = 0;
		if(!empty($expense_amount)){
				$total_expense_amount = $expense_amount[0]['total_expense_amount'];
		}
		
		//Get Daily Sales In Cheque Amount			 
		$chq_sales_amount =$CI->closings->get_todays_cheque_sales_amount();		
		$total_chq_sales_amount = 0;
		if(!empty($chq_sales_amount)){
				$total_chq_sales_amount = $chq_sales_amount[0]['total_chq_sales_amount'];
		}
		
								
		// Get Daily Sales In Cheque Amount			 
		$cash_sales_amount =$CI->closings->get_todays_cash_sales_amount();		
		$total_cash_sales_amount = 0;
		if(!empty($cash_sales_amount)){
				$total_cash_sales_amount = $cash_sales_amount[0]['total_cash_sales_amount'];
		}
		
		
		// Get Daily Sales In Cheque Amount			 
		$cheque_to_cash_amount =$CI->closings->get_todays_cheque_to_cash();		
		$total_cheque_to_cash = 0;
		if(!empty($cash_sales_amount)){
				$total_cheque_to_cash = $cheque_to_cash_amount[0]['cheque_to_cash_amount'];
		}
		
		$final_amount = 0;					
		$final_amount = ($last_closing_amount + $total_cheque_to_cash + $total_cash_sales_amount )-( $total_expense_amount +  $total_draw_amount );
		
		$closing_data = array(
				'last_closing_amount' 	=> $last_closing_amount,
				'total_cheque_sales' 	=> $total_chq_sales_amount,
				'cheque_to_cash'		=> $total_cheque_to_cash,
				'total_cash_sales'		=> $total_cash_sales_amount,
				'total_expense_amount' 	=> $total_expense_amount,
				'total_draw_amount' 	=> $total_draw_amount,
				'final_amount' 		=> $final_amount
			);
		$closing_html = $CI->parser->parse('closing/closing_form',$closing_data,true);

		$data = array(
				'title' => 'Daily Closing',
				'drawing_view' => $draw_view,
				'transaction_view' => $transaction_view,
				'bank_view' => $bank_view,
				'closing_html' => $closing_html
			);
		$closin_view = $CI->parser->parse('closing/closing_container',$data,true);	
		return $closin_view;
	*/
	}
	//Retrieve  Customer List	
	public function daily_closing_list()
	{
		$CI =& get_instance();
		$CI->load->model('Accounts');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$daily_closing_data = $CI->Accounts->get_closing_report();
		
		$i=0;
		if(!empty($daily_closing_data)){
			foreach($daily_closing_data as $k=>$v){
				$daily_closing_data[$k]['final_date'] = $CI->occational->dateConvert($daily_closing_data[$k]['date']);
			}
			foreach($daily_closing_data as $k=>$v){$i++;
			   $daily_closing_data[$k]['sl']=$i;
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
       
		$data = array(
				'title' => display('daily_closing_report'),
				'daily_closing_data' => $daily_closing_data,
				'currency' => $currency_details[0]['currency'],
            	'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('accounts/closing_report',$data,true);
		return $reportList;
	}
	
	//Retrieve  Customer List	
	public function get_date_wise_closing_reports( $from_date,$to_date )
	{
		$CI =& get_instance();
		$CI->load->model('Accounts');
		$CI->load->library('occational');
		$daily_closing_data = $CI->Accounts->get_date_wise_closing_report( $from_date,$to_date );
		
		$i=0;
		if(!empty($daily_closing_data)){
			foreach($daily_closing_data as $k=>$v){
				$daily_closing_data[$k]['final_date'] = $CI->occational->dateConvert($daily_closing_data[$k]['date']);
			}
		
			foreach($daily_closing_data as $k=>$v){$i++;
			   $daily_closing_data[$k]['sl']=$i;
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('closing_search_report'),
				'daily_closing_data' => $daily_closing_data,
				'currency' => $currency_details[0]['currency'],
            	'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('accounts/closing_report',$data,true);
		return $reportList;
	}
}
?>