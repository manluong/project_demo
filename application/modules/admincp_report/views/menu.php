<?php
if($menu){
	foreach($menu as $v){
		$permission = 'r';
		$user_permission = $perm->permission;
		if($perm->id == 1){
			$user_permission = 'all'; // TODO
		}
		$module_id = $v->id;
		
		if(permission_check($permission, $module_id, $user_permission)){
?>
<li class="<?php if($this->uri->segment(2)==$v->name_function){ print 'active'; }?>">
	<a href="<?=PATH_URL_ADMIN.''.$v->name_function.'/'?>">
		<i class="<?=$v->icon?>"></i>
		<span class="title"><?=$v->name?></span>
	</a>
</li>
<?php 
		}
	}
}
?>