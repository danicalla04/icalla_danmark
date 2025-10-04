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
			redirect(site_url('author'));
		}
		
		$this->call->view('auth/login');
	}

	/**
	 * Process login
	 */
	public function login_process() {
		if($this->io->method() !== 'post') {
			redirect(site_url('auth/login'));
		}

		$email = $this->io->post('email');
		$password = $this->io->post('password');

		if(empty($email) || empty($password)) {
			redirect(site_url('auth/login'));
		}

		$user_id = $this->auth->login($email, $password);
		
		if($user_id) {
			$this->auth->set_logged_in($user_id);
			redirect(site_url('author'));
		} else {
			redirect(site_url('auth/login'));
		}
	}

	/**
	 * Display registration form
	 */
	public function register() {
		if($this->auth->is_logged_in()) {
			redirect(site_url('author'));
		}
		
		$this->call->view('auth/register');
	}

	/**
	 * Process registration
	 */
	public function register_process() {
		if($this->io->method() !== 'post') {
			redirect(site_url('auth/register'));
		}

		$name = $this->io->post('name');
		$email = $this->io->post('email');
		$password = $this->io->post('password');
		$confirm_password = $this->io->post('confirm_password');
		$number = $this->io->post('number');

		// Validation
		if(empty($name) || empty($email) || empty($password) || empty($number)) {
			redirect(site_url('auth/register'));
		}

		if($password !== $confirm_password) {
			redirect(site_url('auth/register'));
		}

		if(strlen($password) < 6) {
			redirect(site_url('auth/register'));
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			redirect(site_url('auth/register'));
		}

		// Check if email already exists
		$existing_user = $this->auth->get_user_by_email($email);
		if($existing_user) {
			redirect(site_url('auth/register'));
		}

		$user_id = $this->auth->register($name, $email, $password, $number);
		
		if($user_id) {
			redirect(site_url('auth/login'));
		} else {
			redirect(site_url('auth/register'));
		}
	}


}
?>