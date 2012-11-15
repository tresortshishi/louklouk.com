<?php
$zing_mailz_name = "Mailing List";
$zing_mailz_shortname = "zing_mailz";

function zing_mailz_upgrade() {
	global $zing_mailz_name, $zing_mailz_shortname, $zing_mailz_options;

	zing_mailz_install_db();
	foreach ($zing_mailz_options as $value) {
		if( isset( $_REQUEST[ $value['id'] ] ) ) {
			update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
		} else delete_option( $value['id'] );
	}
	header("Location: admin.php?page=mailz_cp");
	die();
}

function zing_mailz_install() {
	global $zing_mailz_name, $zing_mailz_shortname, $zing_mailz_options;

	if ($_REQUEST['action']=='install') {
		zing_mailz_install_db();
		foreach ($zing_mailz_options as $value) {
			if( isset( $_REQUEST[ $value['id'] ] ) ) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
			} else { delete_option( $value['id'] );
			}
		}
		header("Location: admin.php?page=mailz_cp&installed=true");
		die();
	} else {
		$message='<p>Ready to install this plugin? Simply click on the button below and wait a few seconds.</p><br />';
		$message.='<a href="admin.php?page=mailz_cp&action=install" class="button">Install</a><br />';
		zing_mailz_cp($message);
	}
}

function zing_mailz_admin_menu() {
	global $zing_mailz_name, $zing_mailz_shortname, $zing_mailz_options;
	global $zing_mailz_content;
	global $zing_mailz_menu;

	if (!class_exists('simple_html_dom_node')) require(dirname(__FILE__) . '/addons/simplehtmldom/simple_html_dom.php');
	$zing_mailz_version=get_option("zing_mailz_version");

	if (isset($_REQUEST['action']) && $_REQUEST['action']=='update' && isset($_REQUEST['page']) && $_REQUEST['page']=='mailz_setup') {
		foreach ($zing_mailz_options as $value) {
			if (isset($value['id'])) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				else delete_option( $value['id'] );
			}
		}
	}
	if (isset($_REQUEST['action']) && $_REQUEST['action']=='install' && isset($_REQUEST['page']) && $_REQUEST['page']=='mailz_setup') {
		foreach ($zing_mailz_options as $value) {
			if (isset($value['id'])) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) update_option( $value['id'], $_REQUEST[ $value['id'] ]  );
				else delete_option( $value['id'] );
			}
		}
		zing_mailz_install();
	}
	if (isset($_REQUEST['action']) && $_REQUEST['action']=='uninstall' && isset($_REQUEST['page']) && $_REQUEST['page']=='mailz_setup') {
		zing_mailz_uninstall();
	}

	if (empty($_GET['zlist'])) $_GET['zlist']='admin/index';
	if (!empty($_REQUEST['page']) && $_REQUEST['page'] != 'mailz_cp') {
		$_GET['zlistpage']=str_replace('mailz-','',$_REQUEST['page']);
		$_GET['zlist']='index';
	}

	add_menu_page($zing_mailz_name, $zing_mailz_name, 'administrator', 'mailz_cp','zing_mailz_admin');
	if (get_option("zing_mailz_version")) {
		if ($zing_mailz_version) {
			zing_mailz_header();
			$html=str_get_html($_SESSION['mailz_menu']);
			$first=true;
			foreach($html->find('a') as $e) {
				$link=str_replace("admin.php?page=mailz_cp&zlist=index&zlistpage=","",$e->href);
				$label=ucfirst($e->innertext);
				if ($first) add_submenu_page('mailz_cp', $zing_mailz_name.'- '.$label, $label, 'administrator', 'mailz_cp', 'zing_mailz_admin');
				elseif (substr($link,0,3)!='div') {
					add_submenu_page('mailz_cp', $zing_mailz_name.'- '.$label, $label, 'administrator', 'mailz-'.$link, 'zing_mailz_admin');
				}
				$first=false;
			}
		}
	}
	add_submenu_page('mailz_cp', $zing_mailz_name.'- Integration', 'Integration', 'administrator', 'mailz_setup', 'zing_mailz_setup');
}

function zing_mailz_setup() {
	global $zing_mailz_name, $zing_mailz_shortname, $zing_mailz_options, $wpdb;

	if (!get_option('zing_mailz_key')) update_option('zing_mailz_key',md5(time().sprintf(mt_rand(),'%10d')));

	$controlpanelOptions=isset($zing_mailz_options) ? $zing_mailz_options : array();

	if ( isset($_REQUEST['installed']) && $_REQUEST['installed']=='Install' ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' installed.</strong></p></div>';
	elseif ( isset($_REQUEST['installed']) && $_REQUEST['installed']=='Upgrade' ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' upgraded.</strong></p></div>';
	elseif ( isset($_REQUEST['installed']) && $_REQUEST['installed']=='Update' ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' updated.</strong></p></div>';
	elseif ( isset($_REQUEST['installed']) && $_REQUEST['installed']=='Sync' ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' synced.</strong></p></div>';
	elseif ( isset($_REQUEST['uninstalled']) && $_REQUEST['uninstalled'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' uninstalled.</strong></p></div>';

	?>
<div class="wrap">
<div
	style="width: 75%; float: left; position: relative; min-height: 500px;">
<h2><b>Mailing List</b></h2>
<div style="float: left; width: 50%"><?php
$zing_mailz_version=get_option("zing_mailz_version");

?>
<form method="post"><?php require(dirname(__FILE__).'/includes/cpedit.inc.php')?>

<?php if (!$zing_mailz_version) { ?>
<p class="submit"><input class="button-primary" name="install"
	type="submit" value="Install" /> <input type="hidden" name="action"
	value="install" /></p>

<?php } elseif ($zing_mailz_version != ZING_MAILZ_VERSION) { ?>
<p class="submit"><input class="button-primary" name="install"
	type="submit" value="Upgrade" /> <input type="hidden" name="action"
	value="install" /></p>


<?php } elseif ($controlpanelOptions) { ?>

<p class="submit"><input class="button-primary" name="install"
	type="submit" value="Update" /> <input type="hidden" name="action"
	value="update" /></p>

<?php } ?></form>

<?php if ($zing_mailz_version) { ?>
<form method="post">
<p class="submit"><input name="uninstall" type="submit"
	value="Uninstall" /> <input type="hidden" name="action"
	value="uninstall" /></p>
</form>
<?php }?></div>
</div>
<?php 	require(dirname(__FILE__).'/includes/support-us.inc.php');
zing_support_us('mailing-list','mailz','mailz_cp',ZING_MAILZ_VERSION,true,ZING_MAILZ_URL);

?></div>
<?php
}


function zing_mailz_admin() {
	global $zing_mailz_name, $zing_mailz_shortname, $zing_mailz_options, $wpdb;

	if ( isset($_REQUEST['installed']) && $_REQUEST['installed'] ) echo '<div id="message" class="updated fade"><p><strong>'.$zing_mailz_name.' installed.</strong></p></div>';

	$zing_mailz_version=get_option("zing_mailz_version");

	zing_mailz_cp();
}

function zing_mailz_cp($message='') {
	global $zing_mailz_content,$zing_mailz_name,$zing_mailz_menu;

	$zing_mailz_version=get_option("zing_mailz_version");

	zing_mailz_head();

	echo '<div class="wrap">';
	echo '<div id="zing-mailz-cp-content">';
	if ($message) {
		echo '<h2><b>'.$zing_mailz_name.' - '.$_GET['zlistpage'].'</b></h2>';
		echo $message;
	} elseif ($zing_mailz_version) {
		if (isset($_GET['zlistpage']) && $_GET['zlistpage']=='admin') {
			echo 'Please use the <a href="users.php">Wordpress Users menu</a> to change <strong>admin</strong> user details';
		} else {
			echo '<div id="phplist">'.$zing_mailz_content.'</div>';
		}
	}
	echo '</div>';

	require(dirname(__FILE__).'/includes/support-us.inc.php');
	zing_support_us('mailing-list','mailz','mailz_cp',ZING_MAILZ_VERSION,true,ZING_MAILZ_URL);

	echo '</div>';
	?>
<div style="clear: both"></div>
<hr />
<p>For more info and support, contact us at <a
	href="http://www.zingiri.com/">Zingiri</a> or check out our <a
	href="http://forums.zingiri.com/">support forums</a>.</p>
<hr />
	<?php
}

add_action('admin_menu', 'zing_mailz_admin_menu', 10); ?>