<?php
/**
 * @package llk_registration
 * @author  Trésor Kadnolo Tshishi 
 * @since   wp 3.5 
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

define('WP_DEBUG',true);
define('LLK_REGISTRATION_VERSION','0.1');
define('LLK_REGISTRATION_CURRENT_PATH_THEME',get_theme_root().'/'.get_template());
define( 'LLK_REGISTRATION_PATH', plugin_dir_path(__FILE__) );

add_action( 'admin_init', 'init_table' );
add_shortcode('llk_registration','llk_registration_form') ;
add_shortcode('llk_registration_form_chooser','llk_registration_form_chooser') ;


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



// set short code to render forms

/**
 *  Display registration form
 *
 */
function llk_registration_form(){
  return "test llk_registration" ;
}

/**
 * Form chooser
 *
 */

function llk_registration_form_chooser(){

 $current_title_page = ucfirst(get_query_var('pagename'));//current page title
 $chooser = '<h2>'.$current_title_page.'</h2>
     <form action="" method="get">
            <label>Faite votre choix</label>
                  <select name="type" size="1" dir="ltr" id="sign_type">
                    <option>---</option>
                    <option>Membres</option>
                    <option>Entreprise</option>
                   </select>  
      </form>';
         

  return  $chooser ;
}

function add_this_script_footer(){
    ?>
  <script src="<?php echo plugins_url('llk-registration/js/registration.js') ?>"></script>

  <?php } 

add_action('wp_footer', 'add_this_script_footer'); 

//load_template( dirname( __FILE__ ) . '/templates/some-template.php' );

//load template for company post

function load_company_template(){
  if(file_exists(LLK_REGISTRATION_CURRENT_PATH_THEME.'/archive-company.php')){
    load_template(LLK_REGISTRATION_CURRENT_PATH_THEME.'/archive-company.php');
  }else{
    load_template( dirname( __FILE__ ) . '/templates/archive-company.php' );
  }

  
}

do_action('after_setup_theme','load_company_template');

/////////////////////////////////////////////////////////////////////////////
//
//      Admin manager
//
/////////////////////////////////////////////////////////////////////////////

require_once(LLK_REGISTRATION_PATH.'admin_manager.php');

// test function

function test()
{
  require_once(LLK_REGISTRATION_PATH.'models/model_interface.php');
  require_once(LLK_REGISTRATION_PATH.'models/model_user.php');
  $wp_user_mdl = new Model_user();
  echo $wp_user_mdl->post_type;
  $wp_user_mdl->add(array());
}

add_action('admin_init','test');

?>