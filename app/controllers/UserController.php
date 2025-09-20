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
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 2;

        $all = $this->UserModel->make($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => '⏮ First',
            'last_link'      => 'Last ⏭',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('bootstrap'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page, site_url('author').'?q='.$q);
        $data['page'] = $this->pagination->paginate();

        // Pass page and records_per_page to the view for correct numbering
        $data['current_page'] = (int)$page;
        $data['records_per_page'] = (int)$records_per_page;

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
        redirect('/');
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
           redirect('/');
       } else {
           $data['user'] = $user;
           $this->call->view('Update', $data);
       }
   }

   public function delete($id){
       if($this->UserModel->delete($id)){
           redirect('/');
       }else{
           echo "Error deleting record";
       } 
   }
}