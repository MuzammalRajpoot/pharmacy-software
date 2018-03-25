<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lsupplier {
	//Supplier List
	public function supplier_list()
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$suppliers_list = $CI->Suppliers->supplier_list();
		$i=0;
		if(!empty($suppliers_list)){	
			foreach($suppliers_list as $k=>$v){$i++;
			   $suppliers_list[$k]['sl']=$i;
			}
		}
		$data = array(
				'title' => display('supplier_list'),
				'suppliers_list' => $suppliers_list,
			);
		$supplierList = $CI->parser->parse('supplier/supplier',$data,true);
		return $supplierList;
	}
	//Supplier Search Item
	public function supplier_search_item($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$suppliers_list = $CI->Suppliers->supplier_search_item($supplier_id);
		$i=0;
		if ($suppliers_list) {
			foreach($suppliers_list as $k=>$v){$i++;
           $suppliers_list[$k]['sl']=$i;
			}
			$data = array(
					'title' => display('supplier_search_item'),
					'suppliers_list' => $suppliers_list
				);
			$supplierList = $CI->parser->parse('supplier/supplier',$data,true);
			return $supplierList;
		}else{
			redirect('Csupplier/manage_supplier');
		}
	}
	//Product search by supplier
	public function product_by_search(){
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$suppliers_list = $CI->Suppliers->product_search_item($supplier_id);
		$i=0;
		foreach($suppliers_list as $k=>$v){$i++;
           $suppliers_list[$k]['sl']=$i;
		}
		$data = array(
				'title' => display('supplier_search_item'),
				'suppliers_list' => $suppliers_list
			);
		$supplierList = $CI->parser->parse('supplier/supplier',$data,true);
		return $supplierList;
	}


	//Sub Category Add
	public function supplier_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$data = array(
				'title' => display('add_supplier'),
			);
		$supplierForm = $CI->parser->parse('supplier/add_supplier_form',$data,true);
		return $supplierForm;
	}
	public function insert_supplier($data)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
        $result = $CI->Suppliers->supplier_entry($data);
		if ($result == TRUE) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//supplier Edit Data
	public function supplier_edit_data($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$supplier_detail = $CI->Suppliers->retrieve_supplier_editdata($supplier_id);
		$data=array(
			'supplier_id' 	=> $supplier_detail[0]['supplier_id'],
			'supplier_name' => $supplier_detail[0]['supplier_name'],
			'address' 		=> $supplier_detail[0]['address'],
			'mobile' 		=> $supplier_detail[0]['mobile'],
			'details' 		=> $supplier_detail[0]['details'],
			'status' 		=> $supplier_detail[0]['status']
			);
		$chapterList = $CI->parser->parse('supplier/edit_supplier_form',$data,true);
		return $chapterList;
	}
	//Supplier Details Data
	public function supplier_detail_data($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);
		$purchase_info 	= $CI->Suppliers->supplier_purchase_data($supplier_id);
		$total_amount = 0;
		if(!empty($purchase_info)){
			foreach($purchase_info as $k=>$v){
				$purchase_info[$k]['final_date'] = $CI->occational->dateConvert($purchase_info[$k]['purchase_date']);
				$total_amount = $total_amount+$purchase_info[$k]['grand_total_amount'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data=array(
			'supplier_id' 		=> $supplier_detail[0]['supplier_id'],
			'supplier_name' 	=> $supplier_detail[0]['supplier_name'],
			'supplier_address' 	=> $supplier_detail[0]['address'],
			'supplier_mobile' 	=> $supplier_detail[0]['mobile'],
			'details' 			=> $supplier_detail[0]['details'],
			'total_amount' 		=> $total_amount,
			'purchase_info' 	=> $purchase_info,
			'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],		
			);
		$chapterList = $CI->parser->parse('supplier/supplier_details',$data,true);
		return $chapterList;
	}
	
	public function supplier_sales_data($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->library('occational');
		$supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);
		$sales_info 	= $CI->Suppliers->supplier_sales_data($supplier_id,null);
		print_r($sales_info);
		
		if(!empty($sales_info)){
			foreach($sales_info as $k=>$v){
				$sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);
			}
		}
		$data=array(
			'supplier_id' 		=> $supplier_detail[0]['supplier_id'],
			'supplier_name' 	=> $supplier_detail[0]['supplier_name'],
			'supplier_address' 	=> $supplier_detail[0]['address'],
			'supplier_mobile' 	=> $supplier_detail[0]['mobile'],
			'details' 			=> $supplier_detail[0]['details'],
			'sales_info' 		=> $sales_info,

			);
		$sales_report = $CI->parser->parse('supplier/supplier_sales_report',$data,true);
		return $sales_report;
	}
//Ledger Book Maintaining information....
	public function supplier_ledger($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$supplier_details = $CI->Suppliers->supplier_personal_data($supplier_id);
		$ledger 	= $CI->Suppliers->suppliers_ledger($supplier_id);
		$summary 	= $CI->Suppliers->suppliers_transection_summary($supplier_id);
	
		$balance = 0;
		if(!empty($ledger)){
			foreach($ledger as $index=>$value){
				$ledger[$index]['final_date'] = $CI->occational->dateConvert($ledger[$index]['date']);
				
				if(!empty($ledger[$index]['chalan_no'])or  $ledger[$index]['chalan_no']=="NA")
				{
					$ledger[$index]['credit']=$ledger[$index]['amount'];
					$ledger[$index]['balance']=$balance-$ledger[$index]['amount'];
					$ledger[$index]['debit']="";
					$balance=$ledger[$index]['balance'];
				}
				else
				{
					$ledger[$index]['debit']=$ledger[$index]['amount'];
					$ledger[$index]['balance']=$balance+$ledger[$index]['amount'];
					$ledger[$index]['credit']="";
					$balance=$ledger[$index]['balance'];
				}
				
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data=array(
			'supplier_id' 		=> $supplier_details[0]['supplier_id'],
			'supplier_name' 	=> $supplier_details[0]['supplier_name'],
			'ledger' 			=> $ledger,
			'total_credit'		=> $summary[0][0]['total_credit'],
			'total_debit'		=> $summary[1][0]['total_debit'],
			'total_balance'		=> $summary[1][0]['total_debit']-$summary[0][0]['total_credit'],

			'supplier_ledger'	=> 'Csupplier/supplier_ledger/'.$supplier_id,
			'supplier_sales_details' => 'Csupplier/supplier_sales_details/'.$supplier_id,
			'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/'.$supplier_id,
			'sales_payment_actual' => 'Csupplier/sales_payment_actual/'.$supplier_id,
			'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],
			);
			
		$singlecustomerdetails = $CI->parser->parse('supplier/supplier_ledger',$data,true);
		return $singlecustomerdetails;
	}
	public function supplier_sales_details($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);
		$sales_info 	= $CI->Suppliers->supplier_sales_details($supplier_id);
		$sub_total=0;
		if(!empty($sales_info)){
			foreach($sales_info as $k=>$v){
				$sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);
				$sub_total+=$sales_info[$k]['total'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data=array(
			'supplier_id' 		=> $supplier_detail[0]['supplier_id'],
			'supplier_name' 	=> $supplier_detail[0]['supplier_name'],
			'supplier_address' 	=> $supplier_detail[0]['address'],
			'supplier_mobile' 	=> $supplier_detail[0]['mobile'],
			'details' 			=> $supplier_detail[0]['details'],
			'sub_total'			=> $sub_total,
			'sales_info' 		=> $sales_info,
			'supplier_ledger'	=> 'Csupplier/supplier_ledger/'.$supplier_id,
			'supplier_sales_details' => 'Csupplier/supplier_sales_details/'.$supplier_id,
			'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/'.$supplier_id,
			'sales_payment_actual' => 'Csupplier/sales_payment_actual/'.$supplier_id,
			'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],	
			);
		$sales_report = $CI->parser->parse('supplier/supplier_sales_details',$data,true);
		return $sales_report;
	}
	public function supplier_sales_summary($supplier_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		$supplier_detail = $CI->Suppliers->supplier_personal_data($supplier_id);
		$sales_info 	= $CI->Suppliers->supplier_sales_summary($supplier_id);
		
		$sub_total=0;
		if(!empty($sales_info)){
			foreach($sales_info as $k=>$v){
				$sales_info[$k]['date'] = $CI->occational->dateConvert($sales_info[$k]['date']);
				$sub_total+=$sales_info[$k]['total'];
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data=array(
			'supplier_id' 		=> $supplier_detail[0]['supplier_id'],
			'supplier_name' 	=> $supplier_detail[0]['supplier_name'],
			'supplier_address' 	=> $supplier_detail[0]['address'],
			'supplier_mobile' 	=> $supplier_detail[0]['mobile'],
			'details' 		=> $supplier_detail[0]['details'],
			'sub_total'		=> $sub_total,
			'sales_info' 		=> $sales_info	,
			'supplier_ledger'	=> 'Csupplier/supplier_ledger/'.$supplier_id,
			'supplier_sales_details' => 'Csupplier/supplier_sales_details/'.$supplier_id,
			'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/'.$supplier_id,
			'sales_payment_actual' => 'Csupplier/sales_payment_actual/'.$supplier_id,
			'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],	
		);
		$sales_report = $CI->parser->parse('supplier/supplier_sales_summary',$data,true);
		return $sales_report;
	}
	
	########################## Sales & Payment ledger #########################
	#	This function will be responsible for retreive all actual sales information 
	# 	as well as payment info.Whatever stock that will not be matter .
	############################################################################
	function sales_payment_actual($supplier_id,$limit,$start_record,$links)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
		
		$sales_payment_actual = $CI->Suppliers->sales_payment_actual($supplier_id,$limit,$start_record);
		
		$total_amount=0;
		if(!empty($sales_payment_actual)){
			foreach($sales_payment_actual as $k=>$v){
				$sales_payment_actual[$k]['DATE'] = $CI->occational->dateConvert($sales_payment_actual[$k]['DATE']);
				
				if($sales_payment_actual[$k]['sub_total']>0)
				{
					$sales_payment_actual[$k]['debit']=$sales_payment_actual[$k]['sub_total'];
					$sales_payment_actual[$k]['credit']="";
					$sales_payment_actual[$k]['deposit_no']=$sales_payment_actual[$k]['no_transection'];
					$sales_payment_actual[$k]['cartoon_no']="";
					$sales_payment_actual[$k]['event']="Deposit";
				}
				else
				{
					$sales_payment_actual[$k]['credit']=$sales_payment_actual[$k]['sub_total'];
					$sales_payment_actual[$k]['debit']="";
					$sales_payment_actual[$k]['cartoon_no']=$sales_payment_actual[$k]['no_transection'];
					$sales_payment_actual[$k]['deposit_no']="";
					$sales_payment_actual[$k]['event']="Sale";
				}
				
				$total_amount += $sales_payment_actual[$k]['sub_total'];
				
				$sales_payment_actual[$k]['balance'] = $total_amount;
				
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
			'title' 	=> display('supplier_actual_ledger'),
			'info'				=> $CI->Suppliers->supplier_personal_data($supplier_id),
			'total_details' 	=> $CI->Suppliers->sales_payment_actual_total($supplier_id),
			'ledger' 		=> $sales_payment_actual, 
			'company_info'	=> $CI->Suppliers->retrieve_company(),
			'supplier_ledger' => 'Csupplier/supplier_ledger/'.$supplier_id,
			'supplier_sales_details' => 'Csupplier/supplier_sales_details/'.$supplier_id,
			'supplier_sales_summary' => 'Csupplier/supplier_sales_summary/'.$supplier_id,
			'sales_payment_actual' => 'Csupplier/sales_payment_actual/'.$supplier_id,
			'currency' => $currency_details[0]['currency'],
			'position' => $currency_details[0]['currency_position'],	
			);

		$sales_actual_report = $CI->parser->parse('supplier/sales_payment_ledger',$data,true);
		return $sales_actual_report;
	
	}
	
	//Search supplier
	public function supplier_search_list($cat_id,$company_id)
	{
		$CI =& get_instance();
		$CI->load->model('Suppliers');
		$category_list = $CI->Suppliers->retrieve_category_list();
		$suppliers_list = $CI->Suppliers->supplier_search_list($cat_id,$company_id);
		$data = array(
				'title' => display('supplier_list'),
				'suppliers_list' => $suppliers_list,
				'category_list' => $category_list
			);
		$supplierList = $CI->parser->parse('supplier/supplier',$data,true);
		return $supplierList;
	}
}
?>