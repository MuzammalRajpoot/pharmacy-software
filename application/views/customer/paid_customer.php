<!-- Paid Customer Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('paid_customer') ?></h1>
	        <small><?php echo display('paid_customer_list') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('customer') ?></a></li>
	            <li class="active"><?php echo display('paid_customer') ?></li>
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
	    
		<!-- Paid Customer -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('paid_customer') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
		                    	<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('customer_name') ?></th>
										<th><?php echo display('address') ?></th>
										<th><?php echo display('mobile') ?></th>
										<th style="text-align:right !Important"><?php echo display('ammount') ?></th>
										<th style="text-align:center !Important"><?php echo display('action') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php if($customers_list){
								?>
									{customers_list}
										<tr>
											<td>{sl}</td>
											<td>
												<a href="<?php echo base_url().'Ccustomer/customerledger/{customer_id}'; ?>">{customer_name}</a>				
											</td>
											<td>{customer_address}</td>
											<td>{customer_mobile}</td>
											<td style="text-align:right !Important"> <?php echo (($position==0)?"$currency {customer_balance}":"{customer_balance} $currency");?></td>
											<td>
												<center>
													<?php echo form_open()?>
														<a href="<?php echo base_url().'Ccustomer/customer_update_form/{customer_id}'; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo display('update') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>

														<a href="" class="deleteCustomer btn btn-danger btn-sm" name="{customer_id}" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?> "><i class="fa fa-trash-o" aria-hidden="true"></i></a>

													<?php echo form_close()?>
												</center>
											</td>
										</tr>
									{/customers_list}
								<?php
								}?>
								</tbody>
								<tfoot>
									<tr>
										<td style="text-align:right !Important" colspan="4"> <?php echo display('grand_total') ?></td>
										<td style="text-align:right !Important">
											<?php if ($subtotal) { echo (($position==0)?"$currency {subtotal}":"{subtotal} $currency");} ?>
										</td>
										<td></td>
									</tr>
								</tfoot>
							</table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Paid Customer End -->

<!-- Delete Customer ajax code -->
<script type="text/javascript">
	//Delete Customer 
	$(".deleteCustomer").click(function()
	{	
		var customer_id=$(this).attr('name');
		var csrf_test_name=  $("[name=csrf_test_name]").val();
		var x = confirm("Are You Sure,Want to Delete ?");
		if (x==true){
		$.ajax
	   ({
			type: "POST",
			url: '<?php echo base_url('Ccustomer/customer_delete')?>',
			data: {customer_id:customer_id,csrf_test_name:csrf_test_name},
			cache: false,
			success: function(datas)
			{
			} 
		});
		}
	});
</script>



