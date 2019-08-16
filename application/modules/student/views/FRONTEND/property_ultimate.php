<div id="property_ultimate">
	<div id="container">
		<br><h2 class="orange-title">Property Ultimate Caculator</h2>
		<div class="mortgage-form">
			<form method="post">				
				<div class="form-fields">
					<div class="field">
						<label>Property Price <span class="required"> *</span></label>
						<p class="symbol-currency"><input  name="home_price" value="" required="required" class="currency"></p>
					</div>
					<div class="field">
						<label>Downpayment</label>
						<div class="col-left">								
							<p class="symbol-percent"><input type="text" name="down_payment_percent" value="" class="percent" data-v-min='0' data-v-max='100'></p>			
						</div>
						<div class="col-right">
							<p class="symbol-currency"><input name="down_payment_dola" value="" class="currency"></p>
						</div>
						<div class="error"><p class="required"></p></div>
					</div>
					<div class="field">
						<label>Mortgage Term <span class="required"> *</span></label>					
						<div class="col-left">								
							<p class="symbol symbol_years"><input data-v-min='0' data-v-max='40' name="number_years" value="" required="required"  class="number_years">	</p>				
						</div>
						<div class="col-right">
							<p class="symbol symbol_month"><input data-v-min='0' data-v-max='480' name="number_months" value="" class="number_months"></p>
						</div>
					</div>
					<div class="field">
						<label>Annual Interest Rate <span class="required"> *</span></label>
						 <p class="symbol-percent"><input data-v-min='0' data-v-max='99' name="rate_years" value="" required="required" class="percent"></p>
					</div>				
					<div class="field">	<br/>		
						<input type="submit" name="calculate" value="CALCULATE">
					</div>
				</div>
			</form>
		</div>			
		<div class="results"></div>	
	</div><!-- container -->	
</div><!-- property -->
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-autoNumeric.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-form-calculate.js')?>"></script>