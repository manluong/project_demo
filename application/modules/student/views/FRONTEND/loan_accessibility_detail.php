<div id="loan_accessibility" class="loan_accessibility_detail">
	<div id="container">
	<br><h2 class="orange-title">Total Debt Servicing Ratio</h2>	
	<div class="mortgage-form">	
		<div class="loan_accessibility">
			<form>		
				<div class="form-fields">
					<div class="col-md-6">
						<h3>GROSS INCOME</h3>
						<div class="field">
							<input type="hidden" name="student_loan_id" value="">
							<label>Monthly Basic Salary</label>
							<p class="symbol-currency"><input  name="monthly_fixed_income" value="<?=(!empty($data->monthly_fixed_income)?$data->monthly_fixed_income:'')?>" disabled ="disabled"></p>
						</div><!--field -->
						<div class="field">						
							<label>Monthly Rental Income</label>
							<p class="symbol-currency"><input name="monthly_rental_income" value="<?=(!empty($data->monthly_rental_income)?$data->monthly_rental_income:'')?>" disabled ="disabled"></p>
							
						</div><!--field -->
						<div class="field">								
							<label>Annual Bonus or Commissions
								</label>	
							<p class="symbol-currency"><input name="monthly_variable_income"  value="<?=(!empty($data->monthly_variable_income)?$data->monthly_variable_income:'')?>" disabled ="disabled"></p>
							<div class="error"><p class="required"></p></div>
						</div><!--field -->
						<div class="field">
							<label>Pledged Deposits</label>
							<p class="symbol-currency"><input name="pledged_deposits" value="<?=(!empty($data->pledged_deposits)?$data->pledged_deposits:'')?>" disabled ="disabled"></p>
						</div><!--field -->
						<div class="field">
							<label>Unpledged Deposits</label>
							<p class="symbol-currency"><input name="unpledged_deposits" value="<?=(!empty($data->unpledged_deposits)?$data->unpledged_deposits:'')?>"  disabled ="disabled"></p>
						</div><!--field -->
					</div>
					<div class="col-md-6">
						<h3>DEBT OBLIGATITONS</h3>
						<div class="field">								
							<label>Credit Cards <span>(Sum of minimum payment)</span></label>
							<p class="symbol-currency"><input name="credit_cards" value="<?=(!empty($data->credit_cards)?$data->credit_cards:'')?>" disabled ="disabled"></p>								
						</div><!--field -->
						<div class="field">								
							<label>Car Loans</label>
							<p class="symbol-currency"><input name="car_loans" value="<?=(!empty($data->car_loans)?$data->car_loans:'')?>" disabled ="disabled"></p>
							
						</div><!--field -->
						<div class="field">
							<label>Existing Home Loans</label>
							<p class="symbol-currency"><input  name="existing_home_loans" value="<?=(!empty($data->existing_home_loans)?$data->existing_home_loans:'')?>" disabled ="disabled"></p>
						</div><!--field -->
						<div class="field">
							<label>Other Loans</label>
							<p class="symbol-currency"><input  name="other_loans" value="<?=(!empty($data->other_loans)?$data->other_loans:'')?>"  disabled ="disabled"></p>
						</div><!--field -->
						
					</div>				
				</div>
			</form>
			<div class="results">
				<h3>RESULTS</h3>
				<table data-table="amortization" class="">	
					<thead>
						<tr>
							<td>Gross Income</td>
							<td>60% TDSR Limit</td>
							<td>Debt Obligations</td>
							<td>Current TDSR</td>
							<td>Available for Mortgage Servicing</td>
						</tr>
					</thead>			
					<tbody>
						<tr>						
							<td class="gross_income"><span class="value"><?=(!empty($data->gross_income)?$data->gross_income:'')?></span></td>
							<td class="tdsr_limit"><span class="value"><?=(!empty($data->tdsr_limit)?$data->tdsr_limit:'')?></span></td>
							<td class="debt_obligations"><span class="value"><?=(!empty($data->debt_obligations)?$data->debt_obligations:'')?></span></td>
							<td class="current_tdsr"><span class="value"><?=(!empty($data->current_tdsr)?$data->current_tdsr:'')?></span></td>
							<td class="servicing"><span class="value"><?=(!empty($data->servicing)?$data->servicing:'')?></span></td>
						</tr>
						
					</tbody>
				</table> 
			</div>
			<?php if($data->status != 4){?>
			<div class="action">
				<div class="review-box">
					<a href="<?=PATH_URL.'student/edit_loan_accessibility/'.$data->id?>" id="save_loan_accessibility" class="btn">EDIT</a>
				</div>
			</div><!-- action -->
			<?php }?>
		</div>
		<hr>
		<div class="afforability">
			<form>
				<div class="form-fields">				
					<div class="col-md-6">
						<h3>AFFORABILITY</h3>	
						<div class="field">								
							<label>Monthly Installment  </label>
							<p class="symbol-currency"><input name="car_loans" value="<?=(!empty($data->monthly_installment)?$data->monthly_installment:'')?>" disabled ="disabled"></p>						   
						</div><!--field -->

						<div class="field">								
							<label>Interest Rate(%)  </label>
							<p class="symbol-percent"><input name="car_loans" value="<?=(!empty($data->interest_rate)?$data->interest_rate:'')?>" disabled ="disabled"></p>						
						</div><!--field -->

						<div class="field">								
							<label>Loan Duration (In Years) </label>
							<p class="symbol_years"><input name="car_loans" value="<?=(!empty($data->loan_duration)?$data->loan_duration:'')?>" disabled ="disabled"></p>

						</div><!--field -->
					</div>
				</div>
			</form>
			<div class="results">
				<h3>RESULTS</h3>
				<div class="contain-table">		
					<table data-table="amortization" class="">
						<thead>
							<tr> 
								<td>Maximum Loan:</td>
								<td>Purchase Price (75%)</td>
								<td>Purchase Price (80%)</td>
								<td>Purchase Price (90%)</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="maximum_loan"><span class="value"><?=(!empty($data->maximum_loan)?$data->maximum_loan:'')?></span>$</td>
								<td class="purchase_price_75"><span class="value"><?=(!empty($data->purchase_price_75)?$data->purchase_price_75:'')?></span>%</td>
								<td class="purchase_price_80"><span class="value"><?=(!empty($data->purchase_price_80)?$data->purchase_price_80:'')?></span>%</td>
								<td class="purchase_price_90"><span class="value"><?=(!empty($data->purchase_price_90)?$data->purchase_price_90:'')?></span>%</td>
							</tr>					
							
						</tbody>
					</table> 
				</div>
			</div><!-- results -->
			<?php if($data->status != 4){?>
			<div class="action">
				<div class="review-box">
					<a href="<?=PATH_URL.'student/affordability_edit/'.$data->id?>" id="" class="btn">EDIT</a>
				</div>
			</div><!-- action -->
			<?php }?>
		</div> <!-- afforability -->
				
	</div><!-- mortgage-form -->
	
	<div class="wrap-communication">
		<div class="title">			
			<h2>DISCUSSION</h2>
		</div>
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
									$img_tmp = $this->model->getStudentAvatar($v->student_id);
									if($img_tmp != ''){
										$img = $img_tmp;
									}
								}
								if(!empty($v->banker_id)){
									$img_tmp = $this->model->getStudentAvatar($v->banker_id);
									if($img_tmp != ''){
										$img = $img_tmp;
									}
								}
							?>
							<img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-img">
							<name>
								<span class="user-name">
	                            <?php 
									if(!empty($v->student_id)){
										$username = $this->model->getStudentFullName($v->student_id);
										echo $username;
									}
									if(!empty($v->banker_id)){
										$username = $this->model->getStudentFullName($v->banker_id);
										echo $username;
									}
								?>
	                			</span>
	                		</name>
						</span>
						<span class="box-right">
							<span class="date-time"><?=(!empty($v->created)?date('d-M-y  g:i A',strtotime($v->created)):'')?></span>
						</span>
					</info>
					<p><?=(!empty($v->content)?$v->content:'')?></p>
				</li>
				<?php }}?>
			</ul>
	</div><!--communication -->	
</div>	

	<?php if(!empty($data->student_note)){?>
	<div class="student_note">
		<div class="title">			
			<h2>NOTE</h2>
		</div>
		<p><?=(!empty($data->student_note)?$data->student_note:'')?></p>
	</div>
	<?php }?>
	
	</div><!-- container -->
</div>
<!-- <script type="text/javascript">
	(function($){
		$('#submit_remark').click(function(){
			var remark = $('#remark').val();

			if(remark.length < 6){
				return false;
			}
	        $.post(root+'student/save_loan_remark',{
	            remark : remark,
	            id : $('#student_loan_id').val(),
	            student_id : $('#student_id').val()
	        },function(data){
	            console.log(data);
	            if(data == 'success'){
	                location.reload();
	            }
	        });
	    })
	})(jQuery)

</script> -->