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
        return $this->handleResponse($data, 200, 'adminViews/addUser');
    }

    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);

            $input['employee_code'] = $this->generateEmployeeCode($input['department_id']);

            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);

            $result = $this->userModel->registerEmployee($input);

            if ($result === true) {
                return $this->handleResponse(['status' => 'success', 'response' => 'Employee registered.'], 201);
            } else {
                return $this->handleResponse(['status' => 'error', 'response' => $result], 400);
            }
        }
    }

//    HELPER SO THE EMPLOYEE CODE ISNT MANUALLY ENETERED WHEN CREATING EMPLOYEE ACCOUNT
    private function generateEmployeeCode($deptId): string
    {
        $year = date('Y');
        $employeeCountInDept = $this->userModel->getUserCountByDept($deptId);
        $employeeCount = (1000 + $employeeCountInDept) + 1;
        switch ($deptId){
            case '1001':
                $deptCode = "CREAPRO";
                break;
            case '1002':
                $deptCode = "CONTSOC";
                break;
            case '1003':
                $deptCode = "ACCCLIE";
                break;
            case '1004':
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