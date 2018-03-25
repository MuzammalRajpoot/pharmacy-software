<!-- Supplier Sales Summary Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('supplier_sales_summary') ?></h1>
	        <small><?php echo display('supplier_sales_summary') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('supplier') ?></a></li>
	            <li class="active"><?php echo display('supplier_sales_summary') ?></li>
	        </ol>
	    </div>
	</section>

	<!-- Search Supplier -->
	<section class="content">
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('supplier_sales_summary') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
	  					<?php echo form_open('#',array('class' => 'form-inline'))?>
	  					<?php $today = date('Y-m-d'); ?>
						<label class="select"><?php echo display('search_by_date') ?>: <?php echo display('from') ?></label>
						<input type="text" name="from_date"  value="<?php echo $today; ?>" data-date-format="yyyy-mm-dd" class="datepicker form-control"/>
						<label class="select"><?php echo display('to') ?></label>
							<input type="text" name="to_date" data-date-format="yyyy-mm-dd" class="datepicker form-control"/>
						<button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
						<?php echo form_close()?>
		            </div>
		        </div>
		    </div>
		</div>

		<!-- Sales Report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('sales_report') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th style="text-align:left !Important"> <?php echo display('date') ?> </th>
										<th> Product Name </th>
										<th style="text-align:right !Important"> <?php echo display('rate') ?> </th>
										<th style="text-align:right !Important"> <?php echo display('ammount') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($sales_info) {
								?>
								{sales_info}
									<tr>
										<td style="text-align:left !Important"> {date}</td>
										<td>
											<a href="<?php echo base_url().'Cproduct/product_details/{product_id}'; ?>">
												{product_name} - {product_model}
											</a>
										</td>
										<td style="text-align:right !Important"><?php echo (($position==0)?"$currency {supplier_rate}":"{supplier_rate} $currency") ?></td>
										<td style="text-align:right !Important"><?php echo (($position==0)?"$currency {total}":"{total} $currency") ?></td>
										 
									</tr>
								{/sales_info}
								<?php
									}
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4" style="text-align:right !Important"><b>

										<?php echo display('grand_total') ?> : 
										<?php echo (($position==0)?"$currency {sub_total}":"{sub_total} $currency") ?></b></td>
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
<!-- Supplier Sales Summary End -->