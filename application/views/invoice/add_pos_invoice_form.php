<!-- Customer js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/customer.js.php" ></script>
<!-- Product invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php" ></script>
<!-- Invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>

<!-- Add new invoice start -->
<style>
	#bank_info_hide
	{
		display:none;
	}
    #payment_from_2
    {
        display:none;
    }
</style>

<!-- Customer type change by javascript start -->
<script type="text/javascript">
	function bank_info_show(payment_type)
	{
	    if(payment_type.value=="1"){
	        document.getElementById("bank_info_hide").style.display="none";
	    }
	    else{ 
	        document.getElementById("bank_info_hide").style.display="block";  
	    }
	}
        
    function active_customer(status)
    {
	    if(status=="payment_from_2"){
	        document.getElementById("payment_from_2").style.display="none";
	        document.getElementById("payment_from_1").style.display="block";
	        document.getElementById("myRadioButton_2").checked = false;
	        document.getElementById("myRadioButton_1").checked = true;
	    }
	    else{
	        document.getElementById("payment_from_1").style.display="none";
	        document.getElementById("payment_from_2").style.display="block";
	        document.getElementById("myRadioButton_2").checked = false;
	        document.getElementById("myRadioButton_1").checked = true;
	    }
    }
    //Payment method toggle 
    $(document).ready(function(){
        $(".payment_button").click(function(){
            $(".payment_method").toggle();
        });
    });
</script>
<!-- Customer type change by javascript end -->

<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('new_pos_invoice') ?></h1>
            <small><?php echo display('add_new_pos_invoice') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('Invoice') ?></a></li>
                <li class="active"><?php echo display('new_pos_invoice') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">

        <!-- Alert Message -->
        <?php
            $message = $this->session->userdata('message');
            if (isset($message)) {
        ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('message');
            }
            $error_message = $this->session->userdata('error_message');
            if (isset($error_message)) {
        ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error_message ?>                    
        </div>
        <?php 
            $this->session->unset_userdata('error_message');
            }
        ?>

        <!-- POS Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('new_pos_invoice') ?></h4>
                        </div>
                    </div>
                  
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="add_item" class="col-sm-4 col-form-label"><?php echo display('product_model') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="" class="form-control" placeholder='<?php echo display('product_model') ?>' id="add_item" autocomplete='off'>
                                        <input type="hidden" id="product_value" name="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php echo form_open_multipart('Cinvoice/insert_invoice',array('class' => 'form-vertical', 'id' => 'addinvoice','name' => 'insert_invoice'))?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="invoice_date" class="col-sm-4 col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                       <?php date_default_timezone_set("Asia/Dhaka"); $date = date('Y-m-d'); ?>
                                        <input class="form-control" type="text" size="50" id="invoice_date" name="invoice_date" required value="<?php echo $date; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="customer_name1" class="col-sm-3 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                       <input type="text" size="100"  name="customer_name" class="customerSelection form-control" placeholder='<?php echo display('customer_name') ?>' id="customer_name1" required=""  value="{customer_name}" />

                                        <input id="SchoolHiddenId" class="customer_hidden_value" type="hidden" name="customer_id" value="{customer_id}">
                                    </div>
                                    <div  class="col-sm-3">
                                        <input id="myRadioButton_1" type="button" onClick="active_customer('payment_from_1')" id="myRadioButton_1" class="btn btn-success checkbox_account" name="customer_confirm" checked="checked" value="<?php echo display('new_customer') ?> ">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8" id="payment_from_2">
                               <div class="form-group row">
                                    <label for="customer_name_others" class="col-sm-3 col-form-label"><?php echo display('payee_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-6">
                                       <input  autofill="off" type="text"  size="100" name="customer_name_others" placeholder='<?php echo display('payee_name') ?>' id="customer_name_others" class="form-control" />
                                    </div>

                                    <div  class="col-sm-3">
                                        <input  onClick="active_customer('payment_from_2')" type="button" id="myRadioButton_2" class="btn btn-success checkbox_account" name="customer_confirm_others" value="<?php echo display('old_customer') ?> ">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="customer_name_others_address" class="col-sm-3 col-form-label"><?php echo display('address') ?></label>
                                    <div class="col-sm-6">
                                       <input type="text"  size="100" name="customer_name_others_address" class=" form-control" placeholder='<?php echo display('address') ?>' id="customer_name_others_address" />
                                    </div>
                                </div> 
                            </div>
                        </div>
                     
                        <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover" id="addinvoice">
                                <thead>
									<tr>
										<th class="text-center"><?php echo display('item_information') ?></th>
                                        <th class="text-center"><?php echo display('available_quantity') ?></th>
										<th class="text-center"><?php echo display('quantity') ?></th>
										<th class="text-center"><?php echo display('rate') ?></th>
										<th class="text-center"><?php echo display('discount') ?></th>
										<th class="text-center"><?php echo display('total') ?></th>
										<th class="text-center"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody id="addinvoiceItem">
                                    <tr></tr>
								</tbody>
								<tfoot>
                                    <tr>
                                        <td style="text-align:right;" colspan="5"><b><?php echo display('total_tax') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_tax_ammount" tabindex="-1" class="form-control text-right" name="total_tax" tabindex="-1" value="0.00" readonly="readonly" />
                                        </td>
                                    </tr>
									<tr>
                                        <td align="center">
                                            <input type="button" id="add-invoice-item" class="btn btn-info text-center" name="add-invoice-item"  onClick="addInputField('addinvoiceItem');" value="<?php echo display('add_new_item') ?>" />
                                        </td>
										<td style="text-align:right;" colspan="4"><b><?php echo display('grand_total') ?>:</b></td>
										<td class="text-right">
											<input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url();?>"/>
											<input type="text" id="grandTotal" tabindex="-1" class="form-control text-right" name="grand_total_price" tabindex="-1" value="0.00" min="1" readonly="readonly" />
										</td>
									</tr>
									<tr>
										<td align="center">
                                            <input type="submit" id="add-invoice" class="btn btn-success" name="add-invoice" value="<?php echo display('save_and_paid') ?>" />

										</td>
										<td style="text-align:right;" colspan="4"><b><?php echo display('paid_amount') ?>:</b></td>
										<td class="text-right">
											<input type="text" id="paidAmount" 
                                            onkeyup="invoice_paidamount();"  tabindex="-1" class="form-control text-right" name="paid_amount" value="0.00" />
										</td>
									</tr>
                                    <tr>
                                        <td align="center">
                                            <input type="button" id="add-invoice" class="btn btn-primary payment_button" name="add-invoice" value="<?php echo display('payment_method') ?>" onClick="payment_method('payment')"/>
                                        </td>
                                      
                                        <td style="text-align:right;" colspan="4"><b><?php echo display('due') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="dueAmmount" class="form-control text-right" name="due_amount" value="0.00" readonly="readonly"/>
                                        </td>
                                    </tr>
                                      <tr class="payment_method" style="display: none">
                                        <td colspan="6">
                                            <div class="row">
                                                <div class="col-sm-7">
                                                    <div class="form-group row">
                                                        <label for="payment_method" class="col-sm-4 col-form-label"><?php echo display('payment_method') ?>: </label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" name="payment_method" id="payment_method">
                                                                <option>Credit Card</option>
                                                                <option>Debit Card</option>
                                                                <option>Master Card</option>
                                                                <option>Amex</option>
                                                                <option>Visa</option>
                                                                <option>Paypal</option>
                                                                <option>Others</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-7">
                                                    <div class="form-group row">
                                                        <label for="card_no" class="col-sm-4 col-form-label"><?php echo display('card_no') ?></label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="card_no" id="card_no" placeholder="<?php echo display('card_no') ?>" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-7">
                                                    <div class="form-group row">
                                                        <label for="bank_name" class="col-sm-4 col-form-label"><?php echo display('bank_name') ?>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" name="bank_name" id="bank_name">
                                                            <option>No Bank</option>
                                                                {bank_list}
                                                                <option value="{bank_name}">{bank_name}</option>
                                                                {/bank_list}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
								</tfoot>
                            </table>
                        </div>
                    <?php echo form_close()?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- POS Invoice Report End -->

<script type="text/javascript">

    //Onload filed select
    window.onload = function(){
      var text_input = document.getElementById ('add_item');
      text_input.focus ();
      text_input.select ();
    }

    //Invoice js
    $('#add_item').keydown(function(e) {
        if (e.keyCode == 13) {
            var product_model = $(this).val();
            $.ajax({
                type: "post",
                async: false,
                url: '<?php echo base_url('Cinvoice/insert_pos_invoice')?>',
                data: {product_model: product_model},
                success: function(data) {
                    if (data == false) {
                        alert('This Product Not Found !');
                        document.getElementById('add_item').value = '';
                        document.getElementById('add_item').focus();
                    }else{
                        $("#hidden_tr").css("display", "none");
                        document.getElementById('add_item').value = '';
                        document.getElementById('add_item').focus();
                        $('#addinvoice tbody').append(data);
                        calculateSum();
                    }
                },
                error: function() {
                    alert('Request Failed, Please check your code and try again!');
                }
            });
        }
    });
</script>
