<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovaeditor.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/editor/scripts/innovamanager.js'?>"></script>
<script type="text/javascript" src="<?=PATH_URL.'assets/js/admin/'?>jquery.slugit.js"></script>
<?php
	if(isset($this->lang->languages)){
		$all_lang = $this->lang->languages;
	}else{
		$all_lang = array(
			'' => ''
		);
	}
?>
<script type="text/javascript">
$(document).ready( function(){
	<?php foreach($all_lang as $key=>$val){ ?>
	$("#title<?php ($key!='') ? print '_'.$key : print '' ?>Admincp").slugIt({
		events: 'keyup blur',
		output: '#slug<?php ($key!='') ? print '_'.$key : print '' ?>Admincp',
		map: {'!':'-'},
		space: '-'
	});
	//FOR WYSIWYG content
	$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').liveEdit({
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
	$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').data('liveEdit').startedit();
	//End FOR WYSIWYG content
	<?php } ?>
});

function save(){
	var options = {
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:       showResponse  // post-submit callback 
    };
	<?php foreach($all_lang as $key=>$val){ ?>
	$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').val($('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').data('liveEdit').getXHTMLBody());
	<?php } ?>
	$('#frmManagement').ajaxSubmit(options);
}

function showRequest(formData, jqForm, options) {
	var form = jqForm[0];
	<?php if(empty($id)) { ?>
	if (
		form.thumbnail_urlAdmincp.value == '' || 
		form.image_urlAdmincp.value == ''
	) {
		$('#txt_error').html('Please Upload image.');
		show_perm_denied();
		return false;
	}
	<?php } ?>
	<?php foreach($all_lang as $key=>$val){ ?>
	if (
		form.title<?php ($key!='') ? print '_'.$key : print '' ?>Admincp.value == '' || 
		form.slug<?php ($key!='') ? print '_'.$key : print '' ?>Admincp.value == '' || 
		form.description<?php ($key!='') ? print '_'.$key : print '' ?>Admincp.value == '' || 
		$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').val() == '<br />' || 
		$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').val() == '' || 
		$('#content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').val() == "\n"
	) {
		$('#txt_error').html('Please enter information.');
		show_perm_denied();
		return false;
	}
	<?php } ?>
//
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
				// $('.fancybox-button').attr('href',res[1]);
				// $('.fancybox-button img').attr('src',data);
				$('.fileinput-filename').html('');
				$('.fileinput').removeClass('fileinput-exists');
				$('.fileinput').addClass('fileinput-new');
			});
		}
		show_perm_success();
		<?php } ?>
	}
	
	if(responseText[0]=='error-image'){
		$('#txt_error').html('Only upload image.');
		show_perm_denied();
		return false;
	}
	
	<?php foreach($all_lang as $key=>$val){ ?>
	if(responseText[0]=='error-title<?php ($key!='') ? print '-'.$key : print '' ?>-exists'){
		$('#txt_error').html('Title<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?> already exists.');
		show_perm_denied();
		$('#title<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').focus();
		return false;
	}
	
	if(responseText[0]=='error-slug<?php ($key!='') ? print '-'.$key : print '' ?>-exists'){
		$('#txt_error').html('Slug<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?> already exists.');
		show_perm_denied();
		$('#slug<?php ($key!='') ? print '_'.$key : print '' ?>Admincp').focus();
		return false;
	}
	<?php } ?>

	if(responseText[0]=='permission-denied'){
		$('#txt_error').html('Permission denied.');
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
							<label class="control-label col-md-3">Status</label>
							<div class="col-md-9">
								<div class="checkbox-list">
									<label class="checkbox-inline">
										<div class="checkbox"><span><input <?php if(isset($result->status)){ if($result->status==1){ ?>checked="checked"<?php }}else{ ?>checked="checked"<?php } ?> type="checkbox" name="statusAdmincp"></span></div>
									</label>
								</div>
							</div>
						</div>


						<?php /*get avatar url*/
	                        $image_url = ( ! empty($result->image) ) ? get_resource_url($result->image) : '';
	                        $thumbnail_url = ( ! empty($result->thumbnail) ) ? get_resource_url($result->thumbnail) : null;
		                ?>
						<div class="form-group">
							<label class="control-label col-md-3">Image <span class="required" aria-required="true">*</span></label>
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
							<label class="control-label col-md-3">Category <span class="required" aria-required="true">*</span></label>
							<div class="col-md-9">
								<select onChange="getPerm(this.value)" class="form-control" name="categoryAdmincp" id="groupAdmincp">
									<?php
										if(!empty($list_category)){
											foreach($list_category as $value){
									?>
									<option 
										<?php print ( isset($result->category_id) && ($result->category_id == $value->id) ) ? 'selected' : ''; ?> 
										value="<?=$value->id?>">
											<?=$value->name?>
									</option>
									<?php } } ?>
								</select>
							</div>
						</div>
						
						<?php if(isset($this->lang->languages)){ ?>
						<div class="form-group last" style="padding-bottom:0;">
							<label class="control-label col-md-3">Language</label>
							<div class="col-md-9">
								<ul class="nav nav-tabs">
									<?php $flag = false; ?>
									<?php foreach ($all_lang as $key => $value) { ?>
										<li class="<?= $flag == false ? 'active' : ''; $flag = true; ?>"><a href="#<?=$value?>" data-toggle="tab" aria-expanded="true"><?=ucwords($value)?></a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<?php } ?>
						<div class="tab-content">
							<?php $flag = false; ?>
							<?php
								foreach ($all_lang as $key => $value){
									$title = ($key!='') ? 'title_'.$key : 'title';
									$slug = ($key!='') ? 'slug_'.$key : 'slug';
									//description, content
							?>
							<div class="tab-pane fade <?=$flag == false ? 'active in' : ''; $flag = true; ?>" id="<?=$value?>">
								<div class="form-group">
									<label class="control-label col-md-3">
										Title<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?> 
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-9">
										<input data-required="1" type="text" class="form-control" 
											name="title<?php ($key!='') ? print '_'.$key : print '' ?>Admincp" 
											id="title<?php ($key!='') ? print '_'.$key : print '' ?>Admincp"
											value="<?php print (isset($result->data_lang[$key]->title) ? $result->data_lang[$key]->title : '') ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">
										Slug<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?> 
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-9">
										<input data-required="1" type="text" class="form-control" 
											name="slug<?php ($key!='') ? print '_'.$key : print '' ?>Admincp" 
											id="slug<?php ($key!='') ? print '_'.$key : print '' ?>Admincp" 
											value="<?php print (isset($result->data_lang[$key]->slug) ? $result->data_lang[$key]->slug : '') ?>"
											/>
											</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">
										Description<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?> 
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-9">
										<textarea data-required="1" cols="" rows="6"
											name="description<?php ($key!='') ? print '_'.$key : print '' ?>Admincp"><?php print (isset($result->data_lang[$key]->description) ? $result->data_lang[$key]->description : '') ?></textarea>
									</div>
								</div>
								<div class="form-group last">
									<label class="control-label col-md-3">
										Content<?php ($key!='') ? print ' ('.mb_strtoupper($key).')' : print '' ?>
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="col-md-9">
										<textarea data-required="1" cols="" rows="8" 
												name="content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp" 
												id="content<?php ($key!='') ? print '_'.$key : print '' ?>Admincp"><?php print (isset($result->data_lang[$key]->content) ? $result->data_lang[$key]->content : '') ?></textarea>
									</div>
								</div>
							</div>
							<?php } ?>
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
            onAfterImgCrop:function(){ appendAward(); },
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