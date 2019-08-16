<div class="col-md-10">
	<div class="btn-group">
		<a href="<?=PATH_URL_ADMIN.$module.'/update/'?>"><button class="btn btn-margin green"><i class="fa fa-edit"></i> Add New</button></a>
		<a href="#portlet-alert" data-toggle="modal" class="pull-right"><button class="btn btn-margin red" data-toggle="modal" href="#basic"><i class="fa fa-trash"></i> Delete</button></a>
		<button class="btn btn-margin default pull-right" onclick="hideStatusAll()"><i class="fa fa-close"></i> Blocked</button>
		<?php
		if(permission_check_user('a'))
		{
		?>
		<button class="btn btn-margin blue pull-right" onclick="showStatusAll()"><i class="fa fa-check"></i> Approved</button>
		<?php
		}
		?>
	</div>	
</div>
<div class="col-md-2">
	<div class="btn-group pull-right">
		<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
		</button>
		<ul class="dropdown-menu pull-right">
			<li>
				<a href="#">Export to Excel</a>
			</li>
		</ul>
	</div>
</div>