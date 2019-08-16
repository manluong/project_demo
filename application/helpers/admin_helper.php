<?php


/****** BEGIN - INPUT HELPER ******/
function admin_input_generate_checkbox($config){
	$html = '';
	
	$input_id = $config['input_id'];
	$is_required = !empty($config['input_required']);
	$html_required = $is_required ? 'data-required="1"' : '';
	$obj = !empty($config['obj']) ? $config['obj'] : '';
	$html_checkbox_active = !isset($obj->status) || !empty($obj->status) ? 'checked="checked"' : '';
	
	ob_start();
?>
<div class="checkbox-list">
	<label class="checkbox-inline">
		<div class="checkbox">
			<span>
				<input <?php echo $html_required ?> <?php echo $html_checkbox_active ?> type="checkbox" id="<?php echo $input_id?>" name="<?php echo $input_id?>" />
			</span>
		</div>
	</label>
</div>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}

function admin_input_generate_textbox($config){
	$html = '';
	
	$input_id = $config['input_id'];
	$input_value = $config['input_value'];
	$is_required = !empty($config['input_required']);
	$html_required = $is_required ? 'data-required="1"' : '';
	
	ob_start();
?>
<input <?php echo $html_required ?> type="text" class="form-control" id="<?php echo $input_id?>" name="<?php echo $input_id?>" value="<?php echo $input_value?>" />
<?php
	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}

function admin_input_generate_label($config, $language_key=''){
	$html = '';
	
	$input_label = !empty($config['input_label']) ? $config['input_label'] : '[NO LABEL]';
	$language_suffix =  !empty($language_key) ?  ' ('.mb_strtoupper($language_key).')' :  '';
	$html = $input_label.$language_suffix;
	
	return $html;
}

function admin_input_generate_id($config, $language_key=''){
	$html = '';
	
	$input_id = !empty($config['input_id']) ? $config['input_id'] : 'NO_ID';
	$language_suffix =  !empty($language_key) ?  '_'.$language_key :  '';
	$html = $input_id.$language_suffix;
	
	return $html;
}

function admin_input_generate_value($config, $language_key=''){
	$html = '';
	
	if(!empty($config['obj'])){
		$input_value = '';
		
		$obj = $config['obj'];
		$field_name = $config['field_name'];
		if(!empty($config['multilingual'])){
			if(!empty($config['field_name_access'])){
				$field_name_access = $config['field_name_access'];
				if($field_name_access == 'data_lang'){
					$input_value = isset($obj->data_lang[$language_key]->$field_name) ? $obj->data_lang[$language_key]->$field_name : '';
				} else {
					$field_name = $field_name.'_'.$language_key;
					$input_value = isset($obj->$field_name) ? $obj->$field_name : '';
				}
			}
		} else {
			$input_value = isset($obj->$field_name) ? $obj->$field_name : '';
		}
		
		$html = $input_value;
	}
	
	return $html;
}

function admin_input_generate($config, $language_key=''){
	$html = '';
	
	// Check required properties of array $config
	$config_required_item_arr = array
	(
		'input_type',
		'input_label',
		'input_id',
		'field_name',
	);
	foreach($config_required_item_arr as $config_required_item)
	{
		if(empty($config[$config_required_item]))
		{
			$message_error_required = $config_required_item;
			break;
		}
	}
	
	// Check required multilingual - language_key
	if(!empty($config['multilingual']) || !empty($language_key)){
		if(empty($config['multilingual'])){
			$message_error_required = 'multilingual';
		} else if(empty($language_key)){
			$message_error_required = "language's key";
		}
	}
	
	if(!empty($message_error_required))
	{
		$message_error = 'REQUIRED '.$message_error_required;
	}
	
	if(!empty($message_error))
	{
		$input_id = !empty($config['input_id']) ? $config['input_id'] : 'NO_ID';
		$message_error = "Input '{$input_id}' - {$message_error}";
		die($message_error);
	}
	else
	{
		$input_type = $config['input_type'];
		$input_label = admin_input_generate_label($config, $language_key);
		$config['input_id'] = admin_input_generate_id($config, $language_key); // Redefine
		$config['input_value'] = admin_input_generate_value($config, $language_key); // Redefine
		$is_required = !empty($config['input_required']);
		$input_html = '';
		switch($input_type){
			case 'checkbox':
				$input_html = admin_input_generate_checkbox($config,$language_key);
				break;
			case 'textbox':
				$input_html = admin_input_generate_textbox($config,$language_key);
				break;
		}
		
		ob_start();
?>
<div class="form-group">
	<label class="control-label col-md-3">
		<?php
		echo $input_label;
		if($is_required){
		?>
		<span class="required" aria-required="true">*</span>
		<?php
		}
		?>
	</label>
	<div class="col-md-9">
		<?php echo $input_html ?>
	</div>
</div>
<?php
		$html = ob_get_contents();
		ob_end_clean();
	}
	
	return $html;
}
/****** END - INPUT HELPER ******/