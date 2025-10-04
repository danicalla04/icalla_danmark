<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: UserModel
 * 
 * Automatically generated via CLI.
 */
class UserModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

        public function make($q, $records_per_page = null, $page = null) {
            if (is_null($page)) {
                return $this->db->table('users')->get_all();
            } else {
                $query = $this->db->table('users');

                // Build LIKE conditions
                $query->like('id', '%'.$q.'%')
                    ->or_like('name', '%'.$q.'%')
                    ->or_like('email', '%'.$q.'%')
                    ->or_like('number', '%'.$q.'%');

                // Clone before pagination
                $countQuery = clone $query;

                $data['total_rows'] = $countQuery->select_count('*', 'count')
                                                ->get()['count'];

                $data['records'] = $query->pagination($records_per_page, $page)
                                        ->get_all();

                return $data;
            }
        }

        /**
         * Find user by email for authentication
         */
        public function find_by_email($email) {
            $email = trim($email);
            $account = $this->db->table($this->table)->where('email', $email)->get();
            
            if (!$account) {
                return null;
            }
            
            if (is_object($account)) {
                return (array) $account;
            }
            
            if (is_array($account)) {
                return $account;
            }
            
            return null;
        }

        /**
         * Create new user account
         */
        public function create_account($data) {
            return $this->db->table($this->table)->insert($data);
        }

        /**
         * Verify user password
         */
        public function verify_password($email, $password) {
            $user = $this->find_by_email($email);
            if ($user && $user['password'] === $password) {
                return $user;
            }
            return false;
        }
    }