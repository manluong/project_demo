<script type="text/javascript">token_value = '<?=$this->security->get_csrf_hash()?>';</script>
<div class="dataTables_wrapper no-footer">
	<div class="table-scrollable">
		<table class="table table-striped table-bordered table-hover dataTable no-footer">
			<thead>
				
				<tr role="row">
					<th class="center sorting_disabled" width="35">No.</th>
					<?php
						if($this->session->userdata('userInfo')=='root'){ 
					?>
					<th class="table-checkbox sorting_disabled" width="25"><input type="checkbox" id="selectAllItems" onclick="selectAllItems(<?=count($result)?>)"></th>
					<?php
						}
					?>
					<th class="center" width="150">Full name</th>
					<th class="center sorting" onclick="sort('email')" id="function" width="200">Email</th>
					<th class="center" width="100">Phone</th>
					<th class="center" width="150">Experience</th>
					<th class="center" width="150">Job</th>
					<th class="center" width="90">Type CV</th>
					<th class="center" width="100">Status_register</th>
					<?php
					if($this->session->userdata('userInfo')=='admin' || $this->session->userdata('userInfo')=='root'){
						?>
						<th class="center" width="100">utm_campaign</th>
						<th class="center" width="100">utm_source</th>
						<th class="center" width="100">utm_medium</th>
						<th class="center" width="100">utm_term</th>
						<th class="center" width="100">utm_content</th>
						<?php
					}
					?>
					<th class="center" width="100">File Url</th>
				</tr>
			</thead>
			<tbody>
				<?php
				
					if($result){
						$i=0;
						foreach($result as $k=>$v)
						{

							$gradeX = ($k%2==0) ? 'odd' : 'even';
							?>
							<tr class="item_row<?=$i?> gradeX <?php echo $gradeX; ?>" role="row">
								<td class="center"><?=$k+1+$start?></td>
								<?php
									if($this->session->userdata('userInfo')=='root'){ 
								?>
								<td><input type="checkbox" id="item<?=$i?>" onclick="selectItem(<?=$i?>)" value="<?=$v->id?>"></td>
								<?php
									}
								?>
								<td class="center"><?=$v->name?></td>
								<td class="center"><?=$v->email?></a></td>
								<td class="center"><?=$v->phone?></td>
								<td class="center">
									<?php if(!empty($v->experience)){
										if ($v->experience ==1)
										{ 
											echo 'Chưa Có';
										} else if($v->experience == 2 ) 
										{ 
											echo 'Đã Có';
										} 
									 } ?>
								</td>
								<td class="center"><?=$v->job_name?></td>
								<td class="center"><?=$v->dinhkem?></td>
								<td class="center"><?=$v->status_register?></td>
								<?php
								if($this->session->userdata('userInfo')=='admin' || $this->session->userdata('userInfo')=='root'){
								?>
								<td class="center"><?=$v->utm_campaign?></td>
								<td class="center"><?=$v->utm_source?></td>
								<td class="center"><?=$v->utm_medium?></td>
								<td class="center"><?=$v->utm_term?></td>
								<td class="center"><?=$v->utm_content?></td>
								<?php
								}
								?>
								<?php
								if($v->dinhkem == 'Tài khoản linkedin'){
								?>
								<td class="center"><a href="<?=$v->file_url?>" target = "blank"><?=(!empty($v->file_url))?'Link profile':''?></a></td>
								<?php
								}
								else{
									if($v->file_url != 'https://employer.vietnamworks.com/' ){
									?>
									<td class="center"><a href="<?=$v->file_url?>" target = "blank"><?=(!empty($v->file_url))?'Download':''?></a></td>
									<?php
									}
									else{
									?>
									<td class="center"><a href="<?=$v->file_url?>" target = "blank"><?=(!empty($v->file_url))?$v->file_url:''?></a></td>
									<?php 
									} 
								}
								?>
							</tr>
							<?php 
						$i++;
						}
					}
					else
					{
					 ?>
								<tr class="gradeX odd" role="row">
									<td class="center no-record" colspan="20">No record</td>
								</tr>
						<?php 
					}
					?>
			</tbody>
		</table>
	</div>

	<?php 
	if($result)
		{
			 ?>
	<div class="row">
		<div class="col-md-5 col-sm-12">
			<?php
			 if(($start+$per_page)<$total)
				{ 
					?>
					<div class="dataTables_info" style="padding-left:0;margin-top:3px">Showing <?=$start+1?> to <?=$start+$per_page?> of <?=$total?> entries</div>
					<?php
				}
			 else
				{
				 ?>
					<div class="dataTables_info" style="padding-left:0;margin-top:3px">Showing <?=$start+1?> to <?=$total?> of <?=$total?> entries</div>
					<?php
				}
			  ?>
		</div>

		<div class="col-md-7 col-sm-12">
			<div class="dataTables_paginate paging_bootstrap_full_number" style="margin-top:3px">
				<ul class="pagination" style="visibility: visible;">
					<?=$this->adminpagination->create_links();?>
				</ul>
			</div>
		</div>
	</div>
	<?php
		}
	  ?>
</div>