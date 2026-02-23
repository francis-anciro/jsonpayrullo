<?php
class EmployeeList extends Controller {
    public function __construct(){
        // 1. Dual-compatible Admin check
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            if ($this->isApiRequest()) {
                $this->sendJson(['status' => 'error', 'response' => 'Unauthorized: Admin access required'], 403);
            } else {
                redirect('home');
                exit();
            }
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

        // 2. Automatically returns JSON if Accept header is present
        return $this->handleResponse($data, 200, 'employeeList');
    }

    public function add() {
        $data = [
            'title' => 'Register New Employee'
        ];

        return $this->handleResponse($data, 200, 'adminViews/addUser');
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
                // 3. Handle success for both frontend types
                if ($this->isApiRequest()) {
                    return $this->handleResponse(['status' => 'success', 'response' => 'Employee status updated to Resigned.'], 200);
                } else {
                    $_SESSION['flash_success'] = "Employee status updated to Resigned.";
                    redirect('EmployeeList');
                    exit();
                }
            } else {
                // 4. Handle failure for both frontend types
                if ($this->isApiRequest()) {
                    return $this->handleResponse(['status' => 'error', 'response' => 'Failed to update employee status.'], 400);
                } else {
                    $_SESSION['flash_error'] = "Failed to update employee status.";
                    redirect('EmployeeList');
                    exit();
                }
            }
        }
    }
}