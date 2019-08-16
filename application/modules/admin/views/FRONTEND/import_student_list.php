<link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/libs/bootstrap-4.0.0/css/bootstrap.min.css')?>">
<style type="text/css" media="screen">
</style>

<div class="container">
	<form method="post" id="import_form" enctype="multipart/form-data">
		<p style="text-align: center;"><label>Select Excel File</label>
		<input type="file" name="file" id="file" required accept=".xls, .xlsx" /></p>
		<input type="submit" name="import" value="Import" class="btn btn-info" />
	</form>
	<br />
	<div class="table-responsive" id="customer_data">

	</div>
</div>

<script>
$(document).ready(function(){

	load_data();

	function load_data()
	{
		$.ajax({
			url:"<?php echo base_url(); ?>admin/fetch",
			method:"POST",
			success:function(data){
				$('#customer_data').html(data);
			}
		})
	}

	$('#import_form').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"<?php echo base_url(); ?>admin/import",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			success:function(data){
				$('#file').val('');
				load_data();
				alert(data);
			}
		})
	});

});
</script>