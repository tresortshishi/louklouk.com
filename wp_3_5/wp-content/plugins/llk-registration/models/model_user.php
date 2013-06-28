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

class Model_user extends Model_interface{


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
	public function Model_user()
	{
		$this->__construct();

	}




	/**
	 * Add new user
	 * @param	$args Array User data
	 * @since	0.1
	 * @see 	http://codex.wordpress.org/Function_Reference/wp_insert_user
	 * @return array user info
	 */
	public function add($args)
	{
		/*
		user_pass	 A string that contains the plain text password for the user.	pre_user_pass
		user_login	 A string that contains the user's username for logging in.	pre_user_login
		user_nicename
		*/
		/*return wp_insert_user(
					array(
						'user_pass'=>$args['user_pass'],
						'user_login'=>$args['user_login'],
						'user_email'=>$args['user_email'],
						'user_nicename'=>$args['user_nicename'],
						'role'=>$args['role']
					)
				);*/
	return wp_insert_user($args);

	}

	public function update($id,$args)
	{

	}
	
}


