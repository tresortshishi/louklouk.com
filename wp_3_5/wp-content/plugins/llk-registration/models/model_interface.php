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

	abstract function add($args);
	abstract function update($args);
	
}


