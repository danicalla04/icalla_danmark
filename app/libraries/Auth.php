<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 * 
 * Copyright (c) 2020 Ronald M. Marasigan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @copyright Copyright 2020 (https://ronmarasigan.github.io)
 * @since Version 1
 * @link https://lavalust.pinoywap.org
 * @license https://opensource.org/licenses/MIT MIT License
 */

/**
 * Auth Class - Simple version for simplecrud_tb
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
		'cost' => 4,
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
	 * Set up session for login
	 * @param int $user_id User ID
	 */
	public function set_logged_in($user_id) {
		$this->LAVA->session->set_userdata(array('user_id' => $user_id, 'logged_in' => 1));
	}

	/**
	 * Check if user is Logged in
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
	 * Get Username
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

}

?>