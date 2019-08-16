<div id="notification">	
	<div class="list-news">
		<div class="item-news">			
			<ul class="notifi">
				<?php 
					if(!empty($data)){
						foreach ($data as $k => $v) {
				?>
				<li>
					<span class="date-time-notifi <?=($v->status==2?'un-read':'')?>"><?=(!empty($v->created)?date( 'd-M-y  g:i A',strtotime($v->created)):'')?></span>
					<span class="desc-notifi <?=($v->status==2?'un-read':'')?>">
						<?php 
							if($v->type == 'submit'){
								echo '<a href="'.PATH_URL.'banker/loan_accessibility_detail/'.$v->loan_id.'">You have new AIP submission from '.$this->student_model->getStudentFullName($v->student_id).'</a>'; 
							}else{
								echo '<a href="'.PATH_URL.'banker/loan_accessibility_detail/'.$v->loan_id.'">'.$this->student_model->getStudentFullName($v->student_id).' AIP submission on '.$this->student_model->getLoanDate($v->loan_id).' has new update</a>';
							}
						?>
					</span> 					
					<span onclick="delete_not(<?=$v->id?>)" class="clear-message"></span>
					<span onclick="update_not(<?=$v->id?>)" class="<?=($v->status==1?'mark-read':'')?>"></span>
				</li>
				<?php }}?>
			</ul>
		
		</div>		
			
	</div>
</div>
<script type="text/javascript">
	function delete_not(id){
		var result = confirm('Are you sure want to delete this notification');
		if(result){
			$.post(root+'banker/delete_not',{
	            id : id
	        },function(data){
	      		location.reload();
	        });
		}
	}

	function update_not(id){
		$.post(root+'banker/update_not',{
            id : id
        },function(data){
      		location.reload();
        });
	}

</script>