<div id="personal_info_edit">
  <div class="moomo-style-form">
    <div class="full-width user-detail">
      <div class="wrap-content">
        <h2 class="orange-title">Personal Info</h2>  
        <div class="avatar-user">
            <?php 
              $img = 'user-icon.png';
              if(!empty($user_info->image)){
                  $img = $user_info->image;
              }
            ?>
            <div class="img-avatar">
                <img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-avatar">
            </div>
            <form id="uploadForm" action="<?=PATH_URL.'student/upload_avatar'?>" method="post">
              <input name="userImage" type="file" class="inputFile" /><br/>
              <input id="change-avatar" type="submit" value="Change" class="btnSubmit btn hidden-input " />
            </form>
        </div>
        <div class="form">
          <div class="content-form">
            <form class="sign-up" method="POST">            
              <!-- STEP 3 -->
              <div class="step-3">               
                <div class="fields">
                  <p>
                    <label>First Name<span class="required"> *</span></label>
                    <input id="firstname"  name="first-name" type="text" value="<?=(!empty($user_info->firstname)?$user_info->firstname:'')?>" placeholder="First Name">
                    <span class="show_error"></span>
                  </p>
                  <p>
                    <label>Last Name<span class="required"> *</span></label>
                    <input id="lastname" name="last-name" type="text" value="<?=(!empty($user_info->lastname)?$user_info->lastname:'')?>"  placeholder="Last Name">
                    <span class="show_error"></span>
                  </p>
                  <p>
                    <label>Email <span class="required"> *</span></label>
                    <input disabled=""  id="email" name="email" type="text" value="<?=(!empty($user_info->email)?$user_info->email:'')?>"  placeholder="">
                    <span class="show_error"></span>
                  </p>
                  <p>
                    <label>Mobile Number</label>
                    <input disabled="" id="phone" type="text" value="<?=(!empty($user_info->phone)?substr($user_info->phone,0,2).'-'.substr($user_info->phone,2,strlen($user_info->phone)-2):'')?>" >
                  </p>                
                  <p>
                    <label>Nationality</label>
                     <select id="nationality" name="nationality">
                        <?php if(!empty($national)){
                                foreach ($national as $key => $value) {
                        ?>
                        <option <?=($user_info->nationality==$value->id?'selected':'')?> value="<?=$value->id?>" ><?=$value->national?></option>
                        <?php }}?>
                    </select>
                    <span class="show_error"></span>
                  </p>
                  <p>
                    <label>Gender</label>
                      <select id="gender" name="gender">                          
                          <option <?=(!empty($user_info->gender)?($user_info->gender==1?'selected':''):'')?> value="1">Male</option>
                          <option <?=(!empty($user_info->gender)?($user_info->gender==2?'selected':''):'')?> value="2">Female</option>
                      </select>
                    <span class="show_error"></span>
                  </p>
                  <p class="birthday">
                    <span class="date">
                      <label>Date of Birth<span class="required"> *</span></label>
                      <input id="birthday"  name="birthday" class="datepicker" value="<?=(!empty($user_info->birthday)?date('m/d/Y',strtotime($user_info->birthday)):'')?>" >
                      <span class="show_error"></span>  
                    </span>                 
                  </p>
                  <p>                 
                    <label>PIM Batch No.</label>
                    <select id="batch_no" name="batch_no">
                        <option value="" ></option>
                        <?php if(!empty($pim)){
                                foreach ($pim as $key => $value) {
                        ?>
                        <option <?=($user_info->batch_no==$value->id?'selected':'')?> value="<?=$value->id?>" ><?=$value->pim?></option>
                        <?php }}?>
                    </select>
                  </p>
                  <p>
                    <label>No. & Street Name </label>
                    <input id="street" name="address-1" type="text" value="<?=(!empty($user_info->street)?$user_info->street:'')?>" placeholder="XX - street address">
                  </p>
                  <p>
                    <label>Unit No</label>
                    <input id="unit" name="unit" type="text" value="<?=(!empty($user_info->unit)?$user_info->unit:'')?>" placeholder="##">
                  </p>
                  <p>
                    <label>Building Name</label>
                    <input id="building_name" name="building-name" type="text" value="<?=(!empty($user_info->building_name)?$user_info->building_name:'')?>" placeholder="Building Name">
                  </p>
                  <p>
                    <label>Postal Code<span class="required"> *</span></label>
                    <input id="postal_code"   name="post-code" type="number" value="<?=(!empty($user_info->postal_code)?$user_info->postal_code:'')?>" placeholder="-------">
                    <span class="show_error"></span>
                  </p>
                </div>
              </div>
              <p class="sign-up-row">
                  <button id="update-user-info" class="btn">Save Changes </button>                 
                </p>    
               <div id="alert_box" class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <span></span>
              </div>        
            </form>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?=get_resource_url('statics/css/upload_styles.css')?>">
<script type="text/javascript" src="<?=get_resource_url('assets/js/upload_avatar.js')?>"></script>