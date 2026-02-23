<?php
class EmployeeList extends Controller {
    public function __construct(){
        // CORS and OPTIONS are handled in public/index.php

        // Dual-compatible Admin check
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

        return $this->handleResponse($data, 200, 'employeeList');
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true) ?? $_POST;

            // Hash password before sending to model as per your model comment
            if (!empty($inputData['password'])) {
                $inputData['password'] = password_hash($inputData['password'], PASSWORD_DEFAULT);
            }

            // Call the correct method name found in your User.php
            if ($this->userModel->insertFullEmployee($inputData)) {
                if ($this->isApiRequest()) {
                    return $this->handleResponse(['status' => 'success', 'response' => 'Employee added successfully'], 201);
                } else {
                    $_SESSION['flash_success'] = "Employee added successfully.";
                    redirect('EmployeeList');
                    exit();
                }
            } else {
                if ($this->isApiRequest()) {
                    return $this->handleResponse(['status' => 'error', 'response' => 'Failed to add employee'], 500);
                }
            }
        }

        $data = ['title' => 'Register New Employee'];
        return $this->handleResponse($data, 200, 'adminViews/addUser');
    }

    public function getDeptName($deptId): string{
        switch ($deptId){
            case '1': return "Creative & Production";
            case '2': return "Content & Social Media";
            case '3': return "Accounts & Client Services";
            case '4': return "Operations & Technology";
            default: return "ERROR";
        }
    }

    public function delete($code) {
        $method = $_SERVER['REQUEST_METHOD'];

        // 1. Validate the Request Method
        // React typically uses DELETE, while legacy forms use POST
        if ($method !== 'POST' && $method !== 'DELETE') {
            if ($this->isApiRequest()) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Method Not Allowed'], 405);
            }
            return;
        }

        // 2. Call the Model Method
        // Uses the existing resignEmployee logic from your User model
        if ($this->userModel->resignEmployee($code)) {
            if ($this->isApiRequest()) {
                // Return 200 OK with a success message for React
                return $this->handleResponse([
                    'status' => 'success',
                    'response' => 'Employee status updated to Resigned.',
                    'employee_code' => $code
                ], 200);
            } else {
                $_SESSION['flash_success'] = "Employee status updated to Resigned.";
                redirect('EmployeeList');
                exit();
            }
        } else {
            // 3. Handle Failure
            if ($this->isApiRequest()) {
                // Return 400 Bad Request if the employee code is invalid or DB fails
                return $this->handleResponse(['status' => 'error', 'response' => 'Failed to update employee status.'], 400);
            } else {
                $_SESSION['flash_error'] = "Failed to update employee status.";
                redirect('EmployeeList');
                exit();
            }
        }
    }
}