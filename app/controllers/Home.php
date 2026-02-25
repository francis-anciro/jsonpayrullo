<?php
class Home extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->attendanceModel = $this->model('Attendance'); // for attendance

        // BULLETPROOFING: Safely try to load the Payroll model
        try {
            $this->payrollModel = $this->model('Payroll');
        } catch (Throwable $e) {
            $this->payrollModel = null;
        }
    }

    public function index()
    {
        if ($this->isApiRequest() && !isset($_SESSION['User_ID']) && !isset($_SESSION['User_id'])) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Unauthorized'], 401);
        }

        $employeeId = $_SESSION['Employee_ID'] ?? null;

        // --- SAFE ATTENDANCE FETCH ---
        $attendanceHistory = [];
        try {
            if ($employeeId && method_exists($this->attendanceModel, 'getAttendanceHistory')) {
                $attendanceHistory = $this->attendanceModel->getAttendanceHistory($employeeId) ?: [];
            }
        } catch (Throwable $e) {
        }

        // --- SAFE DEPT FETCH (Fixed to fallback to null) ---
        $deptName = null; // Default to null so Frontend can show "GENERAL DEPT"
        try {
            if ($employeeId && method_exists($this->userModel, 'getDepartmentByEmployeeId')) {
                $deptData = $this->userModel->getDepartmentByEmployeeId($employeeId);
                // Handle both object and array returns safely
                if (is_object($deptData)) {
                    $deptName = $deptData->name ?? null;
                } elseif (is_array($deptData)) {
                    $deptName = $deptData['name'] ?? null;
                }
            }
        } catch (Throwable $e) {
            // If fetching fails, stay null.
        }

        // --- SAFE PAYSLIPS FETCH ---
        $myPayslips = [];
        try {
            if ($employeeId && $this->payrollModel && method_exists($this->payrollModel, 'getRunsByEmployee')) {
                $rawRuns = $this->payrollModel->getRunsByEmployee($employeeId);

                if ($rawRuns) {
                    foreach ($rawRuns as $run) {
                        $start = isset($run->period_start) ? date('M j', strtotime($run->period_start)) : '';
                        $end = isset($run->period_end) ? date('M j, Y', strtotime($run->period_end)) : '';

                        $myPayslips[] = [
                            'period_string' => "$start - $end",
                            'net_pay' => $run->net_pay ?? 0,
                            'period_id' => $run->PayrollPeriod_ID ?? '',
                            'run_id' => $run->PayrollRun_ID ?? ''
                        ];
                    }
                }
            }
        } catch (Throwable $e) {
        }

        $data = [
            'title' => 'Home',
            'username' => $_SESSION['username'] ?? 'Guest',
            'role' => $_SESSION['role'] ?? '---',
            'employee_id' => $employeeId,
            'attendanceHistory' => $attendanceHistory,
            'phone' => $_SESSION['phone'] ?? null,
            'dept' => $deptName, // Sends null if not found, allowing React fallback
            'myPayslips' => $myPayslips
        ];

        return $this->handleResponse($data, 200, 'home');
    }

    public function tapIn()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employeeId = $_SESSION['Employee_ID'] ?? null;

            if (!$employeeId) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Session expired'], 401, 'home');
            }

            try {
                $this->attendanceModel->tapIn($employeeId);
                $msg = ['status' => 'success', 'response' => 'Tapped in successfully!'];
            } catch (Exception $e) {
                $text = str_contains($e->getMessage(), '1062') ? 'Already tapped in today.' : 'Tap in failed.';
                $msg = ['status' => 'error', 'response' => $text];
            }

            return $this->handleResponse($msg, 200, 'home');
        }
    }

    public function updatePhone()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employeeId = $_SESSION['Employee_ID'] ?? null;

            if (!$employeeId) {
                return $this->handleResponse([
                    'status' => 'error',
                    'response' => 'Session expired.'
                ], 401, 'home');
            }

            if ($this->isApiRequest()) {
                $input = json_decode(file_get_contents('php://input'), true);
            } else {
                $input = $_POST;
            }

            if (empty($input['phone'])) {
                return $this->handleResponse([
                    'status' => 'error',
                    'response' => 'Phone number is required.'
                ], 400, 'home');
            }

            if (!preg_match('/^[0-9+\-\s]{7,20}$/', $input['phone'])) {
                return $this->handleResponse([
                    'status' => 'error',
                    'response' => 'Invalid phone number format.'
                ], 400, 'home');
            }

            try {
                $this->userModel->updateContactDetails([
                    'employee_id' => $employeeId,
                    'phone' => trim($input['phone']),
                ]);

                // Update session phone to match
                $_SESSION['phone'] = trim($input['phone']);

                return $this->handleResponse([
                    'status' => 'success',
                    'response' => 'Phone number updated successfully.'
                ], 200, 'home');

            } catch (PDOException $e) {
                return $this->handleResponse([
                    'status' => 'error',
                    'response' => 'Update failed. Please try again.'
                ], 400, 'home');
            }
        }
    }

    public function tapOut()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employeeId = $_SESSION['Employee_ID'] ?? null;

            if (!$employeeId) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Session expired'], 401, 'home');
            }

            try {
                $this->attendanceModel->tapOut($employeeId);
                $msg = ['status' => 'success', 'response' => 'Tapped out successfully!'];
            } catch (Exception $e) {
                $msg = ['status' => 'error', 'response' => 'Tap out failed: ' . $e->getMessage()];
            }

            return $this->handleResponse($msg, 200, 'home');
        }
    }
}