<script type="text/javascript">
function resetPerm(){
	$.post('<?=PATH_URL_ADMIN.$module.'/reset_permission/'?>',{
		id: <?=$id?>,
		csrf_token: token_value,
		permDefault: $('#perm_group').val()
	},function(data){
		responseText = data.split(".");
		token_value  = responseText[1];
		$('#csrf_token').val(token_value);
		if(responseText[0]=='success'){
			show_perm_success();
		}
	});
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
	if(form.usernameAdmincp.value == ''){
		$('#txt_error').html('Please enter information!!!');
		$('#loader').fadeOut(300);
		show_perm_denied();
		return false;
	}
	<?php if($id==0){ ?>
	if(form.passAdmincp.value == ''){
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
	
	if(responseText[0]=='error-username-exists'){
		$('#txt_error').html('Username already exists.');
		show_perm_denied();
		$('#usernameAdmincp').focus();
		return false;
	}
}

function getPerm(val,isUpdate){
	if(isUpdate==0){
		$.get('<?=PATH_URL_ADMIN.'admincp_accounts/ajaxPerm/'?>'+val, function(data) {
			$('#ajax_perm').html(data);
		});
	}else{
		$.post('<?=PATH_URL_ADMIN.'admincp_accounts/ajaxPerm/'?>'+val,{
			'perm': isUpdate,
			csrf_token: token_value
		},function(data){
			$('#ajax_perm').html(data);
		});
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
							<label class="control-label col-md-3">Status</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<input <?php if(isset($result->status)){ if($result->status==1){ ?>checked="checked"<?php }}else{ ?>checked="checked"<?php } ?> type="checkbox" name="statusAdmincp">
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Group <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9">
								<select onChange="getPerm(this.value)" class="form-control" name="groupAdmincp" id="groupAdmincp">
									<?php
										if($list_group){
											foreach($list_group as $value){
									?>
									<option <?php if(isset($result->group_id)){ if($result->group_id==$value->id){ ?>selected="selected"<?php }} ?> value="<?=$value->id?>"><?=$value->name?></option>
									<?php }} ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Username <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->username)) { print $result->username; }else{ print '';} ?>" type="text" name="usernameAdmincp" id="usernameAdmincp" class="form-control"/></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Password <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9"><input value="" type="password" name="passAdmincp" class="form-control"/></div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Image Url <span class="required" aria-required="true"></span></label>
							<div class="col-md-9"><input value="<?php if(isset($result->image_url)) { print $result->image_url; }else{ print '';} ?>" type="url" name="image_url" class="form-control"/></div>
						</div>
						<div class="form-group last">
							<label class="control-label col-md-3">Permission <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9" id="ajax_perm">
								<?php if($id==0){ ?>
								<script type="text/javascript">$(document).ready(function(){ getPerm($('#groupAdmincp').val(),0); })</script>
								<?php }else{ ?>
								<script type="text/javascript">$(document).ready(function(){ getPerm($('#groupAdmincp').val(),'<?=$result->permission?>'); })</script>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="save()" type="button" class="btn green"><i class="fa fa-pencil"></i> Save</button>
								<?php if($id!=0){ ?>
								<button onclick="resetPerm()" type="button" class="btn yellow"><i class="fa fa-refresh"></i> Reset Permission</button>
								<?php } ?>
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