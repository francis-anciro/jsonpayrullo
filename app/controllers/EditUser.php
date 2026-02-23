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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
            $inputData = json_decode(file_get_contents("php://input"), true) ?? $_POST;

            $data = [
                'employee_code' => $inputData['employee_code'] ?? null,
                'username'      => trim($inputData['username'] ?? ''), // ADD THIS LINE
                'email'         => trim($inputData['email'] ?? ''),
                'password'      => !empty($inputData['password']) ? password_hash($inputData['password'], PASSWORD_DEFAULT) : null, // OPTIONAL: Handle password update
                'role'          => $inputData['role'] ?? '',
                'first_name'    => trim($inputData['first_name'] ?? ''),
                'last_name'     => trim($inputData['last_name'] ?? ''),
                'phone'         => trim($inputData['phone'] ?? ''),
                'address'       => trim($inputData['address'] ?? ''),
                'department_id' => $inputData['department_id'] ?? $inputData['Department_id'] ?? null,
                'position_id'   => $inputData['position_id'] ?? null,
                'basic_salary'  => $inputData['basic_salary'] ?? null,
                'shift_id'      => $inputData['shift_id'] ?? null
            ];

            try {
                if ($this->userModel->updateFullEmployee($data)) {
                    // MUST RETURN the response for API
                    return $this->handleResponse(['status' => 'success', 'response' => 'Employee updated successfully'], 200);
                }
            } catch (PDOException $e) {
                $msg = ($e->getCode() == '45000') ? "Department already has a manager." : "DB Error: " . $e->getMessage();

                // Handle API error response
                if ($this->isApiRequest()) {
                    return $this->handleResponse(['status' => 'error', 'response' => $msg], 400);
                }

                $_SESSION['flash_error'] = $msg;
                redirect('EmployeeList');
                exit();
            }
        }
        // If it reaches here without returning, send a fallback
        return $this->handleResponse(['status' => 'error', 'response' => 'Invalid Request'], 400);
    }
}