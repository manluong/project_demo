$(document).ready(function(){
	$('#btn-apply').on('click',function(){
		$('#contact_form').ajaxSubmit({
			'success' : function(responseText, statusText, xhr, $form) {
				var json_data = $.parseJSON(responseText);
				if(json_data && json_data.status == 'success'){
					alert('Submit CV thành công');
					
				} else {
					if(json_data.upload_file){
						alert('Upload file bị lỗi');
					}
				}
			}
		});
	});
});