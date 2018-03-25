<!-- Edit deposite start -->
<h2>Edit Deposit</h2>
<div class="form-container">
    <form class="form-vertical" action="<?=base_url()?>Cdeposit/deposit_update" id="deposit_update" method="post"  name="deposit_update" enctype="multypart/formdata">
        <legend>Deposit detail</legend>
		<?php $date = date('Y-m-d'); ?>
        <div class="row-fluid">
            <div class="span3">
                <div class="control-group">
                    <label class="control-label" for="invoice_date">Date:</label>
                    <div class="controls">
                        <input type="text" class="span10" value="{date}" id="deposit_date" name="deposit_date" required />
                    </div>
                </div>
            </div>
            <div class="span8">
            	<div class="control-group">
                    <label class="control-label">Description:</label>
                    <div class="controls">
                        <textarea class="span6 input-description" tabindex="1" id="description" name="description" placeholder="Optional detail about this deposit" required>{description}</textarea>
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
							<input type="number" class="span2 text-right" name="amount" value="{amount}" placeholder="Amount" required />
						</td>
						<td colspan="3">
						&nbsp;<input type="hidden" class="span10" value="{deposit_id}" id="deposit_id" name="deposit_id" required />
						</td>
					</tr>
				</tbody>
			</table>
        </div>
        <div class="form-actions">
            <input type="submit" id="add-deposit" class="btn btn-primary btn-large" name="add-deposit" value="Save Changes" />
        </div>
    </form>
</div>
<!-- Edit deposite end -->