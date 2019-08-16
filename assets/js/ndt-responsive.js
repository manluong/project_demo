(function($){

	$('.header .icon-menu').click(function(){		
		$('.bg-body .sidebar').css({'left': 0});
		$('.bg-body .box-content').removeClass('enable-notification');
	})
	$('.close').click(function(){
		$('.bg-body .sidebar').css({'left': '-260px'});

	})
	$('.header i.icon-notification').click(function(){
		$('.bg-body .box-content').addClass('enable-notification');
	})

})(jQuery);