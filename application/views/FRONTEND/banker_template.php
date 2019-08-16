<?php
// show options setting
$setting_mapping = null;
function show_options_setttings($key)
{
    if (empty($setting_mapping)) {
        $setting_mapping = modules::run('home/get_admin_settings');
    }
    $value = isset($setting_mapping[$key]) ? $setting_mapping[$key] : '';

    echo $value;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="author" content="">
    <title>
        <?php show_options_setttings('meta_title');?>
    </title>
    <meta name="title" content="<?php show_options_setttings('meta_title');?>">
    <meta name="description" content="<?php show_options_setttings('meta_decripstion');?>">
    <meta name="keyworks" content="<?php show_options_setttings('meta_keyworks');?>">
    <link rel='icon' href='<?=get_resource_url('assets/images/mega/favicon.ico')?>' type='image/jpg'> 
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/jquery-ui.min.css')?>"> 
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/banker-page.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/responsive.css')?>">
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery-3.2.1.js')?>"></script>
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery-ui.min.js')?>"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">
    <script>
        var root = '<?=PATH_URL?>'
    </script>
    <?php show_options_setttings('script_head');?>    
</head>
<body id="banker-tempate">
	<?php show_options_setttings('script_body_begin');?>
<div class="full-width bg-body db-home">
        <div class="header">
            <div class="header-left">
                <div class="icon-menu show-mobile">
                    <img src="<?=get_resource_url('assets/images/tiq/icon-menu.png')?>">
                </div>
                <div class="logo">
                    <img src="<?=get_resource_url('assets/images/tiq/logo-min.png')?>">
                </div>
                
            </div>
            <div class="header-right">
                <?=modules::run('banker/notification_header')?>
                <div class="avatar show-desktop">
                    <?=modules::run('banker/user_avatar')?>
                    <name>Hello, <span class="uer-name">
                    <?=((!empty($this->session->userdata('user_first_name')))?$this->session->userdata('user_first_name'):'')?>
                        
                    </span></name>
                    <div class="action-of-user">
                        <a href="<?=PATH_URL.'banker/personal_info'?>" class="view-profile">View Profile</a>
                        <a href="#" class="log-out">Log out</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-content">
            <div class="sidebar">
                 <div class="show-mobile">
                    <div class="close"> <img src="<?=PATH_URL.'assets/images/tiq/icon-close.png'?>" class="user-avatar"></div>
                    <div class="user">                       
                        <img src="<?=PATH_URL.'assets/images/tiq/user-icon.png'?>" class="user-avatar">
                        <info>
                            <name>User Name</name>  
                            <a href="#" class="log-out">Log out</a>                         
                        </info>
                    </div>
                </div>
                <ul class="menu-sidebar">
                    <li id="notification"><a href="<?=PATH_URL.'banker/notification'?>"><i class="icon icon-notification"></i>Notification</a></li>
                    <li id="personal_info" class="personal_info_edit personal_info_change_password"> <a href="<?=PATH_URL.'banker/personal_info'?>"><i class="icon icon-personal-info"></i>Personal Info</a> </li>                   
                    <li id="loan_accessibility" class="loan_accessibility_detail"><a href="<?=PATH_URL.'banker/loan_accessibility'?>"  ><i class="icon icon-accessibility"></i>Loan Accessibility</a></li>                  
                    
                    <!-- <li id="support"><a href="<?=PATH_URL.'banker/support'?>"><i class="icon icon-support"></i>Support</a>
                        
                    </li> -->
                </ul>
            </div>
            <div class="content">
                 <?php echo $content?>
            </div>
        </div><!-- content -->
    <div class="footer">
            
    </div>
</div>

<script>
    var root = '<?=PATH_URL?>'
</script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-responsive.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/style.js')?>"></script>
<script type="text/javascript" src="<?=get_resource_url('assets/js/ndt-banker.js')?>"></script>

<?php show_options_setttings('script_body_end');?>

</body>
</html> 