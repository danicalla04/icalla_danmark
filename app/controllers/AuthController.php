<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * AuthController
 * Handles authentication operations: login, register, forgot password, email verification
 */
class AuthController extends Controller {

	private $auth;

	public function __construct() {
		$this->auth = $this->load->library('Auth');
	}

	/**
	 * Display login form
	 */
	public function login() {
		if($this->auth->is_logged_in()) {
			redirect('/');
		}
		
		$this->load->view('auth/login');
	}

	/**
	 * Process login
	 */
	public function login_process() {
		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
			redirect('auth/login');
		}

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		if(empty($email) || empty($password)) {
			$this->session->set_flashdata('error', 'Please fill in all fields');
			redirect('auth/login');
		}

		$user_id = $this->auth->login($email, $password);
		
		if($user_id) {
			$this->auth->set_logged_in($user_id);
			$username = $this->auth->get_username($user_id);
			$this->session->set_flashdata('success', 'Welcome back, ' . $username . '!');
			redirect('/');
		} else {
			$this->session->set_flashdata('error', 'Invalid email or password');
			redirect('auth/login');
		}
	}

	/**
	 * Display registration form
	 */
	public function register() {
		if($this->auth->is_logged_in()) {
			redirect('/');
		}
		
		$this->load->view('auth/register');
	}

	/**
	 * Process registration
	 */
	public function register_process() {
		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
			redirect('auth/register');
		}

		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$confirm_password = $this->input->post('confirm_password');
		$number = $this->input->post('number');

		// Validation
		if(empty($name) || empty($email) || empty($password) || empty($number)) {
			$this->session->set_flashdata('error', 'Please fill in all fields');
			redirect('auth/register');
		}

		if($password !== $confirm_password) {
			$this->session->set_flashdata('error', 'Passwords do not match');
			redirect('auth/register');
		}

		if(strlen($password) < 6) {
			$this->session->set_flashdata('error', 'Password must be at least 6 characters');
			redirect('auth/register');
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_flashdata('error', 'Invalid email format');
			redirect('auth/register');
		}

		// Check if email already exists
		$existing_user = $this->auth->get_user_by_email($email);
		if($existing_user) {
			$this->session->set_flashdata('error', 'Email already registered');
			redirect('auth/register');
		}

		$user_id = $this->auth->register($name, $email, $password, $number);
		
		if($user_id) {
			$this->session->set_flashdata('success', 'Registration successful! You can now login.');
			redirect('auth/login');
		} else {
			$this->session->set_flashdata('error', 'Registration failed. Please try again.');
			redirect('auth/register');
		}
	}


	/**
	 * Logout user
	 */
	public function logout() {
		$this->auth->set_logged_out();
		$this->session->set_flashdata('success', 'You have been logged out successfully.');
		redirect('auth/login');
	}

	/**
	 * Display user profile
	 */
	public function profile() {
		if(!$this->auth->is_logged_in()) {
			redirect('auth/login');
		}

		$user_id = $this->auth->get_user_id();
		$this->load->view('auth/profile', ['user_id' => $user_id]);
	}

	/**
	 * Update user profile
	 */
	public function update_profile() {
		if(!$this->auth->is_logged_in()) {
			redirect('auth/login');
		}

		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
			redirect('auth/profile');
		}

		$user_id = $this->auth->get_user_id();
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$number = $this->input->post('number');

		// Validation
		if(empty($name) || empty($email) || empty($number)) {
			$this->session->set_flashdata('error', 'Please fill in all fields');
			redirect('auth/profile');
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_flashdata('error', 'Invalid email format');
			redirect('auth/profile');
		}

		$data = array(
			'name' => $name,
			'email' => $email,
			'number' => $number
		);

		$success = $this->auth->update_profile($user_id, $data);
		
		if($success) {
			$this->session->set_flashdata('success', 'Profile updated successfully!');
		} else {
			$this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
		}
		
		redirect('auth/profile');
	}

	/**
	 * Change password
	 */
	public function change_password() {
		if(!$this->auth->is_logged_in()) {
			redirect('auth/login');
		}

		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
			redirect('auth/profile');
		}

		$current_password = $this->input->post('current_password');
		$new_password = $this->input->post('new_password');
		$confirm_password = $this->input->post('confirm_password');

		if(empty($current_password) || empty($new_password) || empty($confirm_password)) {
			$this->session->set_flashdata('error', 'Please fill in all password fields');
			redirect('auth/profile');
		}

		if($new_password !== $confirm_password) {
			$this->session->set_flashdata('error', 'New passwords do not match');
			redirect('auth/profile');
		}

		if(strlen($new_password) < 6) {
			$this->session->set_flashdata('error', 'Password must be at least 6 characters');
			redirect('auth/profile');
		}

		// Verify current password
		$user_data = $this->auth->get_user_by_email($this->input->post('email'));
		if(!$user_data || !password_verify($current_password, $user_data['password'])) {
			$this->session->set_flashdata('error', 'Current password is incorrect');
			redirect('auth/profile');
		}

		$success = $this->auth->change_password($new_password);
		
		if($success) {
			$this->session->set_flashdata('success', 'Password changed successfully!');
		} else {
			$this->session->set_flashdata('error', 'Failed to change password. Please try again.');
		}
		
		redirect('auth/profile');
	}

}
?>
