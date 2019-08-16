<script type="text/javascript">
  	
  	function signin(){
  		event.preventDefault()
  		var countrycode = $("#countryCode").val();
        var phone = $("#mobilenumber").val();
        var numberphone = '';
        if(phone != ''){
        	numberphone	= countrycode+phone;
        }

        var email = $("#email").val();
        var pass  = $("#pass").val();

        if(pass == ''){
        	alert('Password must be not empty');
        	return false;
        }
        $.post(root+'home/check_sign_in',{
            phone : numberphone,
            email : $("#email").val(), 
            pass  : $("#pass").val()
        },function(data){
            if(data != 'fail'){
                if(data==1){window.location.href = root+'student';}
                if(data==2){window.location.href = root+'banker';}
                if(data==3){window.location.href = root+'admin';}
            }else{
            	if(phone != ''){
            		alert('Your Mobile Number or Password is not correct. Please try again or click Forgot Password to reset your password.');
            	}else{
            		alert('Your Email or Password is not correct. Please try again or click Forgot Password to reset your password.');
            	}
                
            }
        });
  	}

    $(document).ready(function(){
    	$('#pass').keypress(function (e) {
		  	if (e.which == 13) {
		   	 	signin();
		  	}
		});

        $("#sign-in").click(function(e){
        	signin();
        });

        $('.radio-group input').click(function(){
			var click_by = $(this).val();
			
			if(click_by =='login-by-email'){
				$('p.mobile-number-row').addClass('hidden');
				$('input#mobilenumber').val('');
				$('.email-row').removeClass('hidden');
			}else{
				$('p.mobile-number-row').removeClass('hidden');
				$('input#email').val('');
				$('.email-row').addClass('hidden');
			}
		})

    });
</script>
<div class="moomo-style-form">
<div class="full-width">
	<div class="wrap-content">
		<div class="logo"><img src="<?=get_resource_url('assets/images/tiq/logo-min.png')?>"></div>
		<div class="form">
			<div class="content-form">

				<form autocomplete="off" method="POST" class="sign-in">
					<h2 class="title-form">SIGN IN</h2>
					<div class="fields">
						<p>
							<label class="margin-bottom">Mobile Number or Email *</label>
							<span class="radio-group">
								<label>
									<input id="login-by-mobile-number" type="radio" name="sign-in" value="mobile-number" >  Mobile Number
								</label>
								<label>
									<input checked="checked" id="login-by-email" type="radio" name="sign-in" value="login-by-email">  Email
								</label>
							</span>
						</p>
						<p class="mobile-number-row hidden">					
							<select class="country-code" name="countryCode" id="countryCode">	
								<option data-countryCode="SG" value="65" selected="selected">Singapore (+65)</option>
								<option data-countryCode="MY" value="60">Malaysia (+60)</option>
							</select>
							<input type="number" id="mobilenumber" name="phone-number" placeholder="Mobile Number">		
						</p>
						<p class="email-row ">					
							<input type="text" id="email" name="email" placeholder="Email Address" class="required">									
						</p>
						<p>
							<label>Password *</label>
							<input type="password" name="phone-number" id="pass">
						</p>
						<p class="forgot_pass">
							<a href="<?=PATH_URL.'home/forgot_pass'?>">Forgot Password?</a>
							<span>
								<label>
									<input type="checkbox" checked name="remember-me" class="required"><text>Remember me</text>
								</label>
							</span>
						</p>
						<p>
							<button id="sign-in" class="submit">Sign in </button>
						</p>
						<p class="center-text">
							<a href="<?=PATH_URL.'home/sign_up'?>">Don't have an account? Register now</a>
						</p>
					</div>
				</form>
				<div id="alert_box" class="alert alert-success alert-dismissible">
	                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	                <span></span>
	            </div>
			</div>
			
		</div>
	</div>
</div>
</div>