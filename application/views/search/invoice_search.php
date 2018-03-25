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
	        <h1><?php echo display('invoice_search') ?></h1>
	        <small><?php echo display('invoice_search') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('search') ?></a></li>
	            <li class="active"><?php echo display('invoice_search') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">
		<!-- Manage Product report -->
		<div class="row">
			<div class="col-sm-12">
		        <div class="panel panel-default">
		            <div class="panel-body"> 
						<?php echo form_open('Csearch/invoice_search',array('class' => 'form-inline', ));?>
							<?php date_default_timezone_set("Asia/Dhaka"); $today = date('Y-m-d'); ?>
							<label class="select"><?php echo display('search') ?>:</label>
							<input type="text" name="what_you_search" class="form-control" placeholder='<?php echo display('what_you_search') ?>' id="what_you_search" >
							<button type="submit" class="btn btn-primary"><?php echo display('search') ?></button>
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
		                    <h4><?php echo display('invoice_search') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
						<div id="printableArea" style="margin-left:2px;">
			                <div class="table-responsive" style="margin-top: 10px;" >
			                    <table class="table table-bordered table-striped table-hover medicine_search">
			                        <thead>
										<tr>
											<th class="text-center"><?php echo display('date') ?></th>
											<th class="text-center"><?php echo display('invoice_no') ?></th>
											<th class="text-center"><?php echo display('customer') ?></th>
											<th class="text-center"><?php echo display('quantity') ?></th>
											<th class="text-center"><?php echo display('price') ?></th>
											<th class="text-center"><?php echo display('due') ?></th>
											<th class="text-center"><?php echo display('details') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										if ($search_result != null) {
									?>
										{search_result}
										<tr>
											<td>{date}</td>
											<td>{invoice}</td>
											<td><a href="<?php echo base_url('Ccustomer/customerledger/{customer_id}')?>" target = "_blank">{customer_id}</a></td>
											<td>{quantity}</td>
											<td align="right"><?php echo (($position==0)?"$currency {total_price}":"{total_price} $currency") ?></td>
											<td align="right"><?php echo (($position==0)?"$currency {due_amount}":"{due_amount} $currency") ?></td>
											<td align="center">
												<a href="<?php echo base_url('Cinvoice/invoice_inserted_data/{invoice_id}');?>" target="_blank"><button class="btn btn-success"><?php echo display('details')?></button>
												</a>
											</td>
										</tr>
										{/search_result}
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
		</div>
	</section>
</div>
<!-- Stock List End -->



<!-- <script type="text/javascript">
	
    //OnKeyUp search
    $('body').on('keyup','#what_you_search',function() {

        var keyword = $('#what_you_search').val();

        $.ajax({
            url: '<?php echo base_url('Csearch/medicine_search')?>',
            data: {keyword:keyword},
            type: 'post',
            // beforeSend:function(){
            //     $(".mid-content").html('<img class="img img-responsive" src="'+baseUrl+'/assets/web_site/images/loading.gif">');
            // },
            success: function(data){
            	alert(data);
            	if (data == 1) {
            		$('.medicine_search').html('Product Not Found !');
            	}else{
            		$(".medicine_search tbody").html(data);
            		//$('.medicine_search tbody').append(data);
            	}
            },error:function(exc){
                alert('failed');
            }
        });
    });
</script> -->