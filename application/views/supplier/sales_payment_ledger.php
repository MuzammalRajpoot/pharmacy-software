<!-- Supplier Payment Ledger Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('supplier_actual_ledger') ?></h1>
	        <small><?php echo display('supplier_actual_ledger') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('supplier') ?></a></li>
	            <li class="active"><?php echo display('supplier_actual_ledger') ?></li>
	        </ol>
	    </div>
	</section>

	<!-- Supplier information -->
	<section class="content">
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
							<?php
							foreach ($company_info as $company) {
							?>
								<h4><?php echo $company['company_name'] ?></h4>
							<?php
								}
							?>
					        <h6><?php echo display('supplier_ledger') ?></h6>
							<h6><?php echo display('supplier') ?> : {info}{supplier_name}</h6>
					        <span>{address}{/info}</span>
						</div>
						<div style="float:right;margin-right:100px">
						<?php
							if ($total_details) {
						?>
							{total_details}
								<table class="table table-striped table-condensed table-bordered">
									<tr><td> <?php echo display('debit_ammount') ?> </td> <td style="text-align:right !Important;margin-right:20px"><?php echo (($position==0)?"$currency {debit}":"{debit} $currency") ?> </td> </tr>
									<tr><td><?php echo display('credit_ammount') ?></td> <td style="text-align:right !Important;margin-right:20px"> <?php echo (($position==0)?"$currency {credit}":"{credit} $currency") ?> </td> </tr>
									<tr><td><?php echo display('balance_ammount') ?> </td> <td style="text-align:right !Important;margin-right:20px"> <?php echo (($position==0)?"$currency {balance}":"{balance} $currency") ?> </td> </tr>
								</table>
							{/total_details}
						<?php
							}
						?>
						</div>
		            </div>
		        </div>
		    </div>
		</div>

		<!-- Supplier Actual Ledger -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('supplier_actual_ledger') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('event') ?></th>
										<th><?php echo display('description') ?></th>
										<th style="text-align:right !Important;margin-right:20px"><?php echo display('debit') ?></th>
										<th style="text-align:right !Important;margin-right:20px"><?php echo display('credit') ?></th>
										<th style="text-align:right !Important;margin-right:20px"><?php echo display('balance') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($ledger) {
								?>
								{ledger}
									<tr>
										<td>{DATE}</td>
										<td>{event}</td>
										<td>{deposit_no}</td>
										<td style="text-align:right !Important;margin-right:20px"> <?php echo (($position==0)?"$currency {debit}":"{debit} $currency") ?></td>
										<td style="text-align:right !Important;margin-right:20px"> <?php echo (($position==0)?"$currency {credit}":"{credit} $currency") ?></td>
										<td style="text-align:right !Important;margin-right:20px"> <?php echo (($position==0)?"$currency {balance}":"{balance} $currency") ?></td>
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
<!-- Supplier Payment Ledger End  -->