(function($){
	var form_value ={
		'value_home_price': 0,
		'value_down_payment': 0,
		'value_down_payment_percent': 0
	}
	$('#property_ultimate .mortgage-form form').submit(function(e){
		e.preventDefault();
			var data ={			
				'dataForm': $(this).serialize()
			} 
			
			$.post(root+'student/ajax_property_ultimate_func', data, function(response){
				$('.results').html(response);		        
				$('body, html').animate({scrollTop:$('.results').offset().top}, '500');

		    });

		})
	$('#loan_accessibility .mortgage-form form').submit(function(e){
		e.preventDefault();
			var data ={				
				'dataForm': $(this).serialize()
			} 
			
			$.post(root+'student/ajax_loan_accessibility_func', data, function(response){
				response = $.parseJSON(response);
				if(response.gross_income>0){
					$('#affordability_btn').removeClass('hidden-input');
				}
				$('#loan_accessibility .results').html(response.html);
				$('#loan_accessibility .action').removeClass('hidden');
		        $('#loan_accessibility .mortgage-form form input.currency').attr('disabled','disabled');
		        $('#loan_accessibility input#calculate_edit').removeClass('hidden-input');
		        $('#loan_accessibility input#calculate_debt').addClass('hidden-input');
				$('body, html').animate({scrollTop:$('#loan_accessibility .results').offset().top}, '500');

				/* ====  Man begin code more this part === */
				$.post(root+'student/get_student_note',{
			        id : $('input[name=student_loan_id]').val()
		        },function(data){
		           	console.log(data);
		        	if(data != ''){
		        		$('textarea[id=student_note]').val(data);
		        	}
		        });
		        /* ====  end Man === */
		    });

		})
	$('#loan_accessibility input#calculate_edit').click(function(e){
		e.preventDefault();
		$('#loan_accessibility .results').html('');
		$('#affordability_btn').addClass('hidden-input');
		$('#loan_accessibility .action').addClass('hidden');
		$('#loan_accessibility .mortgage-form form input.currency').removeAttr('disabled');
		$('#loan_accessibility input#calculate_edit').addClass('hidden-input');
		$('#loan_accessibility input#calculate_debt').removeClass('hidden-input');
	})
	$('#show_popup_remark').click(function(e){
		e.preventDefault();
		$('.bg-body .box-content').addClass('show_popup');
		$('.popup').removeClass('hide');
	})
	$('.close').click(function(e){
		$('.bg-body .box-content').removeClass('show_popup');
		$('.popup').addClass('hide');
		$('textarea#remark').val('');
	})
	$('#affordability .mortgage-form form').submit(function(e){
		e.preventDefault();
			var data ={				
				'dataForm': $(this).serialize()
			} 
			
			$.post(root+'student/ajax_affordability_calculate_func', data, function(response){
				$('#affordability .results').html(response);		        
				$('body, html').animate({scrollTop:$('.results').offset().top}, '500');
				$('#affordability .action').removeClass('hidden');
				$('#affordability .mortgage-form form input.input-field').attr('disabled','disabled');
		        $('#affordability input#calculate_edit').removeClass('hidden-input');
		        $('#affordability input#calculate_debt').addClass('hidden-input');		       
		    });

		})
	$('#affordability input#calculate_edit').click(function(e){
		e.preventDefault();
		$('#affordability .results').html('');		
		$('#affordability .action').addClass('hidden');
		$('#affordability .mortgage-form form input.input-field').removeAttr('disabled');
		$('#affordability input#calculate_edit').addClass('hidden-input');
		$('#affordability input#calculate_debt').removeClass('hidden-input');
	})
	$('input.currency').autoNumeric('init',{"mDec":0}); 
	$('input.percent').autoNumeric('init',{"mDec":2});
	$('input.number_years, input.number_months').autoNumeric('init',{"mDec":0});

	function get_price_value(){
		
		var value_home_price = $('input[name="home_price"]').autoNumeric('get');
		var value_down_payment = $('input[name="down_payment_dola"]').autoNumeric('get');
		var value_down_payment_percent = $('input[name="down_payment_percent"]').autoNumeric('get');
		
		form_value ={
			'value_home_price': parseFloat(value_home_price),
			'value_down_payment': parseFloat(value_down_payment),
			'value_down_payment_percent': parseFloat(value_down_payment_percent)
		}
		console.log(form_value);
	}

	$('input[name="home_price"]').on('keyup change',function(){		
		percent_to_dola();
	})

	$('input[name="down_payment_dola"]').on('keyup change', function(){
		console.log($(this).val());
		check_value_down_payment($(this));
	})

	$('input[name="down_payment_percent"]').on('keyup change', function(){
		percent_to_dola();
	})
	
	$('input[name="number_years"]').on('keyup change', function(){	
		$('input[name="number_months"]').val($(this).val()*12);		
	})

	$('input[name="number_months"]').on('keyup change', function(){
		$('input[name="number_years"]').val(parseFloat($(this).val())/12);
	})
	
	function check_value_down_payment(element){

		get_price_value();
		if(form_value.value_home_price <form_value.value_down_payment){
			$('.error p').text('Down payment have to less than Home Price');
			$(element).val('');
			$('input[name="down_payment_percent"]').val('');

		}else{
			
			dola_to_percent();
			$('.error p').text('');
		}
	}

	function dola_to_percent(){
		get_price_value();

		var convert_to_month = (form_value.value_down_payment * 100)/ form_value.value_home_price;
		if(isNaN(convert_to_month)){
			$('input[name="down_payment_percent"]').val('');
		}else{
			$('input[name="down_payment_percent"]').autoNumeric('set', convert_to_month);
		}

	}
	function percent_to_dola(){

		get_price_value();
		var convert_to_dola = (form_value.value_home_price * form_value.value_down_payment_percent/100);
		if(isNaN(convert_to_dola) || convert_to_dola ==0){
			$('input[name="down_payment_dola"]').val('');
		}else{			
			$('input[name="down_payment_dola"]').autoNumeric('set', convert_to_dola);
		}

	}

	$('.scroll-top').click(function(){
		$('body, html').animate({scrollTop:0}, '500');
	})
	$(window).scroll(function(){
		
		if($(this).scrollTop()>$(window).height()){
			$('.scroll-top').fadeIn();
		}else{
			$('.scroll-top').fadeOut();
		}
	})

	$('ul.tab li').click(function(){
		$('ul.tab li').removeClass('active');
		$(this).addClass('active');
		var data_item = $(this).data('item');
		$('.tab-content').removeAttr('style');
		$('#'+data_item).show();
		$('.results').html('');
	})
	
	/*SUBMIT LOAN*/
	$('#save_loan_accessibility').click(function(){
		var status = $('#status').val();
		
		if(status !== undefined){
			var new_status = status; //Lay status cũ
		}else{
			var new_status = 1;
		}
		
		$.post(root+'student/submit_loan_accessibility',{
			student_loan_id : $('input[name=student_loan_id]').val(),
            monthly_fixed_income : $('input[name=monthly_fixed_income]').val(),
            monthly_rental_income : $('input[name=monthly_rental_income]').val(),
            credit_cards : $('input[name=credit_cards]').val(),
            monthly_variable_income : $('input[name=monthly_variable_income]').val(),
            car_loans : $('input[name=car_loans]').val(),
            pledged_deposits : $('input[name=pledged_deposits]').val(),
            existing_home_loans : $('input[name=existing_home_loans]').val(),
            unpledged_deposits : $('input[name=unpledged_deposits]').val(),
            other_loans : $('input[name=other_loans]').val(),
            gross_income: $('td[class=gross_income]').html(),
            tdsr_limit: $('td[class=tdsr_limit]').html(),
            debt_obligations: $('td[class=debt_obligations]').html(),
            current_tdsr: $('td[class=current_tdsr]').html(),
            servicing: $('td[class=servicing]').html(),
            student_note : $('textarea[id=student_note]').val(),
            status: new_status,
            monthly_installment : $('input[name=monthly_installment]').val(),
            interest_rate : $('input[name=interest_rate]').val(),
            loan_duration : $('input[name=loan_duration]').val(),
            maximum_loan : $('td[class=maximum_loan]').html(),
            purchase_price_75 : $('td[class=purchase_price_75]').html(),
            purchase_price_80 : $('td[class=purchase_price_80]').html(),
            purchase_price_90 : $('td[class=purchase_price_90]').html()
        },function(data){
        	console.log(data);
        	if(data != 'fail'){
        		 window.location.href = root+'student/summary';
        	}
           	
        });
	})

	$('#submitaip_loan_accessibility').click(function(){

		$.post(root+'student/submit_loan_accessibility',{
            monthly_fixed_income : $('input[name=monthly_fixed_income]').val(),
            monthly_rental_income : $('input[name=monthly_rental_income]').val(),
            credit_cards : $('input[name=credit_cards]').val(),
            monthly_variable_income : $('input[name=monthly_variable_income]').val(),
            car_loans : $('input[name=car_loans]').val(),
            pledged_deposits : $('input[name=pledged_deposits]').val(),
            existing_home_loans : $('input[name=existing_home_loans]').val(),
            unpledged_deposits : $('input[name=unpledged_deposits]').val(),
            other_loans : $('input[name=other_loans]').val(),
            tdsr_limit: $('td[class=tdsr_limit]').html(),
            gross_income: $('td[class=gross_income]').html(),
            debt_obligations: $('td[class=debt_obligations]').html(),
            current_tdsr: $('td[class=current_tdsr]').html(),
            servicing: $('td[class=servicing]').html(),
            status: 2,
            student_loan_id: $('#student_loan_id').val(),
            student_note : $('textarea[id=student_note]').val(),
            monthly_installment : $('input[name=monthly_installment]').val(),
            interest_rate : $('input[name=interest_rate]').val(),
            loan_duration : $('input[name=loan_duration]').val(),
            maximum_loan : $('td[class=maximum_loan]').html(),
            purchase_price_75 : $('td[class=purchase_price_75]').html(),
            purchase_price_80 : $('td[class=purchase_price_80]').html(),
            purchase_price_90 : $('td[class=purchase_price_90]').html()
        },function(data){          	
           	if(data != 'fail'){

        		var remark =  $('#remark').val().trim();
			
				if(remark.length == 0){
					window.location.href = root+'student/summary';
					return false;
				}else{
					$.post(root+'student/save_loan_remark',{
			            remark : remark,
			            id : data
			        },function(data){
			           	console.log(data);
			        	if(data == 'success'){
			        		window.location.href = root+'student/summary';
			        	}
			        });
				}
        	}
        });
	})
	/*END SUBMIT LOAN*/

	/*EDIT LOAN*/
	$('#update_loan_save').click(function(){
		var status = $('#status').val();
		
		$.post(root+'student/save_loan_accessibility',{
			student_loan_id : $('input[name=student_loan_id]').val(),
            monthly_fixed_income : $('input[name=monthly_fixed_income]').val(),
            monthly_rental_income : $('input[name=monthly_rental_income]').val(),
            credit_cards : $('input[name=credit_cards]').val(),
            monthly_variable_income : $('input[name=monthly_variable_income]').val(),
            car_loans : $('input[name=car_loans]').val(),
            pledged_deposits : $('input[name=pledged_deposits]').val(),
            existing_home_loans : $('input[name=existing_home_loans]').val(),
            unpledged_deposits : $('input[name=unpledged_deposits]').val(),
            other_loans : $('input[name=other_loans]').val(),
            gross_income: $('td[class=gross_income]').html(),
            tdsr_limit: $('td[class=tdsr_limit]').html(),
            debt_obligations: $('td[class=debt_obligations]').html(),
            current_tdsr: $('td[class=current_tdsr]').html(),
            servicing: $('td[class=servicing]').html(),
            student_note : $('textarea[id=student_note]').val(),
            status: status
        },function(data){
        	if(data != 'fail'){
        		 window.location.href = root+'student/summary';
        	}
           	
        });
	})

	$('#upgrade_loan_accessibility').click(function(){

		$.post(root+'student/upgrade_loan_accessibility',{
            monthly_fixed_income : $('input[name=monthly_fixed_income]').val(),
            monthly_rental_income : $('input[name=monthly_rental_income]').val(),
            credit_cards : $('input[name=credit_cards]').val(),
            monthly_variable_income : $('input[name=monthly_variable_income]').val(),
            car_loans : $('input[name=car_loans]').val(),
            pledged_deposits : $('input[name=pledged_deposits]').val(),
            existing_home_loans : $('input[name=existing_home_loans]').val(),
            unpledged_deposits : $('input[name=unpledged_deposits]').val(),
            other_loans : $('input[name=other_loans]').val(),
            tdsr_limit: $('td[class=tdsr_limit]').html(),
            gross_income: $('td[class=gross_income]').html(),
            debt_obligations: $('td[class=debt_obligations]').html(),
            current_tdsr: $('td[class=current_tdsr]').html(),
            servicing: $('td[class=servicing]').html(),
            status: 2,
            student_loan_id: $('#student_loan_id').val(),
            student_note : $('textarea[id=student_note]').val()
        },function(data){          	
           	if(data != 'fail'){

        		var remark =  $('#remark').val().trim();
			
				if(remark.length == 0){
					window.location.href = root+'student/summary';
					return false;
				}else{
					$.post(root+'student/save_loan_remark',{
			            remark : remark,
			            id : data
			        },function(data){
			           	console.log(data);
			        	if(data == 'success'){
			        		window.location.href = root+'student/summary';
			        	}
			        });
				}
        	}
        });
	})

	$('#update_affordability').click(function(){

		$.post(root+'student/update_affordability',{
			loan_id : 	$('input[name=loan_id]').val(),
            monthly_installment : $('input[name=monthly_installment]').val(),
            interest_rate : $('input[name=interest_rate]').val(),
            loan_duration : $('input[name=loan_duration]').val(),
            maximum_loan : $('td[class=maximum_loan]').html(),
            purchase_price_75 : $('td[class=purchase_price_75]').html(),
            purchase_price_80 : $('td[class=purchase_price_80]').html(),
            purchase_price_90 : $('td[class=purchase_price_90]').html()
        },function(data){          	
           	if(data != 'fail'){
           		window.location.href = root+'student/summary';
        	}
        });
	})

	
	$('#update_loan_submited').click(function(){

		$.post(root+'student/save_loan_accessibility',{
            monthly_fixed_income : $('input[name=monthly_fixed_income]').val(),
            monthly_rental_income : $('input[name=monthly_rental_income]').val(),
            credit_cards : $('input[name=credit_cards]').val(),
            monthly_variable_income : $('input[name=monthly_variable_income]').val(),
            car_loans : $('input[name=car_loans]').val(),
            pledged_deposits : $('input[name=pledged_deposits]').val(),
            existing_home_loans : $('input[name=existing_home_loans]').val(),
            unpledged_deposits : $('input[name=unpledged_deposits]').val(),
            other_loans : $('input[name=other_loans]').val(),
            tdsr_limit: $('td[class=tdsr_limit]').html(),
            gross_income: $('td[class=gross_income]').html(),
            debt_obligations: $('td[class=debt_obligations]').html(),
            current_tdsr: $('td[class=current_tdsr]').html(),
            servicing: $('td[class=servicing]').html(),
            status: $('#status').val(),
            student_loan_id: $('#student_loan_id').val(),
            student_note : $('textarea[id=student_note]').val()
        },function(data){          	
           	if(data != 'fail'){
        		var remark =  $('#remark_update').val().trim();

				if(remark.length == 0){
					window.location.href = root+'student/summary';
					return false;
				}else{
					$.post(root+'student/save_loan_remark',{
			            remark 	: remark,
			            id 		: $('#student_loan_id').val()
			        },function(data){

			        	if(data == 'success'){
			        		window.location.href = root+'student/summary';
			        	}
			        });
				}
        	}
        });
	})
	/*END EDIT LOAN*/
	$('#submit_for_aip').click(function(){
		$.post(root+'student/update_loan_status',{
            id : $('#new_student_loan_id').val()
        },function(data){
        
        });
		
		var remark =  $('#remark').val();
		
		if(remark.length < 6){
			window.location.href = root+'student/summary';
			return false;
		}else{
			$.post(root+'student/save_loan_remark',{
	            remark : remark,
	            id : $('#new_student_loan_id').val()
	        },function(data){
	           	console.log(data);
	        	if(data == 'success'){
	        		window.location.href = root+'student/summary';
	        	}
	        });
		}
	})

	$('#edit_aip').click(function(){
		var id = $('#new_student_loan_id').val();
		window.location.href = root+'student/edit_loan_accessibility/'+id;
	})
/** =========================Ngadt: 21-07-2019==========
	 click button affordability show affordability form 
	===================================================**/
	$('#affordability_btn').click(function(e){
		e.preventDefault();				
		$('div#loan_accessibility').addClass('hidden');
		$('div#affordability').removeClass('hidden');
	})
	 

})(jQuery)