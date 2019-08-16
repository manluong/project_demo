var timeoutNotification;
$(document).ready(function() {
	//Fix Enter input
	$('#frmManagement input').keypress(function(event){
		if(event.which == 13){
			save();
			return false;
		}
	})

	//Parse URL
	var url = $.url(document.location.href);
	if(url.segment(-1)!='update' && url.segment(-2)!='update' && url.segment(-1)!='import_store' && url.segment(-1)!='import_plan'){
		if(url.fsegment(1)=='back' || url.fsegment(1)=='save'){
			if(url.fsegment(1)=='save'){
				show_perm_success();
			}
			if(url.segment(-1)!='update_profile' && url.segment(-1)!='setting'){
				if($('#start').val()==''){
					$('#start').val(0);
				}
				searchContent($('#start').val(),10);
			}
		}else{
			if(url.segment(-1)!='update_profile' && url.segment(-1)!='setting'){
				//Load Content
				if(module!='admincp'){
					searchContent(0,10);
				}
			}
		}
	}
	
	//Show Notification
	
	
	//Fix box permission
	widthBoxPerm();
});

function show_perm_denied(){
	clearTimeout(timeoutNotification);
	$('.notification').show();
	$('.alert-danger').stop().fadeIn(500);
	timeoutNotification = setTimeout("$('.notification').stop().fadeOut(300);$('.alert-danger').stop().fadeOut(300)",3000);
	$('html,body').animate({
		scrollTop: 0
	}, 1000);
}

function show_perm_success(){
	clearTimeout(timeoutNotification);
	$('.notification').show();
	$('.alert-success').stop().fadeIn(500);
	timeoutNotification = setTimeout("$('.notification').stop().fadeOut(300);$('.alert-success').stop().fadeOut(300)",3000);
	$('html,body').animate({
		scrollTop: 0
	}, 1000);
}

function searchContent(start,per_page){
	var el = $('a.reload').closest(".portlet").children(".portlet-body");
	Metronic.blockUI({
		target: el,
		animate: true,
		overlayColor: 'none'
	});
	if(per_page==undefined){
		if($('#per_page').val()){
			per_page = $('#per_page').val();
		}else{
			per_page = 10;
		}
	}
	var func_sort = $('#func_sort').val();
	var type_sort = $('#type_sort').val();
	$('#start').val(start);
	$.post(root+module+'/ajaxLoadContent',{
		func_order_by: func_sort,
		order_by: type_sort,
		start: start,
		per_page: per_page,
		dateFrom: $('#caledar_from').val(),
		dateTo: $('#caledar_to').val(),
		filter1: $('#filter1').val(),
		filter2: $('#filter2').val(),
		filter3: $('#filter3').val(),
		content: $('#search_content').val(),
		csrf_token: token_value
	},function(data){
		$('.portlet-body').html(data);
		Metronic.unblockUI(el);
		Metronic.initAjax();
		
		//Set Icon Order By
		if(type_sort=='DESC'){
			$('#'+func_sort).removeClass('sorting');
			$('#'+func_sort).addClass('sorting_desc');
		}else{
			$('#'+func_sort).removeClass('sorting');
			$('#'+func_sort).addClass('sorting_asc');
		}
	});
}

function enterSearch(e){
	if (e.keyCode == 13){ 
		searchContent(0);
	}
}

function sort(func){
	var func_sort = $('#func_sort').val();
	var type_sort = $('#type_sort').val();
	if(func==func_sort){
		if(type_sort=='DESC'){
			$('#type_sort').val('ASC');
		}else{
			$('#type_sort').val('DESC');
		}
	}else{
		$('#func_sort').val(func)
		$('#type_sort').val('DESC');
	}
	searchContent(0,$('#per_page').val());
}

function updateStatus(id,status,module){
	var url = root+module+'/ajaxUpdateStatus';
	$.post(url,{
			id: id,
			status: status,
			csrf_token: token_value
		},
		function(data){
			data = data.split(".");
			if(data[1]){
				token_value  = data[1];
			}
			data = data[0];
			if(data=='permission-denied'){
				$('#txt_error').html('Permission denied.');
				show_perm_denied();
			}else{
				if(module=="admincp_modules"){
					top.location.reload();
				}
				$('#loadStatusID_'+id).html(data);
			}
		}
	);
}

function updateType(id,type,email,module){
	var url_user = root+module+'/ajaxUpdateType'+'/edit'+'/'+id;
	window.location.href = url_user;
}

function selectItem(id){
	var itemCheck = document.getElementById('item'+id);
	if(itemCheck.checked==true){
		$('.item_row'+id).addClass('row_active');
	}else{
		$('.item_row'+id).removeClass('row_active');
	}
}

function selectAllItems(max){
	if(document.getElementById('selectAllItems').checked==true){
		for(var i=0;i<max;i++){
			if(document.getElementById('item'+i)!=null){
				$('.item_row'+i).addClass('row_active');
				$('#item'+i).parent("span").addClass('checked');
				$('#item'+i).parent("span").parent("div .checker").addClass('focus');
				itemCheck = document.getElementById('item'+i);
				itemCheck.checked = true;
			}
		}
	}else{
		for(var i=0;i<max;i++){
			if(document.getElementById('item'+i)!=null){
				$('.item_row'+i).removeClass('row_active');
				$('#item'+i).parent("span").removeClass('checked');
				$('#item'+i).parent("span").parent("div .checker").removeClass('focus');
				itemCheck = document.getElementById('item'+i);
				itemCheck.checked = false;
			}
		}
	}
}

function showStatusAll(){
	
	var max = $('#per_page').val();
	for(var i=0;i<max;i++){
		if(document.getElementById('item'+i)!=null){
			if(document.getElementById('item'+i).checked==true){
				updateStatus($('#item'+i).val(),0,module);
			}
		}
	}
}

function hideStatusAll(){
	var max = $('#per_page').val();
	for(var i=0;i<max;i++){
		if(document.getElementById('item'+i)!=null){
			if(document.getElementById('item'+i).checked==true){
				updateStatus($('#item'+i).val(),1,module);
			}
		}
	}
}

function deleteAll(){
	var max = $('#per_page').val();
	for(var i=0;i<max;i++){
		if(document.getElementById('item'+i)!=null){
			if(document.getElementById('item'+i).checked==true){
				$('.modal-header .close').trigger('click');
				id = $('#item'+i).val();
				var url = root+module+'/delete';
				$.post(url,{
					id: id,
					csrf_token: token_value
				},function(data){
					data = data.split(".");
					console.log(data);
					token_value  = data[1];
					if(data[0]=='permission-denied'){
						$('#txt_error').html('Permission denied.');
						show_perm_denied();
						return false;
					}else{
						searchContent($('#start').val(),$('#per_page').val());
					}
				});
			}
		}
	}
}

function chk_perm(id,perm){
	if(perm!='no_access'){
		if(perm=='read'){
			if($('#read'+id).attr('checked')!='checked'){
				$('#noaccess'+id).attr("checked",true);
				$('#write'+id).attr("checked",false);
				$('#delete'+id).attr("checked",false);
				$('#noaccess'+id).parent("span").addClass('checked');
				$('#write'+id).parent("span").removeClass('checked');
				$('#delete'+id).parent("span").removeClass('checked');
			}else{
				$('#noaccess'+id).attr("checked",false);
				$('#noaccess'+id).parent("span").removeClass('checked');
			}
		}else{
			$('#read'+id).attr("checked",true);
			$('#read'+id).parent("span").addClass('checked');
			$('#noaccess'+id).attr("checked",false);
			$('#noaccess'+id).parent("span").removeClass('checked');
		}
	}else{
		if($('#noaccess'+id).attr('checked')!='checked'){
			$('#read'+id).attr("checked",true);
			$('#read'+id).parent("span").addClass('checked');
		}else{
			$('.perm_access'+id).attr("checked",false);
			$('.perm_access'+id).parent("span").removeClass('checked');
		}
	}
}
function widthBoxPerm(){
	if($(".gr_perm").length){
		$(".gr_perm").parent(".col-md-9").css("padding-right",0);
		var e=$(".gr_perm").length;var t=Math.floor(($(".col-md-9").width()+27)/($(".gr_perm").width()+17));
		var n=Math.floor(e/t);$(".gr_perm").removeAttr("style");
		$(".gr_perm").each(function(e){
			for(var r=1;r<=n;r++){
				if(e+1==t*r){
					$(this).css({marginRight:0});
				}
				if(e>=t){
					$(this).css({marginTop:15});
				}
			}
		})
	}
}
function filterTable(start,per_page){
	var el = $('a.reload').closest(".portlet").children(".portlet-body");
	Metronic.blockUI({
		target: el,
		animate: true,
		overlayColor: 'none'
	});
	if(per_page==undefined){
		if($('#per_page').val()){
			per_page = $('#per_page').val();
		}else{
			per_page = 10;
		}
	}
	var func_sort = $('#func_sort').val();
	var type_sort = $('#type_sort').val();
	$('#start').val(start);
	$.post(root+module+'/ajaxLoadFilter',{
		func_order_by: func_sort,
		order_by: type_sort,
		start: start,
		per_page: per_page,
		dateFrom: $('#caledar_from').val(),
		dateTo: $('#caledar_to').val(),
		content: $('#select_catagory').val(),
		csrf_token: token_value
	},function(data){
		$('.portlet-body').html(data);
		Metronic.unblockUI(el);
		Metronic.initAjax();
		
		//Set Icon Order By
		if(type_sort=='DESC'){
			$('#'+func_sort).removeClass('sorting');
			$('#'+func_sort).addClass('sorting_desc');
		}else{
			$('#'+func_sort).removeClass('sorting');
			$('#'+func_sort).addClass('sorting_asc');
		}
	});
}