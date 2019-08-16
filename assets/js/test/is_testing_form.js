$(document).ready(function(){
	var current_url = location.href;
	var is_testing = current_url.indexOf('?test=1') != -1;
	// is_testing = true; // TODO - DEBUG
	if(is_testing){
		pr('IS_TESTING_FORM_REGISTER');
		
		var form_jele = $("#contact_form");
		if(form_jele.length){
			var n = (Math.floor((Math.random() * 24) + 0));
			var chr = String.fromCharCode(97 + n); // where n is 0, 1, 2 ...
			var name = 'Tester ' + chr.toUpperCase() + chr.toUpperCase() + chr.toUpperCase();
			var email = name.replace(' ', '').toLowerCase() + '.pix@gmail.com';
			form_jele.find('input[name=first_name]').val(name);
			form_jele.find('input[name=last_name]').val('PIX');
			form_jele.find('input[name=phone]').val('0987654321');
			form_jele.find('input[name=email]').val(email);
			form_jele.find('select[name=industry] option:nth-child(3)').attr('selected','selected');
			form_jele.find('input[name=address]').val('12 Đặng Thai Mai, P7, Phú Nhuận, HCM');
		}
	}
});