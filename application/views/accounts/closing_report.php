<!-- Closing Report Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('closing_report') ?></h1>
	        <small><?php echo display('account_closing_report') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('accounts') ?></a></li>
	            <li class="active"><?php echo display('closing_report') ?></li>
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
	                	<?php echo form_open('Caccounts/date_wise_closing_reports',array('class' => 'form-inline', ))?>
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

		<!-- Closing report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('closing_report') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
			           			<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('last_day_ammount') ?></th>
										<th><?php echo display('cash_in') ?></th>
										<th><?php echo display('cash_out') ?></th>
										<th><?php echo display('cash_in_hand') ?></th>
										<th><?php echo display('adjustment') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($daily_closing_data) {
								?>
								{daily_closing_data}
									<tr>
										<td>{sl}</td>
										<td>{final_date}</td>
										<td><?php echo (($position==0)?"$currency {last_day_closing}":"{last_day_closing} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {cash_in}":"{cash_in} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {cash_out}":"{cash_out} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {cash_in_hand}":"{cash_in_hand} $currency") ?></td>
										<td><?php echo (($position==0)?"$currency {adjustment}":"{adjustment} $currency") ?></td>
									</tr>
								{/daily_closing_data}
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
<!-- Closing Report End -->