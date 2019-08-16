(function($){
	var url = root+'student/upload_avatar';
	$("input[name='userImage']").change(function () {
		if($(this).val() !=''){
			$('#change-avatar').removeClass('hidden-input');
		}	    
	});
	$("#uploadForm").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
        	url: url,
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
		    {
		    	
				$(".img-avatar").html(data);
				
		    },
		  	error: function() 
	    	{
	    	} 	        
	   });
	}));
})(jQuery);
