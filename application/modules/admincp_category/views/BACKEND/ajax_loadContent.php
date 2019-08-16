					<script type="text/javascript">token_value = '<?=$this->security->get_csrf_hash()?>';</script>
					<div class="dataTables_wrapper no-footer">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover dataTable no-footer">
								<thead>
									<?php
										if(isset($this->lang->languages)){
											$all_lang = $this->lang->languages;
										}else{
											$all_lang = array(
												'' => ''
											);
										}
									?>
									<tr role="row">
										<th class="center sorting_disabled" width="35">No.</th>
										<th class="table-checkbox sorting_disabled" width="25"><input type="checkbox" id="selectAllItems" onclick="selectAllItems(<?=count($result)?>)"></th>										
										<?php 
										foreach($all_lang as $key=>$val){ 
										?>
										<th class="sorting" width="160" onclick="sort('name<?php echo($key!='') ? '_'.$key : '' ?>')" id="name<?php echo($key!='') ? '_'.$key : '' ?>">Name<?php echo($key!='') ? ' - '.mb_strtoupper($key) : '' ?></th>
										<?php 
										} 
										?>
										<th class="center sorting" width="80" onclick="sort('status')" id="status">Status</th>
										<th class="center sorting" width="80" onclick="sort('created')" id="created">Created</th>											
									</tr>
								</thead>
								<tbody>
									<?php
										if($result){
											$i=0;
											foreach($result as $k=>$v)
											{
												
									?>
									<tr class="item_row<?=$i?> gradeX <?php echo($k%2==0) ? 'odd' : 'even' ?>" role="row">
										<td class="center"><?=$k+1+$start?></td>
										<td><input type="checkbox" id="item<?=$i?>" onclick="selectItem(<?=$i?>)" value="<?=$v->id?>"></td>									
										<?php
											foreach($all_lang as $key=>$val){
												$name = ($key!='') ? 'name_'.$key : 'name';
										?>
										<td><?=$v->$name?></td>
										<?php 
										} 
										?>
										<td class="center" id="loadStatusID_<?=$v->id?>"><a class="no_underline" href="javascript:void(0)" onclick="updateStatus(<?=$v->id?>,<?=$v->status?>,'<?=$module?>')"><?php echo($v->status==0) ? '<span class="label label-sm label-default">Blocked</span>' : '<span class="label label-sm label-success">Approved</span>' ?></a></td>
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
						if($result){ 
						?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<?php 
								if(($start+$per_page)<$total){ 
								?>
								<div class="dataTables_info" style="padding-left:0;margin-top:3px">Showing <?=$start+1?> to <?=$start+$per_page?> of <?=$total?> entries</div>
								<?php 
								}else{ 
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