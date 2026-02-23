<?php
class AddUser extends Controller {
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

    public function index() {
        $data = ['title' => 'Register New Employee'];
        // handleResponse allows React to get the title/meta info if needed as JSON
        return $this->handleResponse($data, 200, 'adminViews/addUser');
    }

    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true) ?? $_POST;

            $deptId = $inputData['Department_id'] ?? $inputData['department_id'] ?? null;

            if (!$deptId) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Department ID is required'], 400);
            }

            $employeeCode = $this->generateEmployeeCode($deptId);
            $leaveTypeId = $inputData['leave_type_id'] ?? 1;
            $allocatedDays = $this->getLeaveAllocationByType($leaveTypeId);

            $data = [
                'username'        => trim($inputData['username'] ?? ''),
                'email'           => trim($inputData['email'] ?? ''),
                'password'        => password_hash($inputData['password'] ?? 'default123', PASSWORD_DEFAULT),
                'role'            => $inputData['role'] ?? 'employee',
                'employee_code'   => $employeeCode,
                'first_name'      => trim($inputData['first_name'] ?? ''),
                'middle_name'     => trim($inputData['middle_name'] ?? ''),
                'last_name'       => trim($inputData['last_name'] ?? ''),
                'phone'           => trim($inputData['phone'] ?? ''),
                'address'         => trim($inputData['address'] ?? ''),
                'birthdate'       => $inputData['birthdate'] ?? null,
                'hire_date'       => $inputData['hire_date'] ?? date('Y-m-d'),
                // FIXED: Map to 'employment_type' to match User.php model
                'employment_type' => $inputData['employment_type'] ?? $inputData['employment_status'] ?? 'Full-time',
                'department_id'   => $deptId,
                'position_id'     => $inputData['position_id'] ?? null,
                'basic_salary'    => $inputData['basic_salary'] ?? 0,
                'shift_id'        => $inputData['shift_id'] ?? 1,
                'leave_type_id'   => $leaveTypeId,
                'allocated_days'  => $allocatedDays
            ];

            try {
                if ($this->userModel->insertFullEmployee($data)) {
                    return $this->handleResponse([
                        'status' => 'success',
                        'response' => 'Employee added successfully',
                        'employee_code' => $employeeCode
                    ], 201);
                }
            } catch (PDOException $e) {
                $errorMsg = "A system error occurred.";
                if ($e->getCode() == '45000') {
                    $errorMsg = "DB Error: " . $e->getMessage();
                } else if ($e->getCode() == '23000') {
                    $errorMsg = "Salary must be between 15,000 and 500,000.";
                }

                return $this->handleResponse(['status' => 'error', 'response' => $errorMsg], 400);
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