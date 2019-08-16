<div id="loan_accessibility">
	<div id="container">
	<br><h2 class="orange-title">Total Debt Servicing Ratio</h2>	
	<div class="mortgage-form">	
		<form method="post">		
			<div class="form-fields">
				<div class="col-md-6">
					<h3>GROSS INCOME</h3>
					<div class="field">
						<input  type="hidden" name="student_loan_id" value="<?=(!empty($data->id)?$data->id:'')?>">
						<div class="">
							<label>Monthly Basic Salary</label>
						</div>
						<div class="">
							<p class="symbol-currency"><input  name="monthly_fixed_income" value="<?=(!empty($data->monthly_fixed_income)?str_replace(",","",$data->monthly_fixed_income):'')?>" class="currency"></p>
						</div>
						
					</div><!--field -->
					<div class="field">						
						<div class="">
							<label>Monthly Rental Income</label>
						</div>
						<div class="">
							<p class="symbol-currency"><input name="monthly_rental_income" value="<?=(!empty($data->monthly_rental_income)?str_replace(",","",$data->monthly_rental_income):'')?>" class="currency"></p>
						</div>
						
					</div><!--field -->
					<div class="field">								
						<div class="">								
							<label>Annual Bonus or Commissions
							</label>			
						</div>
						<div class="">
							<p class="symbol-currency"><input name="monthly_variable_income"  value="<?=(!empty($data->monthly_variable_income)?str_replace(",","",$data->monthly_variable_income):'')?>" class="currency"></p>
						</div>
						<div class="error"><p class="required"></p></div>
					</div><!--field -->
					<div class="field">
						<div >
							<label>Pledged Deposits</label>
						</div>
						<div >
							<p class="symbol-currency"><input name="pledged_deposits" value="<?=(!empty($data->pledged_deposits)?str_replace(",","",$data->pledged_deposits):'')?>" class="currency"></p>
						</div>
					</div><!--field -->
					<div class="field">
						<div >
							<label>Unpledged Deposits</label>
						</div>
						<div >
							<p class="symbol-currency"><input name="unpledged_deposits" value="<?=(!empty($data->unpledged_deposits)?str_replace(",","",$data->unpledged_deposits):'')?>"  class="currency"></p>
						</div>
					</div><!--field -->
				</div>
				<div class="col-md-6">
					<h3>DEBT OBLIGATITONS</h3>
					<div class="field">								
						<div >
							<label>Credit Cards <span>(Sum of minimum payment)</span></label>
						</div>
						<div >
							<p class="symbol-currency"><input name="credit_cards" value="<?=(!empty($data->credit_cards)?str_replace(",","",$data->credit_cards):'')?>" class="currency"></p>
						</div>								
					</div><!--field -->
					<div class="field">								
						<div >								
							<label>Car Loans</label>			
						</div>
						<div >
							<p class="symbol-currency"><input name="car_loans" value="<?=(!empty($data->car_loans)?str_replace(",","",$data->car_loans):'')?>" class="currency"></p>
						</div>
						<div class="error"><p class="required"></p></div>
					</div><!--field -->
					<div class="field">
						<div >
							<label>Existing Home Loans</label>
						</div>
						<div >
							<p class="symbol-currency"><input  name="existing_home_loans" value="<?=(!empty($data->existing_home_loans)?str_replace(",","",$data->existing_home_loans):'')?>" class="currency"></p>
						</div>
					</div><!--field -->
					<div class="field">
						<div >
							<label>Other Loans</label>
						</div>
						<div >
							<p class="symbol-currency"><input  name="other_loans" value="<?=(!empty($data->other_loans)?str_replace(",","",$data->other_loans):'')?>"  class="currency"></p>
						</div>
					</div><!--field -->
					
				</div>
				<div class="field">	<br/>	
					<input type="submit" name="calculate_debt" value="CALCULATE" id="calculate_debt">
					<input type="submit" name="calculate_edit" value="EDIT" class="hidden-input" id="calculate_edit">
				</div>
			</div>
		</form>
	</div>
	<div class="results"></div>
	<div class="popup-remark popup hide">
		<div class="center-screen">
			<div class="field">
				<h4>Remark</h4>
				<textarea id="remark">

				</textarea>
				<div class="center-text">
					<span href="#" id="cancel" class="btn close">CLOSE</span>
					<span href="#" id="upgrade_loan_accessibility" class="btn submit-for-aip">SEND</span>
				</div>
				
			</div>
		</div>		
	</div><!-- popup -->		
	<div class="action hidden">
		<div class="review-box">
			<!-- save status 1 -->
			<?php if($data->status == 1){?>
				<div class="field">
					<div class="student_note submit-box">						
						<h4>Note</h4>
						<textarea id="student_note"></textarea>	
					</div>								
				</div>
				<span href="#" id="update_loan_save" class="btn">UPDATE</span>			
				<span href="#" id="show_popup_remark" class="btn">CONFIRM AIP SUBMISSION</span>	
			<?php }?>
			<!-- submit status 2 -->
			<?php if($data->status == 2 || $data->status == 3){?>	
				<div class="field">
					<div class="student_note submit-box">						
						<h4>Note</h4>
						<textarea id="student_note"></textarea>	
					</div>	
					<div class="submit-box">
						<h4>Remark</h4>
						<textarea id="remark_update"></textarea>		
					</div>									
				</div>
				<span href="#" id="update_loan_submited" class="btn">UPDATE</span>				
			<?php }?>
		</div>		
		<input type="hidden" name="student_loan_id" id="student_loan_id" value="<?=(!empty($data->id)?$data->id:'')?>">
		<input type="hidden" id="student_id" value="<?=(!empty($data->student_id)?$data->student_id:'')?>">
		<input type="hidden" id="status" value="<?=(!empty($data->status)?$data->status:'')?>">
	</div><!-- container -->
	</div>
</div>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-autoNumeric.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-form-calculate.js')?>"></script>
