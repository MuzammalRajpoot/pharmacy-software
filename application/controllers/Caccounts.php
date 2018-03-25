<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Caccounts extends CI_Controller {
	function __construct() 
    {
        parent::__construct();
        $this->load->library('lsettings');
        $this->load->library('auth');
        $this->load->library('session');
        $this->load->library('laccounts');

        $this->load->model('Settings');
        $this->load->model('Accounts');
        $this->load->model('Web_settings');
        $this->auth->check_admin_auth();
        $this->template->current_menu = 'Caccounts';

        if ($this->session->userdata('user_type') == '2') {
            $this->session->set_userdata(array('error_message'=>display('you_are_not_access_this_part')));
            redirect('Admin_dashboard');
        }
    }
	
  	public function index()
  	{
  		$data=array('title'=>display('accounts_inflow_form'));
        $data["accounts"]=$this->Accounts->accounts_name_finder(2);
        $data["bank"]=$this->Settings->get_bank_list();
        $content = $this->parser->parse('accounts/inflow',$data,true);
  		$this->template->full_admin_html_view($content);
  	}
    #===============Outflow accounts========#    
    public function outflow()
    {
        $data=array('title'=> display('accounts_outflow_form'));
        $data["accounts"]=$this->Accounts->accounts_name_finder(1);
        $data["bank"]=$this->Settings->get_bank_list();
        $content = $this->parser->parse('accounts/outflow',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }
    #===============Add TAX================#
    public function add_tax()
    {
        $data=array('title'=>display('accounts_tax_form'));
        $content = $this->parser->parse('accounts/add_tax',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }
    #==============TAX Entry==============#
    public function tax_entry()
    {
        $data=array('title'=>display('accounts_tax_form'));
        $tax['tax'] = $this->input->post('enter_tax');
        $result = $this->Accounts->tax_entry($tax);
        if ($result == true) {
           $this->session->set_userdata(array('message'=>display('successfully_inserted')));
        }else{
            $this->session->set_userdata(array('error_message'=>display('already_exists')));
        }
        $content = $this->parser->parse('accounts/add_tax',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }
    #==============Manage TAX==============#
    public function manage_tax()
    {
        $tax_list = $this->db->select('*')
                    ->from('tax_information')
                    ->get()
                    ->result();

        $data=array(
                'title'=>display('accounts_manage_tax'),
                'tax_list'=>$tax_list
            );
        
        $content = $this->parser->parse('accounts/manage_tax',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }

    #==============TAX Edit==============#
    public function tax_edit($tax_id)
    {
        $tax_info = $this->db->select('*')
                    ->from('tax_information')
                    ->where('tax_id',$tax_id)
                    ->get()
                    ->result_array();

        $data=array(
                'title'=>display('accounts_tax_edit'),
                'tax_id'=>$tax_info[0]['tax_id'],
                'tax'=>$tax_info[0]['tax']
            );
        
        $content = $this->parser->parse('accounts/tax_edit',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }
    #==============TAX Update==============#
    public function update_tax($id)
    {
        $data=array('title'=>display('accounts_tax_edit'));
        $tax['tax'] = $this->input->post('enter_tax');
        $result = $this->Accounts->update_tax_data($tax,$id);
        if ($result == true) {
           $this->session->set_userdata(array('message'=>display('successfully_updated')));
        }
        $tax_list = $this->db->select('*')
                    ->from('tax_information')
                    ->get()
                    ->result();

        $data=array(
                'title'=>"Accounts | Manage Tax",
                'tax_list'=>$tax_list
            );
        $content = $this->parser->parse('accounts/manage_tax',$data,true);
        $this->template->full_admin_html_view($content);   
        
    }

    #==============TAX Update==============#
    public function tax_delete($id)
    {
        $data=array('title'=>"Accounts | Tax Form ");
        $tax['tax'] = $this->input->post('enter_tax');
        
        $result = $this->db->delete('tax_information', array('tax_id' => $id)); 

        if ($result == true) {
           $this->session->set_userdata(array('message'=>display('successfully_delete')));
        }
        redirect('Caccounts/manage_tax');
    }

    #==============Closing reports==========#
    public function closing()
    {
      $data=array('title'=>"Accounts | Daily Closing");
      $data=$this->Accounts->accounts_closing_data();
      $content = $this->parser->parse('accounts/closing_form',$data,true);
      $this->template->full_admin_html_view($content);  
    }
    #===============Accounts summary==========#
    public function summary()
    {

        $currency_details = $this->Web_settings->retrieve_setting_editdata();
        $data=array(
            'title'=>display('accounts_summary_data'),
            'currency' => $currency_details[0]['currency'],
            'position' => $currency_details[0]['currency_position'],
            );
      
        $data['table_inflow']=$this->Accounts->table_name(2);
        $data['table_outflow']=$this->Accounts->table_name(1);
      
        $data['inflow']=$this->Accounts->accounts_summary(2);
        $data['total_inflow']=$this->Accounts->sub_total;
      
        $data['outflow']=$this->Accounts->accounts_summary(1);
        $data['total_outflow']=$this->Accounts->sub_total;
      
        $content = $this->parser->parse('accounts/summary',$data,true);
        $this->template->full_admin_html_view($content);  
    }
    #================Summary single===========#
    public function summary_single($start,$end,$account)
    {
        $data=array('title'=>display('accounts_details_data'));
            
        //Getting all tables name.   
        $data['table_inflow']=$this->Accounts->table_name(2);
        $data['table_outflow']=$this->Accounts->table_name(1);
            
        $data['accounts']=$this->Accounts->accounts_summary_details($start,$end,$account);
        //$data['total_inflow']=$this->accounts->sub_total;
            
        $content = $this->parser->parse('accounts/summary_single',$data,true);
	    $this->template->full_admin_html_view($content);      
    }
    #==============Summary report date  wise========#
    public function summary_datewise()
    {
        $start=  $this->input->post('from_date')." 00:00:00";
        $end=  $this->input->post('to_date')." 00:00:00";
        $account=$this->input->post('accounts');
        
        if($account != "All")
            { $url="Caccounts/summary_single/$start/$end/$account";
                redirect(base_url($url));
                exit;     
            }

        $currency_details = $this->Web_settings->retrieve_setting_editdata();
            
        $data=array(
            'title'=>display('datewise_summary_data'),
            'currency' => $currency_details[0]['currency'],
            'position' => $currency_details[0]['currency_position'],
            );
            
        //Getting all tables name.   
        $data['table_inflow']=$this->Accounts->table_name(2);
        $data['table_outflow']=$this->Accounts->table_name(1);
        
        $data['inflow']=$this->Accounts->accounts_summary_datewise($start,$end,"2");
        $data['total_inflow']=$this->Accounts->sub_total;
        
        $data['outflow']=$this->Accounts->accounts_summary_datewise($start,$end,"1");
        $data['total_outflow']=$this->Accounts->sub_total;
        
        $content = $this->parser->parse('accounts/summary',$data,true);
	    $this->template->full_admin_html_view($content);  
    }
        
  #============ Cheque Manager ==============#
  public function cheque_manager()
   {

    $currency_details = $this->Web_settings->retrieve_setting_editdata();
       
	$data=array(
        'title'=>display('accounts_cheque_manager'),
        'currency' => $currency_details[0]['currency'],
        'position' => $currency_details[0]['currency_position'],
        );
    $data["cheque_manager"]=$this->Accounts->cheque_manager(30,1);
    //print_r($data);
    $content = $this->parser->parse('accounts/cheque_manager',$data,true);
	$this->template->full_admin_html_view($content);
   }
   #============ Cheque Manager edit ==============#
  public function cheque_manager_edit($transection_id,$action)
    {
        $this->Accounts->data_update(array('status'=>$action),"customer_ledger",array('transaction_id'=>$transection_id));
        $this->Accounts->data_update(array('cheque_status'=>$action),"cheque_manger",array('transection_id'=>$transection_id));
        $this->session->set_userdata(array('message'=>display('cheque_ammount_asjusted')));
		redirect(base_url('Caccounts/cheque_manager'));exit;
    }
  #===============Outflow entry==============#
  public function outflow_entry()
  {
    $todays_date = $this->input->post('transection_date');
    $customer_id=$this->input->post('supplier_id');
            
    //Data Receive.
    if($this->input->post('supplier_id'))
    {
         $customer_id=$this->input->post('supplier_id');
    }
    else {
        $customer_id=$this->input->post('customer_name_others');

    }
    $payment_type=$this->input->post('payment_type');
    $cheque_no=$this->input->post('cheque_no');
    $cheque_mature_date=$this->input->post('cheque_mature_date');
    $bank_name=$this->input->post('bank_name');
    
    $account_table=$this->input->post('account_table');
    $amount=$this->input->post('amount');
    $description=$this->input->post('description');
    
    if($payment_type==1){$status=1;}else{$status=0;}
    $deposit_no=$this->generator(10);
    $transaction_id=$this->generator(15);
                    
    //Data ready for transferring to customer_ledger
    $data = array(
			'transaction_id'	=>	$transaction_id,
			'supplier_id'		=>	$customer_id,
			'chalan_no'		=>	NULL,
			'deposit_no'		=>	$deposit_no,
                        'amount'		=>	$amount,
                        'description'		=>	$description,
			'payment_type'          =>	$payment_type,
			'cheque_no'		=>	$cheque_no,
			'date'			=>	$todays_date,
			'status'		=>      $status
		);
                    
    //Data ready for transferring to accounts ledger
    $data1 = array(
			'transection_id'	=>	$transaction_id,
			'tracing_id'		=>	$customer_id,
			'payment_type'		=>	$payment_type,
			'date'                  =>	$todays_date,
            'amount'		=>	$amount,
            'description'		=>	$description,
			'status'		=>      $status
		);  
                      
    //################### Cheque  || Pay Order ########## Start ########
                    
    //This part is for being payment type cheque or Pay order.
     if($payment_type==2 || $payment_type==3)
     {
        $cheque_id=$this->auth->generator(12);
        //Data ready for transfering to cheque table.
        $data_cheque=array(
                'cheque_id'             =>	$cheque_id,
                'transection_id'	=>	$transaction_id,
          		'customer_id'		=>	$customer_id,
          		'bank_id'		=>	$bank_name,
          		'cheque_no'		=>	$cheque_no,
                                  'date'                  =>	$cheque_mature_date,
          		'transection_type'	=>	"outflow",
          		'cheque_status'		=>	0, //not matured, 1 will be cleared from bank
                                  'amount'		=>	$amount,
          		'status'		=>      1
            );
        $this->Accounts->pay_table($data_cheque,"cheque_manger");
     }
             
    //################### Cheque  || Pay Order ########## Finish ########
               
		$this->Accounts->supplier_ledger($data);
                $this->Accounts->pay_table($data1,$account_table);
		$this->session->set_userdata(array('message'=>display('successfully_payment_paid')));
		redirect(base_url('Caccounts/outflow'));exit;
    }


    //This function will receive inflow data afte submitting.
    public function inflow_entry()
    {
		$todays_date =  $todays_date = $this->input->post('transection_date'); // date("Y-m-d");
                
        //Data Receive.
        if($this->input->post('customer_id'))
        {
             $customer_id=$this->input->post('customer_id');
        }
        else {
            $customer_id=$this->input->post('customer_name_others');

        }
        $payment_type=$this->input->post('payment_type');
        $cheque_no=$this->input->post('cheque_no');
        $cheque_mature_date=$this->input->post('cheque_mature_date');
        $bank_name=$this->input->post('bank_name');
        
        $account_table=$this->input->post('account_table');
        $amount=$this->input->post('amount');
        $description=$this->input->post('description');
        
        if($payment_type=="1"){$status=1;}else{$status=0;}
        $receipt_no=$this->auth->generator(10);
        $transaction_id=$this->auth->generator(15);
                    
        //Data ready for transferring to customer_ledger
        $data = array(
    			'transaction_id'	=>	$transaction_id,
    			'customer_id'		=>	$customer_id,
    			'invoice_no'		=>	NULL,
    			'receipt_no'		=>	$receipt_no,
                            'amount'		=>	$amount,
                            'description'		=>	$description,
    			'payment_type'          =>	$payment_type,
    			'cheque_no'		=>	$cheque_no,
    			'date'			=>	$todays_date,
    			'status'		=>      $status
    		);
                        
        //Data ready for transferring to own table
        $data1 = array(
    			'transection_id'	=>	$transaction_id,
    			'tracing_id'		=>	$customer_id,
    			'payment_type'		=>	$payment_type,
    			'date'                  =>	$todays_date,
                            'amount'		=>	$amount,
                            'description'		=>	$description,
    			'status'		=>      $status
    		);  
    
                    
        //################### Cheque  || Pay Order ########## Start ########
                    
        //This part is for being payment type cheque or Pay order.
           if($payment_type=="2" || $payment_type=="3")
           {
                $cheque_id=$this->auth->generator(12);
                //Data ready for transfering to cheque table.
                $data_cheque=array(
                        'cheque_id'             =>	$cheque_id,
                        'transection_id'	=>	$transaction_id,
            			'customer_id'		=>	$customer_id,
            			'bank_id'		=>	$bank_name,
            			'cheque_no'		=>	$cheque_no,
                                    'date'                  =>	$cheque_mature_date,
            			'transection_type'	=>	"inflow",
            			'cheque_status'		=>	0, //not matured, 1 will be cleared from bank
                                    'amount'		=>	$amount,
            			'status'		=>      1
                    );
                $this->Accounts->pay_table($data_cheque,"cheque_manger");
            }
             
        //################### Cheque  || Pay Order ########## Finish ########
           
		$this->Accounts->customer_ledger($data);
                $this->Accounts->pay_table($data1,$account_table);
                
		$this->session->set_userdata(array('message'=>display('successfully_payment_received')));
		redirect(base_url('Caccounts'));exit;   
    }
        
        
    //This function will be used to edit the inflow & outflow data.    
    public function inout_edit($transection_id,$table,$action)
    {
        $data=array('title'=>display('accounts_edit_data'));
     
        if($action=="del")
        {
            //Call the delete method to destroy data from both table.
           $data=$this->Accounts->in_out_del($transection_id,$table);
           $this->session->set_userdata(array('message'=> $data));
           redirect(base_url('Caccounts/summary/'));exit;
                
        }
        else 
        {
        
        if(substr($table,0,2)=="in")
            {
              $data["edit"]=$this->Accounts->inflow_edit($transection_id,$table,"2");
              
              $content = $this->parser->parse('accounts/inflow_edit.php',$data,true);
              $this->template->full_admin_html_view($content); 
            }
            else 
            {
                $data["edit"]=$this->Accounts->outflow_edit($transection_id,$table,"1");
                $content = $this->parser->parse('accounts/outflow_edit.php',$data,true);
                $this->template->full_admin_html_view($content); 
            }
        } 
    }
    // Inflow edit form
    public function inflow_edit_form()
    {
        //Call the delete method to destroy data from both table.
        $data=$this->Accounts->in_out_del($transection_id,$table);
        $this->session->set_userdata(array('message'=> display('successfully_updated')));
        redirect(base_url('Caccounts/summary/'));exit;
    }
    // Inflow edit receiver 
    public function inflow_edit_receiver($transection_id)
    {
	   $todays_date = date("Y-m-d");
         //Data Receive.
        if($this->input->post('customer_id'))
        {
            $customer_id=$this->input->post('customer_id');
        }
        else {
            $customer_id=$this->input->post('customer_name_others');

        }
        $payment_type=$this->input->post('payment_type');
        $cheque_no=$this->input->post('cheque_no');
        $cheque_mature_date=$this->input->post('cheque_mature_date');
        $bank_name=$this->input->post('bank_name');
      
        $account_table=   $this->input->post('account_table');
        $previous_table=  $this->input->post('pre_table');
      
        $amount=$this->input->post('amount');
        $description=$this->input->post('description');
      
        $pre_data=$this->Accounts->transection_info($transection_id,$previous_table,array("transection_id"=>$transection_id));
                  
                    
        //Data ready for transferring to customer_ledger
        $data = array(
            'customer_id'	=>	$customer_id,
            'amount'		=>	$amount,
            'description'	=>	$description,
			'payment_type'  =>	$payment_type,
			'cheque_no'		=>	$cheque_no
		);
                    
                    
        //Data ready for transferring to 
        $data1 = array(
			'transection_id'	=>	$transection_id,
			'tracing_id'		=>	$customer_id,
			'payment_type'		=>	$payment_type,
			'date'              =>	$todays_date.' 00:00:00',
            'amount'		    =>	$amount,
            'description'		=>	$description,
			'status'		    =>      1
		); 
                  
        // Following group data for other days corrections.           
                                   
        //Data ready for transferring to customer_ledger
        $data2= array(
			'customer_id'		=>	$customer_id,
            'description'		=>	$description,
			'payment_type'      =>	$payment_type,
			'cheque_no'		    =>	$cheque_no
		);
                    
                    
         //Data ready for transferring to 
        $data3 = array(
			'transection_id'	=>	$transection_id,
			'tracing_id'		=>	$customer_id,
			'payment_type'		=>	$payment_type,
            'date'              =>  $pre_data[0]['date'],
            'amount'            =>  $pre_data[0]['amount'],
             'description'		=>	$description,
			'status'		    =>      1
		); 
                
                
                                         
                    
        //################### Cheque  || Pay Order ########## Start ########
                    
        //This part is for being payment type cheque or Pay order.
           if($payment_type==2 || $payment_type==3)
           {
                $cheque_id=$this->auth->generator(12);
                //Data ready for transfering to cheque table.
                $data_cheque=array(
                    'cheque_id'         =>	$cheque_id,
                    'transection_id'	=>	$transection_id,
        			'customer_id'		=>	$customer_id,
        			'bank_id'		    =>	$bank_name,
        			'cheque_no'		    =>	$cheque_no,
                    'date'                  =>	$cheque_mature_date,
        			'transection_type'	=>	"inflow",
        			'cheque_status'		=>	0, //not matured, 1 will be cleared from bank
                    'amount'		=>	$amount,
        			'status'		=>      1
                );
                      
                //Deleting Old data.
                $this->Accounts->delete_all_table_data("cheque_manger",array('transection_id' => $transection_id));
                //Inserting new data.
                $this->Accounts->pay_table($data_cheque,"cheque_manger");
              
            }
            else 
            {
                //Deleting Old data.
                $this->Accounts->delete_all_table_data("cheque_manger",array('transection_id' => $transection_id));
            }
             
            //################### Cheque  || Pay Order ########## Finish ########
              
            if($todays_date." 00:00:00"==$pre_data[0]["date"])
            {
                //Updating data on Supplier Ledger table.
                $this->Accounts->data_update($data,"customer_ledger",array('transaction_id'=>$transection_id));
                 
                //Deleting Old data.
                $this->Accounts->delete_all_table_data($previous_table,array('transection_id' => $transection_id));

                //Inserting new data.
                $this->Accounts->pay_table($data1,$account_table);

                $this->session->set_userdata(array('message'=>display('successfully_updated')));
                redirect(base_url('Caccounts/inout_edit/'.$transection_id.'/'.$account_table.'/'.'edit'));exit;
            }
            else {
                //Updating data on Supplier Ledger table.
                $this->Accounts->data_update($data2,"customer_ledger",array('transaction_id'=>$transection_id));
                 
                //Deleting Old data.
                $this->Accounts->delete_all_table_data($previous_table,array('transection_id' => $transection_id));

                //Inserting new data.
                $this->Accounts->pay_table($data3,$account_table);


                $this->session->set_userdata(array('message'=> display('successfully_updated_2_closing_ammount_not_changeale')));
                redirect(base_url('Caccounts/inout_edit/'.$transection_id.'/'.$account_table.'/'.'edit'));exit;
            }
        }

    // Outflow edit receiver     
    public function outflow_edit_receiver($transection_id)
    {
	    $todays_date = date("Y-m-d");
        //Data Receive.
        if($this->input->post('supplier_id'))
        {
             $customer_id=$this->input->post('supplier_id');
        }
        else {
            $customer_id=$this->input->post('customer_name_others');

        }
        $payment_type=$this->input->post('payment_type');
        $cheque_no=$this->input->post('cheque_no');
        $cheque_mature_date=$this->input->post('cheque_mature_date');
        $bank_name=$this->input->post('bank_name');
        
        $account_table=   $this->input->post('account_table');
        $previous_table=  $this->input->post('pre_table');
        
        $amount=$this->input->post('amount');
        $description=$this->input->post('description');
        
        $pre_data=$this->Accounts->transection_info($transection_id,$previous_table,array("transection_id"=>$transection_id));
              
                
        //Data ready for transferring to customer_ledger
        $data = array(
    		'supplier_id'		=>	$customer_id,
                        'amount'		=>	$amount,
                        'description'		=>	$description,
    		'payment_type'          =>	$payment_type,
    		'cheque_no'		=>	$cheque_no
    	);   
                
        //Data ready for transferring to 
        $data1 = array(
    		'transection_id'	=>	$transection_id,
    		'tracing_id'		=>	$customer_id,
    		'payment_type'		=>	$payment_type,
    		'date'                  =>	$todays_date.' 00:00:00',
                        'amount'		=>	$amount,
                        'description'		=>	$description,
    		'status'		=>      1
    	); 
        // Following group data for other days corrections.           
                               
        //Data ready for transferring to customer_ledger
        $data2= array(
    		'supplier_id'		=>	$customer_id,
            'description'		=>	$description,
    		'payment_type'          =>	$payment_type,
    		'cheque_no'		=>	$cheque_no
    	);
                 
        //Data ready for transferring to 
        $data3 = array(
    		'transection_id'	=>	$transection_id,
    		'tracing_id'		=>	$customer_id,
    		'payment_type'		=>	$payment_type,
            'date'                  =>      $pre_data[0]['date'],
            'amount'                =>      $pre_data[0]['amount'],
            'description'		=>	$description,
    		'status'		=>      1
    	);       
        //################### Cheque  || Pay Order ########## Start ########
                
        //This part is for being payment type cheque or Pay order.
       if($payment_type==2 || $payment_type==3)
       {
            $cheque_id=$this->auth->generator(12);
            //Data ready for transfering to cheque table.
            $data_cheque=array(
                            'cheque_id'             =>	$cheque_id,
                            'transection_id'	=>	$transection_id,
        		'customer_id'		=>	$customer_id,
        		'bank_id'		=>	$bank_name,
        		'cheque_no'		=>	$cheque_no,
                            'date'                  =>	$cheque_mature_date,
        		'transection_type'	=>	"outflow",
        		'cheque_status'		=>	0, //not matured, 1 will be cleared from bank
                            'amount'		=>	$amount,
        		'status'		=>      1
            );       
            //Deleting Old data.
            $this->Accounts->delete_all_table_data("cheque_manger",array('transection_id' => $transection_id));
            //Inserting new data.
            $this->Accounts->pay_table($data_cheque,"cheque_manger");
       }
        else 
        {
            //Deleting Old data.
            $this->Accounts->delete_all_table_data("cheque_manger",array('transection_id' => $transection_id));
        }
         
        //################### Cheque  || Pay Order ########## Finish ########
           
           
        if($todays_date." 00:00:00"==$pre_data[0]["date"])
        {
            //Updating data on Supplier Ledger table.
            $this->Accounts->data_update($data,"supplier_ledger",array('transaction_id'=>$transection_id));
             
            //Deleting Old data.
            $this->Accounts->delete_all_table_data($previous_table,array('transection_id' => $transection_id));

            //Inserting new data.
            $this->Accounts->pay_table($data1,$account_table);

            $this->session->set_userdata(array('message'=> display('successfully_updated')));
            redirect(base_url('Caccounts/inout_edit/'.$transection_id.'/'.$account_table.'/'.'edit'));exit;
        }
        else {
            //Updating data on Supplier Ledger table.
            $this->Accounts->data_update($data2,"supplier_ledger",array('transaction_id'=>$transection_id));
                 
             //Deleting Old data.
            $this->Accounts->delete_all_table_data($previous_table,array('transection_id' => $transection_id));

             //Inserting new data.
            $this->Accounts->pay_table($data3,$account_table);

            $this->session->set_userdata(array('message'=> display('successfully_updated_2_closing_ammount_not_changeale')));
            redirect(base_url('Caccounts/inout_edit/'.$transection_id.'/'.$account_table.'/'.'edit'));exit;
        }
    }

    // Add daily closing 
    public function add_daily_closing()
	{
		date_default_timezone_set('Asia/Dhaka');
		$todays_date = date("Y-m-d H:i:s");
		
		$data = array(
			'closing_id'		=>	$this->generator(15),			
			'last_day_closing'	=>	$this->input->post('last_day_closing'),
			'cash_in'		=>	$this->input->post('cash_in'),
			'cash_out'		=>	$this->input->post('cash_out'),
			'date'			=>	$todays_date,
			'amount'		=>	$this->input->post('cash_in_hand'),
			'adjustment'		=>	$this->input->post('adjusment'),
			'status'		=>      1
		);
		
		$invoice_id = $this->Accounts->daily_closing_entry( $data );
		$this->session->set_userdata(array('message'=> display('successfully_added')));
		redirect(base_url('Caccounts'));exit;
	}
    // Add drawing entry
    public function add_drawing_entry()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Closings');
		date_default_timezone_set('Asia/Dhaka');
		$todays_date = date("Y-m-d");
		
		$data = array(
			'drawing_id'		=>	$this->generator(15),
			'date'				=>	$todays_date,
			'drawing_title'		=>	$this->input->post('title'),
			'description'		=>	$this->input->post('description'),
			'amount'		=>	$this->input->post('amount'),
			'status'		=>1
		);
		
		$invoice_id = $CI->Closings->drawing_entry( $data );
		$this->session->set_userdata(array('message'=> display('successfully_draw_added')));
		redirect(base_url('cclosing'));exit;
	}
	// Add expance entry
	public function add_expence_entry()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Closings');
		date_default_timezone_set('Asia/Dhaka');
		$todays_date = date("Y-m-d");
		
		$data = array(
			'expence_id'		=>	$this->generator(15),
			'date'				=>	$todays_date,
			'expence_title'		=>	$this->input->post('title'),
			'description'		=>	$this->input->post('description'),
			'amount'		=>	$this->input->post('amount'),
			'status'		=>1
		);
		
		$invoice_id = $CI->Closings->expence_entry( $data );
		$this->session->set_userdata(array('message'=> display('successfully_added')));
		redirect(base_url('cclosing'));exit;
	}
	// Add bank entry
	public function add_banking_entry()
	{
		$CI =& get_instance();
		$this->auth->check_admin_auth();
		$CI->load->model('Closings');
		date_default_timezone_set('Asia/Dhaka');
		$todays_date = date("Y-m-d");
		
		$data = array(
			'banking_id'		=>	$this->generator(15),
			'date'				=>	$todays_date,
			'bank_id'			=>	$this->input->post('bank_id'),
			'deposit_type'		=>	$this->input->post('deposit_name'),
			'transaction_type'	=>	$this->input->post('transaction_type'),
			'description'		=>	$this->input->post('description'),
			'amount'			=>	$this->input->post('amount'),
			'status'			=>1
		);
		
		$invoice_id = $CI->Closings->banking_data_entry( $data );
		$this->session->set_userdata(array('message'=> display('successfully_added')));
		redirect(base_url('cclosing'));exit;
	}
	
	//Closing report
	public function closing_report()
	{	
		// $config = array();
		// $config["base_url"] = base_url()."Caccounts/closing_report";
		// $config["total_rows"] = $this->Accounts->count_daily_closing_data();	  
		// $config["per_page"] = 10;
		// $config["uri_segment"] = 3;	
		// $config['full_tag_open'] = '<div id="pagination">';
		// $config['full_tag_close'] = '</div>';
		// $this->pagination->initialize($config);
		
		// $page =($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// $limit = $config["per_page"];
  //       $links =$this->pagination->create_links();

        $content =$this->laccounts->daily_closing_list();

        $sub_menu = array(
            array('label'=> 'Income', 'url' => 'Caccounts'),
            array('label'=> 'Expense', 'url' => 'Caccounts/outflow/'),
            array('label'=> 'Accounts Summary', 'url' => 'Caccounts/summary/'),
            array('label'=> 'Cheque Manager', 'url' => 'Caccounts/cheque_manager/'),
            array('label'=> 'Closing', 'url' => 'Caccounts/closing/'),
            array('label'=> 'Closing Report', 'url' => 'Caccounts/closing_report/','class' =>'active')
        );
		$this->template->full_admin_html_view($content,$sub_menu);
	}
    // Date wise closing reports 
	public function date_wise_closing_reports()
	{			
		$from_date = $this->input->post('from_date');		
		$to_date = $this->input->post('to_date');	
		
        $content = $this->laccounts->get_date_wise_closing_reports( $from_date,$to_date );
       
		$this->template->full_admin_html_view($content);
	}
	// Random Id generator
 	public function generator($lenth)
	{
		$number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9","0");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,61);
			$rand_number=$number["$rand_value"];
		
			if(empty($con))
			{ 
			$con=$rand_number;
			}
			else
			{
			$con="$con"."$rand_number";}
		}
		return $con;
	}
}
