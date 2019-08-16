<script type="text/javascript">

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
function saveSetBackground(){
    var result = 0;
	var id = $('#hiddenIdAdmincp').val();
	if(document.getElementById('status').checked === true){
      	result = '1';
	}
    $.ajax
	(
		{
			type : 'POST',
			url: '<?=PATH_URL_ADMIN?>admincp_background/ajaxSetBackground',
			data:
			{
				'id': id,
				'status': result
			},
			success: function(data)
			{
				myObj = JSON.parse(data);
				if(myObj.success==true){
					alert('Success!');
					setTimeout(function(){window.location.href="<?=PATH_URL_ADMIN?>admincp_background"},500);							
				}else{
					alert("fail!");
				}
			}
		}
	)
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
					<input type="hidden" value="<?=$id?>" name="hiddenIdAdmincp" id="hiddenIdAdmincp" />
					<div class="form-body">

						<?php /*get avatar url*/
	                        $image_url = ( ! empty($result->background) ) ? get_resource_url($result->background) : '';
	                        $thumbnail_url = ( ! empty($result->background) ) ? get_resource_url($result->background) : null;

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
	                                                <img id="preview_image" height=500 width=350 src="<?=$thumbnail_url?>"> 
	                                            </a>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Install</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input <?=( ! empty($result->status) && ($result->status == 1) ) ? 'checked' : ''?> id="status" type="checkbox" name="status">
									</label>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="saveSetBackground()" id="btn" type="button" class="btn green"><i class="fa fa-pencil"></i> Save</button>
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
