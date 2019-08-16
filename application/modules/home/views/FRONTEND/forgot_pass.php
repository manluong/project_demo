<script type="text/javascript">

    $(document).ready(function(){
        $("#next-step-2").click(function(){
            var countrycode = $("#countryCode").val();
            var phone = $("#mobilenumber").val();

            if(phone ==''){
                alert('Mobile Number must be not empty');
                return false;
            }

            if(phone.length < 6 | phone.length > 10){
                alert('Please input valid Mobile Number');
                return false;
            }

            $.post(root+'home/check_forget_pass',{
                phone : countrycode+phone
            },function(data){
                if(data == 'fail'){
                    alert('Phone number not exist');
                }else{
                    alert('OTP Code Sent');
                    $('.phone-receiv-pass').text('****'+phone.slice(-4));
                    $('form.fofgot-password .step-2').removeClass('hidden');
                    $('form.fofgot-password .step-1').hide();
                    $('.timer').startTimer({
                        onComplete: function(element){
                          element.addClass('is-complete');
                      }
                    });
                }
            });
        });

        $("#next-step-3").click(function(){
            var otp = $("#otp").val();
        
            $.post(root+'home/check_otp',{
                otp : otp,
            },function(data){
                if(data == 'success'){
                    $('form.fofgot-password .step-3').removeClass('hidden');
                    $('form.fofgot-password .step-2').hide();
                }else{
                    alert('fail');
                }
            });
        });

        $("#submit_change_pass").click(function(e){
            e.preventDefault();

            var pass = $("#pass").val();
            var repass = $("#repass").val();
            var strong_pass = checkStrongPassword(pass);

            if(pass==''){
                alert('New Password must be not empty');
                return false;
            }
            if(pass != repass){
                alert('Repeat New Password does not match');
                return false;
            }
            if(password.length > 12){
                alert('Your password should max 12 characters in length');
                return false;
            }
            if(strong_pass <3){
                alert('Your password should has at least 8 characters in length, 1 uppercase letter, 1 lowercase letter and 1 number');
                return false;
            }

            $.post(root+'home/submit_change_pass',{
                pass : pass
            },function(data){
                if(data == 'success'){
                    $("#next-step-3").addClass('hidden');
                    $('.change-pass-success').removeClass('hidden');
                }else{
                    alert('fail');
                }
            });
        });

        $(".otp_time .resend_otp").click(function(){
            var status = confirm("Do you want resend OTP");
            if(status == true){
                alert('OK');
                $.post(root+'home/resend_otp',{
                },function(data){
                    alert('OTP Code Sent');
                    $('.otp_time .timer').remove();

                    var no = Math.floor(Math.random() * 10);
                    $('.otp_time').prepend('<p data-seconds-left="120" class="timer no'+no+'"></p>');
                    $('.no'+no).startTimer({0
                        
                        onComplete: function(element){
                          element.addClass('is-complete');
                      }
                    });
                });
            }else{
                alert('Cancel');
            }
        });

    });
</script>
<div class="moomo-style-form">
<div class="full-width">
    <div class="wrap-content">
        <div class="logo"><img src="<?=get_resource_url('assets/images/tiq/logo-min.png')?>"></div>
        <div class="form">
            <div class="content-form">
                <form class="fofgot-password">
                    <div class="step-1">
                        <h2 class="title-form">Forgot Password</h2>
                        <span class="desc">Forgot your password? Please enter your number, You will receive an OTP via SMS.</span>
                        <div class="fields">
                            
                            <label>Your Mobile Number*</label>
                            <p class="mobile-number-row">
                                 <select class="country-code" name="countryCode" id="countryCode">  
                                    <option data-countryCode="SG" value="65" selected="selected">Singapore (+65)</option>
                                    <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                </select>
                                <input type="number" name="mobilenumber" id="mobilenumber" placeholder="Mobile Number" required="required">
                            </p>
                            <p>
                                <div id="next-step-2" class="submit">Reset Password</div>
                            </p>
                        </div>
                    </div>
                    <div class="step-2 hidden">
                        <h2 class="title-form">SMS Password Reset</h2>
                        <span class="desc">Enter the 4-digit One-Time Password (OTP) sent to your mobile number (<span class="phone-receiv-pass"></span>). <a href="sign-up.html">Not your mobile number?</a></span>
                        <div class="fields">
                            <p>
                                <label>OTP Code*</label>
                                <input id="otp" type="password" name="otp">
                            </p>
                            <div class="otp_time">
                                <p data-seconds-left="120" class="timer"></p>
                                <p class="resend_otp">Resend OTP?</p>
                            </div>
                            <p>
                                <div id="next-step-3" class="submit">Reset Password</div>
                            </p>
                        </div>
                    </div>
                    <div class="step-3 hidden">
                        <h2 class="title-form">Reset Password</h2>
                        <span class="desc">Enter your new password below</span>
                        <div class="fields">
                            <p>
                                <label>New Password *</label>
                                <input id="pass" type="password" name="old-password" class="check-strong-password">
                                <ul class="display-suggestion hide">
                                    <li id="length">Password must be minimum 8 characters in length</li>
                                    <li id="uppercase">Password must have at least 1 Uppercase Letter</li>
                                    <li id="lowercase">Password must have at least 1 Lowercase Letter</li>
                                    <li id="number">Password must have at least 1 Number</li>
                                </ul>

                            </p>
                            <p>
                                <label>Repeat New Password *</label>
                                <input id="repass" type="password" name="new-password">
                            </p>
                            <p>                                 
                                <div id="submit_change_pass" class="submit">Reset Password</div>
                                <input type="text" required="required" class="hidden hidden-input">
                            </p>
                        </div>
                    </div><!-- step 3-->
                    <div id="alert_box" class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span></span>
                    </div>
                    <div class="change-pass-success hidden">
                        <p class="alert text-center">Your Password has been reset. </p>
                        <a href="<?php echo PATH_URL; ?>/home/sign_in" class="btn">Sign In</a>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
</div>