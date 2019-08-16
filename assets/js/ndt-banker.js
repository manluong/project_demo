(function($){
    /* =================================
        Acitve menu click
    ================================== **/
    var url = $(location).attr('href'),
    parts = url.split("/"),
    page_current = parts[parts.length-1].replace("#", "");  

    if($('ul.menu-sidebar li#'+page_current)!== undefined){
        $('ul.menu-sidebar li#'+page_current+',ul.menu-sidebar li.'+page_current+', ul.menu-sidebar li.'+page_current+'>a,ul.menu-sidebar li#'+page_current+'>a').addClass('active');               
        $('ul.menu-sidebar li[data="'+page_current+'"] a').addClass('active');
        $('ul.menu-sidebar li#'+page_current).parents('.has-childrent').addClass('active');
        $('li.has-childrent>a').removeClass('active');  
    }
    /* ===============================
        Click active tab menu
    ================================== */
    $('ul.tab-discuss li').click(function(){      
        $('ul.tab-discuss li').toggleClass('active');
        $('.content-tab>div').toggleClass('hidden');

    })

    /*Banker update profile*/
    $("#banker-update-profile").click(function(){
        $.post(root+'banker/update_profile',{
            firstname : $("#firstname").val(),
            lastname : $("#lastname").val(),
            email : $("#email").val(),
            bank : $("#bank").val(),
        },function(data){
            if(data == 'success'){
                window.location.href = root+'banker/personal_info';
            }else{
                alert('Update fail');
            }
        });
    });
    /*End Banker update profile*/

    /*Banker change password*/
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
               window.location.href = root+'banker/personal_info';
            }else{
                alert(data);
            }
        });
    });
    /*Banker change password*/

    /*BANKER DETAIL PAGE*/
    $("#banker_agree").change(function(){
        if($(this).is(":checked"))
        {
            var data =  $('td[id=servicing]').html();
                data = data.slice(1);

            $('input[name=verified_amount]').val(data);
            $('input[name=verified_amount]').attr('disabled','true');
        }else{
            $('input[name=verified_amount]').val('');
            $('input[name=verified_amount]').removeAttr('disabled');
        }
    });

    $("#banker_verify_loan").click(function(e){
        e.preventDefault();
        var result = confirm('Are you sure want verify for this loan !');

        if(result){
            var agree = 0;
            var verified_amount = $('input[name=verified_amount]').val();
            var student_loan_id = $("#student_loan_id").val();
            var student_id = $("#student_id").val();

            if(verified_amount == ''){
                alert('Verified Amount much be not empty');
                return false;
            }
            
            if($('input[id=banker_agree]').is(":checked"))
            {
                agree = 1;
            }
           
            $.post(root+'banker/banker_verify',{
                status : 4,
                agree  : agree,
                verified_amount : verified_amount,
                student_loan_id : student_loan_id 
            },function(data){
                console.log(data);
                window.location.href = root+'banker/loan_accessibility_list/'+student_id;
            });
        }
    });
    
    $("#send_remark").click(function(){
        var banker_remark = $("#banker_remark").val();
        var student_loan_id = $("#student_loan_id").val();
        var banker_id = $("#banker_id").val();

        $.post(root+'banker/banker_remark',{
            remark : banker_remark,
            student_loan_id : student_loan_id,
            banker_id : banker_id
        },function(data){
            console.log(data);
            location.reload();
        });
    });

    $("#send_comment").click(function(){
        var banker_comment = $("#banker_comment").val();
        var hdb_install = $("#hdb_install").val();
        var student_loan_id = $("#student_loan_id").val();
       
        $.post(root+'banker/banker_comment',{
            banker_comment : banker_comment,
            hdb_install : hdb_install,
            student_loan_id : student_loan_id
            
        },function(data){
            console.log(data);
             $('textarea[id=banker_comment]').prop("disabled", true);
             $('input[id=hdb_install]').prop("disabled", true);
             $('#send_comment').addClass('btn_hide');
             $('#edit_comment').removeClass('btn_hide');
        });
    });

     $("#edit_comment").click(function(){
        $('textarea[id=banker_comment]').removeAttr('disabled');
        $('input[id=hdb_install]').removeAttr('disabled');
        $('#edit_comment').addClass('btn_hide');
        $('#send_comment').removeClass('btn_hide');
        
    });

    /*END BANKER DETAIL PAGE*/
})(jQuery);