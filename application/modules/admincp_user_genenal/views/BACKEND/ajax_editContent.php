<?php
$is_venue= !empty($result->user_type_id) && $result->user_type_id == 1;
$is_artist= !empty($result->user_type_id) && $result->user_type_id == 2;
$is_label= !empty($result->user_type_id) && $result->user_type_id == 3;
$is_promoter= !empty($result->user_type_id) && $result->user_type_id == 4;
$is_marketer= !empty($result->user_type_id) && $result->user_type_id == 5;
?>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovaeditor.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovamanager.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/js/admin/'?>jquery.slugit.js"></script>
<?php
	if(isset($this->lang->languages))
	{
		$all_lang = $this->lang->languages;
	}
	else
	{
		$all_lang = array(
			'' => ''
		);
	}
?>
<script type="text/javascript">


function save()
{
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) 
{
	var form = jqForm[0];
	<? if(empty($id)){ ?>
	if(form.email.value  == ''){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	<? } ?>
	
}

function showResponse(responseText, statusText, xhr, $form) 
{
	responseText = responseText.split(".");
	token_value  = responseText[1];
	$('#csrf_token').val(token_value);
	if(responseText[0]=='success'){
		<?php if($id==0){ ?>
		location.href=root+module+"/#/save";
		<?php }else{ ?>
		if($('.form-upload').val() != ''){
			$.get('<?=PATH_URL_ADMIN.$module.'/ajaxGetImageUpdate/'.$id?>',function(data){
				var res = data.split("src=");
				$('.fileinput-filename').html('');
				$('.fileinput').removeClass('fileinput-exists');
				$('.fileinput').addClass('fileinput-new');
			});
		}
		show_perm_success();
		<?php } ?>
	}
	if(responseText[0]=='permission-denied'){
		$('#txt_error').html('Permission denied.');
		show_perm_denied();
		return false;
	}
	
	if(responseText[0]=='error-image'){
		$('#txt_error').html('Only upload image.');
		show_perm_denied();
		return false;
	}
	if(responseText[0]=='permission-denied'){
		$('#txt_error').html('Permission denied.');
		show_perm_denied();
		return false;
	}
}
</script>
<style type="text/css">
		.btn-bg{
		margin-left: 30px;	
		background: #3498DB !important;
		color: #fff !important;
	}
	.btn-bg:hover{
		background: #3498DB99 !important;
		color: #000 !important;
		/*/font-weight: bold;*/
	}

</style>
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"><?=$this->session->userdata('Name_Module')?></h3>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<i class="fa fa-home"></i>
				<a href="<?=PATH_URL_ADMIN?>">Home</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="<?=PATH_URL_ADMIN.$module?>"><?=$this->session->userdata('Name_Module')?></a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li><?php ($this->uri->segment(4)=='') ? print 'Add new' : print 'Information' ?></li>
	</ul>
	<a class="btn-canel-back" href="<?=PATH_URL_ADMIN.$module.'/#/back'?>">
		<button type="button" class="btn default btn-bg">Cancel
		</button>
	</a>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box grey-cascade">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-globe"></i>Form Information
				</div>
			</div>
			
			<div class="portlet-body form">
				<div class="form-body notification" style="display:none">
					<div class="alert alert-success" style="display:none">
						<strong>Success!</strong> The page has been saved.
					</div>
					
					<div class="alert alert-danger" style="display:none">
						<strong>Error!</strong> 
						<span id="txt_error"></span>
					</div>
				</div>
				
				<!-- BEGIN FORM-->

				<form id="frmManagement" action="<?=PATH_URL_ADMIN.$module.'/save/'?>" method="post" enctype="multipart/form-data" class="form-horizontal form-row-seperated">
					<input type="hidden" value="<?=$this->security->get_csrf_hash()?>" id="csrf_token" name="csrf_token" />
					<input type="hidden" value="<?=$id?>" name="hiddenIdAdmincp" />

					<div class="form-body">	

						<div class="tab-content">
					
						<style type="text/css">
							.title-user-info {
							    color: #000;
							    font-size: 18px;
							    text-align: center;
							    padding: 15px;
							    font-weight: bold;
							}
							

						</style>
					
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-2">Type
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->type)) ? $result->type : '' ?>" type="text" name="type" id="type" class="form-control"/>
								</div>
							</div>	
							
							<div class="form-group">
								<label class="control-label col-md-2">Last Name
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->last_name)) ? $result->last_name : '' ?>" type="text" name="last_name" id="last_name" class="form-control"/>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2">First Name
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->first_name)) ? $result->first_name : '' ?>" type="text" name="first_name" id="first_name" class="form-control"/>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">Email
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->email)) ? $result->email : '' ?>" type="text" name="email" id="email" class="form-control"/>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2">Phone<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->phone)) ? $result->phone : '' ?>" type="text" name="phone" id="phone" class="form-control"/>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2">Address
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->address)) ? $result->address : '' ?>" type="text" name="address" id="address" class="form-control"/>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">Industry
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->industry)) ? $result->industry : '' ?>" type="text" name="industry" id="industry" class="form-control"/>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">Created
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->created)) ? $result->created : '' ?>" type="text" name="created" id="created" class="form-control"/>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">file_url 
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->file_url)) ? $result->file_url : '' ?>" type="text" name="file_url" id="file_url" class="form-control"/>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-2">Address IP 
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->ip)) ? $result->ip : '' ?>" type="text" name="ip" id="ip" class="form-control"/>
								</div>
							</div>
							
							<?php
								if($this->session->userdata('userInfo')=='admin' || $this->session->userdata('userInfo')=='root'){
								?>
							
								<div class="form-group">
									<label class="control-label col-md-2">utm_source
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-10">
										<input data-required="1" value="<?php echo (isset($result->utm_source)) ? $result->utm_source : '' ?>" type="text" name="utm_source" id="utm_source" class="form-control"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2">utm_medium
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-10">
										<input data-required="1" value="<?php echo (isset($result->utm_medium)) ? $result->utm_medium : '' ?>" type="text" name="utm_medium" id="utm_medium" class="form-control"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2">utm_campaign
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-10">
										<input data-required="1" value="<?php echo (isset($result->utm_campaign)) ? $result->utm_campaign : '' ?>" type="text" name="utm_campaign" id="utm_campaign" class="form-control"/>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2">utm_term
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-10">
										<input data-required="1" value="<?php echo (isset($result->utm_term)) ? $result->utm_term : '' ?>" type="text" name="utm_term" id="utm_term" class="form-control"/>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">utm_content
									<span class="required" aria-required="true">*</span>
								</label>
								<div class="col-md-10">
									<input data-required="1" value="<?php echo (isset($result->utm_content)) ? $result->utm_content : '' ?>" type="text" name="utm_content" id="utm_content" class="form-control"/>
								</div>
							</div>
							<?php
							}
							?>
					</div>
					<div class="form-actions fix-bg">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<!-- <button onclick="save()" type="button" class="btn green">
									<i class="fa fa-pencil"></i>Save
								</button> -->
								
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiJHmYESPNUKsb6YIVaYIOivKZPTQnJ2M&amp;libraries=places"></script>
<script type="text/javascript">
			function initialize() {
				var options = {
					types: ['(regions)']
				};
				var input = document.getElementById('searchTextField');
				var autocomplete = new google.maps.places.Autocomplete(input , options);
			}
			google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style type="text/css">
	#cropic_element {
		height: 200px;
		width: 200px;
	}
	#cropic_element_image_1{
		height: 200px;
		width: 200px;
	}
</style>


<script>
    var croppicHeaderOptions = {
            
            uploadUrl:"<?=get_resource_url('img_upload_to_file.php')?>",
            cropUrl:"<?=get_resource_url('img_crop_to_file.php')?>",
            uploadData:{'prefix':'avatar_'},
            enableMousescroll:true,
            customUploadButtonId:'cropContainerHeaderButton',
            outputUrlId:'input_thumbnail_url',
            modal:true,
            rotateControls: false,
            doubleZoomControls:false,
            imgEyecandyOpacity:0.4,
            onBeforeImgUpload: function(){ },
            onAfterImgUpload: function(){ appendOriginImageAward(); },
            onImgDrag: function(){ },
            onImgZoom: function(){ },
            onBeforeImgCrop: function(){ },
            onAfterImgCrop:function(){appendAward(); },
            onReset:function(){ onResetCropic(); },
            onError:function(errormessage){ console.log('onError:'+errormessage) }
    }   
    var croppic = new Croppic('cropic_element', croppicHeaderOptions);
    function appendOriginImageAward() {
        var url_origin = $("div#croppicModalObj > img").attr('src');
        $('#input_image_url').val(url_origin);
        $("#preview_image_fancybox").attr("href", $('#input_image_url').val());
    }
    function appendAward(){
        $("#preview_image").attr("src", $('#input_thumbnail_url').val());
        $("#preview_image").show();
    }
    function onResetCropic(){
    	$('#input_image_url').val('');
    	$('#input_thumbnail_url').val('');
    	$("#preview_image_fancybox").attr("href", '');
    	$("#preview_image").attr("src", '');
    }
   
</script>
<script>
// setting country user
function initialize5() {
 options = {types: ['(regions)']};
 var input = document.getElementById('location'); //
 var autocomplete = new google.maps.places.Autocomplete(input , options);
                                
	google.maps.event.addListener(autocomplete, 'place_changed', 
	function() {
	var address_components=autocomplete.getPlace().address_components;
	var city='';
	var country='';
	for(var j =0 ;j<address_components.length;j++) {
		city =address_components[0].short_name;
	
		if(address_components[j].types[0]=='country')
		{
		   country=address_components[j].short_name;
		   console.log(address_components[j]);
		}
	}
		 //document.getElementById('data').innerHTML="City Name : <b>" + city + "</b> <br/>Country Name : <b>" + country + "</b>";
		document.getElementById('country-hides').value = country;
		document.getElementById('city-hides').value = city;

	});
}
 google.maps.event.addDomListener(window, 'load', initialize5);
</script>
<script>
    var croppicHeaderOptions_1 = {
            
            uploadUrl:"<?=get_resource_url('img_upload_to_file.php')?>",
            cropUrl:"<?=get_resource_url('img_crop_to_file.php')?>",
            uploadData:{'prefix':'avatar_image_'},
            enableMousescroll:true,
            customUploadButtonId:'crop_image_1',
            outputUrlId:'input_thumbnail_url_1',
            modal:true,
            rotateControls: false,
            doubleZoomControls:false,
            imgEyecandyOpacity:0.4,
            onBeforeImgUpload: function(){ },
            onAfterImgUpload: function(){ appendOriginImageAwards(); },
            onImgDrag: function(){ },
            onImgZoom: function(){ },
            onBeforeImgCrop: function(){ },
            onAfterImgCrop:function(){appendAwards(); },
            onReset:function(){ onResetCropics(); },
            onError:function(errormessage){ console.log('onError:'+errormessage) }
    }   
    var croppic = new Croppic('cropic_element_image_1', croppicHeaderOptions_1);
    function appendOriginImageAwards() {
        var url_origin = $("div#croppicModalObj > img").attr('src');
        $('#input_image_url_1').val(url_origin);
        $("#preview_image_fancybox_1").attr("href", $('#input_image_url_1').val());
    }
    function appendAwards(){
        $("#preview_image_1").attr("src", $('#input_thumbnail_url_1').val());
        $("#preview_image_1").show();
    }
    function onResetCropics(){
    	$('#input_image_url_1').val('');
    	$('#input_thumbnail_url_1').val('');
    	$("#preview_image_fancybox_1").attr("href", '');
    	$("#preview_image_1").attr("src", '');
    }
   
</script>

<!-- END PAGE CONTENT-->
