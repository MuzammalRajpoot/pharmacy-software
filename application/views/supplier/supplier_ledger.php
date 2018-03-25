<!-- Supplier Ledger Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('supplier_ledger') ?></h1>
	        <small><?php echo display('manage_supplier_ledger') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('supplier') ?></a></li>
	            <li class="active"><?php echo display('supplier_ledger') ?></li>
	        </ol>
	    </div>
	</section>

	<!-- Supplier information -->
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
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('supplier_information') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
	  					<div style="float:left">
							<h4>{supplier_name}</h4>
						</div>
						<div style="float:right;margin-right:100px">
							<table class="table table-striped table-condensed table-bordered">
								<tr><td> Debit Amount </td> <td > <?php if ($total_debit) { echo (($position==0)?"$currency {total_debit}":"{total_debit} $currency");} ?></td> </tr>
								<tr><td>Credit Amount</td> <td style="text-align:right !Important;margin-right:20px"> <?php if ($total_credit) { echo (($position==0)?"$currency {total_credit}":"{total_credit} $currency");} ?></td> </tr>
								<tr><td>Balance Amount </td> <td style="text-align:right !Important;margin-right:20px"> <?php if ($total_balance) { echo (($position==0)?"$currency {total_balance}":"{total_balance} $currency");} ?></td> </tr>
							</table>
						</div>
		            </div>
		        </div>
		    </div>
		</div>

		<!-- Manage Supplier -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('supplier_ledger') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th class="text-center"><?php echo display('date') ?></th>
										<th class="text-center"><?php echo display('invoice_id') ?></th>
										<th class="text-center"><?php echo display('deposite_id') ?></th>
										<th class="text-center"><?php echo display('description') ?></th>
										<th class="text-center"><?php echo display('debit') ?></th>
										<th class="text-center"><?php echo display('credit') ?></th>
										<th class="text-center"><?php echo display('balance') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($ledger) {
								?>
								{ledger}
									<tr>
										<td>{final_date}</td>
										<td>
											<a href="<?php echo base_url().'Cpurchase/purchase_details_data/{transaction_id}';?>">
												{chalan_no}
											</a>
										</td>
										<td>{deposit_no}</td>
										<td>{description}</td>
										<td><?php echo (($position==0)?"$currency {debit}":"{debit} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {credit}":"{credit} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {balance}":"{balance} $currency") ?></td>
									</tr>
								{/ledger}
								<?php
									}
								?>
								</tbody>
		                    </table>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Supplier Ledger End  -->