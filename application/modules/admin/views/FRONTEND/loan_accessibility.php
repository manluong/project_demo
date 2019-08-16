<div id="loan_accessibility" class="limit-width">
	<div id="container">
	<br><h2 class="orange-title">Loan Accessibility </h2>
	<!-- <div class="search-form">
				<form method="post">
					<input type="text" name="" id="key-word" placeholder="Search...">
					<input type="hidden" name="search" value="Search">
				</form>
			</div> -->		
	<div id="summary">
		<table>
			<thead>
				<tr>						
					<td class="width-70">No.</td>
					<td>Student</td>
					<td>Email</td>
					<td>Mobile Number</td>
					<td class="center-text width-100">PIM Batch</td>
					<td>Detail</td>
				</tr>
			</thead>
			<?php 
					if($data){
						$i = 0;
						foreach($data as $k=>$v)
						{$i++;
				?>
				<tr>
					<td><?=$i?></td>
					<td><?=(!empty($v->firstname)?$v->firstname:'')?> <?=(!empty($v->lastname)?$v->lastname:'')?></td>
					<td><?=(!empty($v->email)?$v->email:'')?></td>
					<td><?=(!empty($v->phone)?$v->phone:'')?></td>	
					<td class="center-text"><?=(!empty($v->batch_no)?$v->batch_no:'')?></td>			
					<td><a href="<?=PATH_URL.'admin/loan_accessibility_list/'.$v->student_id?>">View</a></td>
				</tr>
				<?php }}?>
		</table>
		<div class="pagination"><?php echo $links; ?></div>
	</div>
	</div><!-- container -->
	
</div>
<link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/pagination.css')?>">