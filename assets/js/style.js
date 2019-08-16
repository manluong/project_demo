$(document).ready(function(){
    
	$(".action-of-user .log-out").click(function(){
        $.post(root+'student/logout',{
        },function(data){
            if(data == 'success'){
            	 window.location.href = root+'home/sign_in';
            }
        });
    });
    
    $( ".check-strong-password" ).change(function() {
      var password = $(this).val();
      $('.display-suggestion').removeClass('hide');     
      checkStrongPassword(password);
    });
    $(".datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
      yearRange: '1910:2019'
    });
});

function alert(text){
    $("#alert_box span").html(text);
    $("#alert_box").show();
}

function stop_alert(text){
    $("#alert_box span").html('');
    $("#alert_box").hide();
}

function checkStrongPassword(passwd)
    {
        var intScore             = 0;       
        var strScoreLend         = "";
        var strScoreUppercase    = "";
        var strScoreLowercase    = "";
        var strScoreNumber       = "";

        if(passwd.length){
            if (passwd.length>=8)
                    {
                        $('.display-suggestion #length').addClass('hide');
                        $strScoreLend = 1;
                    }else{
                        $('.display-suggestion #length').removeClass('hide');
                        $strScoreLend = 0;
                    }
                    // PASSWORD UPPERCASE
                    if (passwd.match(/[A-Z]/))                              // [verified] at least one upper case letter
                    {
                        $('.display-suggestion #uppercase').addClass('hide');
                        strScoreUppercase =1;
                    }else{
                        $('.display-suggestion #uppercase').removeClass('hide');
                        strScoreUppercase =0;
                    }
                    //LOWER CASE
                    if (passwd.match(/[a-z]/))                              // [verified] at least one lower case letter
                    {
                        $('.display-suggestion #lowercase').addClass('hide'); 
                        strScoreLowercase =1;
                    }else{

                        $('.display-suggestion #lowercase').removeClass('hide');
                        strScoreLowercase =0;
                    }
                    //NUMBER
                    if (passwd.match(/\d+/))                                 // [verified] at least one number
                    {
                        $('.display-suggestion #number').addClass('hide');
                        strScoreNumber =1;
                    }else{
                        $('.display-suggestion #number').removeClass('hide');
                        strScoreNumber =0;
                    }
        }else{
            $('.display-suggestion').addClass('hide');
        }        
        intScore = strScoreLend + strScoreUppercase + strScoreLowercase + strScoreNumber;  
        return intScore;
    
}