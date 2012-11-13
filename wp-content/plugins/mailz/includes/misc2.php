<?php
define("ZING_PHPLIST_URL",'http://eu1.ml.clientcentral.info'); //URL end point for web services stored on Zingiri servers

$zing_mailz_options[]=array(  "name" => "General settings",
            "type" => "heading",
			"desc" => "This section manages the Mailing List settings.");
$zing_mailz_options[]=array(	"name" => "API key",
			"desc" => "This plugin uses remote web services to provide mailing list functionality. This API key has been automatically generated for you. Once you click on Install, the API key, in combination with your web site address <strong>".home_url()."</strong> will create an account on our servers allowing the plugin to access the remote web services.<br />The combination of API key and your web site address uniquely identifes you so please make sure to keep it in a safe place. ",
			"id" => "zing_mailz_key",
			"type" => "text");
$zing_mailz_options[]=array(	"name" => "Show footer",
			"desc" => "Show your support by enabling our page footer.",
			"id" => "zing_mailz_footer",
			"type" => "checkbox");
$zing_mailz_options[]=array(  "name" => "Before you install",
		"type" => "heading",
		"desc" => '<div style="text-decoration:underline;display:inline;font-weight:bold">IMPORTANT:</div> Mailing List uses web services stored on Zingiri\'s servers. In doing so, personal data is collected and stored on our servers. 
				This data includes amongst others your admin email address as this is used, together with the API key as a unique identifier for your account on Zingiri\'s servers.
				We have a very strict <a href="http://www.zingiri.com/privacy-policy/" target="_blank">privacy policy</a> as well as <a href="http://www.zingiri.com/terms/" target="_blank">terms & conditions</a> governing data stored on our servers.
				<div style="font-weight:bold;display:inline">By installing this plugin you accept these terms & conditions.</div>');

function zing_mailz_http($module,$to_include="index",$get=array()) {
	global $wpdb,$current_user;

	$vars="";
	if (!$to_include || $to_include==".php") $to_include="index";

	$t=explode('/',$to_include);
	if (count($t)==2) {
		$http=ZING_PHPLIST_URL.'/'.$t[0].'/api.php';
		//$pg=$t[1];
	} else {
		$http=ZING_PHPLIST_URL.'/api.php';
		//$pg=$t[0];
	}

	$and="&";
	$vars="pg=".urlencode($to_include);

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

	/*
	 $vars.=$and.'&wpf='.zing_urlencode($wpdb->prefix);
	 $vars.='&wpn='.md5(DB_HOST.DB_NAME.DB_USER.DB_PASSWORD);
	 $vars.='&wppageid='.zing_mailz_mainpage();
	 $vars.='&wpsiteurl='.urlencode(get_option('siteurl'));
	 */
	$wp=array();
	if (is_user_logged_in()) {
		$wp['login']=$current_user->data->user_login;
		$wp['email']=$current_user->data->user_email;
		$wp['first_name']=isset($current_user->data->first_name) ? $current_user->data->first_name: $current_user->data->display_name;
		$wp['last_name']=isset($current_user->data->last_name) ? $current_user->data->last_name : $current_user->data->display_name;
		$wp['roles']=$current_user->roles;
	}
	$wp['default_page']=zing_mailz_default_page();
	$wp['lic']=get_option('zing_mailz_lic');
	$wp['gmt_offset']=get_option('gmt_offset');
	$wp['siteurl']=home_url();
	$wp['sitename']=get_bloginfo('name');
	$wp['pluginurl']=ZING_MAILZ_URL;
	if (is_admin()) {
		$wp['mode']='b';
		//$wp['pageurl']=zing_mailz_home();
		$wp['pageurl']=get_admin_url().'admin.php?page=bookings&';
	} else {
		$wp['mode']='f';
		$wp['pageurl']=zing_mailz_home();
	}

	$wp['time_format']=get_option('time_format');
	$wp['admin_email']=get_option('admin_email');
	$wp['key']=get_option('zing_mailz_key');
	$wp['lang']=get_option('zing_mailz_lang'); //get_bloginfo('language');
	$wp['client_version']=ZING_MAILZ_VERSION;
	//if (current_user_can(zing_mailz_ADMIN_CAP)) $wp['cap']='admin';
	//elseif (current_user_can(zing_mailz_USER_CAP)) $wp['cap']='operator';

	$vars.='&wp='.urlencode(base64_encode(json_encode($wp)));


	if ($vars) $http.='?'.$vars;

	//echo '<br /><br /><br />'.$http;

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

	$zing_mailz_version=get_option("zing_mailz_version");

	//create standard pages
	if (!$zing_mailz_version) {
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

	//setup
	if (!$zing_mailz_version) {
		$http=zing_mailz_http('mailz','admin/index.php',array('zlistpage'=>'initialise','firstintall'=>1));
		$news = new zHttpRequest($http,'mailz');
		if ($news->live()) {
			$output=$news->DownloadToString();
			//echo $output;die();
		}
	}

	//default options
	if (count($zing_mailz_options) > 0) {
		foreach ($zing_mailz_options as $value) {
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

	$http=zing_mailz_http('mailz','deactivate.php');
	$news = new zHttpRequest($http,'mailz');
	if ($news->live()) {
		$output=$news->DownloadToString();
	}

	$ids=get_option("zing_mailz_pages");
	$ida=explode(",",$ids);
	foreach ($ida as $id) {
		wp_delete_post($id);
	}
	delete_option("zing_mailz_key");
	delete_option("zing_mailz_remote");
	delete_option("zing_mailz_version");
	delete_option("zing_mailz_pages");
	delete_option("zing_mailz_news");
	delete_option("zing_mailz_news_time");
}

function zing_mailz_login() {
	global $current_user,$wpdb;

	return true;

	$loggedin=false;

	if (!isset($_SESSION['zing']['mailz']['loggedin'])) $_SESSION['zing']['mailz']['loggedin']=0;
	if (!current_user_can('edit_plugins') && $_SESSION['zing']['mailz']['loggedin'] > 0) {
		zing_mailz_logout();
	}
	if (!is_admin()) {
		$loggedin=true;
	} elseif (is_admin() && current_user_can('edit_plugins') && time()-$_SESSION['zing']['mailz']['loggedin'] > 60) { //We relogin every minute to avoid time outs
	}
	elseif (isset($_SESSION['zing']['mailz']['loggedin'])) $loggedin=true;
	return $loggedin;
}

function zing_mailz_logout() {
	return true;
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
//add_action('zing_mailz_cron_hook','zing_mailz_cron');

function zing_mailz_default_page() {
	$pageID=zing_mailz_mainpage();
	if (get_option('permalink_structure')){
		$homePage = get_option('home');
		$wordpressPageName = get_permalink($pageID);
		$wordpressPageName = str_replace($homePage,"",$wordpressPageName);
		$home=$homePage.$wordpressPageName;
		if (substr($home,-1) != '/') $home.='/';
		$and='?';
	}else{
		$home=get_option('home').'/?page_id='.$pageID;
		$and='&';
	}
	return $home.$and;


}

function zing_mailz_home() {
	global $post,$page_id;

	$pageID = $page_id;

	if (get_option('permalink_structure')){
		$homePage = get_option('home');
		$wordpressPageName = get_permalink($pageID);
		$wordpressPageName = str_replace($homePage,"",$wordpressPageName);
		$home=$homePage.$wordpressPageName;
		if (substr($home,-1) != '/') $home.='/';
		$home.='?';
	}else{
		$home=get_option('home').'/?page_id='.$pageID.'&';
	}

	return $home;
}

function zing_mailz_footer() {
	$bail_out = ( ( defined( 'WP_ADMIN' ) && WP_ADMIN == true ) || ( strpos( $_SERVER[ 'PHP_SELF' ], 'wp-admin' ) !== false ) );
	if ( $bail_out ) return $footer;

	$f='';
	if (get_option('zing_mailz_footer')) {
		$f='<div style="clear:both"></div>';
		$f.='<center style="margin-top:0px;font-size:x-small">';
		$f.='Wordpress and <a href ="http://www.phplist.com/" target="_blank">phpList</a> integration by <a href="http://www.zingiri.com" target="_blank">Zingiri</a>';
		$f.='</center>';
	}

	return $f;
}

