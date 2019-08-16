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
									<span class="explain_more">
										<span class="icon_question">?</span>
										<span class="detail-explain ">							
												<p>⁃ Use (Total Income in NOA) less (Basic Pay X 12 months). For illustration, (NOA: $90,000) - (Basic Pay: $5,000 x 12 months) = $30,000 <br>
 												⁃ However, If Basic Pay X 12 months is higher than Total Income in NOA, then Annual Bonus & Comms = 0
												</p>
											</span>
									</span>									
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
								<label>Existing Home Loans
									<span class="explain_more">
										<span class="icon_question">?</span>
										<span class="detail-explain ">
											<p>Use monthly instalment stated in the HDB loan/bank statement. If co-share with partner/others, use NOA to apportion. For illustration,</br>										
											 ⁃ NOA of Wife = $100k</br>
											 ⁃ NOA of Husband = $50k</br>
											 ⁃ Monthly Instalment (MI) = $1,000</br>
											 ⁃ (MI) for Husband: 50/150 X $1,000 = $333.34</br>
											</p>
										</span>
									</span>
									
								</label>
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
		<button class="btn hidden-input" id="affordability_btn">AFFORDABILITY</button>
		<div class="popup-remark popup hide">
			<div class="center-screen">
				<div class="field">
					<h4>Remark</h4>
					<textarea id="remark">

					</textarea>
					<div class="center-text">
						<span href="#" id="cancel" class="btn close">CLOSE</span>
						<span href="#" id="submitaip_loan_accessibility" class="btn submit-for-aip">SEND</span>
					</div>
					
				</div>
			</div>			
		</div>			
	</div><!-- container -->
</div>
<div id="affordability" class="tab-content hidden">
	<div id="container">
		<br><h2 class="orange-title">Affordability Calculator</h2>
		<div class="mortgage-form">				
			<form method="post">				
				<div class="form-fields">
					<div class="field">
						
						<div>
							<label>Monthly Installment <span class="required"> *</span></label>
						</div>
						<div>
							<p class="symbol-currency"><input  name="monthly_installment" value="" class="currency input-field" required="required"></p>
						</div>
						
					</div><!--field -->
					<div class="field">
						
						<div>
							<label>Interest Rate(%) <span class="required"> *</span></label>
						</div>
						<div>
							<p class="symbol-percent"><input  name="interest_rate" value="" class="percent input-field" data-v-min="0" data-v-max="100" required="required"></p>
						</div>
						
					</div><!--field -->
					<div class="field">
						
						<div>
							<label>Loan Duration (In Years) <span class="required"> *</span></label>
						</div>
						<div>
							<p class="symbol symbol_years"><input data-v-min="0" data-v-max="40" name="loan_duration" value="" required="required" class="number_years input-field"></p>
						</div>
						
					</div><!--field -->
					<div class="field">	<br>		
						<input type="submit" name="calculate_debt" id="calculate_debt" value="CALCULATE">
						<input type="submit" name="calculate_edit" value="EDIT" class="hidden-input" id="calculate_edit">
					</div>
				</div>
			</form>
			
		</div>
		<div class="results"></div>
		<div class="action hidden">
			<div class="review-box flex-box">
				<span href="#" id="save_loan_accessibility" class="btn">SAVE</span>			
				<span href="#" id="show_popup_remark" class="btn">CONFIRM AIP SUBMISSION</span>			
			</div>		
		</div> <!-- action -->
	</div><!-- container -->
</div>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-autoNumeric.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-form-calculate.js')?>"></script>
