<script type="text/javascript">
    $(document).ready(function(){
        
        $(".otp_time .resend_otp").click(function(){
            var status = confirm("Do you want resend OTP");
            if(status == true){
                
                $.post(root+'home/resend_otp',{
                },function(data){
                    $('.otp_time .timer').remove();

                    var no = Math.floor(Math.random() * 10);
                    $('.otp_time').prepend('<p data-seconds-left="120" class="timer no'+no+'"></p>');
                    $('.no'+no).startTimer({
                        onComplete: function(element){
                          element.addClass('is-complete');
                      }
                    });
                });
            }else{
                alert('Cancel');
            }
        });

        $("#next-step-2").click(function(){
            var countrycode = $("#countryCode").val();
            var phone = $("#mobilenumber").val();
            var countryCode = $("#countryCode").val();

            if(phone ==''){
                alert('Mobile Number must be not empty');
                return false;
            }

            if(countrycode == '65'){
                if(phone.length < 8){
                    alert('Your Mobile Number is less than 8 digits');
                    return false;
                }
                if(phone.length > 8){
                    alert('Your Mobile Number is more than 8 digits');
                    return false;
                }

                var first_number_phone = phone.slice(0, 1);
                if(first_number_phone != 8 && first_number_phone != 9){
                    alert('Your Mobile Number should start with number 8 or 9');
                    return false;
                }
            }
            
            if(countrycode == '60'){
                if(phone.length < 9){
                    alert('Your Mobile Number is less than 9 digits');
                    return false;
                }
                if(phone.length > 9){
                    alert('Your Mobile Number is more than 9 digits');
                    return false;
                }
            }

            var new_phone = countryCode+'-'+phone;
            $.post(root+'home/student_reg',{
                phone : countrycode+phone,
            },function(data){
                if(data == 'not_student'){
                    alert('You are not an existing student. This app is only for TIQ members');
                }else{
                    if(data == 'fail'){
                        alert('Register Fail');
                    }else{
                        //alert(data);
                         alert('OTP Code Sent');
                        $('form.sign-up .step-2').removeClass('hidden');
                        $('.step-1').hide();
                        $('.phone-receiv-pass').text('****'+phone.slice(-4));
                        $('form.sign-up .step-3 input#phone').val(new_phone);
                        $('.timer').startTimer({
                            onComplete: function(element){
                              element.addClass('is-complete');
                          }
                        });
                    }   
                }
                
            });
        });

        $("#next-step-3").click(function(){
            var otp = $("#otp").val();
            
            if(otp ==''){
                alert('OTP must be not empty');
                return false;
            }
            $.post(root+'home/check_otp',{
                otp : otp,
            },function(data){
               
                alert(data);
                if(data == 'success'){
                    
                    stop_alert();
                    $('form.sign-up .step-3').removeClass('hidden');
                    $('.step-2').hide();
                    $('.full-width').addClass('user-detail');
                    $('moomo-style-form').css('height', 'auto !important');
                }else{
                    alert(data);
                }
            });
        });

        $("#sign-up").click(function(e){
            
             e.preventDefault();

            var firstname = $("#firstname").val();
            if(firstname == ''){
                $("#firstname").addClass('error');
                $("#firstname").next().html('First Name must be not empty');
                return false;
            }else{
                $("#firstname").removeClass('error');
                $("#firstname").next().html('');
            }
            
            var lastname = $("#lastname").val();
            if(lastname == ''){
                $("#lastname").addClass('error');
                $("#lastname").next().html('Last Name must be not empty');
                return false;
            }else{
                $("#lastname").removeClass('error');
                $("#lastname").next().html('');
            }

            var email = $("#email").val();
            if(email == ''){
                $("#email").addClass('error');
                $("#email").next().html('Email must be not empty');
            }else{
                
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
                {
                    $.post(root+'home/check_email_exits',{
                        email : email,
                    },function(data){
                        if(data == 'success'){
                            $("#email").removeClass('error');
                            $("#email").next().html('');
                        }else{
                            $("#email").addClass('error');
                            $("#email").next().html('Email already exists');
                            return false;
                        }
                    });
                }else{
                   $("#email").addClass('error');
                   $("#email").next().html('Please provide a valid email address');
                   return false;
                }
            }

            var birthday = $("#birthday").val();
            if(birthday == ''){
                $("#birthday").addClass('error');
                $("#birthday").next().html('Birthday must be not empty');
                return false;
            }else{
                $("#birthday").removeClass('error');
                $("#birthday").next().html('');
            }

            var postal_code = $("#postal_code").val();
            if(postal_code == ''){
                $("#postal_code").addClass('error');
                $("#postal_code").next().html('Postal Code must be not empty');
                return false;
            }else{
                $("#postal_code").removeClass('error');
                $("#postal_code").next().html('');
            }

            var password = $("#password").val();
            var repass = $("#repass").val();

            if(password.length > 12){
                alert('Your password should max 12 characters in length');
                return false;
            }

            if(repass == ''){
                $("#repass").addClass('error');
                $("#rrepass").next().html('Repeat Password must be not empty');
                return false;
            }else{
                $("#repass").removeClass('error');
                $("#repass").next().html('');
            }
            if(password != repass){
                $("#repass").addClass('error');
                $("#repass").next().html('Password & Repeat Password does not match');
                return false;
            }else{
                $("#repass").removeClass('error');
                $("#repass").next().html('');
            }

            if($('input[id=pdpa]').is(":checked"))
            {
                console.log('PDPA Checked');
            }else{
                $("#pdpa").addClass('error');
                $(".pdpa .show_error").html('PDPA must be check');
                return false;
            }
            
            var strong_pass = checkStrongPassword(password); 
            
            if(strong_pass <3){

                alert('Your password should has at least 8 characters in length, 1 uppercase letter, 1 lowercase letter and 1 number');
                return false;
            }else{
                $.post(root+'home/submit_sign_up',{
                    pass : $("#password").val(),
                    repass : $("#repass").val(),
                    firstname : $("#firstname").val(),
                    lastname : $("#lastname").val(),
                    nationality : $("#nationality").val(),
                    gender : $("#gender").val(),
                    email : $("#email").val(),
                    birthday : $("#birthday").val(),
                    batch_no : $("#batch_no").val(),
                    street : $("#street").val(),
                    unit : $("#unit").val(),
                    building_name : $("#building_name").val(),
                    postal_code : $("#postal_code").val()
                },function(data){
                    /*alert(data);*/
                    if(data == 'success'){
                        window.location.href = root+'student/dashboard';
                    }else{
                        alert('fail');
                    }
                });
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
          <form autocomplete="off" class="sign-up" method="POST">
            <!-- STEP 1 -->
            <div class="step-1">
              <h2 class="title-form">SIGN UP</h2>
              <div class="fields">
                <label  class="margin-bottom">Mobile Number *</label>
                <p class="mobile-number-row">
                  
                <select class="country-code" name="countryCode" id="countryCode">  
                    <option data-countryCode="SG" value="65" selected="selected">Singapore (+65)</option>
                    <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                </select>
                  <input type="number" name="mobilenumber" id="mobilenumber" placeholder="Mobile Number" >
                </p>              
                <p>
                  <div id="next-step-2" class="submit">Next </div>
                </p>
              </div>
            </div>
            <!-- END STEP 1 -->
            <!-- STEP 2 -->
            <div class="step-2 hidden">
              <h2 class="title-form">SIGN UP </h2>
              <h3>One-Time Password (OTP) Authentication </h3>
              <span class="desc">Enter the 4-digit One-Time Password (OTP) sent to your mobile number (<span class="phone-receiv-pass"></span>). <a href="<?=PATH_URL.'home/sign_up'?>">Not your mobile number?</a></span>
             
              <div class="fields">
                <p>
                  <label>OTP *</label>
                  <input type="text" name="otp" id="otp" placeholder="">
                </p>
                <div class="otp_time">
                    <p data-seconds-left="120" class="timer"></p>
                    <p class="resend_otp">Resend OTP?</p>
                </div>              
                <p>
                  <div id="next-step-3" class="submit">Next </div>             
                </p>
              </div>
            </div>
            <!-- END STEP 2 -->
            <!-- STEP 3 -->
            <div class="step-3 hidden">
              <h2 class="title-form">SIGN UP - Personal Info</h2>
              <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.  Nulla quam velit, vulputate eu pharetra nec</span>
              <div class="fields">
                <p>
                  <label>First Name*</label>
                  <input id="firstname"  name="first-name" type="text" value="" placeholder="First Name">
                  <span class="show_error"></span>
                </p>
                <p>
                  <label>Last Name*</label>
                  <input id="lastname" name="last-name" type="text" value=""  placeholder="Last Name">
                  <span class="show_error"></span>
                </p>
                <p>
                  <label>Email *</label>
                  <input id="email" name="email" type="text" value=""  placeholder="">
                  <span class="show_error"></span>
                </p>
                <p>
                  <label>Mobile Number</label>
                  <input disabled="" id="phone" type="text" value="" >
                </p>                
                <p>
                  <label>Nationality *</label>
                  <select id="nationality" name="nationality">
                        <?php if(!empty($national)){
                                foreach ($national as $key => $value) {
                        ?>
                        <option value="<?=$value->id?>" ><?=$value->national?></option>
                        <?php }}?>
                    </select>
                  <span class="show_error"></span>
                </p>
                <p>
                    <label>Gender</label>
                    <select id="gender" name="gender">
                        <option value="0"></option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                    </select>
                  <span class="show_error"></span>
                </p>
                <p class="birthday">
                  <span class="date">
                    <label>Date of Birth*</label>
                    <input id="birthday" name="birthday" class="datepicker" value="" >
                    <span class="show_error"></span>  
                  </span>                 
                </p>
                <p>                 
                  <label>PIM Batch No.</label>
                  <select id="batch_no" name="batch_no">
                        <option value="" ></option>
                        <?php if(!empty($pim)){
                                foreach ($pim as $key => $value) {
                        ?>
                        <option value="<?=$value->id?>" ><?=$value->pim?></option>
                        <?php }}?>
                    </select>
                  <span class="show_error"></span>
                </p>
                <p>
                  <label>No. & Street Name </label>
                  <input id="street" name="address-1" type="text" value="" placeholder="XX - street address">
                </p>
                <p>
                  <label>Unit No</label>
                  <input id="unit" name="unit" type="text" value="" placeholder="##">
                </p>
                <p>
                  <label>Building Name</label>
                  <input id="building_name" name="building-name" type="text" value="" placeholder="Building Name">
                </p>
                <p>
                  <label>Postal Code*</label>
                  <input id="postal_code"   name="post-code" type="number" value="" placeholder="-------">
                  <span class="show_error"></span>
                </p>
                <div class="password-field">
                  <label>Password *</label>
                  <input id="password" name="password" type="password" value="" class="check-strong-password" >
                  <span class="show_error"></span>
                  <ul class="display-suggestion hide">
                    <li id="length">Password must be minimum 8 characters in length</li>
                    <li id="uppercase">Password must have at least 1 Uppercase Letter</li>
                    <li id="lowercase">Password must have at least 1 Lowercase Letter</li>
                    <li id="number">Password must have at least 1 Number</li>
                </ul>
                </div> 
                <p>
                  <label>Repeat Password *</label>
                  <input id="repass" name="repass" type="password" value="" placeholder="" >
                  <span class="show_error"></span>
                </p>
                <p class="pdpa">
                  <label>
                    <input id="pdpa"  type="checkbox" name="pdpa">
                    <text>
                      By providing The I Quadrant with my personal data, I agree that The I Quadrant may collect, use and disclose my personal data for purposes in accordance with its Privacy Policy and the Personal Data Protection Act 2012.

                      I understand that my personal data may be used for marketing purposes by The I Quadrant or its partners; and I hereby consent to receive marketing and promotional materials by telephone, SMS or e-mail and through other channels as determined by The I Quadrant.</text>
                  </label>
                  <span class="show_error"></span>
                </p>
                <p class="sign-up-row">
                  <div id="sign-up" class="submit">Next </div>
                  <input type="text"  class="hidden hidden-input">
                </p>
                
              </div>
            </div>
            <div id="alert_box" class="alert alert-success alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span></span>
            </div>
            <!-- END STEP 3 -->
            <!-- <div class="step-4 hidden">
              <p class="center-text">
                Your information has been successfully submitted.
              </p>
              
            </div> -->
          </form>
        </div>
        
      </div>
    </div>
  </div>
</div>
 