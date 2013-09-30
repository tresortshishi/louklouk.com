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
register_activation_hook( __FILE__, 'add_user_role_customer' );

/**
 * add new user role
 *
 */

function add_user_role_customer()
{
  add_role('customer', 'Customer', array(
    'read' => true
  ));
}


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
function llk_registration_form()
{
  //echo language_attributes();
  $current_lg = 'en';
   ?>
    <form action="" method="post" id="registration">
    <?php     

      
      //get signing type action (user /enterprise/ enterprise_confirmation)
      $type = @$_REQUEST['type'];
      

      switch ($type) {
        case 'user_sign':
        //add new user
          //if ($current_lg!==""){        
            if(file_exists(LLK_REGISTRATION_PATH.'forms/register_user/'.$current_lg.'/'.'register_user.php')){
            require(LLK_REGISTRATION_PATH.'forms/register_user/'.$current_lg.'/'.'register_user.php');
              }
          //}
        
          break;
        case 'user_add':
              add_subcribers();
        break;
        case 'entreprise_sign':
        //add enterprise sign in waiting queue
          //if ($current_lg!==""){        
            if(file_exists(LLK_REGISTRATION_PATH.'forms/register_enterprise/'.'register_entreprise.php')){
            
            require(LLK_REGISTRATION_PATH.'forms/labels/'.$current_lg.'/'.'register_enterprise.php');
            //echo get_template_directory().'/forms/labels/'.$current_lg.'/'.'register_enterprise.php';
            //load labels translation
            if(isset($labels_register_enterprise)){
              // call enterprise forms
              require(LLK_REGISTRATION_PATH.'/forms/register_enterprise/'.'register_entreprise.php');

            }
              }
        
          break;
        case 'entreprise_add' :
          // add new enterprise
          add_company_customer();

        break;
        case 'enteprise_confirmation':
        //confirmation of enterprise owner to create enterprise article and blog
          # code...
          break;

        default:

        //
          break;
      }
    ?>
    </form>
    <?php
}

/**
 * Form chooser
 *
 */

function llk_registration_form_chooser(){

 $current_title_page  = ucfirst(get_query_var('pagename'));//current page title
   
  ?>
    <form action="" method="get">
      
            <label>Faite votre choix</label>
                  <select name="type" size="1" dir="ltr" id="sign_type">
                    <option value="">---</option>
                    <option value="Membres">Membres1</option>
                    <option value="Entreprise">Entreprise1</option>
                   </select>  
    </form>
  <?php
  
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

add_filter('editable_roles', function($roles){

  // sort alphabetically (ignores case)
  usort($roles, function($a, $b){
    return strcasecmp($a["name"], $b["name"]);
  });   

  return $roles;
});

/////////////////////////////////////////////////////////////////////////////
//
//      Admin manager
//
/////////////////////////////////////////////////////////////////////////////

require_once(LLK_REGISTRATION_PATH.'admin_manager.php');


///////////////////////////////////////////////////////////////////////////
//
//    Front actions
//
//
///////////////////////////////////////////////////////////////////////////

/**
 *
 *
 *
 * @since 0.1
 * @return void
 */
function add_subcribers()
{
  require_once(LLK_REGISTRATION_PATH.'classes/user.php');

  if(isset($_POST)){
    if(isset($_POST['action']) && ($_POST['action'] == 'add_subcribers')){
      $new_user             = new User();
      $new_user->name       = $_POST['name'];
      $new_user->lastname   = $_POST['firstname'];
      $new_user->login      = $_POST['login'];
      $new_user->password   = $_POST['password'];
      $new_user->email      = $_POST['email'];
      $new_user->add_user();
    }
  }

}

/**
 * Add new customer user and create new post compy
 * @since 0.1
 * @return void
 */

function add_company_customer()
{
    require_once(LLK_REGISTRATION_PATH.'classes/user.php');
    require_once(LLK_REGISTRATION_PATH.'classes/company.php');
    $rand = rand();
    $new_company = new Company();
    $new_company->name = 'name'.$rand;
    $new_company->description = 'description'.$rand;
  
    $new_company->adress = 'adress'.$rand;
    $new_company->number = 'description'.$rand;
    $new_company->zip_code = 'zip_code'.$rand;
    $new_company->country = 'country'.$rand;
    $new_company->phone_number = 'phone_number'.$rand;
    $new_company->blog_name = 'blog_name'.$rand;
    $new_company->blog_title = 'blog_title'.$rand;
    $new_company->activation_code = 'activation_code'.$rand;


    $new_user             = new User();
    $new_user->name       = 'testname'.$rand;
    $new_user->lastname   = 'test-lastname'.$rand;
    $new_user->login      = 'test_login'.$rand;
    $new_user->password   = 'test_password'.$rand;
    $new_user->email      = $new_user->name.'@test.com';

    $new_company->register($new_user);

}

// test function

function test()
{
  
  require_once(LLK_REGISTRATION_PATH.'classes/user.php');
  require_once(LLK_REGISTRATION_PATH.'classes/company.php');
  $rand = rand();
  $new_company = new Company();
  $new_company->name = 'name'.$rand;
  $new_company->description = 'description'.$rand;
  /*
      'adress',
                'number',
                'zip_code',
                'country',
                'phone_number',
                'blog_name',
                'blog_title',
                'activation_code',
    */
     $new_company->adress = 'adress'.$rand;
     $new_company->number = 'description'.$rand;
     $new_company->zip_code = 'zip_code'.$rand;
     $new_company->country = 'country'.$rand;
     $new_company->phone_number = 'phone_number'.$rand;
     $new_company->blog_name = 'blog_name'.$rand;
     $new_company->blog_title = 'blog_title'.$rand;
     $new_company->activation_code = 'activation_code'.$rand;


    $new_user             = new User();
    $new_user->name       = 'testname'.$rand;
    $new_user->lastname   = 'test-lastname'.$rand;
    $new_user->login      = 'test_login'.$rand;
    $new_user->password   = 'test_password'.$rand;
    $new_user->email      = $new_user->name.'@test.com';

    $new_company->register($new_user);
 
  /*$new_company->owner_id        = $new_user->add_owner();

  $new_company->add_company();*/
 /* $new_company->adress = 'test-adress';
  $fields = $new_company->company_mdl->custom_fields;
  $fields = 'adress';
  var_dump($new_company->$fields);
  add_post_meta(153, 'test-key1', 'test-value1',true);
  */
  

}

add_action('admin_init','test',100);

?>