<div id="loan_accessibility_detail">
	<div id="container">
		<br><h2 class="orange-title">Loan Accessibility Detail  </h2>	
	
		<div class="info-user style-2 round">		
			<div class="user">
				<?php 
					$img = 'user-icon.png';
					if(!empty($user_info->image)){
						$img = $user_info->image;
					}
				?>
				<img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-img">
				<info>
					<name><?=(!empty($user_info->firstname)?$user_info->firstname:'')?> <?=(!empty($user_info->lastname)?$user_info->lastname:'')?></name>
					<span class="member_date">Member since <?=(!empty($user_info->created)?date('d M Y',strtotime($user_info->created)):'')?></span>
				</info>
			</div>
			<div class="table-detail-user">
				<div class="row">
					<div class="col-md-6">
						<p>
							<label>Email</label>
							<value><?=(!empty($user_info->email)?$user_info->email:'')?></value>
						</p>
						<p>
							<label>Mobile</label>
							<value><?=(!empty($user_info->phone)?$user_info->phone:'')?></value>
						</p>
						<p>
							<label>Birthday</label>
							<value><?=(!empty($user_info->birthday)?date('d/m/Y',strtotime($user_info->birthday)):'')?></value>					
						</p>
						
					</div>
					<div class="col-md-6">					
						<p>
							<label>PIM Batch No</label>
							<value><?=(!empty($user_info->batch_no)?$user_info->batch_no:'')?></value>
							
						</p>					
						<p>
							<label>Address</label>
							<value><?=(!empty($user_info->street)?$user_info->street:'')?> <?=(!empty($user_info->unit)?$user_info->unit:'')?> <?=(!empty($user_info->building_name)?$user_info->building_name:'')?> <?=(!empty($user_info->postal_code)?$user_info->postal_code:'')?></value>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="loan-accessibility-calculate round">
			<div class="value-input table-detail-user">
				<div class="row">
					<div class="col-md-6">
						<h3 class="title">GROSS INCOME</h3>
						<p>
							<label>Monthly Fixed Income</label>
							<value><?=(!empty($data->monthly_fixed_income)?'$'.$data->monthly_fixed_income:'')?></value>							
						</p>
						<p>
							<label>Annual Variable Income </label>
							<value><?=(!empty($data->monthly_variable_income)?'$'.$data->monthly_variable_income:'')?></value>							
						</p>
						<p>
							<label>Pledged Deposits</label>
							<value><?=(!empty($data->pledged_deposits)?'$'.$data->pledged_deposits:'')?></value>							
						</p>
						<p>
							<label>Unpledged Deposits </label>
							<value><?=(!empty($data->unpledged_deposits)?'$'.$data->unpledged_deposits:'')?></value>							
						</p>
						
					</div>	
					<div class="col-md-6">
						<h3 class="title">DEBT OBLIGATITONS</h3>
						<p>
							<label>Credit Cards</label>
							<value><?=(!empty($data->credit_cards)?'$'.$data->credit_cards:'')?></value>							
						</p>
						<p>
							<label>Car Loans</label>
							<value><?=(!empty($data->car_loans)?'$'.$data->car_loans:'')?></value>							
						</p>
						<p>
							<label>Existing Home Loans</label>
							<value><?=(!empty($data->existing_home_loans)?'$'.$data->existing_home_loans:'')?></value>							
						</p>
						<p>
							<label>Other Loans</label>
							<value><?=(!empty($data->other_loans)?'$'.$data->other_loans:'')?></value>							
						</p>
					</div>
				</div>

			</div>
			<div class="result-form">
				<h3 class="title">RESULT</h3>
				<table>
					<thead>
						<tr>
							<td> Gross Income</td>
							<td> 60% TDSR Limit</td>
							<td> Debt Obligations</td>
							<td> Current TDSR</td>
							<td> Available for Mortage</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?=(!empty($data->gross_income)?$data->gross_income:'')?></td>
							<td><?=(!empty($data->tdsr_limit)?$data->tdsr_limit:'')?></td>
							<td><?=(!empty($data->debt_obligations)?$data->debt_obligations:'')?></td>
							<td><?=(!empty($data->current_tdsr)?$data->current_tdsr:'')?></td>
							<td id="servicing"><?=(!empty($data->servicing)?$data->servicing:'')?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="verified symbol-currency">
				<label class="agree"><input disabled <?=($data->agree==1?'checked':'')?> type="checkbox" id="banker_agree" name="banker_agree"> Agree</label>	
				<label class="verified-amount">Verified Amount</label>	
				<input type="text" disabled name="verified_amount" value="<?=(!empty($data->verified_amount)?$data->verified_amount:'')?>" >				
				<input type="hidden" name="" id="student_loan_id" value="<?=(!empty($data->id)?$data->id:'')?>" placeholder="">
				<input type="hidden" name="" id="banker_id" value="<?=(!empty($data->banker_id)?$data->banker_id:'')?>" placeholder="">				
			</div>
		</div>

		<div class="banker-discussion">
			<ul class="tab-discuss">
				<li class="discuss active">DISCUSSION</li>
				<li class="comment">COMMENTS</li>
			</ul>
			<div class="content-tab">
				<div class="discuss">
					<div class="communication">
						<ul class="list-communi">
							<?php 
								if($remark){
									foreach($remark as $k=>$v)
									{
							?>
							<li class="question">
								<info>
									<span class="box-left">
										<?php 
											$img = 'user-icon.png';
											if(!empty($v->student_id)){
												$img = $this->student_model->getStudentAvatar($v->student_id);
											}
											if(!empty($v->banker_id)){
												$img = $this->student_model->getStudentAvatar($v->banker_id);
											}
										?>
										<img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-img">
										<name>
											<span class="user-name">
											<?php 
												if(!empty($v->student_id)){
													$username = $this->student_model->getStudentFullName($v->student_id);
													echo $username;
												}
												if(!empty($v->banker_id)){
													$username = $this->student_model->getStudentFullName($v->banker_id);
													echo $username;
												}
											?>
											</span>
										</name>
									</span>
									<span class="box-right">
										<span class="date-time"><?=(!empty($v->created)?date('M d, G:i',strtotime($v->created)):'')?></span>
									</span>
								</info>
								<p><?=(!empty($v->content)?$v->content:'')?></p>
							</li>
							<?php }}?>
						</ul>

					</div><!--communication -->
				</div>
				<div class="comment hidden">					
					<div class="box-comment">					
					
						<div class="date-comment">
							<label>1st HDB installment</label>
							<input id="hdb_install" disabled type="date" value="<?=(!empty($data->hdb_installmemt)?$data->hdb_installmemt:'')?>">
						</div>
						<div class="field">
							<h4>Comment</h4>
							<textarea disabled id="banker_comment">
								<?=(!empty($data->banker_comment)?$data->banker_comment:'')?>
							</textarea>							
						</div>
					</div><!-- box-content -->		
				</div><!-- comment -->
			</div><!-- content-tab -->		
		</div><!-- banker discuss -->
	</div><!-- container-->
</div>