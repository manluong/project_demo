<style type="text/css" media="screen">
	.input-banker{
		display: none;
	}
</style>

<script type="text/javascript">

function add_student_loan(){
	$('#wap_student_loan').show();
}

function submit_student_loan(){

	var options = {
		beforeSubmit:  sl_showRequest,  // pre-submit callback 
		success:       sl_showResponse  // post-submit callback 
    };
   
	$('#student_loan_form').ajaxSubmit(options);
}

function sl_showRequest(formData, jqForm, options){
	return true;
}

function sl_showResponse(responseText, statusText, xhr, $form){
	if(responseText=='success'){
		$('#wap_student_loan').hide();
		document.getElementById("student_loan_form").reset();
		show_perm_success();
	}
}

function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) {
	var form = jqForm[0];
	if(form.name.value == ''){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
	<?php if($id==0){ ?>
	if(form.name.value == ''){
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
}

function update_student_loan(id, name){
	var content = $("#"+id+name).attr('value');
	
	$.post(root+module+'/update_student_loan',{
		id : id,
		content : content,
		name : name
	},function(data){
		if(data=='success'){
			$("#"+"td_edit_"+id+"_"+name).html(content);
			$("#"+id+name).parent().hide();
			$("#"+id+name).parent().prev().show();
		}else{
			alert('update fail');
		}
	});
}

$(document).ready(function(){
	var type = $("#type").val();
    	console.log(type);
    if(type == 1){
		$(".input-student").show();
		$(".input-banker").hide();
	}
	if(type == 2){
		$(".input-banker").hide();
		$(".input-student").show();
	}
	if(type == 3){
		$(".input-banker").hide();
		$(".input-student").hide();
	}
	
  	$("#type").change(function(){
    	var type = $("#type").val();
    	console.log(type);
    	if(type == 1){
    		$(".input-student").show();
    		$(".input-banker").hide();
    	}
    	if(type == 2){
    		$(".input-banker").hide();
    		$(".input-student").show();
    	}
    	if(type == 3){
    		$(".input-banker").hide();
    		$(".input-student").hide();
    	}
  	});

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
	                        $image_url = ( ! empty($result->image) ) ? get_resource_url($result->image) : '';
	                        $thumbnail_url = ( ! empty($result->image) ) ? get_resource_url($result->image) : null;
		                ?>
		                <!-- UPLOAD AVATAR -->
						<!-- <div class="form-group">
							<label class="control-label col-md-3">Student avatar <span class="required" aria-required="true">*</span></label>
							<form id="uploadForm" action="<?=PATH_URL.'student/upload_avatar'?>" method="post">
										         
							<div class="col-md-3">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="input-group input-large">
										<div class="col-md-4 col-xs-12">
						                                           <div>
						                                                <input type="button" name="input_avatar" value="Select File" class="update-img-upload " id="cropContainerHeaderButton" />
						                                                image thumbnail
												<input type="hidden" id="input_thumbnail_url" name="thumbnail_urlAdmincp">
												image original
												<input type="hidden" id="input_image_url" name="image_urlAdmincp">
						
						                                            </div>
						                                            <div class="img-profile">
						                                            	<div id="cropic_element" style="display:none"></div>
							                                            <a class="fancybox-button" id="preview_image_fancybox" href="<?=$image_url?>">
							                                                <img id="preview_image" height=200 width="auto" src="<?=$thumbnail_url?>"> 
							                                            </a>
						                                            </div>
						                                        </div>
									</div>
								</div>
							</div>
						</div> -->
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
							<label class="control-label col-md-3">Type</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<select name="type" id="type">
										  <option <?=((!empty($result->type) && $result->type==1)?'selected':'')?> value="1">Student</option>
										  <option <?=((!empty($result->type) && $result->type==2)?'selected':'')?> value="2">Banker</option>
										  <option <?=((!empty($result->type) && $result->type==3)?'selected':'')?> value="3">Admin</option>
										</select>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">First Name </label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->firstname)) ? $result->firstname : ''?>"" type="text" name="firstname">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Last Name </label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->lastname)) ? $result->lastname : ''?>"" type="text" name="lastname">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">PIM Batch No. </label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->batch_no)) ? $result->batch_no : ''?>"" type="text" name="batch_no">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Email</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->email)) ? $result->email : ''?>"" type="text" name="email">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Phone</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->phone)) ? $result->phone : ''?>"" type="text" name="phone">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Birthday</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->birthday)) ? $result->birthday : ''?>"" type="text" name="birthday">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Password</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="" type="text" name="password">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Street</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->street)) ? $result->street : ''?>"" type="text" name="street">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Unit</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->unit)) ? $result->unit : ''?>"" type="text" name="unit">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Building Name</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->building_name)) ? $result->building_name : ''?>"" type="text" name="building_name">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Postal Code</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->postal_code)) ? $result->postal_code : ''?>"" type="text" name="postal_code">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-student">
							<label class="control-label col-md-3">Nationality</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->nationality)) ? $result->nationality : ''?>"" type="text" name="nationality">
									</label>
								</div>
							</div>
						</div>	
						<div class="form-group input-student">
							<label class="control-label col-md-3">Gender</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<select id="gender" name="gender">
					                        <option <?=((!empty($result->gender) && $result->gender==1)?'selected':'')?> value="1">Male</option>
					                        <option <?=((!empty($result->gender) && $result->gender==2)?'selected':'')?> value="2">Female</option>
					                    </select>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group input-banker">
							<label class="control-label col-md-3">Bank</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input value="<?php echo (isset($result->bank)) ? $result->bank : ''?>"" type="text" name="bank">
									</label>
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
		height: 500px;
		width: 350px;
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