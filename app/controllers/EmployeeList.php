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

        $data = [
            'title' => 'employeeList',
            'users' => $users,
            'role'  => $_SESSION['role'] ?? 'employee',
        ];
        $this->view('employeeList', $data);
    }

    public function addUser(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $deptId = $_POST['Department_id'];
            $deptCode = "";
            $year = date('Y');

            $employeeCountInDept = $this->userModel->getUserCountByDept($deptId);
            $employeeCount = (1000 + $employeeCountInDept) + 1;


            switch ($deptId){
                case '1':
                    $deptCode = "CREAPRO";
                    break;
                case '2':
                    $deptCode = "CONTSOC";
                    break;
                case '3':
                    $deptCode = "ACCCLIE";
                    break;
                case '4':
                    $deptCode = "OPETECH";
                    break;
                default:
                    $deptCode = "NA";
                    break;
            }
            $employeeCode = "{$deptCode}-{$year}-{$employeeCount}";

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => $_POST['role'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];
            if ($this->userModel->insertUser($data)) {
                redirect('employeeList');
            }else{
                die('Something went wrong');
            }
        }
    }

//    public function deleteUser($User_id) {
//        // Basic security check: only admins should delete
//        if ($_SESSION['role'] !== 'admin') {
//            redirect('employeeList');
//            exit();
//        }
//
//        if ($this->userModel->deleteUser($User_id)) {
//            redirect('employeeList');
//        } else {
//            die('Something went wrong during deletion.');
//        }
//    }
//    public function editUser($User_id) {
//        $user = $this->userModel->getUserById($User_id);
//
//        if (!$user) {
//            redirect('employeeList');
//        }
//        $data = [
//            'title' => 'Edit User',
//            'user'  => $user,
//            'role'  => $_SESSION['role']
//        ];
//        $this->view('users/updateUser', $data);
//    }
//    public function updateUser($User_id)
//    {
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $data = [
//                'User_id' => $User_id,
//                'username' => trim($_POST['username']),
//                'email' => trim($_POST['email']),
//                'role' => $_POST['role'],
//                'is_active' => isset($_POST['is_active']) ? 1 : 0
//            ];
//
//            if ($this->userModel->update($data)) {
//                redirect('employeeList');
//            } else {
//                die('Something went wrong');
//            }
//        }
//    }
}