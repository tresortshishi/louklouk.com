<?php


/**
 * This model class interacte with db to update blog user list
 * 
 * 
 * @author Trésor Kandolo Tshishi
 * @since 0.1
 * 
 */

 abstract class Model_interface{

 	/**
 	 * Plugin post type name
 	 * 
 	 * @var string
 	 *
 	 */
 	public $post_type = 'company';

 	/**
 	 * Plugin custom fields for company post type
 	 * @var array
 	 *
 	 */

 	/*
	 $columns['adress'] = __('Adress');
  $columns['number'] = __('Number');
  $columns['zip_code'] = __('Zip code');
  $columns['country'] = __('Country');
  $columns['phonenumber'] = __('Phone number');
  $columns['blogname'] = __('Blog name');
  $columns['blogtitle'] = __('Blog title');
  $columns['activation_code'] = __('Activation code');

 	*/
 	public $custom_fields = array(
 								'adress',
 								'number',
 								'zip_code',
 								'country',
 								'phone_number',
 								'blog_name',
 								'blog_title',
 								'activation_code',
 								);

	abstract function add($args);
	abstract function update($id,$args);
	
}


