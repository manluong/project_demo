<script type="text/javascript">token_value = '<?=$this->security->get_csrf_hash()?>';</script>
<div class="dataTables_wrapper no-footer">
	<div class="table-scrollable">
		<table class="table table-striped table-bordered table-hover dataTable no-footer">
			<thead>
				<tr role="row">
					<th class="center sorting_disabled" width="35">No.</th>
					<th class="table-checkbox sorting_disabled" width="25"><input type="checkbox" id="selectAllItems" onclick="selectAllItems(<?=count($result)?>)"></th>
					<th class="sorting" onclick="sort('firstname')" id="firstname">Full Name</th>
					<th class="sorting" onclick="sort('email')" id="email">Email</th>
					<th class="center sorting" onclick="sort('user_role')" id="user_role">User Role</th>
					<th class="center sorting" width="150" onclick="sort('status')" id="status">Status</th>
					<th class="center sorting" width="150" onclick="sort('created_at')" id="created_at">Created</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($result){
						$i=0;
						foreach($result as $k=>$v){
				?>
				<tr class="item_row<?=$i?> gradeX <?php ($k%2==0) ? print 'odd' : print 'even' ?>" role="row">
					<td class="center"><?=$k+1+$start?></td>
					<td class="center"><input type="checkbox" id="item<?=$i?>" onclick="selectItem(<?=$i?>)" value="<?=$v->id?>"></td>
					<td><a href="<?=PATH_URL_ADMIN.$module.'/update/'.$v->id?>"><?=$v->firstname.' '.$v->lastname?></a></td>
					<td><a href="<?=PATH_URL_ADMIN.$module.'/update/'.$v->id?>"><?=$v->email?></a></td>
					<td class="center"><?=$v->user_role == USER_ROLE_PRO ? '<span class="label label-sm label-danger">'.$list_role[$v->user_role].'</span>' : '<span class="label label-sm label-primary">'.$list_role[$v->user_role].'</span>' ?></td>
					<td class="center" id="loadStatusID_<?=$v->id?>"><a class="no_underline" href="javascript:void(0)" onclick="updateStatus(<?=$v->id?>,<?=$v->status?>,'<?=$module?>')"><?php ($v->status==0) ? print '<span class="label label-sm label-default">Blocked</span>' : print '<span class="label label-sm label-success">Approved</span>' ?></a></td>
					<td class="center"><?=date('d-m-Y H:i:s',strtotime($v->created_at))?></td>
				</tr>
				<?php $i++;}}else{ ?>
				<tr class="gradeX odd" role="row">
					<td class="center no-record" colspan="20">No record</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<?php if($result){ ?>
	<div class="row">
		<div class="col-md-5 col-sm-12">
			<?php if(($start+$per_page)<$total){ ?>
			<div class="dataTables_info" style="padding-left:0;margin-top:3px">Showing <?=$start+1?> to <?=$start+$per_page?> of <?=$total?> entries</div>
			<?php }else{ ?>
			<div class="dataTables_info" style="padding-left:0;margin-top:3px">Showing <?=$start+1?> to <?=$total?> of <?=$total?> entries</div>
			<?php } ?>
		</div>

		<div class="col-md-7 col-sm-12">
			<div class="dataTables_paginate paging_bootstrap_full_number" style="margin-top:3px">
				<ul class="pagination" style="visibility: visible;">
					<?=$this->adminpagination->create_links();?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
</div>