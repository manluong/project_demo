<div id="summary" class="limit-width ">
	<table>
		<thead>
			<tr>
				<td class="width-50">AIP ID</td>
				<td class="width-100">Gross Income</td>
				<td class="width-100">Debt Obligations</td>
				<td class="width-100">Available for <br/>Mortage Servicing</td>
				<td>Status</td>
				<td>Banker</td>
				<td class="width-81">Date</td>
				<td>Latest Remark</td>
				<td>Detail</td>
			</tr>
		</thead>
		<tbody>
			<?php 
				if($data){
					$i = 0;
					foreach($data as $k=>$v)
					{$i++;
			?>
			<tr>
				<td><?=$i?></td>
				<td class="right-text"><?=(!empty($v->gross_income)?$v->gross_income:'')?></td>
				<td class="right-text"><?=(!empty($v->debt_obligations)?$v->debt_obligations:'')?></td>
				<td class="right-text"><?=(!empty($v->servicing)?$v->servicing:'')?></td>
				<td class="status">
				<?php 
					if(!empty($v->status)){
						switch ($v->status) {
	                        case 1:
	                            echo  'SAVE';
	                            break;
	                        case 2:
	                            echo 'SUBMITED';
	                            break;
	                        case 3:
	                            echo 'PENDING';
	                            break;
	                        case 4:
	                            echo 'VERIFIED';
	                            break;
	                    }
					}
				?>
				</td>
				<td><?=(!empty($v->banker_id)?$this->model->getStudentFullName($v->banker_id):'')?></td>
				<td>
					<?php 
						if(strtotime($v->submit_date)>0){
							echo date('d-M-y  g:i A',strtotime($v->submit_date));
						}else{
							echo date('d-M-y  g:i A',strtotime($v->save_date));
						}
					?>
				</td>
				<td class="remark"><?=(!empty($v->id)?CutText($this->banker_model->getLatestRemark($v->id),90):'')?></td>
				<td><a href="<?=PATH_URL.'student/loan_accessibility_detail/'.$v->id?>">View</a></td>
			</tr>
			<?php }}?>
		</tbody>
	</table>
</div>
