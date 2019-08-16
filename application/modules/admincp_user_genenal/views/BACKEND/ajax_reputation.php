
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovaeditor.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovamanager.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/js/admin/'?>jquery.slugit.js"></script>

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
		<li>Reputation</li>
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
							<div class="col-md-12">
								<div class="form-group">
								<label class="control-label col-md-2">Point_Login</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$point_daily_login?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Point_like</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$point_like?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Point_Comment</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$point_comment?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Point_Share</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$point_share?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Add_Post</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$add_post?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Add_Event</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$add_event?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Points from other users</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?=$other_user?>">
											</label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-2">Total</label>
									<div class="col-md-10">
										<div class="checkbox-list">
											<label class="checkbox-inline">
												<input class="center" value="<?php echo($point_daily_login + $point_like + $point_comment + $point_share + $add_post + $add_event + $other_user)?>">
											</label>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="form-actions fix-bg">
						<div class="row">
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>

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

<!-- END PAGE CONTENT-->
