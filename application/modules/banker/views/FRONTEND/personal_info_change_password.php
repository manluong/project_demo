<div id="personal_info_change_password">
    <div class="moomo-style-form">
    <div class="full-width">
        <div class="wrap-content">
            <h2 class="orange-title">Change Password</h2>  
            <div class="form">
                <div class="content-form">
                    <form class="fofgot-password">  
                        <div class="step-3">                       
                            <div class="fields">
                                <p>
                                    <label>Current Password *</label>
                                    <input id="oldpass" type="password" name="old-password" placeholder="Please enter your current password" class="required">
                                </p>
                                <p>
                                    <label>New Password *</label>
                                    <input id="pass" type="password" name="old-password" class=" check-strong-password">
                                    <ul class="display-suggestion hide">
                                        <li id="length">Password must be minimum 8 characters in length</li>
                                        <li id="uppercase">Password must have at least 1 Uppercase Letter</li>
                                        <li id="lowercase">Password must have at least 1 Lowercase Letter</li>
                                        <li id="number">Password must have at least 1 Number</li>
                                    </ul>
                                </p>
                                <p>
                                    <label>Repeat New Password *</label>
                                    <input id="repass" type="password" name="new-password" placeholder="Please retype your password">
                                </p>
                                <p>                                 
                                    <button id="submit_change_pass" class="submit btn">Change Password</button>
                                </p>
                            </div>
                        </div><!-- step 3-->
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