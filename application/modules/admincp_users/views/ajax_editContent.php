<script type="text/javascript">

function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) {
	var phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
	var form = jqForm[0];
	var phoneInput = form.phone.value;
	if(phoneInput != ""){
		if(!phoneRegex.test(phoneInput)){
			$('#txt_error').html('Phone format not match!!!');
			$('#loader').fadeOut(300);
			show_perm_denied();
			return false;
		}
	}
	if(form.firstname.value == '' || form.lastname.value == '' || form.email.value == '' <?php if($id == 0){ ?>  || form.password.value == '' <?php } ?>){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
	<?php if($id==0){ ?>
	if(form.firstname.value == ''){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
	<?php } ?>
}

function showResponse(responseText, statusText, xhr, $form) {
	responseText = responseText.split(".");
	token_value  = responseText[1];

	$('#csrf_token').val(token_value);
	if(responseText[0]=='success'){
		show_perm_success();
	}

	if(responseText[0]=='permission-denied'){
		$('#txt_error').html('Permission denied.');
		show_perm_denied();
		return false;
	}
	
	if(responseText[0]=='error-email-exists'){
		$('#txt_error').html('Email already exists.');
		show_perm_denied();
		$('#email').focus();
		return false;
	}
	if(responseText[0]=='fail_validate'){
		var html = '';
		for (var i = 1; i < responseText.length; i++) {
			html += responseText[i];
		}
		$('#txt_error').html(html);
		show_perm_denied();
	}
}

function get_cities(countryID, city_id = 0){
	if(countryID == 0){
		$('#city').val('0').prop('disabled', 'disabled');
		return;
	}
	$.post('<?=PATH_URL_ADMIN.'admincp_users/ajaxLoadCities/'?>',{
		'countryID': countryID,
		'cityID': city_id,
		csrf_token: token_value
	},function(data){
		$('#city').html(data).prop('disabled', false);
	});

}

function showProUserType(userRole){
	if(userRole == <?=USER_ROLE_PRO?>){
		$('#proUserRole').fadeIn('800');
	} else {
		$('#proUserRole').fadeOut('800');
	}
}

$(document).ready(function() {
	// $('#country').select2();
	// $('#city').select2();
	$('#city').prop('disabled', 'disabled');
	$('#proUserRole').hide();

	get_cities($('#country').val(), <?=isset($result->city_id) ? $result->city_id : 0 ?>);

	var userRole = $('#user_role').val();
	if(userRole == <?=USER_ROLE_PRO ?>){
		$('#proUserRole').show();
	}


});
</script>
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"><?=$this->session->userdata('Name_Module')?></h3>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li><i class="fa fa-home"></i><a href="<?=PATH_URL_ADMIN?>">Home</a><i class="fa fa-angle-right"></i></li>
		<li><a href="<?=PATH_URL_ADMIN.$module?>"><?=$this->session->userdata('Name_Module')?></a><i class="fa fa-angle-right"></i></li>
		<li><?php ($this->uri->segment(4)=='') ? print 'Add new' : print 'Edit' ?></li>
	</ul>
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
						<strong>Error!</strong> <span id="txt_error"></span>
					</div>
				</div>
				
				<!-- BEGIN FORM-->
				<form id="frmManagement" action="<?=PATH_URL_ADMIN.$module.'/save/'?>" method="post" enctype="multipart/form-data" class="form-horizontal form-row-seperated">
					<input type="hidden" value="<?=$this->security->get_csrf_hash()?>" id="csrf_token" name="csrf_token" />
					<input type="hidden" value="<?=$id?>" name="hiddenIdAdmincp" />
					<div class="form-body">

						<?php /*get avatar url*/
	                        $image_url = ( ! empty($result->avatar) ) ? get_resource_url($result->avatar) : '';
	                        $thumbnail_url = ( ! empty($result->thumbnail) ) ? get_resource_url($result->thumbnail) : null;
		                ?>
						<div class="form-group">
							<label class="control-label col-md-3">Avatar <span class="required" aria-required="true">*</span></label>
							<div class="col-md-3">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="input-group input-large">
										<div class="col-md-4 col-xs-12">
                                           <div>
                                                <input type="button" name="input_avatar" value="Select File" class="update-img-upload " id="cropContainerHeaderButton" />
                                                <!-- image thumbnail -->
												<input type="hidden" id="input_thumbnail_url" name="thumbnail_urlAdmincp">
												<!-- image original -->
												<input type="hidden" id="input_image_url" name="image_urlAdmincp">

                                            </div>
                                            <div class="img-profile">
                                            	<div id="cropic_element" style="display:none"></div>
	                                            <a class="fancybox-button" id="preview_image_fancybox" href="<?=$image_url?>">
	                                                <img id="preview_image" src="<?=$thumbnail_url?>"> 
	                                            </a>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Status</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input <?php if(isset($result->status)){ if($result->status==1){ ?>checked="checked"<?php }}else{ ?>checked="checked"<?php } ?> type="checkbox" name="status">
									</label>
								</div>
							</div>
						</div>

						
						<div class="form-group">
							<label class="control-label col-md-3">Firstname <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->firstname)) { print $result->firstname; }else{ print '';} ?>" type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter firstname..." required /></div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Lastname <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->lastname)) { print $result->lastname; }else{ print '';} ?>" type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter lastname..." required/></div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Email<span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->email)) { print $result->email; }else{ print '';} ?>" type="email" name="email" id="email" class="form-control" placeholder="Example: example@email.com..." required/></div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Password<span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="" type="password" id="password" name="password" class="form-control" placeholder="Enter password..." required/></div>
						</div>
		
						<div class="form-group">
							<label class="control-label col-md-3">Gender </label>
							<div class="col-md-9">
								<div class="radio-list">
									<label class="radio-inline">
										<div class="">
											<span><input <?php if(isset($result->gender)){ print $result->gender == 1 ? 'checked="true"': ''; } else { print 'checked="true"'; } ?> type="radio" name="gender" value="1"> Male</span>
											<span><input <?php if(isset($result->gender)){ print $result->gender == 2 ? 'checked="true"': ''; }?> type="radio" name="gender" value="2"> Female</span>
										</div>
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Phone Number </label>
							<div class="col-md-9"><input value="<?php if(isset($result->phone)) { print $result->phone; }else{ print '';} ?>" type="phone" id="phone" name="phone" class="form-control" placeholder="Example: +84111222333" required/></div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Birthday</label>
							<div class="col-md-9">
								<div class="input-group input-large date-picker input-daterange" data-date-format="yyyy-mm-dd">
									<input value="<?php if(isset($result->birthday)) { print $result->birthday; }else{ print ''; } ?>" type="birthday" name="birthday" id="birthday" class="form-control"/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Social Link</label>
							<div class="col-md-9"><input value="<?php if(isset($result->social_account)) { print $result->social_account; }else{ print '';} ?>" type="text" name="social_link" id="social_link" placeholder="https://www.facebook.com/example.social.link..." class="form-control"/></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Country</label>
							<div class="col-md-9">
								<select name="country" id="country" class="form-control" onchange="get_cities(this.value, <?=isset($result->city_id) ? $result->city_id : 0 ?>)">
									<option value="0">--Choose--</option>
									<?php 
										if($list_country){
											foreach ($list_country as $country) {?>
												<option <?php if(isset($result->country_id)){ print $result->country_id == $country->id ? 'selected' : ''; } ?> value="<?=$country->id?>"><?=$country->name.' (+'.$country->phonecode.')'?></option>
									<?php	}	
										}
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">City</label>
							<div class="col-md-9">
								<select name="city" id="city" class="form-control">
									<option value="0">--Choose--</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">User Role </label>
							<div class="col-md-9">
								<select class="form-control" name="user_role" id="user_role" onchange="showProUserType(this.value)">
									<?php
										if($list_permission){
											foreach($list_permission as $value){
									?>
									<option <?php if(isset($result->user_role)){ print $result->user_role == $value->id ? 'selected' : ''; } ?> value="<?=$value->id?>"><?=$value->name?></option>
									<?php }} ?>
								</select>
							</div>
						</div>

						<div class="form-group" id="proUserRole">
							<label class="control-label col-md-3">User Type </label>
							<div class="col-md-9">
								<div class="radio-list">
									<label class="radio-inline">
										<div class="">
											<span><input <?php if(!isset($result->user_type)){ print 'checked="true"'; }?> type="radio" name="user_type" value="0" checked="true"> No Type</span>
											<span><input <?php if(isset($result->user_type)){ print $result->user_type == 1 ? 'checked="true"': ''; } ?> type="radio" name="user_type" value="1"> Artist</span>
											<span><input <?php if(isset($result->user_type)){ print $result->user_type == 2 ? 'checked="true"': ''; } ?> type="radio" name="user_type" value="2"> Promoter</span>
											<span><input <?php if(isset($result->user_type)){ print $result->user_type == 3 ? 'checked="true"': ''; } ?> type="radio" name="user_type" value="3"> Venue</span>
										</div>
									</label>
								</div>
							</div>
						</div>		

					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="save()" type="button" class="btn green"><i class="fa fa-pencil"></i> Save</button>
								<a href="<?=PATH_URL_ADMIN.$module.'/#/back'?>"><button type="button" class="btn default">Cancel</button></a>
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
<!-- END PAGE CONTENT-->

<style type="text/css">
	#cropic_element {
		height: 200px;
		width: 200px;
	}

	#preview_image{
		margin-top: 10px;
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