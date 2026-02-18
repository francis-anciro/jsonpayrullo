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
        // Only allow deletion via POST for security
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Call the model method we created earlier
            if ($this->userModel->deleteUserByCode($code)) {
                // Success: Redirect back to the list
                redirect('EmployeeList');
            } else {
                die('Error: Could not delete the employee.');
            }
        } else {
            // If someone tries to access via URL (GET), send them back
            redirect('EmployeeList');
        }
    }
}