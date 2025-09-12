<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: UserController
 * 
 * Automatically generated via CLI.
 */
class UserController extends Controller {
    public function __construct()
    {
        parent::__construct();
            $this->call->database();
             $this->call->model('UserModel');
    }

    public function show(){ 

    $data ['users'] = $this->UserModel->all();
    $this->call->view('View', $data);
    }   

    public function create(){
    if($this->io->method() === 'post'){
        $name = $this->io->post('name');
        $email = $this->io->post('email');
        $number = $this->io->post('number');

        $data = array (
            'name' => $name,
            'email' => $email,
            'number' => $number
       );
        $this->UserModel->insert($data);
        redirect('/show');
    } else {
        $this->call->view('Create');
    }
    }


    public function edit($id){
       $user = $this->UserModel->find($id);
       if($this->io->method() === 'post'){
           $name = $this->io->post('name');
           $email = $this->io->post('email');
           $number = $this->io->post('number');

           $data = array (
               'name' => $name,
               'email' => $email,
               'number' => $number
          );
           $this->UserModel->update($id, $data);
           redirect('/show');
       } else {
           $data['user'] = $user;
           $this->call->view('Update', $data);
       }
   }

   public function delete($id){
       if($this->UserModel->delete($id)){
           redirect('/show');
       }else{
           echo "Error deleting record";
       } 
   }
}