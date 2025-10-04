<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust Authentication Library
 * ------------------------------------------------------------------
 * Adapted from LavaLust-Auth for repository project
 * 
 * @package LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/**
 * Auth Class
 * Adapted to work with simplecrud.simplecrud_tb table structure
 */
class Auth {

	private $LAVA;

	public function __construct() {
		$this->LAVA =& lava_instance();
		$this->LAVA->call->database();
		$this->LAVA->call->library('session');
		$this->LAVA->call->helper('string');
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
	 * @param  string $email_token Email verification token
	 * @return mixed User ID on success, false on failure
	 */
	public function register($name, $email, $password, $number, $email_token)
	{
		$this->LAVA->db->transaction();
		$data = array(
			'name' => $name,
			'email' => $email,
			'number' => $number,
			'password' => $this->passwordhash($password),
			'email_token' => $email_token
		);

		$res = $this->LAVA->db->table('simplecrud_tb')->insert($data);
		if($res) {
			$this->LAVA->db->commit();
			return $this->LAVA->db->last_id();
		} else {
			$this->LAVA->db->roll_back();
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
	 * Set up session for login
	 * @param int $user_id User ID
	 */
	public function set_logged_in($user_id) {
		$session_data = hash('sha256', md5(time().$user_id));
		$data = array(
			'user_id' => $user_id,
			'browser' => $_SERVER['HTTP_USER_AGENT'],
			'ip' => $_SERVER['REMOTE_ADDR'],
			'session_data' => $session_data
		);
		$res = $this->LAVA->db->table('user_sessions')
				->insert($data);
		if($res) $this->LAVA->session->set_userdata(array('session_data' => $session_data, 'user_id' => $user_id, 'logged_in' => 1));
	}

	/**
	 * Check if user is Logged in
	 * @return bool TRUE is logged in
	 */
	public function is_logged_in()
	{
		$data = array(
			'user_id' => $this->LAVA->session->userdata('user_id'),
			'browser' => $_SERVER['HTTP_USER_AGENT'],
			'session_data' => $this->LAVA->session->userdata('session_data')
		);
		$count = $this->LAVA->db->table('user_sessions')
						->select_count('session_id', 'count')
						->where($data)
						->get()['count'];
		if($this->LAVA->session->userdata('logged_in') == 1 && $count > 0) {
			return true;
		} else {
			if($this->LAVA->session->has_userdata('user_id')) {
				$this->set_logged_out();
			}
		}
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
	 * Set logged out
	 * @return bool Success status
	 */
	public function set_logged_out() {
		$data = array(
			'user_id' => $this->get_user_id(),
			'browser' => $_SERVER['HTTP_USER_AGENT'],
			'session_data' => $this->LAVA->session->userdata('session_data')
		);
		$res = $this->LAVA->db->table('user_sessions')
						->where($data)
						->delete();
		if($res) {
			$this->LAVA->session->unset_userdata(array('user_id'));
			$this->LAVA->session->sess_destroy();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Verify email token
	 * @param string $token Email verification token
	 * @return mixed User data or false
	 */
	public function verify($token) {
		return $this->LAVA->db
						->table('simplecrud_tb')
						->select('id')
						->where('email_token', $token)
						->where_null('email_verified_at')
						->get();	
	}

	/**
	 * Mark email as verified
	 * @param string $token Email verification token
	 * @return bool Success status
	 */
	public function verify_now($token) {
		return $this->LAVA->db
						->table('simplecrud_tb')
						->where('email_token' ,$token)
						->update(array('email_verified_at' => date("Y-m-d h:i:s", time())));	
	}
	
	/**
	 * Get user data for verification email
	 * @param string $email Email address
	 * @return mixed User data or false
	 */
	public function send_verification_email($email) {
		return $this->LAVA->db
						->table('simplecrud_tb')
						->select('name, email_token')
						->where('email', $email)
						->where_null('email_verified_at')
						->get();	
	}
	
	/**
	 * Generate reset password token
	 * @param string $email Email address
	 * @return mixed Reset token or false
	 */
	public function reset_password($email) {
		$row = $this->LAVA->db
						->table('simplecrud_tb')
						->where('email', $email)
						->get();
		if($this->LAVA->db->row_count() > 0) {
			$this->LAVA->call->helper('string');
			$data = array(
				'email' => $email,
				'reset_token' => random_string('alnum', 32),
				'created_at' => date("Y-m-d h:i:s", time())
			);
			$this->LAVA->db
				->table('password_reset')
				->insert($data);
			return $data['reset_token'];
		} else {
			return FALSE;
		}
	}

	/**
	 * Check if user email is verified
	 * @param string $email Email address
	 * @return bool Is verified
	 */
	public function is_user_verified($email) {
		$this->LAVA->db
				->table('simplecrud_tb')
				->where('email', $email)
				->where_not_null('email_verified_at')
				->get();
	return $this->LAVA->db->row_count();
	}

	/**
	 * Get reset password token data
	 * @param string $token Reset token
	 * @return mixed Token data or false
	 */
	public function get_reset_password_token($token)
	{
		return $this->LAVA->db
				->table('password_reset')	
				->select('email')			
				->where('reset_token', $token)
				->get();
	}

	/**
	 * Reset password using token
	 * @param string $token Reset token
	 * @param string $password New password
	 * @return bool Success status
	 */
	public function reset_password_now($token, $password)
	{
		$email_data = $this->get_reset_password_token($token);
		if($email_data) {
			$email = $email_data['email'];
			$data = array(
				'password' => $this->passwordhash($password)
			);
			// Delete the used token
			$this->LAVA->db->table('password_reset')
					->where('reset_token', $token)
					->delete();
			
			return $this->LAVA->db
					->table('simplecrud_tb')
					->where('email', $email)
					->update($data);
		}
		return false;
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