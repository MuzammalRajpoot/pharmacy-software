<!-- Cheaque Manager Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('cheque_manager') ?></h1>
	        <small><?php echo display('cheque_manager') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('accounts') ?></a></li>
	            <li class="active"><?php echo display('cheque_manager') ?></li>
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

		<!-- Inflow report -->
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('inflow_report') ?> </h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
			           			<thead>
									<tr>
										<th><?php echo display('sl') ?></th>
										<th><?php echo display('date') ?></th>
										<th><?php echo display('cheque_no') ?></th>
										<th><?php echo display('name') ?></th>
										<th><?php echo display('bank_name') ?></th>
										<th><?php echo display('ammount') ?></th>
										<th><?php echo display('action') ?> </th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($cheque_manager) {
								?>
								{cheque_manager}
									<tr>
										<td>{sl}</td>
										<td>{date}</td>
										<td>{cheque_no}</td>
						                <td>{name}</td>
										<td>{bank_name}</td>
										<td><?php echo (($position==0)?"$currency {amount}":"{amount} $currency") ?></td>
						                <td>
						                    <center>
					                            <a href="<?php echo base_url().'Caccounts/cheque_manager_edit/{transection_id}/{action}'; ?>">{action_value}</a>&nbsp; | &nbsp;
					                            <a href="<?php echo base_url().'Caccounts/inout_edit/{transection_id}/del'; ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?php echo display('delete') ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						                    </center>
						                </td>
									</tr>
								{/cheque_manager} 
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
<!-- Cheaque Manager End -->