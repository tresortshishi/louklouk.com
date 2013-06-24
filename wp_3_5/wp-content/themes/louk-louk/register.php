<?php
/*
Template Name: register
*/


/**
* Customized by Tshishi Trésor 24/02/2013
*
*/
get_header(); ?>

<?php //get current uri
			$current_uri = get_permalink( $post->ID );
			//default code language
			$current_lg = 'fr';
			//find code language 
			$uri_part = explode('/', $current_uri);
			$current_lg = (string)$uri_part[3];
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<!--  display form-->

		<?php 
			//submition form
			if(isset($_REQUEST['form'])){
				var_dump($_REQUEST);
				//clean post data
				unset($_REQUEST['form']);
				unset($_REQUEST['owner']);
				unset($_REQUEST['type']);
				unset($_REQUEST['email1']);
				unset($_REQUEST['password1']);
				unset($_REQUEST['submit']);

				global $wpdb;

   				$table_name = $wpdb->prefix . "llk-registration";
   				$wpdb->insert($table_name,$_REQUEST);

			}
		?>

		<h2><?php echo ucfirst(get_query_var('pagename'));//current page title?></h2>
		 <form action="" method="get">
            <label>Faite votre choix</label>
                  <select name="type" size="1" dir="ltr" id="sign_type">
                    <option>---</option>
                    <option>Membres</option>
                    <option>Entreprise</option>
                   </select>  
         </form>     

         <form action="" method="post" id="registration">         
			<?php 

			
			//get signing type action (user /enterprise/ enterprise_confirmation)
			$type = @$_REQUEST['type'];
			

			switch ($type) {
				case 'user_sign':
				//add new user
					//if ($current_lg!==""){				
						if(file_exists(get_template_directory().'/forms/register_user/'.$current_lg.'/'.'register_user.php')){
						require(get_template_directory().'/forms/register_user/'.$current_lg.'/'.'register_user.php');
							}
					//}
				
					break;
				case 'user_add':
				break;
				case 'entreprise_sign':
				//add enterprise sign in waiting queue
					//if ($current_lg!==""){				
						if(file_exists(get_template_directory().'/forms/register_enterprise/'.'register_entreprise.php')){
						
						require(get_template_directory().'/forms/labels/'.$current_lg.'/'.'register_enterprise.php');
						//echo get_template_directory().'/forms/labels/'.$current_lg.'/'.'register_enterprise.php';
						//load labels translation
						if(isset($labels_register_enterprise)){
							// call enterprise forms
							require(get_template_directory().'/forms/register_enterprise/'.'register_entreprise.php');

						}
							}
					//}

					//old code
					/*
					if ($current_lg!==""){				
						if(file_exists(get_template_directory().'/forms/register_enterprise/'.$current_lg.'/'.'register_entreprise.php')){
						
						require(get_template_directory().'/forms/labels/'.$current_lg.'/'.'register_enterprise.php');
						//echo get_template_directory().'/forms/labels/'.$current_lg.'/'.'register_enterprise.php';
						//load labels translation
						if(isset($labels_register_enterprise)){
							require(get_template_directory().'/forms/register_enterprise/'.$current_lg.'/'.'register_entreprise.php');
							//test general forms
							

						}
							}
					}
					*/
					//print activation code

					break;
				case 'entreprise_add' :
					// add new enterprise
					// send email notification
				/*

				INSERT INTO `louklouk`.`wp_llk_registration` (`id`, `name`, `firstname`, `login`, `email`, `password`, `country`, `activation_code`, `registration_code`, `status`, `enterprise_name`, `enterprise_description`, `adress`, `number`, `box_number`, `zip_code`, `phonenumber`, `blog_adress`, `blog_title`) VALUES (NULL, 'xb', 'xcv', 'xcv', 'xcv', 'xcv', 'xcv', 'xcv', 'xcv', 'xcv', 'xcv', 'xcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsvxcvgvsdvsv', 'uktyuktk', '145', '1070', '1070', '14574845854', 'test', 'testtt');
				*/

				break;
				case 'enteprise_confirmation':
				//confirmation of enterprise owner to create enterprise article and blog
					# code...
					break;

				default:

				//
					break;
			}
			// display sigin type (user or enterprise)
				

			


		?>
			
                
        
     	</form>


 </div><!-- #content-->
	</div><!-- #primary -->

<!-- load js for  the registration page-->

<?php

function register_enterprise(){
	
	$wpdb->query("
		INSERT INTO `wp_llk_register_enterprise` 
		(`email`, `nom`, `prenom`, `adress_num`, `adress_box_num`, `zip_code`, `adress`, `activation_code`, `enterprise_name`, `sector`) 
		VALUES ('".$_REQUEST['name']."', 'sss', 'sss', 'sss', 'sss', 'ssss', 'sss', 'sss', 'sss', 'sss');");
}

function add_this_script_footer(){ ?>
<script src="<?php echo get_template_directory_uri().'/js/registration.js' ?>">
// check form before submit
</script>

<?php } 

add_action('wp_footer', 'add_this_script_footer'); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>