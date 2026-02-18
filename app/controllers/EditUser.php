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
                'position_id'   => $_POST['position_id'],
                'basic_salary'  => $_POST['basic_salary'],
                'shift_id'      => $_POST['shift_id']
            ];

            if ($this->userModel->updateFullEmployee($data)) {
                redirect('EmployeeList');
            } else {
                die('Update failed.');
            }
        }
    }
    private function validatePassword() {

    }
}