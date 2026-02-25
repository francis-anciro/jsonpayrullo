<?php
class Analytics extends Controller {
    public function __construct() {
//        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//            if ($this->isApiRequest()) {
//                return $this->handleResponse(['status' => 'error', 'response' => 'Admin access required'], 403);
//            }
//            redirect('home');
//            exit();
//        }
        $this->analytic = $this->model('Analytic');
    }

    public function index() {
        $data = [
            'title'           => 'Company Analytics',
            'departmentCount' => $this->getDepartmentCount(),
            'positionCount'   => $this->getPositionCount(),
            'activeEmployees' => $this->getTotalActiveEmployeeCount(),
            'statsByStatus'   => $this->getEmployeeByStatus(),
            'statsByDept'     => $this->getEmployeeCountByDepartment()
        ];

        return $this->handleResponse($data, 200, 'adminViews/analytics');
    }

    private function getDepartmentCount() {
        // Return the result directly because the model already extracted the integer
        return $this->analytic->getDepartmentCount();
    }

    private function getTotalActiveEmployeeCount() {
        return $this->analytic->getEmployeeCountByStatus('active') + $this->analytic->getEmployeeCountByStatus('on_leave');
    }

    private function getPositionCount() {
        return $this->analytic->getPositionCount();
    }

    private function getEmployeeByStatus() {
        $statuses = ['active', 'on_leave', 'resigned'];
        $statusCounts = [];

        foreach ($statuses as $status) {
            $count = $this->analytic->getEmployeeCountByStatus($status);
            $statusCounts[] = [
                'status' => $status,
                'count'  => $count
            ];
        }

        return $statusCounts;
    }

    public function getEmployeeCountByDepartment() {
        // 1. Get both name and ID from the model
        $departments = $this->analytic->getDepartmentNames();
        $stats = [];

        foreach ($departments as $dept) {
            // 2. Extract both values (handling the object return)
            $id = $dept->department_id;
            $name = $dept->name;

            // 3. Pass the ID to the query for the count
            $count = $this->analytic->getEmployeeCountByDepartment($id);

            // 4. Return the result using the name as the key
            $stats[] = [
                'department' => $name,
                'count'      => $count
            ];
        }

        return $stats;
    }
}