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
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/banker-page.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/responsive.css')?>">
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery-3.2.1.js')?>"></script>
    
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
                    <li id="loan_accessibility" class="loan_accessibility_detail"><a href="<?=PATH_URL.'admin/loan_list'?>"  ><i class="icon icon-accessibility"></i>Loan Accessibility</a></li>                  
                    <li id="btn_export"><a href="#"><i class="icon icon-support"></i>Export Report</a></li>
                    <li class="loan_accessibility_detail"><a href="<?=PATH_URL.'admin/import_student_list'?>"  ><i class="icon icon-accessibility"></i>Import Student List</a></li>
                    <li id="export_student_list"><a href="#"><i class="icon icon-support"></i>Export Student List</a></li>
                </ul>
            </div>
            <div class="download_file_section" style="display:none;"></div>
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
<script>
    $(document).ready(function(){
        //Export Report Loan
        $('#btn_export').bind('click',function(){

            $.post('<?=PATH_URL?>export_user/ajax_export',{ 
                },function(data){
                    var message = data.message;
                    var status = data.status;
                    $('.msg-fail').hide();
                    $('.msg-success').hide();
                    
                    if(data && status == 'success'){
                        var file_name = data.file_name;
                        console.log(file_name);
                        if(file_name)
                            $('.download_file_section').append("<iframe src='<?=PATH_URL?>export_user/export_download_file/export_user/"+file_name+"' style='display: none;'></iframe>");
                        
                        $('.msg-success').show();
                        $('.msg-success').html(message);
                    }else if(status == 'fail'){
                        $('.msg-fail').show();
                        $('.msg-fail').html(message);
                    }   
                },'JSON');
        });
        //Export Student List
        $('#export_student_list').bind('click',function(){

            $.post('<?=PATH_URL?>export_user/student_list_ajax_export',{ 
                },function(data){
                    console.log(data);
                    var message = data.message;
                    var status = data.status;
                    $('.msg-fail').hide();
                    $('.msg-success').hide();
                    
                    if(data && status == 'success'){
                        var file_name = data.file_name;
                        console.log(data);
                        if(file_name)
                            $('.download_file_section').append("<iframe src='<?=PATH_URL?>export_user/export_student_list_download_file/export_student_list/"+file_name+"' style='display: none;'></iframe>");
                        
                        $('.msg-success').show();
                        $('.msg-success').html(message);
                    }else if(status == 'fail'){
                        $('.msg-fail').show();
                        $('.msg-fail').html(message);
                    }   
                },'JSON');
        });
    });
</script>
<?php show_options_setttings('script_body_end');?>
</body>
</html> 