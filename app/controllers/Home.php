<?php
class Home extends Controller{
    public function __construct(){
        $this->userModel = $this->model('User');
    }
    public function index(){
        $users = $this->userModel->getUsers();

        $data = [
            'title' => 'home',
            'users' => $users,
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
        ];
        $this->view('home', $data);
    }

}
