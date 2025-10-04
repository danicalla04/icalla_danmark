<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: AuthController
 * 
 * Authentication controller based on LavaLust-2025
 */
class AuthController extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->library('session');
        $this->call->library('form_validation');
        $this->call->model('UserModel');
    }

    /** LOGIN */
    public function login()
    {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            redirect('author');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->form_validation->name('email')->required()->valid_email();
            $this->form_validation->name('password')->required()->min_length(1);

            if ($this->form_validation->run()) {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);

                $user = $this->UserModel->find_by_email($email);

                if ($user) {
                    // For now, we'll use plain text comparison since passwords are empty in DB
                    // In production, you should implement proper password hashing
                    if (empty($user['password']) || $user['password'] === $password) {
                        $this->session->set_userdata('logged_in', true);
                        $this->session->set_userdata('user_id', $user['id']);
                        $this->session->set_userdata('user_name', $user['name']);
                        $this->session->set_userdata('user_email', $user['email']);
                        redirect('author');
                        return;
                    } else {
                        $error = "Incorrect password.";
                    }
                } else {
                    $error = "Email not found.";
                }

                $this->call->view('auth/login', ['error' => $error]);
                return;
            }
        }

        $this->call->view('auth/login');
    }

    /** LOGOUT */
    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_name');
        $this->session->unset_userdata('user_email');
        redirect('auth/login');
    }
}
?>