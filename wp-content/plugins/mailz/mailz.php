<?php
/*
 Plugin Name: Mailing List
 Plugin URI: http://www.zingiri.com/plugins-and-addons/mailing-list
 Description: This plugin provides easy to use mailing list functionality to your Wordpress site
 Author: Zingiri
 Version: 2.1.1
 Author URI: http://www.zingiri.com/
 */

define("ZING_MAILZ_VERSION","2.1.1");
define("ZING_MAILZ_PREFIX","zing_");

if (isset($wpdb)) $dbtablesprefix=$wpdb->prefix.ZING_MAILZ_PREFIX;

// Pre-2.6 compatibility for wp-content folder location
if (!defined("WP_CONTENT_URL")) {
	define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}
if (!defined("WP_CONTENT_DIR")) {
	define("WP_CONTENT_DIR", ABSPATH . "wp-content");
}

if (!defined("ZING_MAILZ_PLUGIN")) {

	$zing_mailz_plugin=substr(dirname(__FILE__),strlen(WP_CONTENT_DIR)+9,strlen(dirname(__FILE__))-strlen(WP_CONTENT_DIR)-9);
	define("ZING_MAILZ_PLUGIN", $zing_mailz_plugin);
}

if (!defined("ZING_MAILZ_DIR")) {
	define("ZING_MAILZ_DIR", WP_CONTENT_DIR . "/plugins/".ZING_MAILZ_PLUGIN."/lists/");
}

if (!defined("ZING_MAILZ_LOC")) {
	define("ZING_MAILZ_LOC", WP_CONTENT_DIR . "/plugins/".ZING_MAILZ_PLUGIN."/");
}

if (!defined("ZING_MAILZ_URL")) {
	define("ZING_MAILZ_URL", WP_CONTENT_URL . "/plugins/".ZING_MAILZ_PLUGIN."/");
}
if (!defined("ZING_MAILZ_LOGIN")) {
	define("ZING_MAILZ_LOGIN", get_option("zing_mailz_login"));
}

if (!defined("BLOGUPLOADDIR")) {
	$upload=wp_upload_dir();
	define("BLOGUPLOADDIR",$upload['path']);
}

$zing_mailz_version=get_option("zing_mailz_version");

if ($zing_mailz_version) {
	add_action("init","zing_mailz_init");
	add_filter('the_content', 'zing_mailz_content', 10, 3);
	add_action('wp_head','zing_mailz_head');
	add_action('wp_head','zing_mailz_header');
	add_action('admin_notices','zing_mailz_notices');
}
add_action('admin_head','zing_mailz_admin_head');

register_activation_hook(__FILE__,'zing_mailz_activate');
register_deactivation_hook(__FILE__,'zing_mailz_deactivate');
if (zing_mailz_remote()) require_once(dirname(__FILE__) . '/includes/misc2.php');
else require_once(dirname(__FILE__) . '/includes/misc1.php');
require_once(dirname(__FILE__) . '/includes/index.php');
require_once(dirname(__FILE__) . '/classes/index.php');
require_once(dirname(__FILE__) . '/mailz_cp.php');

function zing_mailz_notices() {
	$zing_mailz_version=get_option("zing_mailz_version");
	$warnings=array();

	if (($ssp=session_save_path()) && !is_writable($ssp)) $warnings[]='PHP sessions are not properly configured on your server, the sessions save path '.$ssp.' is not writable.';

	if (phpversion() < '5')	$warnings[]="You are running PHP version ".phpversion().". You require PHP version 5.2 or higher for this plugin, with version 5.3 recommended.";
	if (!function_exists('curl_init')) $warnings[]="You need to have cURL installed. Contact your hosting provider to do so.";

	$upload=wp_upload_dir();
	if ($upload['error']) $warnings[]=$upload['error'];

	if (empty($zing_mailz_version)) $warnings[]='Please proceed with a clean install or deactivate your plugin';
	elseif ($zing_mailz_version != ZING_MAILZ_VERSION) $warnings[]='You downloaded version '.ZING_MAILZ_VERSION.' and need to <a href="admin.php?page=mailz_setup">upgrade</a> your database (currently at version '.$zing_mailz_version.').';

	if (get_option('activation-output')) {
		//	$warnings[]='An error occured during activation:<br /><div style="background-color:white">'.get_option('activation-output').'</div>';
	}
	if (count($warnings)>0) {
		echo "<div id='zing-warning' style='clear:both;background-color:greenyellow' class='updated fade'>";
		foreach ($warnings as $message) {
			echo "<p><strong>Mailing list: ".$message."</strong></p>";
		}
		echo "</div>";
	}
}


function zing_activation_output($buffer) {
	update_option('activation-output',$buffer);
	if ($buffer) die();
}

function zing_mailz_activate() {
}

/**
 * Deactivation
 * @return void
 */
function zing_mailz_deactivate() {
	wp_clear_scheduled_hook('zing_mailz_cron_hook');
}


/**
 * Main function handling content
 * @param $process
 * @param $content
 * @return unknown_type
 */
function zing_mailz_main($process,$content="") {
	global $zing_mailz_content;

	if ((!is_admin() || (is_admin() && strstr($_REQUEST['page'],'mailz'))) && $zing_mailz_content) {
		$content='<div id="phplist">'.$zing_mailz_content.'</div>';
		$content.=zing_mailz_footer();
	}
	return $content;
}

function zing_mailz_output($process) {

	global $post;
	global $wpdb;
	global $cfg;
	global $thisuser;
	global $nav;
	global $zing_mailz_loaded,$zing_mailz_mode;

	$content="";

	switch ($process)
	{
		case "content":
			if (isset($_POST['zname'])) {
				$_POST['name']=$_POST['zname'];
				unset($_POST['zname']);
			}
			if (isset($post)) $cf=get_post_custom($post->ID);
			if (isset($_GET['zlist'])) {
				if (isset($_GET['page']) && ($_GET['page']=='mailz_cp')) $to_include='admin/index';
				elseif (isset($_GET['page'])) $to_include='admin/index';
				else $to_include=$_GET['zlist'];
				$zing_mailz_mode="client";
			}
			elseif (isset($_GET['zscp']))
			{
				$to_include="index";

				$zing_mailz_mode="admin";
			}
			elseif (isset($_GET['zsetup']))
			{
				$to_include="setup/".$_GET['zscp'];
				$zing_mailz_mode="setup";
			}
			elseif (isset($cf['zing_mailz_page']) && ($cf['zing_mailz_page'][0]=='mailz'))
			{
				$to_include="index";
			}
			elseif (isset($cf['zing_mailz_page']) && ($cf['zing_mailz_page'][0]=='admin'))
			{
				$to_include="index.php";
				$zing_mailz_mode="admin";
			}
			else
			{
				return $content;
			}
			if (isset($cf['cat'])) {
				$_GET['cat']=$cf['cat'][0];
			}
			break;
		default:
			return $content;
			break;
	}
	if (zing_mailz_login()) {
		$http=zing_mailz_http('mailz',$to_include.'.php');
		$news = new zHttpRequest($http,'mailz');
		if ($news->live()) {
			$output=$news->DownloadToString();
			if ($news->type=='application/csv') {
				ob_end_clean();
				header( "Content-type: ".$news->type );
				header('Content-Disposition: attachment; filename="download.csv"');
				echo $output;
				die();
			} else {
				if ($to_include=='admin/help/index') die($output);
				$output=stripslashes($output);
				$content.=zing_mailz_ob($output);
			}
			return $content;
		}
	}
}

function zing_mailz_mainpage() {
	$ids=get_option("zing_mailz_pages");
	$ida=explode(",",$ids);
	return $ida[0];
}

function zing_mailz_ob($buffer) {
	global $current_user,$zing_mailz_mode,$wpdb,$wp_version;

	$prefix=$wpdb->prefix.ZING_MAILZ_PREFIX;
	$home=get_option('home');
	$admin=get_option('siteurl').'/wp-admin/';
	$pid=zing_mailz_mainpage();

	$buffer=str_replace('page=','zlistpage=',$buffer);
	if (is_admin()) {
		$buffer=str_replace('<span class="menulinkleft"><a href="./?zlistpage=logout">logout</a><br /></span>','',$buffer);
		$buffer=str_replace('<a href="./?zlistpage=logout">logout</a>','',$buffer);
		$buffer=str_replace('./?','admin.php?'.'page=mailz_cp&zlist=index&',$buffer);
		if (isset($_GET['zlistpage'])) $buffer=str_replace('<form method=post >','<form method=post action="'.$admin.'admin.php?page=mailz_cp&zlist=index&zlistpage='.$_GET['zlistpage'].'">',$buffer);
		$buffer=str_replace('name="page"','name="zlistpage"',$buffer);
		$buffer=str_replace('<form method="get" name="listcontrol" action="">','<form method="get" name="listcontrol" action="admin.php"><input type="hidden" value="mailz-users" name="page"/>',$buffer);
		if (isset($_GET['zlistpage'])) $buffer=str_replace('<form method=get>','<form method=get><input type="hidden" name="page" value="mailz_cp" /><input type="hidden" name="zlist" value="index" /><input type="hidden" name="zlistpage" value="'.$_GET['zlistpage'].'" />',$buffer);
		if (isset($_GET['zlistpage'])) $buffer=str_replace('<form method="post" action="">','<form method=post action="'.$admin.'admin.php?page=mailz_cp&zlist=index&zlistpage='.$_GET['zlistpage'].'">',$buffer);
		$buffer=str_replace(ZING_PHPLIST_URL.'/?',$admin.'admin.php?page=mailz_cp&zlist=index&',$buffer);
		$buffer=str_replace('./FCKeditor',ZING_PHPLIST_URL.'/admin/FCKeditor',$buffer);
		$buffer=str_replace('src="images/','src="'.ZING_PHPLIST_URL.'/admin/images/',$buffer);
		$buffer=str_replace('src="js/jslib.js"','src="'.ZING_PHPLIST_URL.'/js/jslib.js"',$buffer);
		$buffer=str_replace('url( styles/tabs.css )','url( '.ZING_PHPLIST_URL.'/admin/styles/tabs.css )',$buffer);
		if ($wp_version >= '3.3') {
			//$f[]='/<textarea.id\="message".*class\="theEditor".*>((.|\s)*?)<\/textarea>/';
			$f[]='/<textarea id="message" class="theEditor" name=message cols=65 rows=20>((.|\s)*?)<\/textarea>/';
			$buffer=preg_replace_callback($f,'zingiri_mailz_editor_replace',$buffer);
		}

	} else {
		$buffer=str_replace('/lists/admin',$admin.'admin.php?page=mailz_cp&zlist=index&',$buffer); //go to admin page
		$buffer=str_replace('./?',$home.'/?page_id='.$pid.'&zlist=index&',$buffer);
		$buffer=str_replace(ZING_PHPLIST_URL.'/?',$home.'/?page_id='.$pid.'&zlist=index&',$buffer);
		if (isset($_GET['p']) && $_GET['p']=='subscribe' && isset($current_user->data->user_email)) {
			$buffer=str_replace('name=email value=""','name=email value="'.$current_user->data->user_email.'"',$buffer);
			$buffer=str_replace('name=emailconfirm value=""','name=emailconfirm value="'.$current_user->data->user_email.'"',$buffer);
		}
		if (isset($_GET['p']) && $_GET['p']=='unsubscribe' && isset($current_user) && isset($current_user->data->user_email)) {
			$query="select uniqid from ".$prefix."phplist_user where email='".$current_user->data->user_email."'";
			$uid=$wpdb->get_var($query);
			$buffer=str_replace('name="unsubscribeemail" value=""','name="unsubscribeemail" value="'.$current_user->data->user_email.'"',$buffer);
			$buffer=str_replace('uid="','uid='.$uid.'"',$buffer);
		}
		if (isset($_GET['p']) && $_GET['p']=='preferences' && isset($current_user->data->user_email)) {
			$buffer=str_replace('name=email value=""','name=email value="'.$current_user->data->user_email.'"',$buffer);
			$buffer=str_replace('name=emailconfirm value=""','name=emailconfirm value="'.$current_user->data->user_email.'"',$buffer);
		}
	}

	return '<!--buffer:start-->'.$buffer.'<!--buffer:end-->';
}

function zingiri_mailz_editor_replace($match) {
	$id='message';
	$content=html_entity_decode($match[1]);
	ob_start();
	wp_editor($content,$id);
	return ob_get_clean();
}


/**
 * Page content filter
 * @param $content
 * @return unknown_type
 */
function zing_mailz_content($content) {
	return zing_mailz_main("content",$content);
}


/**
 * Header hook: loads FWS addons and css files
 * @return unknown_type
 */
function zing_mailz_header()
{
	global $zing_mailz_content;
	global $zing_mailz_menu;
	global $zing_mailz_post;

	if (isset($_POST) && isset($zing_mailz_post)) {
		$_POST=array_merge($_POST,$zing_mailz_post);
	}

	$output=zing_mailz_output("content");
	$menu1=zing_integrator_cut($output,'<div class="menutableright">','</div>');
	if ($menu1) {
		$menu1=str_replace('<span','<li><span',$menu1);
		$menu1=str_replace('</span>','</span></li>',$menu1);
		$menu1='<ul>'.$menu1.'</ul>';
		$menu1=str_replace('menulinkleft','xmenulinkleft',$menu1);
		$menu1=str_replace('<hr>','',$menu1);
	}
	$zing_mailz_menu=$menu1;
	if ($zing_mailz_menu) $_SESSION['mailz_menu']=$zing_mailz_menu;
	$body=zing_integrator_cut($output,'<body','</body>',true);
	$body=strchr($body,'>');
	$zing_mailz_content=trim(substr($body,1));
}

function zing_mailz_admin_head() {
	global $wp_version;
	if (isset($_REQUEST['page']) && (strstr($_REQUEST['page'],'mailz_') || strstr($_REQUEST['page'],'mailz-'))) {
		echo '<link rel="stylesheet" type="text/css" href="' . ZING_MAILZ_URL . 'lists/admin/styles/phplist.css" media="screen" />';
		echo '<link rel="stylesheet" type="text/css" href="' . ZING_MAILZ_URL . 'zing.css" media="screen" />';
	}
	if (($wp_version < '3.3') && ((isset($_REQUEST['zlistpage']) && ($_REQUEST['zlistpage']=='templatee' || $_REQUEST['zlistpage']=='send')) || (isset($_REQUEST['page']) && ($_REQUEST['page']=='mailz-send')))) {
		wp_tiny_mce( false, array( 'editor_selector' => 'theEditor' ) );
	}
}

function zing_mailz_head() {
	echo '<link rel="stylesheet" type="text/css" href="' . ZING_MAILZ_URL . 'lists/styles/phplist.css" media="screen" />';
	echo '<link rel="stylesheet" type="text/css" href="' . ZING_MAILZ_URL . 'zing.css" media="screen" />';
}

/**
 * Initialization of page, action & page_id arrays
 * @return unknown_type
 */
function zing_mailz_init() {
	global $wp_version;

	ob_start();
	if (!session_id()) @session_start();

	if (is_admin() && ((isset($_REQUEST['zlistpage']) && ($_REQUEST['zlistpage']=='templatee' || $_REQUEST['zlistpage']=='send')) || (isset($_REQUEST['page']) && $_REQUEST['page']=='mailz-send'))) {
		wp_enqueue_script(array('jquery'));
		if ($wp_version < '3.3') wp_enqueue_script(array('editor', 'thickbox', 'media-upload'));
	}

}

function mailz_log($type=0,$msg='',$filename="",$linenum=0) {
	if (get_option('mailz_debug')) {
		if (is_array($msg)) $msg=print_r($msg,true);
		$v=get_option('mailz_debug_log');
		if (!is_array($v)) $v=array();
		array_unshift($v,array(time(),$type,$msg));
		update_option('mailz_debug_log',$v);
	}
}

function zing_mailz_remote() {
	global $wpdb;
	$query="show tables like '".$wpdb->prefix.ZING_MAILZ_PREFIX."phplist_config'";
	$rows=$wpdb->get_results($query);
	if (count($rows) > 0) return false;
	else return true;
}
