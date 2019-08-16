<div class="col-md-10 col-sm-12">
	<div class="dataTables_filter">
		<button onclick="searchContent(0)" class="btn btn-margin yellow" style="float:right;margin-right:0 !important;margin-left:10px"><i class="fa fa-search"></i> Search</button>
		<div style="float:right;" class="input-group input-large date-picker input-daterange" data-date-format="yyyy/mm/dd">
			<input onkeypress="return enterSearch(event)" id="caledar_from" type="text" class="form-control" name="from">
			<span class="input-group-addon">to</span>
			<input onkeypress="return enterSearch(event)" id="caledar_to" type="text" class="form-control" name="to" style="width:100px">
		</div>
		<label style="margin-bottom:0;margin-left:10px">Choose date:&nbsp;</label>
		<label style="margin-bottom:0;">My search: <input onkeypress="return enterSearch(event)" id="search_content" placeholder="type here..." type="search" class="form-control input-medium input-inline" placeholder="" ></label>
	</div>
</div>