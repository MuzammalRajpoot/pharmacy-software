<!-- div print by js -->
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

<!-- Product statement start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('product_statement') ?></h1>
	        <small><?php echo display('product_statement') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('product') ?></a></li>
	            <li class="active"><?php echo display('product_statement') ?></li>
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

		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
		               	<?php echo form_open('Cproduct/product_sales_supplier_rate/{product_id}',array('class' => 'form-inline', ))?>
	               			<?php $today = date('Y-m-d'); $monthearlier= date('Y-m-d',strtotime('-30 days'));?>
	               			<label class="text"><?php echo display('product_model') ?> </label>
							<input type="text" name="product_name" onclick="producstList();" class="form-control productSelection" placeholder='<?php echo display('product_model') ?>' value="{product_model}" id="product_name" >
							<input type="hidden" class="autocomplete_hidden_value" name="product_id"  value="{product_id}" id="SchoolHiddenId"/>
							<label class="select"><?php echo display('date') ?> </label>
								<input type="text" name="from_date"  value="{startdate}" data-date-format="yyyy-mm-dd" class="datepicker form-control"/>
							<label class="select"> <?php echo display('to') ?> </label>
								<input type="text" name="to_date" value="{enddate}"  data-date-format="yyyy-mm-dd" class="datepicker form-control" required/>
							<button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
							<a  class="btn btn-info" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
						<?php echo form_close()?>           
					</div>
		        </div>
		    </div>
	    </div>
		<!--Total sales report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('sales_report') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body" id="printableArea">
		                <div class="table-responsive">
		                    <table id="dataTableExample3" class="table table-bordered table-striped table-hover">
						    <thead>
								<tr>
									<th><?php echo display('date') ?></th>
									<th><?php echo display('in') ?></th>
									<th><?php echo display('out') ?></th>
									<th><?php echo display('stock') ?></th>
									<th><?php echo display('rate') ?></th>
									<th><?php echo display('total_purchase') ?></th>
									<th><?php echo display('total_sales') ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="3"><b><?php echo display('previous_stock') ?></b></td>
									<td>{opening_balance}</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							<?php
								if ($salesData) {
							?>
							{salesData}
								<tr>
									<td>{fdate}</td>
									<td>{in}</td>
									<td>{out}</td>
									<td>{stock}</td>
									<td> <?php echo (($position==0)?"$currency {rate}":"{rate} $currency") ?></td>
									<td> <?php echo (($position==0)?"$currency {total_purchase}":"{total_purchase} $currency") ?></td>
									<td> <?php echo (($position==0)?"$currency {total_sell}":"{total_sell} $currency") ?></td>
								</tr>
							{/salesData}
							<?php
								}
							?>
								<tr>
									<td><b>Grand Total</b></td>
									<td><b>{totalIn}</td>
									<td><b>{totalOut}</b></td>
									<td><b>{totalstock}</b></td>
									<td></td>
									<td><b> <?php echo (($position==0)?"$currency {gtotal_purchase}":"{gtotal_purchase} $currency") ?></b></td>
									<td><b> <?php echo (($position==0)?"$currency {gtotal_sell}":"{gtotal_sell} $currency") ?></b></td>
								</tr>
							</tbody>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Product statement end -->