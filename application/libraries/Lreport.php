<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lreport {
	// Retrieve All Stock Report
	public function stock_report($limit,$page,$links)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$stok_report = $CI->Reports->stock_report($limit,$page);
	
		if(!empty($stok_report)){
			$i=$page;
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['sl']=$i;
			}
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['stok_quantity'] = $stok_report[$k]['totalBuyQnty']-$stok_report[$k]['totalSalesQnty'];
			   $stok_report[$k]['totalSalesCtn'] = $stok_report[$k]['totalSalesQnty']/$stok_report[$k]['cartoon_quantity'];
			   $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalBuyQnty']/$stok_report[$k]['cartoon_quantity'];

			$stok_report[$k]['stok_quantity_cartoon'] = $stok_report[$k]['stok_quantity']/$stok_report[$k]['cartoon_quantity'];

			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('stock_list'),
				'stok_report' => $stok_report,
				'links' => $links,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
				
			);
		$reportList = $CI->parser->parse('report/stock_report',$data,true);
		return $reportList;
	}
	// Retrieve Single Item Stock Stock Report
	public function stock_report_single_item($product_id,$date,$limit,$page,$link)
	{   //echo "$product_id,$date,$limit,$page";
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->library('occational');
		$stok_report = $CI->Reports->stock_report_bydate($product_id,$date,$limit,$page);

		if(!empty($stok_report)){
			$i=$page;
			foreach($stok_report as $k=>$v){$i++;
			   $stok_report[$k]['sl']=$i;
			}

			foreach($stok_report as $k=>$v){$i++;

			   $stok_report[$k]['stok_quantity_cartoon'] = ($stok_report[$k]['totalPurchaseQnty']-$stok_report[$k]['totalSalesQnty']);

			   $stok_report[$k]['totalSalesCtn'] = $stok_report[$k]['totalSalesQnty'];

			   $stok_report[$k]['totalPrhcsCtn'] = $stok_report[$k]['totalPurchaseQnty'];

			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info = $CI->Reports->retrieve_company();
		$data = array(
				'title' => display('stock_report'),
				'stok_report' => $stok_report,
				'link'=>$link,
				'date'=>$date,
				'company_info' => $company_info,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		
		$reportList = $CI->parser->parse('report/stock_report',$data,true);
		return $reportList;
	}
	// Retrieve daily Report
	public function retrieve_all_reports()
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$sales_report = $CI->Reports->todays_sales_report();
		$sales_amount = 0;
		if(!empty($sales_report)){
			$i=0;
			foreach($sales_report as $k=>$v){$i++;
			   $sales_report[$k]['sl']=$i;
			   $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
			   $sales_amount = $sales_amount+$sales_report[$k]['total_amount'];
			}
		}		
		$purchase_report = $CI->Reports->todays_purchase_report();		
		$purchase_amount = 0;
		if(!empty($purchase_report)){
			$i=0;
			foreach($purchase_report as $k=>$v){$i++;
			    $purchase_report[$k]['sl']=$i;
			    $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
				$purchase_amount = $purchase_amount+$purchase_report[$k]['grand_total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('all_report'),
				'sales_report' => $sales_report,
				'sales_amount' => $sales_amount,
				'purchase_amount' => $purchase_amount,
				'purchase_report' => $purchase_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);

		// report/all_report
		$reportList = $CI->parser->parse('report/all_report',$data,true);
		return $reportList;
	}
	// Retrieve todays_sales_report
	public function todays_sales_report()
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$sales_report = $CI->Reports->todays_sales_report();
		$sales_amount = 0;
		if(!empty($sales_report)){
			$i=0;
			foreach($sales_report as $k=>$v){$i++;
			   $sales_report[$k]['sl']=$i;
			   $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
			   $sales_amount = $sales_amount+$sales_report[$k]['total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('daily_sales_report'),
				'sales_amount' 	=> $sales_amount,
				'sales_report' 		=> $sales_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/sales_report',$data,true);
		return $reportList;
	}
	public function retrieve_dateWise_SalesReports($start_date,$end_date)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$sales_report = $CI->Reports->retrieve_dateWise_SalesReports($start_date,$end_date);
		$sales_amount = 0;
		if(!empty($sales_report)){
			$i=0;
			foreach($sales_report as $k=>$v){$i++;
			   $sales_report[$k]['sl']=$i;
			   $sales_report[$k]['sales_date'] = $CI->occational->dateConvert($sales_report[$k]['date']);
			   $sales_amount = $sales_amount+$sales_report[$k]['total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' 		=> display('sales_report'),
				'sales_amount' 	=>  $sales_amount,
				'sales_report' 	=> $sales_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/sales_report',$data,true);
		return $reportList;
	}
	// Retrieve todays_purchase_report
	public function todays_purchase_report()
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$purchase_report = $CI->Reports->todays_purchase_report();		
		$purchase_amount = 0;
		if(!empty($purchase_report)){
			$i=0;
			foreach($purchase_report as $k=>$v){$i++;
			    $purchase_report[$k]['sl']=$i;
			    $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
				$purchase_amount = $purchase_amount+$purchase_report[$k]['grand_total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('purchase_report'),
				'purchase_amount' 	=>  $purchase_amount,
				'purchase_report' => $purchase_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/purchase_report',$data,true);
		return $reportList;
	}
	//Total profit report
	public function total_profit_report(){
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$total_profit_report = $CI->Reports->total_profit_report();	

	
		$profit_ammount = 0;
		if(!empty($total_profit_report)){
			$i=0;
			foreach($total_profit_report as $k=>$v){
				$total_profit_report[$k]['sl']=$i;
			    $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);

				$profit_ammount = $profit_ammount+$total_profit_report[$k]['total_profit'];
			}
		}

		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('profit_report'),
				'profit_ammount' 	=>  $profit_ammount,
				'total_profit_report' => $total_profit_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/profit_report',$data,true);
		return $reportList;
	}

	public function retrieve_dateWise_PurchaseReports($start_date,$end_date)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$purchase_report = $CI->Reports->retrieve_dateWise_PurchaseReports($start_date,$end_date);
		$purchase_amount = 0;
		if(!empty($purchase_report)){
			$i=0;
			foreach($purchase_report as $k=>$v){$i++;
			    $purchase_report[$k]['sl']=$i;
			    $purchase_report[$k]['prchse_date'] = $CI->occational->dateConvert($purchase_report[$k]['purchase_date']);
				$purchase_amount = $purchase_amount+$purchase_report[$k]['grand_total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('purchase_report'),
				'purchase_amount' 	=>  $purchase_amount,
				'purchase_report' => $purchase_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/purchase_report',$data,true);
		return $reportList;
	}
	
	//Retrive date wise total profit report
	public function retrieve_dateWise_profit_report($start_date,$end_date)
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$total_profit_report = $CI->Reports->retrieve_dateWise_profit_report($start_date,$end_date);

		$profit_ammount = 0;
		if(!empty($total_profit_report)){
			$i=0;
			foreach($total_profit_report as $k=>$v){
				$total_profit_report[$k]['sl']=$i;
			    $total_profit_report[$k]['prchse_date'] = $CI->occational->dateConvert($total_profit_report[$k]['date']);

				$profit_ammount = $profit_ammount+$total_profit_report[$k]['total_profit'];
			}
		}

		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('profit_report'),
				'profit_ammount' 	=>  $profit_ammount,
				'total_profit_report' => $total_profit_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/profit_report',$data,true);
		return $reportList;
	}
	public function get_products_report_sales_view()
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$product_report = $CI->Reports->retrieve_product_sales_report();
	
		if(!empty($product_report)){
			$i=0;
			foreach($product_report as $k=>$v){$i++;
			    $product_report[$k]['sl']=$i;
			}
		}
		$sub_total = 0;
		if(!empty($product_report)){
			foreach($product_report as $k=>$v){
			    $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
				$sub_total = $sub_total+$product_report[$k]['total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('product_wise_sales_report'),
				'sub_total' =>  $sub_total,
				'product_report' => $product_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/product_report',$data,true);
		return $reportList;
	}
	public function get_products_search_report( $from_date,$to_date )
	{
		$CI =& get_instance();
		$CI->load->model('Reports');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$product_report = $CI->Reports->retrieve_product_search_sales_report( $from_date,$to_date );
		
		if(!empty($product_report)){
			$i=0;
			foreach($product_report as $k=>$v){$i++;
			    $product_report[$k]['sl']=$i;
			}
		}
		$sub_total = 0;
		if(!empty($product_report)){
			foreach($product_report as $k=>$v){
			    $product_report[$k]['sales_date'] = $CI->occational->dateConvert($product_report[$k]['date']);
				$sub_total = $sub_total+$product_report[$k]['total_price'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('product_wise_sales_report'),
				'sub_total' =>  $sub_total,
				'product_report' => $product_report,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$reportList = $CI->parser->parse('report/product_report',$data,true);
		return $reportList;
	}
}
?>