<?php
class EditUser extends Controller {
//    CONTROLLER/API ENDPOINT FOR UPDATING EMPLOYEE INFO
    public function __construct() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            if ($this->isApiRequest()) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Admin access required'], 403);
            }
            redirect('home');
            exit();
        }
        $this->userModel = $this->model('User');
    }

    public function index($code = null) {
        if (!$code) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Employee code required'], 400);
        }

        $user = $this->userModel->getUserByCode($code);

        if (!$user) {
            return $this->handleResponse(['status' => 'error', 'response' => 'User not found'], 404);
        }

        $data = [
            'user' => $user,
            'title' => 'Edit Employee: ' . $code
        ];

        return $this->handleResponse($data, 200, 'adminViews/editUserDetails');
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->handleResponse(['status' => 'error', 'response' => 'Invalid Request Method'], 405);
        }

        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!$inputData) {
            return $this->handleResponse(['status' => 'error', 'response' => 'No data provided'], 400);
        }

        $data = [
            'employee_code' => $inputData['employee_code'] ?? null,
            'username'      => trim($inputData['username'] ?? ''),
            'email'         => trim($inputData['email'] ?? ''),

            'password'      => !empty($inputData['password']) ? password_hash($inputData['password'], PASSWORD_DEFAULT) : null,
            'role'          => $inputData['role'] ?? 'employee',
            'first_name'    => trim($inputData['first_name'] ?? ''),
            'last_name'     => trim($inputData['last_name'] ?? ''),
            'phone'         => trim($inputData['phone'] ?? ''),
            'address'       => trim($inputData['address'] ?? ''),
            'department_id' => $inputData['department_id'] ?? null,
            'position_id'   => $inputData['position_id'] ?? null,
            'basic_salary'  => $inputData['basic_salary'] ?? null,
            'shift_id'      => $inputData['shift_id'] ?? null,
            'admin_id' => $_SESSION['Employee_ID'] ?? null
        ];

        try {
            if ($this->userModel->updateEmployee($data)) {
                return $this->handleResponse(['status' => 'success', 'response' => 'Employee updated successfully'], 200);
            } else {
                return $this->handleResponse(['status' => 'error', 'response' => 'Update failed at database level'], 500);
            }
        } catch (PDOException $e) {
            $msg = ($e->getCode() == '45000') ? "Department already has a manager." : "DB Error: " . $e->getMessage();
            return $this->handleResponse(['status' => 'error', 'response' => $msg], 400);
        }
    }
}