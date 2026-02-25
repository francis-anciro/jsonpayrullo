<?php
class EditUser extends Controller {
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

        // Automatically returns JSON if Accept header is application/json
        return $this->handleResponse($data, 200, 'adminViews/editUserDetails');
    }

    public function update() {
        // 1. Only allow POST (as sent by your React frontend)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->handleResponse(['status' => 'error', 'response' => 'Invalid Request Method'], 405);
        }

        // 2. Decode the JSON data
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!$inputData) {
            return $this->handleResponse(['status' => 'error', 'response' => 'No data provided'], 400);
        }

        // 3. Structure the data for the model (ensuring all 14 parameters are ready)
        $data = [
            'employee_code' => $inputData['employee_code'] ?? null,
            'username'      => trim($inputData['username'] ?? ''),
            'email'         => trim($inputData['email'] ?? ''),
            // Handle password hashing here if provided
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
            // 4. Call the model that executes the stored procedure
            if ($this->userModel->updateEmployee($data)) {
                return $this->handleResponse(['status' => 'success', 'response' => 'Employee updated successfully'], 200);
            } else {
                return $this->handleResponse(['status' => 'error', 'response' => 'Update failed at database level'], 500);
            }
        } catch (PDOException $e) {
            // 5. Catch custom SQL signals (like your Manager check) or general DB errors
            $msg = ($e->getCode() == '45000') ? "Department already has a manager." : "DB Error: " . $e->getMessage();
            return $this->handleResponse(['status' => 'error', 'response' => $msg], 400);
        }
    }
}