<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件



/**
 * This function adds once the CKEditor's config vars
 * @author Samuel Sanchez
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_initialize($data = array()) {

	$return = '';

	if(!defined('CI_CKEDITOR_HELPER_LOADED')) {
		if (!isset($data['path'])) $data['path'] = '/static/editor/ckeditor/';
		define('CI_CKEDITOR_HELPER_LOADED', TRUE);
		$return =  '<script type="text/javascript" src="'.$data['path'] . 'ckeditor.js"></script>';
		$return .=  '<script type="text/javascript" src="/static/editor/ckfinder/ckfinder.js"></script>';
		$return .=	"<script type=\"text/javascript\">CKEDITOR_BASEPATH = '" . $data['path'] . "';</script>";
	}

	return $return;

}

/**
 * This function create JavaScript instances of CKEditor
 * @author Samuel Sanchez
 * @access private
 * @param array $data (default: array())
 * @return string
 */
function cke_create_instance($data = array()) {

    $return = "<script type=\"text/javascript\">
     	var editor = CKEDITOR.replace('" . $data['id'] . "', {";

    		if(!isset($data['config']['width'])) $data['config']['width'] = '600';
			if(!isset($data['config']['height'])) $data['config']['height'] = '600';

    		//Adding config values
    		if(isset($data['config'])) {


	    		foreach($data['config'] as $k=>$v) {

	    			// Support for extra config parameters
	    			if (is_array($v)) {
	    				$return .= $k . " : [";
	    				$return .= config_data($v);
	    				$return .= "]";

	    			}
	    			else {
	    				$return .= $k . " : '" . $v . "'";
	    			}

	    			if(array_key_exists($k,$data['config'])) {
						$return .= ",";
					}
	    		}
    		}

    $return .= '}); CKFinder.setupCKEditor( editor, "__PUBLIC__/editor/ckfinder/" );</script>';

    return $return;

}

/**
 * This function displays an instance of CKEditor inside a view
 * @author Samuel Sanchez
 * @access public
 * @param array $data (default: array())
 * @return string
 */
function display_ckeditor($data = array())
{
	// Initialization
	$return = cke_initialize($data);

    // Creating a Ckeditor instance
    $return .= cke_create_instance($data);


    // Adding styles values
    if(isset($data['styles'])) {

    	$return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles_" . $data['id'] . "', [";


	    foreach($data['styles'] as $k=>$v) {

	    	$return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

	    	if(isset($v['styles'])) {
	    		foreach($v['styles'] as $k2=>$v2) {

	    			$return .= "'" . $k2 . "' : '" . $v2 . "'";

					if($k2 !== end(array_keys($v['styles']))) {
						 $return .= ",";
					}
	    		}
    		}

	    	$return .= '} }';

	    	if($k !== end(array_keys($data['styles']))) {
				$return .= ',';
			}


	    }

	    $return .= ']);';

		$return .= "CKEDITOR.instances['" . $data['id'] . "'].config.stylesCombo_stylesSet = 'my_styles_" . $data['id'] . "';
		</script>";
    }

    return $return;
}

/**
 * config_data function.
 * This function look for extra config data
 *
 * @author ronan
 * @link http://kromack.com/developpement-php/codeigniter/ckeditor-helper-for-codeigniter/comment-page-5/#comment-545
 * @access public
 * @param array $data. (default: array())
 * @return String
 */
function config_data($data = array())
{
	$return = '';
	foreach ($data as $k => $key)
	{
		if (is_array($key)) {
			$return .= "[";
			foreach ($key as $k2 => $string) {
				$return .= "'" . $string . "'";
				if(array_key_exists($k2,$key)) $return .= ",";
			}
			$return .= "]";
		}
		else {
			$return .= "'".$key."'";
		}
		if(array_key_exists($k,$key)) $return .= ",";

	}
	return $return;
}
