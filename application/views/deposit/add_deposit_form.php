<h2>New Deposit</h2>
<div class="form-container">
    <form class="form-vertical" action="<?=base_url()?>cdeposit/insert_deposit" id="insert_deposit" method="post"  name="insert_deposit" enctype="multypart/formdata">
        <legend>Deposit detail</legend>
		<?php $date = date('Y-m-d'); ?>
        <div class="row-fluid">
            <div class="span3">
                <div class="control-group">
                    <label class="control-label" for="invoice_date">Date:</label>
                    <div class="controls">
                        <input type="text" class="span10" value="<?php echo $date; ?>" id="deposit_date" name="deposit_date" required />
                    </div>
                </div>
            </div>
            <div class="span8">
            	<div class="control-group">
                    <label class="control-label">Description:</label>
                    <div class="controls">
                        <textarea class="span6 input-description" tabindex="1" id="description" name="description" placeholder="Optional detail about this deposit" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
			<table class="table table-condensed table-striped">
				<thead>
					<tr>
						<th colspan="2" class="span2 text-right">Amount</th>
						<th colspan="6">&nbsp;</th>
					</tr>
				</thead>
				<tbody id="form-actions">
					<tr class="">
						<td colspan="3" class="span2">
							<input type="number" class="span2 text-right" name="amount" placeholder="Amount" required />
						</td>
						<td colspan="3">
						&nbsp;
						</td>
					</tr>
				</tbody>
			</table>
        </div>
        <div class="form-actions">
            <input type="submit" id="add-deposit" class="btn btn-primary btn-large" name="add-deposit" value="Save" />
            <input type="submit" value="Save and add another one" name="add-deposit-another" class="btn btn-large" id="add-deposit-another">
        </div>
    </form>
</div>
