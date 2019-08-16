
<?php if(!empty($data)){?>
<div onclick="go_not_banker()" class="alert-notifi active">
    <i class="icon icon-notification"></i>
    <div class="content-notification">
     <ul>
        <?php 
        foreach ($data as $k => $v) {
            if($v->type == 'submit'){
                echo '<li> You have new AIP submission from '.$this->student_model->getStudentFullName($v->student_id).'</li>'; 
            }else{
                echo '<li>'.$this->student_model->getStudentFullName($v->student_id).' AIP submission on '.$this->student_model->getLoanDate($v->loan_id).' has new update</li>';
            }
        }
        ?>
    </ul>    
    </div>
</div>
<?php }?>

<script type="text/javascript">
    function go_not_banker(){
        window.location.href = root+'banker/notification';
    }
</script>