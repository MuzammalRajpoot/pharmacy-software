<h2>New Receipt</h2>
<script src="<?php echo base_url(); ?>my-assets/js/admin_js/json/customer.js.php" ></script>
	<script type="text/javascript">
	$(document).ready(function(){ 	
		$(".radioField").change(function(){
			var user_type = $("input:radio[name=payment_type]:checked").val();
		   // alert(user_type);
		  if(user_type == 2){
			$(".checque_type").slideDown();
		  }else if(user_type == 1){
			$(".checque_type").slideUp();
		  }
		});
	});
	</script>
<div class="form-container">
    <form class="form-vertical" action="<?=base_url()?>creceipt/insert_receipt" id="insert_receipt" method="post"  name="insert_receipt" enctype="multypart/formdata">
        <legend>Receipt detail</legend>
		<?php $date = date('Y-m-d'); ?>
        <div class="row-fluid">
            <div class="span3">
                <div class="control-group">
                    <label class="control-label" for="invoice_date">Date:</label>
                    <div class="controls">
                        <input type="text" class="span10" value="<?php echo $date; ?>" id="receipt_date" name="receipt_date" required />
                    </div>
                </div>
            </div>
            <div class="span8">
            	<div class="control-group">
                    <label class="control-label">Description:</label>
                    <div class="controls">
                        <textarea class="span6 input-description" tabindex="1" id="description" name="description" placeholder="Optional detail about this receipt" required></textarea>
                    </div>
                </div>
            </div>
        </div>
		<div class="row-fluid">
			<div class="control-group">
				<label class="control-label">Customer Name</label>
				<div class="controls">
					<input type="text" name="customer_name" class="span3 customerSelection" placeholder='Type Customer Name' id="customer_name" >
					<input type="hidden" class="customer_hidden_value" name="customer_id" id="SchoolHiddenId"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Payment Type</label>
				<div class="controls">
					<div class="radioField"><input type="radio" value="1" name="payment_type" required /> Cash </div>
					<div class="radioField"><input type="radio" value="2" name="payment_type" required /> Cheque </div>
				</div>
			</div><br/><br/>
			<div class="control-group checque_type" style="display:none;">
				<label class="control-label">Checque Number</label>
				<div class="controls">
					<input type="text" class="span3" name="cheque_no" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Amount</label>
				<div class="controls">
					<input type="text" class="span3" name="amount" placeholder="Amount" required />
				</div>
			</div>
        </div>
        <div class="form-actions">
            <input type="submit" id="add-receipt" class="btn btn-primary btn-large" name="add-receipt" value="Save" />
            <input type="submit" value="Save and add another one" name="add-receipt-another" class="btn btn-large" id="add-receipt-another">
        </div>
    </form>
</div>
