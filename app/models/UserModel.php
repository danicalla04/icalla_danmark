<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: UserModel
 * 
 * Automatically generated via CLI.
 */
class UserModel extends Model {
    protected $table = 'simplecrud_tb';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

        public function make($q, $records_per_page = null, $page = null) {
            if (is_null($page)) {
                return $this->db->table('simplecrud_tb')->get_all();
            } else {
                $query = $this->db->table('simplecrud_tb');

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

        public function find_by_email($email)
        {
            $email = trim($email);
        
            // kunin ang unang row lang
            $account = $this->db->table($this->table)->where('email', $email)->get();
        
            // kung walang laman, balik null
            if (!$account) {
                return null;
            }
        
            // kung object (stdClass), gawin array
            if (is_object($account)) {
                return (array) $account;
            }
        
            // kung array na, ibalik as is
            if (is_array($account)) {
                return $account;
            }
        
            return null;
        }
        
        
            public function create_account($data)
            {
                return $this->db->table($this->table)->insert($data);
            }

    }