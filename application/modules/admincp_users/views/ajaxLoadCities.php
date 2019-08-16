<option value="0">--Choose--</option>
<?php 
	if($list_city){
		foreach ($list_city as $city) {?>
			<option <?= $city->city_id == $cityID ? 'selected' : ''; ?> value="<?=$city->city_id?>"><?=$city->city_name?></option>
	<?php }
	}

?>