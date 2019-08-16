<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovaeditor.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovamanager.js'?>"></script>

<script type="text/javascript">
$(document).ready( function(){
	$('#content').liveEdit({
		height: 350,
		css: ['<?=PATH_URL?>assets/editor/bootstrap/css/bootstrap.min.css', '<?=PATH_URL?>assets/editor/bootstrap/bootstrap_extend.css'] /* Apply bootstrap css into the editing area */,
		fileBrowser: '<?=PATH_URL?>assets/editor/assetmanager/asset.php',
		returnKeyMode: 3,
		groups: [
				["group1", "", ["Bold", "Italic", "Underline", "ForeColor"]],
				["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
				["group3", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull"]],
				["group4", "", ["Paragraph", "FontSize", "FontDialog", "TextDialog"]],
				["group5", "", ["LinkDialog", "ImageDialog", "TableDialog"]],
				["group6", "", ["Undo", "Redo", "FullScreen", "SourceDialog"]]
				] /* Toolbar configuration */
	});
	$('#content').data('liveEdit').startedit();
	
});

function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#content').val($('#content').data('liveEdit').getXHTMLBody());
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
						<div class="form-group">
							<label class="control-label col-md-3">Name job <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->name)) { print $result->name; }else{ print '';} ?>" type="text" name="name" id="name" class="form-control" placeholder="Enter name..." required /></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Job Id <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->job_id)) { print $result->job_id; }else{ print '';} ?>" type="text" name="job_id" id="job_id" class="form-control" placeholder="Enter job_id..." required /></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Link <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->link)) { print $result->link; }else{ print '';} ?>" type="text" name="link" id="link" class="form-control" placeholder="Enter link..." required /></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Location <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->location)) { print $result->location; }else{ print '';} ?>" type="text" name="location" id="location" class="form-control" placeholder="Enter location..." required /></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Wage <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->wage)) { print $result->wage; }else{ print '';} ?>" type="text" name="wage" id="wage" class="form-control" placeholder="Enter wage..." required /></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3">Content <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9">
							<textarea data-required="1" cols="" rows="8" 
												name="content" 
												id="content"><?php echo (isset($result->content) ? $result->content : '') ?></textarea>
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