<?php
class EmployeeList extends Controller {
    public function __construct(){
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('home');
            exit();
        }
        $this->userModel = $this->model('User');
    }
    public function index(){
        $users = $this->userModel->getUsers();
        foreach ($users as $user) {
            $user->department_name = $this->getDeptName($user->Department_ID);
        }
        $data = [
            'title' => 'employeeList',
            'users' => $users,
            'role'  => $_SESSION['role'],
            'current_user_id' => $_SESSION['User_id']
        ];
        $this->view('employeeList', $data);
    }
    public function add() {
        $data = [
            'title' => 'Register New Employee'
        ];

        $this->view('adminViews/addUser', $data);
    }
    public function getDeptName($deptId): string{
        switch ($deptId){
            case '1':
                $deptName =  "Creative & Production";
                break;
            case '2':
                $deptName = "Content & Social Media";
                break;
            case '3':
                $deptName = "Accounts & Client Services";
                break;
            case '4':
                $deptName = "Operations & Technology";
                break;
            default:
                $deptName = "ERROR";
                break;
        }
        return $deptName;
    }
    public function delete($code) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->resignEmployee($code)) {
                $_SESSION['flash_success'] = "Employee status updated to Resigned.";
            } else {
                $_SESSION['flash_error'] = "Failed to update employee status.";
            }
            redirect('EmployeeList');
        }
    }
}