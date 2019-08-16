<div id="loan_accessibility_list" class="limit-width"> 
	<div id="container">
		<br><h2 class="orange-title">Loan Accessibility </h2>	
		<div class="header-info">
			<div class="user">
				<?php 
					$img = 'user-icon.png';
					if(!empty($user_info->image)){
						$img = $user_info->image;
					}
				?>
				<img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-avatar">
				<info>
					<name><?=(!empty($user_info->firstname)?$user_info->firstname:'')?> <?=(!empty($user_info->lastname)?$user_info->lastname:'')?></name>
					<span class="member_date">Member since <?=(!empty($user_info->created)?date('d M Y',strtotime($user_info->created)):'')?></span>
				</info>
			</div>
			<div class="filter hidden">
				<form class="filter-form">
					<label> Filter by:</label>
					<select>
						<option>option 1</option>
						<option>option 2</option>
						<option>option 3</option>
					</select>
				</form>
			</div>

		</div>
		<div id="summary">
			<table>
				<thead>
					<tr>
						<td class="width-50">AIP ID</td>
						<td class="width-81">Date</td>
						<td class="width-100">Gross Income</td>
						<td class="width-100">Debt Obligations</td>
						<td class="width-100">Available for Mortage Servicing</td>
						<td>Status</td>
						<td>Banker</td>
						<td>Latest Remark</td>
						<td>Action</td>
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
						<td class="center-text"><?=$i?></td>
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
						<td class="remark"><?=(!empty($v->id)?$this->model->getLatestRemark($v->id):'')?></td>
						<td><a href="<?=PATH_URL.'admin/loan_accessibility_detail/'.$v->id?>">View</a></td>
					</tr>
					<?php }}?>
				</tbody>
			</table>
		</div>
	</div>
</div>