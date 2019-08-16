<script type="text/javascript">

function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) {
	
}

$(document).ready(function() {
	
});
function sendmail(){
	var id = $('#hiddenIdAdmincp').val();
	var checkedradio = $('[name="radio"]:radio:checked').val();
	$.ajax
	(
		{
			type : 'POST',
			url: '<?=PATH_URL_ADMIN?>admincp_user_genenal/ajaxSaveAndSendEmail',
			data:
			{
				'id': id, 
				'checkedradio': checkedradio
			},
			success: function(data)
			{
				myObj = JSON.parse(data);
				if(myObj.success==true){
					alert('NOTIFICATION EMAIL HAS BEEN SENT TO THE USER');
					setTimeout(function(){window.location.href="<?=PATH_URL_ADMIN?>admincp_user_genenal"},500);							
				}else{
					alert("fail!");
				}
			}
		}
	)
}
</script>

<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovaeditor.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovamanager.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/js/admin/'?>jquery.slugit.js"></script>

<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"><?=$this->session->userdata('Name_Module')?></h3>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li><i class="fa fa-home"></i><a href="<?=PATH_URL_ADMIN?>">Home</a><i class="fa fa-angle-right"></i></li>
		<li><a href="<?=PATH_URL_ADMIN.'admincp_user_genenal'?>"><?=$this->session->userdata('Name_Module')?></a><i class="fa fa-angle-right"></i></li>
		<li><?php print 'Edit' ?></li>
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
				<form id="frmManagement" action="<?=PATH_URL_ADMIN.'admincp_user_genenal'.'/save/'?>" method="post" enctype="multipart/form-data" class="form-horizontal form-row-seperated">
					<input type="hidden" value="<?=$this->security->get_csrf_hash()?>" id="csrf_token" name="csrf_token" />
					<input type="hidden" value="<?=$id?>" name="hiddenIdAdmincp" id = "hiddenIdAdmincp"/>
					<div class="form-body">
						<div class="">
							<div class="form-group">
							<input type="hidden" value="<?=$result[0]->id?>" name="hiddenIdAdmincp" />
							<label class="control-label col-md-3"> <span class="required" aria-required="true"></span></label>
								<div class="col-md-9">
									<div class=" mg-t30">
										<div class="" style="veritcal-align:top">
											<input type="radio" name="radio"
											<?php if ($result[0]->user_type_id == 2) echo "checked";?>											
											value="2" class="artists" />&nbsp Artists
										</div>
										<div class="" style="veritcal-align:top">
											<input type="radio" name="radio" 
											<?php if ($result[0]->user_type_id == 3) echo "checked";?>
											value="3" class="labels" />&nbsp Labels
										</div>
										<div class="" style="veritcal-align:top">
											<input type="radio" name="radio" 
											<?php if ($result[0]->user_type_id == 1) echo "checked";?>
											value="1" class="venues" />&nbsp Venues
										</div>
										<div class="" style="veritcal-align:top">
											<input type="radio" name="radio" 
											<?php if ($result[0]->user_type_id == 4) echo "checked";?>
											value="4" class="promoters" />&nbsp Promoters 
										</div>
										<div class="" style="veritcal-align:top">
											<input type="radio" name="radio"
											<?php if ($result[0]->user_type_id == 5) echo "checked";?>											
											value="5" class="marketers" />&nbsp Marketers
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class = "">
							<div class="form-group">
								<label class="control-label col-md-3">User Email <span class="required" aria-required="true">*</span></label>
								<div class="col-md-9">
									<div class ="form-control">
										<label><?=$result[0]->email?></label>
									</div>
								</div>
							</div>
						</div>
						<div class = "center">
							<label>NOTIFICATION EMAIL WILL SEND TO THE USER</label>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="sendmail()" type="button" class="btn blue"><i class="fa fa-pencil"></i> Save</button>
								<a href="<?=PATH_URL_ADMIN.'admincp_user_genenal'.'/#/back'?>"><button type="button" class="btn default">Cancel</button></a>
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