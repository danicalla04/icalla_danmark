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
}