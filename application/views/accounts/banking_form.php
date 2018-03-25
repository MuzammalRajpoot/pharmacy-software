<!-- Bamking form start -->
<div class="form-container">
    <form class="form-vertical" action="<?php echo base_url('cclosing/add_banking_entry')?>" id="insert_deposit" method="post"  name="insert_deposit" enctype="multypart/formdata">
		<div class="lblFieldContnr">
			<div class="lblContnr">Bank</div>
			<div class="fieldContnr">
				<select name="bank_id" id="bank_id" required >
					<option selected="selected">--Select Bank--</option>
					{bank_list}
						<option value="{bank_id}">{bank_name}</option>
					{/bank_list}
				</select>
			</div>
		</div>
		<div class="lblFieldContnr">
			<div class="lblContnr">Deposit</div>
			<div class="fieldContnr">
				<select name="deposit_name" id="deposit_name" required >
					<option selected="selected">--Select Type--</option>
					<option value="cheque">Cheque</option>
					<option value="cash">Cash</option>
					<option value="pay_order">Pay Order</option>
				</select>
			</div>
		</div>
		<div class="lblFieldContnr">
			<div class="lblContnr">  Transaction Type</div>
			<div class="fieldContnr">
				<select name="transaction_type" id="transaction_type" required >
					<option selected="selected">--Select Type--</option>
					<option value="deposit">Deposit</option>
					<option value="draw">Draw</option>
				</select>
			</div>
		</div>
		<div class="lblFieldContnr">
			<div class="lblContnr">Description</div>
			<div class="fieldContnr">
				<textarea name="description"></textarea>
			</div>
		</div>
		<div class="lblFieldContnr">
			<div class="lblContnr">Amount</div>
			<div class="fieldContnr">
				<input type="number" id="amount" name="amount" required />
			</div>
		</div>
		<div class="lblFieldContnr">
			<div class="lblContnr"></div>
			<div class="fieldContnr">
				<input type="submit" id="add-deposit" class="btn btn-primary" name="add-deposit" value="Save" required />
			</div>
		</div>
    </form>
</div>
<!-- Bamking form end -->