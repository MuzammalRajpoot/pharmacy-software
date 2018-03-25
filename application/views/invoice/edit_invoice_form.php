<!-- Customer js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/customer.js.php" ></script>
<!-- Product invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product_invoice.js.php" ></script>
<!-- Invoice js -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<!-- Edit Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('invoice_edit') ?></h1>
            <small><?php echo display('invoice_edit') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('invoice') ?></a></li>
                <li class="active"><?php echo display('invoice_edit') ?></li>
            </ol>
        </div>
    </section>

    <section class="content">
        <!-- Invoice report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('invoice_edit') ?></h4>
                        </div>
                    </div>
                    <?php echo form_open('Cinvoice/invoice_update',array('class' => 'form-vertical','id'=>'insert_invoice' ))?>
                    <div class="panel-body">
             
                        <div class="row">
                        	<div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="product_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                       <input type="text" name="customer_name" value="{customer_name}" class="form-control customerSelection" placeholder='<?php echo display('customer_name') ?>' required id="customer_name" >
										<input type="hidden" class="customer_hidden_value" name="customer_id" value="{customer_id}" id="SchoolHiddenId"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        	<div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="product_name" class="col-sm-4 col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input type="text" tabindex="3" class="form-control" name="invoice_date" value="{date}"  required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" style="margin-top: 10px">
                            <table class="table table-bordered table-hover">
         						<thead>
									<tr>
										<th class="text-center"><?php echo display('item_information') ?> <i class="text-danger">*</i></th>
										<th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
										<th class="text-center"><?php echo display('rate') ?> <i class="text-danger">*</i></th>
										<th class="text-center"><?php echo display('discount') ?> <i class="text-danger">*</i></th>
										<th class="text-center"><?php echo display('total') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody id="addinvoiceItem">
								{invoice_all_data}
									<tr>
										<td class="span3">
											<input type="text" name="product_name" onclick="invoice_productList({sl});" value="{product_name}-{product_model}" class="form-control productSelection" required placeholder='<?php echo display('product_name') ?>' id="product_names" >
											<input type="hidden" class="product_id_{sl} autocomplete_hidden_value" name="product_id[]" value="{product_id}" id="SchoolHiddenId"/>
										</td>
										<td class="text-right">
											<input type="number" name="product_quantity[]" onkeyup="quantity_calculate({sl});" value="{quantity}" id="total_qntt_{sl}" class="form-control text-right" min="1" />
										</td>
										<td>
											<input type="number" name="product_rate[]" value="{rate}" id="price_item_{sl}" class="price_item{sl} form-control text-right" readonly="readonly" />
										</td>
										<td>
											<input type="number" name="discount[]" onkeyup="quantity_calculate({sl}); stockLimit({sl});" id="discount_{sl}" class="form-control text-right" placeholder="Discount" value="{discount}"/>
										</td>
										<td>
											<input class="total_price form-control text-right" type="text" name="total_price[]" id="total_price_{sl}" value="{total_price}" readonly="readonly" />
											<input type="hidden" name="invoice_details_id[]" id="invoice_details_id" value="{invoice_details_id}"/>
										</td>
                                        <td>
                                            <!-- Tax calculate start-->
                                            <input type="hidden" id="total_tax_1" class="total_tax_1" value="{tax}" />
                                            <input type="hidden" id="all_tax_1" class=" total_tax"/>
                                            <!-- Tax calculate end -->
                                            <button style="text-align: right;" class="btn btn-danger" type="button" value="<?php echo display('delete')?>" onclick="deleteRow(this)"><?php echo display('delete')?></button>
                                        </td>
									</tr>
								{/invoice_all_data}
								</tbody>
								<tfoot>
									<tr>
                                        <td style="text-align:right;" colspan="4"><b><?php echo display('total_tax') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_tax_ammount" tabindex="-1" class="form-control text-right" name="total_tax" tabindex="-1" value="{total_tax}" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"  style="text-align:right;"><b><?php echo display('grand_total') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" tabindex="-1" class="form-control text-right" name="grand_total_price" value="{total_amount}" readonly="readonly" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url();?>"/>
                                            <input type="hidden" name="invoice_id" id="invoice_id" value="{invoice_id}"/>

                                        </td>
                                        <td style="text-align:right;" colspan="3"><b><?php echo display('paid_ammount') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="paidAmount" 
                                            onkeyup="invoice_paidamount();"  tabindex="-1" class="form-control text-right" name="paid_amount" value="{paid_amount}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <input type="submit" id="add-invoice" class="btn btn-success btn-large" name="add-invoice" value="<?php echo display('save_changes') ?>" />
                                        </td>
                                      
                                        <td style="text-align:right;" colspan="3"><b><?php echo display('due') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="dueAmmount" class="form-control text-right" name="due_amount" value="{due_amount}" readonly="readonly"/>
                                        </td>
                                    </tr>
								</tfoot>
                            </table>
                        </div>
                    </div>
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Edit Invoice End -->



