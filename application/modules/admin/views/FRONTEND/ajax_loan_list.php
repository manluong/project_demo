
	<table>
		<thead>
			<tr>
				<td class="width-50">AIP ID</td>
				<td class="width-81">Date</td>
				<td>Student name</td>
				<td class="width-100">Gross Income</td>
				<td class="width-100">Debt Obligations</td>
				<td class="width-100">Available for Mortage Servicing</td>
				<td>Status</td>
				<td>Banker</td>
				<td>PIM</td>
				<td>Action</td>
			</tr>
		</thead>
		<tbody>
			<?php 
				if($result){
					$i = 0;
					foreach($result as $k=>$v)
					{$i++;
			?>
			<tr>
				<td class="center-text"><?=$k+1+$start?></td>
				<td>
				<?php 
					if(strtotime($v->update_date) > 0){
						echo date('d-m-Y',strtotime($v->update_date));
					}else{
						if(strtotime($v->submit_date)>0){
							echo date('d-m-Y',strtotime($v->submit_date));
						}else{
							echo 'N/A';
						}
						
					}
				?>
				</td>
				<td><a href="<?=PATH_URL.'admin/loan_accessibility_list/'.$v->student_id?>"><?=(!empty($v->firstname)?$v->firstname:'')?> <?=(!empty($v->lastname)?$v->lastname:'')?></a></td>
				<td class="right-text"><?=(!empty($v->gross_income)?$v->gross_income:'')?></td>
				<td class="right-text"><?=(!empty($v->debt_obligations)?$v->debt_obligations:'')?></td>
				<td class="right-text"><?=(!empty($v->servicing)?$v->servicing:'')?></td>
				<td class="status">
				<?php 
					if(!empty($v->status)){
						if($v->status == 1){echo 'SAVED';}
						if($v->status == 2){echo 'SUBMITED';}
						if($v->status == 3){echo 'PENDING';}
						if($v->status == 4){echo 'VERIFIED';}
					}
				?>
				</td>
				<td><?=$this->student_model->getStudentFullName($v->banker_id)?></td>
				<td class="remark"><?=(!empty($v->batch_no)?$this->student_model->getPimName($v->batch_no):'')?></td>
				<td><a href="<?=PATH_URL.'admin/loan_accessibility_detail/'.$v->id?>">View</a></td>
			</tr>
			<?php }}?>
		</tbody>
	</table>
	<div class="pagination"><?=$this->adminpagination->create_links();?></div>
