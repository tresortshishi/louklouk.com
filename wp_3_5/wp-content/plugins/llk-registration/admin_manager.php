<?php 
/**
 *	@author Trésor Kandolo Tshishi
 *	Initialize all the plugin admin side
 *	Create post type 'company'
 *	Add menu
 *	and even more ..
 */




 //add menu "enterprise " in admin dashboard

add_action( 'admin_menu', 'register_my_custom_menu_page' );
add_action( 'init', 'create_company_post_type' );
add_filter('manage_edit-company_columns','company_add_columns');
//add_action( 'manage_company_posts_custom_column', 'company_fill_columns', 10, 2 );


function register_my_custom_menu_page(){
   // add_menu_page( 'custom menu title', 'custom menu', 'manage_options', str_replace(site_url(), '', plugins_url('llk-registration/admin_pages/index.php')) /*, 'my_custom_menu_page', plugins_url( 'myplugin/images/icon.png' ), 6 */); 
    add_menu_page( 'custom menu title', 'custom menu', 'manage_options', 'edit.php' /*, 'my_custom_menu_page', plugins_url( 'myplugin/images/icon.png' ), 6 */); 
}

function my_custom_menu_page(){
    echo "Admin Page Test";	
}



// add new post type 'Company'

/**
 * Create a new post type
 *
 * @since 0.1
 * @uses  register_post_type 	Add new post type to wordpress
 * @uses  register_taxonomy 	Add some useful element on to post type edit page
 * @return void
 */

function create_company_post_type() {

  $labels = array(
        'name' => __( 'Companies' ),
        'singular_name' => __( 'Company' ),
        'show_ui' => true,
        'capability_type' => 'company',
      );


  register_post_type( 'company',
    array(
      'labels' => $labels,
      'public' => true,
      'capabilities' => array(
                              'publish_posts'       => 'publish_companies',
                              'edit_posts'          => 'edit_companies',
                              'edit_others_posts'   => 'edit_others_companies',
                              'delete_posts'        => 'delete_companies',
                              'delete_others_posts' => 'delete_others_companies',
                              'read_private_posts'  => 'read_private_companies',
                              'edit_post'           => 'edit_event',
                              'delete_post'         => 'delete_event',
                              'read_post'           => 'read_event',
      ),
      // show in admin bar menu
      'show_in_admin_bar' => true,
      'has_archive' => true,
      'supports'=> array('title','editor','excerpt','author','thumbnail','page-attributes','custom-fields')
    )
  );

//create taxonomy and category for new post type "company"
  register_taxonomy( 'cat_company', 'company', array( 'hierarchical' => true, 'label' => 'Category company', 'query_var' => true, 'rewrite' => true ) );  


  
}

// add custom columns

function company_add_columns($columns)
{
  /*
name` varchar(100) NOT NULL COMMENT 'owner name',
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
  */

  $columns['id'] = __('ID');
  

  $columns['title'] = _x('Company name','column name');
  if ( isset( $columns['author'] ) ) $columns['author'] = __('Owner');
  $columns['adress'] = __('Adress');
  $columns['number'] = __('Number');
  $columns['zip_code'] = __('Zip code');
  $columns['country'] = __('Country');
  $columns['phonenumber'] = __('Phone number');
  $columns['blogname'] = __('Blog name');
  $columns['blogtitle'] = __('Blog title');
  $columns['activation_code'] = __('Activation code');
  $columns['companycategories'] = __('Categories');
  return $columns;
}

/**
 * What to display in the custom of Compant count table
 * @since 0.1
 * 
 *
 *
 */
 function company_fill_columns($column_name,$id)
{
	//compnany category
	$terms = get_the_terms( $post->company_id, 'company-category' );
 			
			if ( !empty( $terms) ) {
       	 		foreach ( $terms as $term )
			            $post_terms[] = '<a href="'.add_query_arg( 'company-category', $term->slug ).'">'.esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'company-category', 'display' ) ).'</a>';
			        echo join( ', ', $post_terms );
			}

}

