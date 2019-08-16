<div id="affordability" class="tab-content affordability_edit">
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
							<p class="symbol-currency"><input  name="monthly_installment" value="<?=(!empty($data->monthly_installment)?str_replace(",","",$data->monthly_installment):'')?>" class="currency" required="required"></p>
						</div>
						
					</div><!--field -->
					<div class="field">
						
						<div>
							<label>Interest Rate(%) <span class="required"> *</span></label>
						</div>
						<div>
							<p class="symbol-percent"><input  name="interest_rate" value="<?=(!empty($data->interest_rate)?str_replace(",","",$data->interest_rate):'')?>" data-v-min="0" data-v-max="100" required="required"></p>
						</div>
						
					</div><!--field -->
					<div class="field">
						
						<div>
							<label>Loan Duration (In Years) <span class="required"> *</span></label>
						</div>
						<div>
							<p class="symbol symbol_years"><input data-v-min="0" data-v-max="40" name="loan_duration" value="<?=(!empty($data->loan_duration)?str_replace(",","",$data->loan_duration):'')?>" required="required" class="number_years"></p>
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
			<input type="hidden" name="loan_id" id="loan_id" value="<?=(!empty($data->id)?$data->id:'')?>">
			<button href="#" id="update_affordability" class="btn">SAVE</button>		
		</div>	
	</div><!-- container -->
</div>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-autoNumeric.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-form-calculate.js')?>"></script>