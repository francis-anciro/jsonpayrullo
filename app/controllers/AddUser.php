<?php
class AddUser extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    public function index() {
        $data = ['title' => 'Register New Employee'];
        $this->view('adminViews/addUser', $data);
    }
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $deptId = $_POST['Department_id'];

            // Generate code using your existing helper function
            $employeeCode = $this->generateEmployeeCode($deptId);
            $allocatedDays = $this->getLeaveAllocationByType($_POST['leave_type_id']);

            $data = [
                'username'        => trim($_POST['username']),
                'email'           => trim($_POST['email']),
                'password'        => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role'            => $_POST['role'],
                'employee_code'   => $employeeCode, // Generated via your helper
                'first_name'      => trim($_POST['first_name']),
                'middle_name'     => trim($_POST['middle_name']),
                'last_name'       => trim($_POST['last_name']),
                'phone'           => trim($_POST['phone']),
                'address'         => trim($_POST['address']),
                'birthdate'       => $_POST['birthdate'],
                'hire_date'       => $_POST['hire_date'],
                'employment_type' => $_POST['employment_type'],
                'department_id'   => $_POST['Department_id'],
                'position_id'     => $_POST['position_id'],
                'basic_salary'    => $_POST['basic_salary'],
                'shift_id'        => $_POST['shift_id'],
                'leave_type_id'   => $_POST['leave_type_id'],
                'allocated_days'  => $allocatedDays // Generated via your helper
            ];

            try {
                if ($this->userModel->insertFullEmployee($data)) {
                    $_SESSION['flash_success'] = "Employee added successfully.";
                    redirect('EmployeeList');
                }
            } catch (PDOException $e) {
                // Check for the specific SQLSTATE 45000 from your SQL SIGNAL
                if ($e->getCode() == '45000') {
                    $data['error'] = "DB Error: " . $e->getMessage();
                } else if ($e->getCode() == '23000') {
                    $data['error'] = "Salary must be between 15,000 and 500,000.";
                } else {
                    $data['error'] = "A system error occurred. Please try again.";
                }

                // Reload the view with the error message and existing input data
                $this->view('adminViews/addUser', $data);
            }
        }
    }
    private function generateEmployeeCode($deptId): string
    {
//        This is a helper function to generate the employee code
//        Format DEPTCODE-YEAR-EMPLOYEEID
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
        return "{$deptCode}-{$year}-{$employeeCount}";
    }
    public function getPositions($deptId)
    {
        $positions = $this->showPositionByDept($deptId);

        header('Content-Type: application/json');
        echo json_encode($positions);
    }
    private function showPositionByDept($deptId): array
    {
        switch($deptId){
            case '1':
                $positionOptions = [
                  'label' => ['Art Director', 'Graphic Designer', 'Video Editor', 'Copywriter'],
                    'values' => [1,2,3,4]
                ];
                break;
            case '2':
                $positionOptions = [
                    'label' => ['Social Media Manager', 'Content Strategist', 'Community Manager'],
                    'values' => [5,6,7]
                ];
                break;
            case '3':
                $positionOptions = [
                    'label' => ['Account Executive', 'Account Manager', 'Client Success Specialist'],
                    'values' => [8,9,10]
                ];
                break;
            case '4':
                $positionOptions = [
                    'label' => ['Web Developer', 'IT Support Specialist', 'Operations Manager'],
                    'values' => [11,12,13]
                ];
                break;
            default:
                $positionOptions = [
                    'label' => ['ERROR'],
                    'values' => [0]
                ];
        }
        return $positionOptions;
    }
    private function getLeaveAllocationByType($leaveType): int
    {
    switch($leaveType){
        case 1:
            $leaveAllocations = 15;
            break;
        case 2:
            $leaveAllocations = 10;
            break;
        case 3:
            $leaveAllocations = 30;
            break;
        case 4:
            $leaveAllocations = 5;
            break;
        default:
            $leaveAllocations = 0;
            break;
    }
    return $leaveAllocations;
    }

}