<!-- Product js php -->
<script src="<?php echo base_url()?>my-assets/js/admin_js/json/product.js.php" ></script>

<!-- Stock report start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
	document.body.style.marginTop="0px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<!-- Stock List Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('stock_report') ?></h1>
	        <small><?php echo display('all_stock_report') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('stock') ?></a></li>
	            <li class="active"><?php echo display('stock_report') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">
		<!-- Manage Product report -->
		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
						<?php echo form_open('Creport',array('class' => 'form-inline', ));?>
							<?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); ?>
							<label class="select"><?php echo display('search_by_product') ?>:</label>
							<input type="text" name="product_name" onclick="producstList();" class="form-control productSelection" placeholder='<?php echo display('product_name') ?>' id="product_name" >
							<input type="hidden" class="autocomplete_hidden_value" name="product_id" id="SchoolHiddenId"/>
		                    <label class="select"><?php echo display('date') ?></label>
							<input type="text" name="stock_date" value="<?php echo $today; ?>" data-date-format="yyyy-mm-dd" class="form-control productSelection datepicker"/>
							<button type="submit" class="btn btn-primary"><?php echo display('search') ?></button>
		                	<a  class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
			            <?php echo form_close()?>
		            </div>
		        </div>
		    </div>
	    </div>

		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('stock_report') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
						<div id="printableArea" style="margin-left:2px;">

								{company_info}
								<h3> {company_name} </h3>
								<h5 >{address} </h5>
								{/company_info}
								<h5> <?php echo display('stock_date') ?> : {date} </h5>
								<span> <?php echo display('print_date') ?>: <?php echo date("d/m/Y h:i:s"); ?> </span>

			                <div class="table-responsive" style="margin-top: 10px;">
			                    <table id="" class="table table-bordered table-striped table-hover">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('sl') ?></th>
											<th class="text-center"><?php echo display('product_name') ?></th>
											<th class="text-center"><?php echo display('product_model') ?></th>
											<th class="text-center"><?php echo display('purchase') ?></th>
											<th class="text-center"><?php echo display('sales') ?></th>
											<th class="text-center"><?php echo display('stock') ?></th>
											<th class="text-center"><?php echo display('price') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										if ($stok_report) {
									?>
									{stok_report}
										<tr>
											<td>{sl}</td>
											<td>
												<a href="<?php echo base_url().'Cproduct/product_details/{product_id}'; ?>">
												{product_name}
												</a>	
											</td>
											<td>{product_model}</td>
											<td>{totalPrhcsCtn}</td>
											<td>{totalSalesCtn}</td>
											<td>{stok_quantity_cartoon}</td>
											<td style="text-align: right;"><?php echo (($position==0)?"$currency {price}":"{price} $currency") ?></td>
										</tr>
									{/stok_report}
									<?php
										}
									?>
									</tbody>
			                    </table>
			                </div>
			            </div>
		                <div class="text-center">
		                	 <?php if (isset($link)) { echo $link ;} ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Stock List End -->