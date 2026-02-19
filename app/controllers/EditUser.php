<?php
class EditUser extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index($code) {
        if (!$code) {
            redirect('EmployeeList');
        }
        $user = $this->userModel->getUserByCode($code);

        $data = [
            'user' => $user,
            'title' => 'Edit Employee: ' . $code
        ];

        $this->view('adminViews/editUserDetails', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'employee_code' => $_POST['employee_code'],
                'email'         => trim($_POST['email']),
                'role'          => $_POST['role'],
                'first_name'    => trim($_POST['first_name']),
                'last_name'     => trim($_POST['last_name']),
                'phone'         => trim($_POST['phone']),
                'address'       => trim($_POST['address']),
                'department_id' => $_POST['Department_id'],
                'position_id'   => $_POST['position_id'], // This key name must match line 113 in User.php
                'basic_salary'  => $_POST['basic_salary'],
                'shift_id'      => $_POST['shift_id']
            ];

            try {
                if ($this->userModel->updateFullEmployee($data)) {
                    $_SESSION['flash_success'] = "Employee updated successfully.";
                    redirect('EmployeeList');
                }
            } catch (PDOException $e) {
                // 45000 is the custom error code from your SIGNAL in the Stored Procedure
                if ($e->getCode() == '45000') {
                    $_SESSION['flash_error'] = "This department already has a manager assigned.";
                } else {
                    // Fallback for other errors (like the argument count error)
                    $_SESSION['flash_error'] = "An unexpected database error occurred.";
                }
                redirect('EmployeeList');
            }
        }
    }
    private function validatePassword() {

    }
}