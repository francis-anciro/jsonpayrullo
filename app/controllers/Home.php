<?php
class Home extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->attendanceModel = $this->model('Attendance'); // for attendance
    }

    public function index()
    {
        // Use the correct case for your Session ID
        if ($this->isApiRequest() && !isset($_SESSION['User_ID']) && !isset($_SESSION['User_id'])) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Unauthorized'], 401);
        }

        $employeeId = $_SESSION['Employee_ID'] ?? null;
        $attendanceHistory = $employeeId ? $this->attendanceModel->getAttendanceHistory($employeeId) : [];

        // --- SAFETY CHECK ---
        $deptName = "ERROR"; // Default fallback
        if ($employeeId) {
            $deptData = $this->userModel->getDepartmentByEmployeeId($employeeId);
            // Ensure $deptData is an array and NOT false before accessing the key
            $deptName = $deptData->name ?? $deptData['name'] ?? "ERROR";
        }

        $data = [
            'title' => 'Home',
            'username' => $_SESSION['username'] ?? 'Guest',
            'role' => $_SESSION['role'] ?? '---',
            'employee_id' => $employeeId,
            'attendanceHistory' => $attendanceHistory,
            'dept' => $deptName,
        ];

        return $this->handleResponse($data, 200, 'home');
    }
// attendance, i added this here instead of creating an attendance
//controller bc the tap in and tap out is in the home and not in jsonpayrullo/attendance
    public function tapIn()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employeeId = $_SESSION['Employee_ID'] ?? null;

            if (!$employeeId) {
                // Passing 'home' as the 3rd arg just in case it falls back to a view
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


?>