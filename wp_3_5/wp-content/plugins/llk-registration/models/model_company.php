<?php
if(!class_exists('Model_interface'))
	require_once(LLK_REGISTRATION_PATH.'models/model_interface.php');
/**
 * This model class interacte with db to update blog user list
 * 
 * 
 * @author Trésor Kandolo Tshishi
 * @since 0.1
 *
 */

class Model_company extends Model_interface{


	/**
	 * constructor
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * PhP 4 constructor
	 *
	 */
	public function Model_company()
	{
		$this->__construct();

	}




	/**
	 * Add new company
	 * @param	$args Array User data
	 * @since	0.1
	 * @see 	http://codex.wordpress.org/Function_Reference/wp_insert_user
	 * @return array user info
	 */
	public function add($args)
	{
		
		return wp_insert_post($args);

	}

	public function update($id,$args)
	{

	}
	
}


