<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Active template
|--------------------------------------------------------------------------
|
| The $template['active_template'] setting lets you choose which template 
| group to make active.  By default there is only one group (the 
| "default" group).
|
*/
$template['active_template'] = 'default';

/*
|--------------------------------------------------------------------------
| Explaination of template group variables
|--------------------------------------------------------------------------
|
| ['template'] The filename of your master template file in the Views folder.
|   Typically this file will contain a full XHTML skeleton that outputs your
|   full template or region per region. Include the file extension if other
|   than ".php"
| ['regions'] Places within the template where your content may land. 
|   You may also include default markup, wrappers and attributes here 
|   (though not recommended). Region keys must be translatable into variables 
|   (no spaces or dashes, etc)
| ['parser'] The parser class/library to use for the parse_view() method
|   NOTE: See http://codeigniter.com/forums/viewthread/60050/P0/ for a good
|   Smarty Parser that works perfectly with Template
| ['parse_template'] FALSE (default) to treat master template as a View. TRUE
|   to user parser (see above) on the master template
|
| Region information can be extended by setting the following variables:
| ['content'] Must be an array! Use to set default region content
| ['name'] A string to identify the region beyond what it is defined by its key.
| ['wrapper'] An HTML element to wrap the region contents in. (We 
|   recommend doing this in your template file.)
| ['attributes'] Multidimensional array defining HTML attributes of the 
|   wrapper. (We recommend doing this in your template file.)
|
| Example:
| $template['default']['regions'] = array(
|    'header' => array(
|       'content' => array('<h1>Welcome</h1>','<p>Hello World</p>'),
|       'name' => 'Page Header',
|       'wrapper' => '<div>',
|       'attributes' => array('id' => 'header', 'class' => 'clearfix')
|    )
| );
|
*/

/*
|--------------------------------------------------------------------------
| Default Template Configuration (adjust this or create your own)
|--------------------------------------------------------------------------
*/
/*Template banker*/
$template['banker']['template'] = 'FRONTEND/banker_template';
$template['banker']['regions'] = array(
	'title',
	'header',	
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['banker']['parser'] = 'parser';
$template['banker']['parser_method'] = 'parse';
$template['banker']['parse_template'] = FALSE;
/*End Template banker*/

/*Template Admin*/
$template['tiqadmin']['template'] = 'FRONTEND/admin_template';
$template['tiqadmin']['regions'] = array(
	'title',
	'header',	
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['tiqadmin']['parser'] = 'parser';
$template['tiqadmin']['parser_method'] = 'parse';
$template['tiqadmin']['parse_template'] = FALSE;
/*End Template Admin*/

/*Template student*/
$template['student']['template'] = 'FRONTEND/student_template';
$template['student']['regions'] = array(
	'title',
	'header',	
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['student']['parser'] = 'parser';
$template['student']['parser_method'] = 'parse';
$template['student']['parse_template'] = FALSE;
/*End Template student*/

/*Template FRONTEND*/
$template['default']['template'] = 'FRONTEND/template';
$template['default']['regions'] = array(
	'title',
	'header',	
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['default']['parser'] = 'parser';
$template['default']['parser_method'] = 'parse';
$template['default']['parse_template'] = FALSE;
/*End Template FRONTEND*/

/*Template BACKEND*/
$template['admin']['template'] = 'BACKEND/template';
$template['admin']['regions'] = array(
	'title',
	'header',
	'content',
	'footer',
	'left_column',
	'right_column',
	'menu',
);
$template['admin']['parser'] = 'parser';
$template['admin']['parser_method'] = 'parse';
$template['admin']['parse_template'] = FALSE;
/*End Template BACKEND*/

/*Template template_profile*/
$template['template_profile']['template'] = 'FRONTEND/template_profile';
$template['template_profile']['regions'] = array(
	'title',
	'header',
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['template_profile']['parser'] = 'parser';
$template['template_profile']['parser_method'] = 'parse';
$template['template_profile']['parse_template'] = FALSE;
/*End Template template_profile */

/*Template HABfit Home Pro*/
$template['home_pro']['template'] = 'FRONTEND/home_pro';
$template['home_pro']['regions'] = array(
	'title',
	'header',
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_video',
);
$template['home_pro']['parser'] = 'parser';
$template['home_pro']['parser_method'] = 'parse';
$template['home_pro']['parse_template'] = FALSE;
/*End Template HABfit Home Pro*/

/*Template HABfit Home User*/
$template['home_user']['template'] = 'FRONTEND/home_user';
$template['home_user']['regions'] = array(
	'title',
	'header',
	'content',
	'footer',
	'meta_description',
	'meta_keywords',
	'meta_image',
	'meta_url',
	'meta_video',
);
$template['home_user']['parser'] = 'parser';
$template['home_user']['parser_method'] = 'parse';
$template['home_user']['parse_template'] = FALSE;
/*End Template HABfit Home User*/

/* End of file template.php */