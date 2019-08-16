(function($){    
/* =================================
    Acitve menu click
 ================================== **/
	var url = $(location).attr('href'),
    parts = url.split("/"),
    page_current = parts[parts.length-1].replace("#", "");    
   
	if($('ul.menu-sidebar li#'+page_current)!== undefined && $('ul.menu-sidebar li#'+page_current)!== ""){
		$('ul.menu-sidebar li#'+page_current+',ul.menu-sidebar li.'+page_current+', ul.menu-sidebar li.'+page_current+'>a,ul.menu-sidebar li#'+page_current+'>a').addClass('active');
		$('ul.menu-sidebar li[data="'+page_current+'"] a').addClass('active');
		$('ul.menu-sidebar li#'+page_current).parents('.has-childrent').addClass('active');
		$('li.has-childrent>a').removeClass('active');		

    }   

    $('a.review_aip').click(function(e){
    	e.preventDefault();
    	$('#loan_accessibility .mortgage-form, .results .amortization-schedule h2').addClass('hidden');
    	$('.review-box').addClass('hidden');
    	$('.submit-box').removeClass('hidden');
    })

   $('#loan_accessibility .submit-box #edit').click(function(e){
   		e.preventDefault();
   		$('#loan_accessibility .mortgage-form, .results .amortization-schedule h2').removeClass('hidden');
    	$('.review-box').removeClass('hidden');
    	$('.submit-box').addClass('hidden');
   })
/* =================================
    personal info change password
 ================================== **/
   $("#submit_change_pass").click(function(e){
            e.preventDefault();

            var oldpass = $("#oldpass").val();
            var pass = $("#pass").val();
            var repass = $("#repass").val();           
            var strong_pass = checkStrongPassword(pass);

            if(oldpass==''){
                alert('Current Password must be not empty');
                return false;
            }
            if(pass==''){
                alert('New Password must be not empty');
                return false;
            }
            if(pass != repass){
                alert('Repeat New Password does not match');
                return false;
            }
            if(strong_pass <3){
                alert('Your password should has at least 8 characters in length, 1 uppercase letter, 1 lowercase letter and 1 number');
                return false;
            }
            if(pass.length > 12){
                alert('Your password should max 12 characters in length');
                return false;
            }
            if(oldpass == pass){
                alert('New password can not same as current password');
                return false;
            }

            $.post(root+'student/change_pass',{
                oldpass : oldpass,
                pass : pass
            },function(data){
                if(data == 'success'){
                    window.location.href = root+'student/personal_info';
                }else{
                    alert(data);
                }
            });
        });
/** ===================================
    Personal info edit
    ======================================= **/
    
    $("#update-user-info").click(function(e){

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

       /* validate_lastname();*/
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
                $("#email").removeClass('error');
                $("#email").next().html('');
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

        $.post(root+'student/update_user_profile',{
            firstname : $("#firstname").val(),
            lastname : $("#lastname").val(),
            email : $("#email").val(),
            nationality : $("#nationality").val(),
            gender : $("#gender").val(),
            birthday : $("#birthday").val(),
            batch_no : $("#batch_no").val(),
            street : $("#street").val(),
            unit : $("#unit").val(),
            building_name : $("#building_name").val(),
            postal_code : $("#postal_code").val()
        },function(data){
            
            if(data == 'success'){
                /*alert('Update success');*/
                window.location.href = root+'student/personal_info';
            }else{
                alert('Update fail');
            }
        });
    });

})(jQuery);