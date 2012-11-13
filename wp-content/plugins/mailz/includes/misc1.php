<?php
define("ZING_PHPLIST_URL",ZING_MAILZ_URL.'lists');
$zing_mailz_options[]=array(  "name" => "General settings",
            "type" => "heading",
			"desc" => "This section manages the Mailing List settings.");
$zing_mailz_options[]=array(	"name" => "Show footer",
			"desc" => "Show your support by enabling our page footer.",
			"id" => "zing_mailz_footer",
			"type" => "checkbox");

if ($zing_mailz_version) {
	add_filter('upgrader_pre_install', 'zing_mailz_pre_upgrade', 9, 2);
	add_filter('upgrader_post_install', 'zing_mailz_post_upgrade', 9, 3);
}

function zing_mailz_http($module,$to_include="index",$get=array()) {
	global $wpdb;

	$vars="";
	if (!$to_include || $to_include==".php") $to_include="index";
	$http=ZING_PHPLIST_URL.'/';
	$http.= $to_include;
	$and="";

	$unload=array("_wpnonce","zlist","zpage","page_id","zscp","zlistpage","page","action","plugin");

	$get=array_merge($_GET,$get);

	if (count($get) > 0) {
		foreach ($get as $n => $v) {
			if (!in_array($n,$unload)) {
				$vars.= $and.$n.'='.zing_urlencode($v);
				$and="&";
			} elseif ($n=="zlistpage" && $v !== null) {
				$vars.= $and.'page'.'='.zing_urlencode($v);
				$and="&";
			}
		}
	}

	//$wpconnect=base64_encode(serialize(array('wpdb'=>DB_NAME,'wpf'=>$wpdb->prefix,'wph'=>DB_HOST,'wpu'=>DB_USER,'wpp'=>DB_PASSWORD)));
	//$vars.=$and.'wpconnect='.$wpconnect;
	//$vars.=$and.'wpdb='.zing_urlencode(DB_NAME);
	$vars.=$and.'&wpf='.zing_urlencode($wpdb->prefix);
	//$vars.='&wph='.zing_urlencode(DB_HOST);
	//$vars.='&wpu='.zing_urlencode(DB_USER);
	//$vars.='&wpp='.zing_urlencode(DB_PASSWORD);
	$vars.='&wpn='.md5(DB_HOST.DB_NAME.DB_USER.DB_PASSWORD);
	$vars.='&wppageid='.zing_mailz_mainpage();
	$vars.='&wpsiteurl='.urlencode(get_option('siteurl'));
	if ($vars) $http.='?'.$vars;
	return $http;
}

/**
 * Activation: creation of database tables & set up of pages
 * @return unknown_type
 */
function zing_mailz_install_db() {
	global $wpdb;
	global $current_user;
	global $zing_mailz_options;

	if (isset($_REQUEST['action']) && ($_REQUEST['action']=='error_scrape')) {
		echo get_option('activation-output');
		return;
	}
	delete_option('activation-output');
	$prefix=$wpdb->prefix.ZING_MAILZ_PREFIX;
	$zing_mailz_version=get_option("zing_mailz_version");

	ob_start('zing_activation_output');

	//install
	if (!file_exists(ZING_MAILZ_LOC.'lists')) {
		if (!class_exists('ZipArchive')) die('Class ZipArchive doesn\'t exist, try installing it or manually unzip the file lists.zip in the plugin folder. Then try the upgrade again.');

		$file=ZING_MAILZ_LOC.'lists.zip';
		$to=ZING_MAILZ_LOC.'lists';

		$zip = new ZipArchive;
		$res = $zip->open($file);
		if ($res === TRUE) {
			$zip->extractTo($to);
			$zip->close();
			if (file_exists(ZING_MAILZ_LOC.'__MACOSX')) zing_mailz_rrmdir(ZING_MAILZ_LOC.'__MACOSX');
		} else {
			echo 'Failed to install latest copy of phpList (' . $res . ')';
			die();
		}
	}

	//create database tables
	if (!$zing_mailz_version) {
		$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage'=>'initialise','firstintall'=>1));
		$news = new zHttpRequest($http,'mailz');
		if ($news->live()) {
			$output=$news->DownloadToString();
		}
	} else {
		foreach (array('user','user_history','attribute','user_attribute') as $t) { //renaming tables to use new prefix
			$wpdb->query("RENAME TABLE ".$wpdb->prefix.$t." TO ".$prefix.'phplist_'.$t);
			$wpdb->query("RENAME TABLE ".$prefix.$t." TO ".$prefix.'phplist_'.$t);
		}
		$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage'=>'upgrade','doit'=>'yes'));
		$news = new zHttpRequest($http,'mailz');
		if ($news->live()) {
			$output=$news->DownloadToString();
		}
	}

	//verify installation
	$query="show tables like '".$prefix."phplist_%'";
	$rows = $wpdb->get_results($query,ARRAY_N);
	if (count($rows) == 0) {
		print('Could not create phpList database tables');
		ob_flush();
	}

	//set admin password
	$password=md5(time().get_option('home'));
	$query="update ".$prefix."phplist_admin set password='".$password."' where loginname='admin'";
	$wpdb->query($query);
	update_option("zing_mailz_password",$password);

	//set configuration options
	$query="update ".$prefix."phplist_config set value='".str_replace('http://','',get_option('siteurl'))."' where item='website'";
	$wpdb->query($query);

	//create standard pages
	if ($zing_mailz_version <= '0.1') {
		$pages=array();
		$pages[]=array("Mailing list","mailz","*",0);

		$ids="";
		foreach ($pages as $i =>$p)
		{
			$my_post = array();
			$my_post['post_title'] = $p['0'];
			$my_post['post_content'] = '';
			$my_post['post_status'] = 'publish';
			$my_post['post_author'] = 1;
			$my_post['post_type'] = 'page';
			$my_post['comment_status'] = 'closed';
			$my_post['menu_order'] = 100+$i;
			$id=wp_insert_post( $my_post );
			if (empty($ids)) { $ids.=$id; } else { $ids.=",".$id; }
			if (!empty($p[1])) add_post_meta($id,'zing_mailz_page',$p[1]);
		}
		update_option("zing_mailz_pages",$ids);
	}

	//default options
	if (count($zing_mailz_options) > 0) {
		foreach ($zing_mailz_options as $value) {
			delete_option( $value['id'] );
			if ( !empty($value['id']) && !get_option($value['id']) ) update_option( $value['id'], $value['std'] );
		}
	}

	update_option("zing_mailz_version",ZING_MAILZ_VERSION);

}

/**
 * Uninstallation: removal of database tables
 * @return void
 */
function zing_mailz_uninstall() {
	global $wpdb;

	$prefix=$wpdb->prefix.ZING_MAILZ_PREFIX;
	$rows=$wpdb->get_results("show tables like '".$prefix."phplist_%'",ARRAY_N);
	if (count($rows) > 0) {
		foreach ($rows as $id => $row) {
			$query="drop table ".$row[0];
			$wpdb->query($query);
		}
	}
	$ids=get_option("zing_mailz_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		wp_delete_post($id);
	}
	
	delete_option("zing_mailz_key");
	delete_option("zing_mailz_cron");
	delete_option("zing_mailz_password");
	delete_option("zing_mailz_remote");
	delete_option("zing_mailz_version");
	delete_option("zing_mailz_pages");
	delete_option("zing_mailz_news");
	delete_option("zing_mailz_news_time");
}

function zing_mailz_login() {
	global $current_user,$wpdb;

	$loggedin=false;

	if (!isset($_SESSION['zing']['mailz']['loggedin'])) $_SESSION['zing']['mailz']['loggedin']=0;
	if (!current_user_can('edit_plugins') && $_SESSION['zing']['mailz']['loggedin'] > 0) {
		zing_mailz_logout();
	}
	if (!is_admin()) {
		$loggedin=true;
	} elseif (is_admin() && current_user_can('edit_plugins') && time()-$_SESSION['zing']['mailz']['loggedin'] > 60) { //We relogin every minute to avoid time outs
		$post['do']='scplogin';
		$post['login']='admin';//$current_user->data->user_login;
		$post['password']=get_option('zing_mailz_password');
		$post['submit']='Enter';
		$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage' => null));
		$news = new zHttpRequest($http,'mailz');
		$news->post=$post;
		if ($news->live()) {
			$output=stripslashes($news->DownloadToString());
			if (strpos($output,"invalid password")===false && strpos($output,"Default login is admin")===false) {
				$loggedin=true;
				$_SESSION['zing']['mailz']['loggedin']=time();
			} else echo '<br /><strong style="color:red">Couldn\'t log in to PHPlist</strong><br />';
		}
	}
	elseif (isset($_SESSION['zing']['mailz']['loggedin'])) $loggedin=true;
	return $loggedin;
}

function zing_mailz_logout() {
	$_GET['zlistpage']='logout';
	$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage' => null));
	$news = new zHttpRequest($http,'mailz');
	if ($news->live()) {
		$output=$news->DownloadToString(true);
		unset($_SESSION['zing']['mailz']['loggedin']);
	}
}

/*
 function zing_mailz_more_reccurences() {
 return array(
 'minute' => array('interval' => 60, 'display' => 'Every minute'),
 'weekly' => array('interval' => 604800, 'display' => 'Once Weekly'),
 'fortnightly' => array('interval' => 1209600, 'display' => 'Once Fortnightly'),
 );
 }
 add_filter('cron_schedules', 'zing_mailz_more_reccurences');
 */

function zing_mailz_cron() {

	$msg=time();

	$post['login']='admin';
	$post['password']=get_option('zing_mailz_password');

	$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage'=>'processqueue','user'=>'admin','password'=>get_option('zing_mailz_password')));

	$news = new zHttpRequest($http,'mailz');
	$news->post=$post;

	if ($news->live()) {
		$output=$news->DownloadToString();
		$msg.='ok';
	} else {
		$msg.='failed';
	}
	update_option('zing_mailz_cron',$msg);
}
if (!wp_next_scheduled('zing_mailz_cron_hook')) {
	wp_schedule_event( time(), 'hourly', 'zing_mailz_cron_hook' );
}
add_action('zing_mailz_cron_hook','zing_mailz_cron');

function zing_mailz_footer() {
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $footer;

	//Please contact us if you wish to remove the Zingiri logo in the footer
	$f='<div style="clear:both"></div>';
	$f.='<center style="margin-top:0px;font-size:x-small">';
	$f.='Wordpress and <a href ="http://www.phplist.com/" target="_blank">phpList</a> integration by <a href="http://www.zingiri.com" target="_blank">Zingiri</a>';
	$f.='</center>';

	return $f;
}

function zing_mailz_pre_upgrade($success, $hook_extra) {
	if ($success && ($hook_extra['plugin'] == 'mailz/mailz.php')) {
		echo '<p>Backing up lists folder</p>';
		zing_mailz_recurse_copy(ZING_MAILZ_LOC.'lists',BLOGUPLOADDIR.'lists.tmp');
	}
}

function zing_mailz_post_upgrade($success, $hook_extra, $result) {
	if ($success && ($hook_extra['plugin'] == 'mailz/mailz.php')) {
		echo '<p>Restoring lists folder</p>';
		zing_mailz_recurse_copy(BLOGUPLOADDIR.'lists.tmp',ZING_MAILZ_LOC.'lists');
		zing_mailz_rrmdir(BLOGUPLOADDIR.'lists.tmp');
	}

}

function zing_mailz_recurse_copy($src,$dst) {
	$dir = opendir($src);
	if (!file_exists($dst)) mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (!in_array($file,array('.','..','.svn'))) {
			if ( is_dir($src . '/' . $file) ) {
				zing_mailz_recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}

function zing_mailz_rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") zing_mailz_rrmdir($dir."/".$object);
				else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}
