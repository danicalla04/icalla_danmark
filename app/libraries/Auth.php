<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * Simple Authentication Library
 * ------------------------------------------------------------------
 * Simplified version using only simplecrud_tb table
 * 
 * @package LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/**
 * Auth Class
 * Uses only simplecrud_tb table - no additional tables needed
 */
class Auth {

	private $LAVA;

	public function __construct() {
		$this->LAVA =& lava_instance();
		$this->LAVA->call->database();
		$this->LAVA->call->library('session');
	}

	/**
	 * Password Default Hash
	 * @param  string $password User Password
	 * @return string  Hashed Password
	 */
	public function passwordhash($password)
	{
		$options = array(
		'cost' => 12, // Increased security
		);
		return password_hash($password, PASSWORD_BCRYPT, $options);
	}

	/**
	 * Register new user
	 * @param  string $name     User Name
	 * @param  string $email    Email
	 * @param  string $password Password
	 * @param  string $number   Phone Number
	 * @return mixed User ID on success, false on failure
	 */
	public function register($name, $email, $password, $number)
	{
		$data = array(
			'name' => $name,
			'email' => $email,
			'number' => $number,
			'password' => $this->passwordhash($password)
		);

		$res = $this->LAVA->db->table('simplecrud_tb')->insert($data);
		if($res) {
			return $this->LAVA->db->last_id();
		} else {
			return false;
		}
	}

	/**
	 * Login user
	 * @param  string $email Email
	 * @param  string $password Password
	 * @return mixed User ID on success, false on failure
	 */
	public function login($email, $password)
	{				
    	$row = $this->LAVA->db
    					->table('simplecrud_tb') 					
    					->where('email', $email)
    					->get();
		if($row) {
			if(password_verify($password, $row['password'])) {
					return $row['id'];
			} else {
			return false;
			}
		}
	}

	/**
	 * Change Password
	 * @param string $password New password
	 * @return bool Success status
	 */
	public function change_password($password) {
		$data = array(
					'password' => $this->passwordhash($password)
				);
		return  $this->LAVA->db
					->table('simplecrud_tb')
					->where('id', $this->get_user_id())
					->update($data);
	}

	/**
	 * Set up session for login (Simple version)
	 * @param int $user_id User ID
	 */
	public function set_logged_in($user_id) {
		$this->LAVA->session->set_userdata(array('user_id' => $user_id, 'logged_in' => 1));
	}

	/**
	 * Check if user is Logged in (Simple version)
	 * @return bool TRUE is logged in
	 */
	public function is_logged_in()
	{
		return $this->LAVA->session->userdata('logged_in') == 1;
	}

	/**
	 * Get User ID
	 * @return string User ID from Session
	 */
	public function get_user_id()
	{
		$user_id = $this->LAVA->session->userdata('user_id');
		return !empty($user_id) ? (int) $user_id : 0;
	}

	/**
	 * Get User Name
	 * @param int $user_id User ID
	 * @return string Username
	 */
	public function get_username($user_id)
	{
		$row = $this->LAVA->db
						->table('simplecrud_tb')
						->select('name')					
    					->where('id', $user_id)
    					->limit(1)
    					->get();
    	if($row) {
    		return $row['name'];
    	}
	}

	/**
	 * Set logged out (Simple version)
	 * @return bool Success status
	 */
	public function set_logged_out() {
		$this->LAVA->session->unset_userdata(array('user_id', 'logged_in'));
		$this->LAVA->session->sess_destroy();
		return true;
	}


	/**
	 * Get user data by email
	 * @param string $email Email address
	 * @return mixed User data or false
	 */
	public function get_user_by_email($email) {
		return $this->LAVA->db
					->table('simplecrud_tb')
					->where('email', $email)
					->get();
	}

	/**
	 * Update user profile
	 * @param int $user_id User ID
	 * @param array $data User data to update
	 * @return bool Success status
	 */
	public function update_profile($user_id, $data) {
		return $this->LAVA->db
					->table('simplecrud_tb')
					->where('id', $user_id)
					->update($data);
	}

}

?>