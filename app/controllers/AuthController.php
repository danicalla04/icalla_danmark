<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * AuthController - Simple authentication
 */
class AuthController extends Controller {

	private $auth;

	public function __construct() {
		parent::__construct();
		$this->call->library('Auth');
		$this->auth = $this->Auth;
	}

	/**
	 * Display login form
	 */
	public function login() {
		if($this->auth->is_logged_in()) {
			redirect('/author');
		}
		
		$this->call->view('auth/login');
	}

	/**
	 * Process login
	 */
	public function login_process() {
		if($this->io->method() !== 'post') {
			redirect('auth/login');
		}

		$email = $this->io->post('email');
		$password = $this->io->post('password');

		if(empty($email) || empty($password)) {
			$this->session->set_flashdata('error', 'Please fill in all fields');
			redirect('auth/login');
		}

		$user_id = $this->auth->login($email, $password);
		
		if($user_id) {
			$this->auth->set_logged_in($user_id);
			$username = $this->auth->get_username($user_id);
			$this->session->set_flashdata('success', 'Welcome back, ' . $username . '!');
			redirect('/author');
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
			redirect('/author');
		}
		
		$this->call->view('auth/register');
	}

	/**
	 * Process registration
	 */
	public function register_process() {
		if($this->io->method() !== 'post') {
			redirect('auth/register');
		}

		$name = $this->io->post('name');
		$email = $this->io->post('email');
		$password = $this->io->post('password');
		$confirm_password = $this->io->post('confirm_password');
		$number = $this->io->post('number');

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


}
?>