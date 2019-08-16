<div id="loan_accessibility_list" class="limit-width"> 
	<div id="container">
		<br><h2 class="orange-title">Loan Accessibility </h2>	
		<div class="header-info">
			<div class="filter">
				<form  action="" class="filter-form" method="GET">
					<p>
						<label> Status</label>
						<select name="status">
							<option value=""></option>
							<option value="2">SUBMITED</option>
							<option value="3">PENDING</option>
							<option value="4">VERIFIED</option>
						</select>
					</p>
					<p>
						<label> PIM</label>
						<?php 
							$pim = $this->student_model->getPIM();
						?>
						<select name="pim">
							<option value="" ></option>
	                        <?php if(!empty($pim)){
	                                foreach ($pim as $key => $value) {
	                        ?>
	                        <option value="<?=$value->id?>" ><?=$value->pim?></option>
	                        <?php }}?>
						</select>
					</p>
					<p>
						<label> Date from</label>
						<input type="date" name="dateFrom" value="" placeholder="">
					</p>
					<p>
						<label> Date to</label>
						<input type="date" name="dateTo" value="" placeholder="">
					</p>
					<p>
						<label> Student</label>
						<input type="text" name="student_name" value="" placeholder="">
					</p>
					<p><button id="fillter_loan">FILLTER</button></p>
				</form>
			</div>
		</div>
		<div id="summary">
		</div>
	</div>
</div>
<input type="hidden" value="<?php ($this->session->userdata('start'))? print $this->session->userdata('start') : print 0 ?>" id="start" />
<link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/pagination.css')?>">
<script type="text/javascript">
$(document).ready(function(){
	searchLoan(0,10);

	$("#fillter_loan").click(function(e){
		e.preventDefault();
        searchLoan(0,10);
    });
	
});

function searchLoan(start,per_page){
	if(per_page==undefined){
		if($('#per_page').val()){
			per_page = $('#per_page').val();
		}else{
			per_page = 10;
		}
	}
	$('#start').val(start);
	console.log(start);
	$.post(root+'admin/ajax_loan_list',{
        status : $('select[name=status]').val(),
        pim : $('select[name=pim]').val(),
        start: start,
		per_page: per_page,
		dateFrom: $('input[name=dateFrom]').val(),
		dateTo: $('input[name=dateTo]').val(),
		student_name: $('input[name=student_name]').val()
    },function(data){
        $('#summary').html(data);
    });
}
</script>