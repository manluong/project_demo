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
              <input id="change-avatar" type="submit" value="Change" class="btnSubmit btn hidden-input" />
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
                    <label>Bank</label>
                    <input id="bank"   name="bank" type="text" value="<?=(!empty($user_info->bank)?$user_info->bank:'')?>" >
                    <span class="show_error"></span>
                  </p>
                </div>
              </div>
              <p class="sign-up-row">
                  <a href="#" id="banker-update-profile" class="max-width-350 btn">Save Changes</a>                               
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