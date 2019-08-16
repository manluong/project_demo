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
				<li><a href="<?php echo PATH_URL; ?>banker/personal_info_edit">Edit Profile</a></li>
				<li><a href="<?php echo PATH_URL; ?>banker/personal_info_change_password">Change Password</a></li>
			</ul>
		</div>
	</div>
	<div class="table-detail-user">
		<div class="col-md-6">
			<p>
				<label>Email</label>
				<value><?=(!empty($user_info['0']->email)?$user_info['0']->email:'')?></value>
			</p>
			
		</div>
		<div class="col-md-6">
			<p>
				<label>Mobile Number</label>
				<value><?=(!empty($user_info['0']->phone)?substr($user_info['0']->phone,0,2).'-'.substr($user_info['0']->phone,2,strlen($user_info['0']->phone)-2):'')?></value>
			</p> 			
		</div>
		<div class="col-md-12">
			<p class="underline">
				<label class="pull-left">Bank</label>
				<value class="pull-right"><?=(!empty($user_info['0']->bank)?$user_info['0']->bank:'')?></value>
			</p> 			
		</div>
	</div>
</div>
