<script src="<?php echo base_url(); ?>my-assets/js/admin_js/json/customer.js.php" ></script>
<h2>Receipt list</h3>
<div class="row-fluid">
	<div>
		<form class="well form-inline" method="post" action="<?=base_url()?>creceipt/search_receipt_item">
			<label class="select">Search By Customer Name : </label>
				<input type="text" name="customer_name" class="span3 customerSelection" placeholder='Type Customer Name' id="customer_name" >
				<input type="hidden" class="customer_hidden_value" name="customer_id" id="SchoolHiddenId"/>
			<button type="submit" class="btn">Search</button>
		</form>
	</div>
</div>
<?php
if(!empty($receipt_list)){
?>
<table class="table table-striped table-condensed table-condensed table-bordered">
	<thead>
		<tr>
			<th>#</th>
			<th>Receipt No</th>
			<th>Customer Name</th>
			<th>Date</th>
			<th>Amout</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	{receipt_list}
		<tr>
			<td>{sl}</td>
			<td>{receipt_no}</td>
			<td>
				<a href="<?php echo base_url().'ccustomer/customerledger/{customer_id}'; ?>">{customer_name}</a>				
			</td>
			<td>{final_date}</td>
			<td>{amount}</td>
			<td>
				<center>
					<a href="<?php echo base_url().'creceipt/receipt_update_form/{receipt_no}'; ?>"><i title="Edit" class="icon-edit"></i></a>
				</center>
			</td>
		</tr>
	{/receipt_list}
	</tbody>
</table>
<div id="pagin"><center><?php if(isset($links)){echo $links;} ?></center></div>
<?php
}else{
?>
<div class="NoDataFound"><center>No Data Found</center></div>
<?php
}
?>