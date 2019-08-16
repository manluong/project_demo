
<?php if(!empty($data)){?>
<div onclick="go_not_student()" class="alert-notifi active">
    <i class="icon icon-notification"></i>
    <div class="content-notification">
     <ul>
        <?php 
        foreach ($data as $k => $v) {
            if($v->type == 'remark'){
                echo '<li>Your AIP submission on '.$this->model->getLoanDate($v->loan_id).' has new remark</li>';
            }else{
                echo '<li>Your AIP submission on '.$this->model->getLoanDate($v->loan_id).' has been confirmed</li>';
            }
        }
        ?>
    </ul>    
    </div>
</div>
<?php }?>
<script type="text/javascript">
    function go_not_student(){
        window.location.href = root+'student/notification';
    }
</script>
