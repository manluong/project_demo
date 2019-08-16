<script type="text/javascript">
    $(document).ready(function(){
        $("#submit_phone").click(function(){
            var phone = $("#phone").val();
           
            $.post(root+'home/student_reg',{
                phone : phone,
            },function(data){
                if(data == 'fail'){
                    alert('Phone number exist');
                }else{
                    alert(data);
                    window.location.href = root+'home/verify_otp';
                }
            });
        });

    });
</script>

<form id="frmphone" method="POST" class="col-md-3">
    <div class="form-group">
        <label for="exampleInputEmail1">Phone Number</label>
        <input type="text" class="form-control"  id="phone" name="phone">
    </div>
    <div type="submit" id="submit_phone" class="btn btn-primary">Submit</div>
</form>

