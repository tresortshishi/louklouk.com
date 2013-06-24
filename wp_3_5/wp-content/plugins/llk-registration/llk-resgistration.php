<?php
/**
 * @package llk_registration
 * @version 0.1
 */
/*
Plugin Name: Louklouk registration
Plugin URI: http://louk-louk.com
Description: This plugin register new members or new enterprise with their owners 
Author: Tshishi Kandolo Trésor
Version: 0.1
Author URI: http://twitter.com/goosy13
*/


//create enterprise table if not exist

/**
 * Check if table 'wp_llk_enterprise' is create
 *
 *
 * @return void
 */
function init_table(){

	global $wpdb;

   $table_name = $wpdb->prefix . "llk-registration"; 

   $sql = "CREATE TABLE IF NOT EXISTS `wp_llk_registration` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'owner name',
  `firstname` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `activation_code` varchar(100) NOT NULL,
  `registration_code` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `enterprise_name` varchar(100) NOT NULL,
  `enterprise_description` mediumtext NOT NULL,
  `adress` varchar(200) NOT NULL,
  `number` int(50) NOT NULL,
  `box_number` int(100) NOT NULL,
  `zip_code` varchar(100) NOT NULL,
  `phonenumber` varchar(100) NOT NULL,
  `blog_adress` varchar(100) NOT NULL,
  `blog_title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

}

add_action( 'admin_init', 'init_table' );

//add menu "enterprise " in admin dashboard

add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
   // add_menu_page( 'custom menu title', 'custom menu', 'manage_options', str_replace(site_url(), '', plugins_url('llk-registration/admin_pages/index.php')) /*, 'my_custom_menu_page', plugins_url( 'myplugin/images/icon.png' ), 6 */); 
    add_menu_page( 'custom menu title', 'custom menu', 'manage_options', 'edit.php' /*, 'my_custom_menu_page', plugins_url( 'myplugin/images/icon.png' ), 6 */); 
}

function my_custom_menu_page(){
    echo "Admin Page Test";	
}

?>