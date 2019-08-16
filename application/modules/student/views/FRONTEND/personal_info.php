<div class="personal_info">
	<h2 class="title center-text">Personal Info</h2>
	<div class="avatar-user">
		 <?php 
			$img = 'user-icon.png';
			if(!empty($user_info['0']->image)){
				$img = $user_info['0']->image;
			}
		?>
		 <div class="img-avatar">
		 	
		 	<img src="<?=get_resource_url('statics/uploads/avatar/'.$img.'')?>" class="user-avatar">
		 </div>
		<div class="info_user">
			<name><?=(!empty($user_info['0']->firstname)?$user_info['0']->firstname:'')?> <?=(!empty($user_info['0']->lastname)?$user_info['0']->lastname:'')?></name>
			<info>Member since <?=(!empty($user_info['0']->created)?date('d M Y',strtotime($user_info['0']->created)):'')?></info>
			<ul>
				<li><a href="<?php echo PATH_URL; ?>student/personal_info_edit">Edit Profile</a></li>
				<li><a href="<?php echo PATH_URL; ?>student/personal_info_change_password">Change Password</a></li>
			</ul>
		</div>
	</div>
	<div class="table-detail-user">
		<div class="col-md-6">
			<p>
				<label>Email</label>
				<value><?=(!empty($user_info['0']->email)?$user_info['0']->email:'')?></value>
			</p>
			<p>
				<label>Nationality</label>
				<value><?=(!empty($user_info['0']->nationality)?$this->model->getNationalName($user_info['0']->nationality):'')?></value>
			</p>
			<p>
				<span>
					<label>Date of Birth</label>
					<value><?=(!empty($user_info['0']->birthday)?date('m/d/Y',strtotime($user_info['0']->birthday)):'')?></value>
				</span>
				<span>
					<label>Age</label>
					<value><?=(!empty($user_info['0']->birthday)?floor((time() - strtotime($user_info['0']->birthday)) / 31556926):'')?></value>
				</span>
				
			</p>
			<p>
				<label>No. & Street Name</label>
				<value><?=(!empty($user_info['0']->street)?$user_info['0']->street:'')?></value>
			</p>
			<p>
				<label>Building Name</label>
				<value><?=(!empty($user_info['0']->building_name)?$user_info['0']->building_name:'')?></value>
			</p>
		</div>
		<div class="col-md-6">
			<p>
				<label>Mobile Number</label>
				<value><?=(!empty($user_info['0']->phone)?substr($user_info['0']->phone,0,2).'-'.substr($user_info['0']->phone,2,strlen($user_info['0']->phone)-2):'')?></value>
			</p> 
			<p>
				<label>Gender</label>
				<value><?=(!empty($user_info['0']->gender)?($user_info['0']->gender==1?'Male':'Female'):'')?></value>
			</p>
			<p>
				<label>PIM Batch No</label>
				<value><?=(!empty($user_info['0']->batch_no)?$this->model->getPimName($user_info['0']->batch_no):'')?></value>
				
			</p>
			<p>
				<label>Unit No</label>
				<value>#<?=(!empty($user_info['0']->unit)?substr($user_info['0']->unit,0,2).'-'.substr($user_info['0']->unit,2,strlen($user_info['0']->unit)-2):'')?></value>
			</p>
			<p>
				<label>Postal Code</label>
				<value><?=(!empty($user_info['0']->postal_code)?$user_info['0']->postal_code:'')?></value>
			</p>
		</div>
		
	</div>
</div>