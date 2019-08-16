					<script type="text/javascript">token_value = '<?=$this->security->get_csrf_hash()?>';</script>
					<div class="dataTables_wrapper no-footer">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover dataTable no-footer">
								<thead>
									
									<tr role="row">
										<th class="center sorting_disabled" width="35">No.</th>
										<th class="table-checkbox sorting_disabled" width="25">
										<input class="center" type="checkbox" id="selectAllItems" onclick="selectAllItems(<?=count($result)?>)">
										</th>	
										<th class="center" width="150">Name</th>
										<th class="center" width="150">Job id</th>
										<th class="center" width="150">Link</th>
										<th class="center" width="150">Location</th>
										<th class="center" width="150">Wage</th>
										<th class="center sorting" width="80" onclick="sort('created')" id="created">Created</th>
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
													<td>
														<input type="checkbox" id="item<?=$i?>" onclick="selectItem(<?=$i?>)" value="<?=$v->id?>">
													</td>
													<td class="center"><a href="<?=PATH_URL_ADMIN.$module.'/update/'.$v->id?>"><?php echo (isset($v->name)) ? $v->name:'' ?></td>
													<td class="center"><?php echo (isset($v->job_id)) ? $v->job_id:'' ?></td>
													<td class="center"><?php echo (isset($v->link)) ? $v->link:'' ?></td>
													<td class="center"><?php echo (isset($v->location)) ? $v->location:'' ?></td>
													<td class="center"><?php echo (isset($v->wage)) ? $v->wage:'' ?></td>
													<td class="center"><?=date('d-m-Y H:i:s',strtotime($v->created))?></td>
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