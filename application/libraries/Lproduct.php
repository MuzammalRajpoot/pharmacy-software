<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lproduct {
	/*
	** Retrieve  Quize List From DB 
	*/
	public function product_list()
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$CI->load->model('Web_settings');
		$products_list = $CI->Products->product_list();
		
		
		$i=0;
		if(!empty($products_list)){		
			foreach($products_list as $k=>$v){$i++;
			   $products_list[$k]['sl']=$i;
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title' => display('product_list'),
				'products_list' => $products_list,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);

		$productList = $CI->parser->parse('product/product',$data,true);
		return $productList;
	}
	//Sub Category Add
	public function product_add_form()
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$CI->load->model('Suppliers');
		$CI->load->model('Categories');
		$supplier=$CI->Suppliers->supplier_list("110","0");
		$category_list=$CI->Categories->category_list();
		$tax_list = $CI->db->select('*')
                    ->from('tax_information')
                    ->get()
                    ->result();
		$data = array(
				'title' => display('add_product'),
				'supplier'=>$supplier,
				'category_list'=>$category_list,
				'tax_list'=>$tax_list
			);
		$productForm = $CI->parser->parse('product/add_product_form',$data,true);
		return $productForm;
	}
	public function insert_product($data)
	{
		$CI =& get_instance();
		$CI->load->model('Products');
        $result=$CI->Products->product_entry($data);
        if ($result == 1) {
        	return TRUE;
        }else{
        	return FALSE;
        }
	}
	//Product Edit Data
	public function product_edit_data($product_id)
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$CI->load->model('Suppliers');
		$CI->load->model('Categories');
		
		$product_detail = $CI->Products->retrieve_product_editdata($product_id);
		@$supplier_id= $product_detail[0]['supplier_id'];
		@$category_id= $product_detail[0]['category_id'];
		$supplier_list=$CI->Suppliers->supplier_list("110","0");
		$supplier_selected=$CI->Suppliers->supplier_search_item($supplier_id);

		$category_list = $CI->Categories->category_list();
		$category_selected=$CI->Categories->category_search_item($category_id);
		$tax_list = $CI->Products->retrieve_product_tax();

		$data=array(
			'product_id' 			=> $product_detail[0]['product_id'],
			'product_name' 			=> $product_detail[0]['product_name'],
			'price' 				=> $product_detail[0]['price'],
			'supplier_price' 		=> $product_detail[0]['supplier_price'],
			'product_model' 		=> $product_detail[0]['product_model'],
			'generic_name' 			=> $product_detail[0]['generic_name'],
			'box_size' 				=> $product_detail[0]['box_size'],
			'expire_date' 			=> $product_detail[0]['expire_date'],
			'product_location' 		=> $product_detail[0]['product_location'],
			'product_details' 		=> $product_detail[0]['product_details'],
			'image' 				=> $product_detail[0]['image'],
			'supplier_list'			=> $supplier_list,
			'supplier_selected'		=> $supplier_selected,
			'category_list'			=> $category_list,
			'category_selected'		=> $category_selected,
			'tax_list'				=>$tax_list,
			);
		$chapterList = $CI->parser->parse('product/edit_product_form',$data,true);
		
		return $chapterList;
	}
	//Search Product
	public function product_search_list($product_id)
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$products_list = $CI->Products->product_search_item($product_id);
		$i=0;
		if ($products_list) {
			foreach($products_list as $k=>$v){$i++;
           $products_list[$k]['sl']=$i;
			}
			$data = array(
					'title' => display('product_list'),
					'products_list' => $products_list,
				);
			$productList = $CI->parser->parse('product/product',$data,true);
			return $productList;
		}else{
			redirect('Cproduct/manage_product');
		}
	}
	//Product Details
	public function product_details($product_id)
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$CI->load->library('occational');
		$CI->load->model('Web_settings');
		$details_info = $CI->Products->product_details_info($product_id);
		//
		$purchaseData = $CI->Products->product_purchase_info($product_id);
		$totalPurchase = 0;		
		$totalPrcsAmnt = 0;		
		if(!empty($purchaseData)){	
			foreach($purchaseData as $k=>$v){
				$purchaseData[$k]['final_date'] = $CI->occational->dateConvert($purchaseData[$k]['purchase_date']);
				$totalPrcsAmnt = ($totalPrcsAmnt + $purchaseData[$k]['grand_total_amount']);
				$totalPurchase = ($totalPurchase + $purchaseData[$k]['quantity']);
			}
		}	
		$salesData = $CI->Products->invoice_data($product_id);		
		$totalSales = 0;		
		$totaSalesAmt = 0;		
		if(!empty($salesData)){	
			foreach($salesData as $k=>$v){
				$salesData[$k]['final_date'] = $CI->occational->dateConvert($salesData[$k]['date']);
				$totalSales = ($totalSales + $salesData[$k]['quantity']);
				$totaSalesAmt = ($totaSalesAmt + $salesData[$k]['total_amount']);
			}
		}
		$stock = ($totalPurchase - $totalSales);
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$data = array(
				'title'					=> display('product_details'),
				'product_name' 			=> $details_info[0]['product_name'],
				'product_model' 		=> $details_info[0]['product_model'],
				'price'					=> $details_info[0]['price'],
				'purchaseTotalAmount'	=> $totalPrcsAmnt,
				'salesTotalAmount'		=> $totaSalesAmt,
				'total_purchase'		=> $totalPurchase,
				'total_sales'			=> $totalSales,
				'purchaseData'		=> $purchaseData,
				'salesData'			=> $salesData,
				'stock'				=> $stock,
				'product_statement' => 'Cproduct/product_sales_supplier_rate/'.$product_id,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$productList = $CI->parser->parse('product/product_details',$data,true);
		return $productList;
	}
	
	
	//Product Details
	public function product_sales_supplier_rate($product_id,$startdate,$enddate)
	{
		$CI =& get_instance();
		$CI->load->model('Products');
		$CI->load->model('Web_settings');
		$CI->load->library('occational');
	//Product Summary
		$details_info = $CI->Products->product_details_info($product_id);
	
		$opening_balance=$CI->Products->previous_stock_data($product_id,$startdate);
		
	//Stock details.
		$salesData = $CI->Products->invoice_data_supplier_rate($product_id,$startdate,$enddate);
		$stock=$opening_balance[0]['quantity'];
		$totalIn=0;
		$totalOut=0;
		$totalstock =0;
		$gtotal_sell=0;
		$gtotal_purchase=0;
		
		if(!empty($salesData)){	
			foreach($salesData as $k=>$v){
				$salesData[$k]['fdate'] = $CI->occational->dateConvert($salesData[$k]['date']);

				
				if($salesData[$k]['account']=="a")
					{
						$salesData[$k]['in']=round($salesData[$k]['quantity'],0);
						$salesData[$k]['total_purchase']=$salesData[$k]['total_price'];
						$salesData[$k]['total_sell']=0;
						$salesData[$k]['out']=0;
						$stock=$stock +$salesData[$k]['out']+$salesData[$k]['in'];
						$totalIn+=$salesData[$k]['in'];
						
						$gtotal_purchase+=$salesData[$k]['total_purchase'];
					}
					else
					{
						$salesData[$k]['out']=round($salesData[$k]['quantity'],0);	
						$salesData[$k]['in']=0;
						$stock=$stock +$salesData[$k]['out']+$salesData[$k]['in'];
						$totalOut+=$salesData[$k]['out'];
						
					//total group
						$salesData[$k]['total_purchase']=0;
						$salesData[$k]['total_sell']=$salesData[$k]['total_price'];
						$gtotal_sell+=$salesData[$k]['total_sell'];
					}
					$salesData[$k]['stock']=$stock;
					
					$totalstock =$salesData[$k]['stock'];
				
			}
		}
		$currency_details = $CI->Web_settings->retrieve_setting_editdata();
		$company_info=$CI->Products->retrieve_company();
	
		$data = array(
				'title'					=> display('product_details'),
				'product_id' 			=> $details_info[0]['product_id'],
				'product_name' 			=> $details_info[0]['product_name'],
				'product_model' 		=> $details_info[0]['product_model'],
				'startdate' 			=> $startdate,
				'enddate' 				=> $enddate,
				'price'					=> $details_info[0]['price'],
				'totalIn'				=> $totalIn,
				'totalOut'				=> $totalOut,
				'totalstock'			=> $totalstock,
				'gtotal_sell'			=> $gtotal_sell,
				'gtotal_purchase'		=> $gtotal_purchase,
				'opening_balance'		=> round($opening_balance[0]['quantity'],0),
				'salesData'				=> $salesData,
				'company_info'			=> $company_info,
				'currency' => $currency_details[0]['currency'],
				'position' => $currency_details[0]['currency_position'],
			);
		$productList = $CI->parser->parse('product/product_sales_supplier_rate',$data,true);
		return $productList;
	}
}
?>