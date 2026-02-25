<?php
class EmployeeList extends Controller {
    public function __construct(){

//        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//            if ($this->isApiRequest()) {
//                $this->sendJson(['status' => 'error', 'response' => 'Unauthorized: Admin access required'], 403);
//            } else {
//                redirect('home');
//                exit();
//            }
//        }
        $this->attendanceModel = $this->model('Attendance'); // for attendance
        $this->userModel = $this->model('User');
    }

    public function index() {
        // 1. Fetch all users using your updated query (with phone, dates, etc.)
        $users = $this->userModel->getUsers();

        // 2. Loop and attach supplemental data
        foreach ($users as $user) {
            // Attach Department Name
            $user->department_name = $this->getDeptName($user->Department_ID);

            // Fetch FULL attendance history using your existing model method
            // This replaces the broken $attendanceHistory variable
            $user->attendance_history = $this->attendanceModel->getAttendanceHistory($user->Employee_ID);
        }

        $data = [
            'title' => 'Employee List',
            'users' => $users,
            'role'  => $_SESSION['role'] ?? 'guest',
            'current_user_id' => $_SESSION['User_id'] ?? null
        ];

        // Returns JSON if the React 'Accept' header is present
        return $this->handleResponse($data, 200, 'adminViews/employeeList');
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
            case '1001': return "Creative & Production";
            case '1002': return "Content & Social Media";
            case '1003': return "Accounts & Client Services";
            case '1004': return "Operations & Technology";
            default: return "ERROR";
        }
    }

    public function delete($code) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Pass the employee_code (e.g., OPETECH-2026-1002) to the model
            if ($this->userModel->resignEmployee($code)) {
                return $this->handleResponse([
                    'status' => 'success',
                    'response' => 'Employee successfully resigned.'
                ], 200);
            }
            return $this->handleResponse(['status' => 'error', 'response' => 'Database update failed.'], 400);
        }
    }
    public function toggleLeave($code) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true);

            // Take the status directly from the request body
            $newStatus = $inputData['status'] ?? 'on-leave';

            if ($this->userModel->updateEmploymentStatus($code, $newStatus)) {
                return $this->handleResponse([
                    'status' => 'success',
                    'response' => "Status updated to $newStatus."
                ], 200);
            }

            return $this->handleResponse(['status' => 'error', 'response' => 'Update failed.'], 400);
        }
    }
    public function history($code = null) {
        if (!$code) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Employee code required'], 400);
        }

        // Get Employee_ID from code
        $employee = $this->userModel->getUserByCode($code);

        if (!$employee) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Employee not found'], 404);
        }

        $logs = $this->userModel->getEditLogs($employee->Employee_ID);

        return $this->handleResponse([
            'status' => 'success',
            'data'   => $logs
        ], 200, 'employeeList');
    }

}