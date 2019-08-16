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
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/style.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=get_resource_url('assets/scss/responsive.css')?>">
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery-3.2.1.js')?>"></script>  
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery-ui.min.js')?>"></script>  
    <script type="text/javascript" src="<?=get_resource_url('assets/js/jquery.simple.timer.js')?>"></script>    
    <script type="text/javascript" src="<?=get_resource_url('assets/js/style.js')?>"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">  
   
    <?php show_options_setttings('script_head');?>
   
</head>
<body id="home" class="position-relative" data-spy="scroll" data-target="#collapsibleNavbar" data-offset="100">
    <?php show_options_setttings('script_body_begin');?>
    <header class="position-relative">
        
    </header>


   <?=$content?>


    <section class="footer box_footer">
        
    </section>
<a href="#home">
    <i class="icon-top" id="icon-scroll-top"></i>
</a>
<script>
    var root = '<?=PATH_URL?>'
</script>

<script type="text/javascript">
    /*BEGIN: DEBUG*/
    function pr(message) {
        if (console && console.log) {
            console.log(message);
        }
    }
    /*END: DEBUG*/
</script>
<script type="text/javascript">
    <?php if (isset($select_text)) {?>
        $(document).ready(function() {
            setTimeout(function(){
                <?php echo $select_text; ?>
                
            },300);
            //window.history.pushState('', '','<?=PATH_URL?>');
        });
    <?php }?>
</script>
<?php show_options_setttings('script_body_end');?>

</body>
</html>