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
        // Get all users (optional)
        $users = $this->userModel->getUsers();

        // Get attendance history for the currently logged-in employee
        $attendanceHistory = [];
        if (isset($_SESSION['Employee_ID'])) {

            $attendanceHistory = $this->attendanceModel->getAttendanceHistory($_SESSION['Employee_ID']);


        }



        $data = [
            'title' => 'Home',
            'users' => $users,
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'employee_id' => $_SESSION['Employee_ID'] ?? null, // pass Employee_ID to view
            'attendanceHistory' => $attendanceHistory,
            'message' => $_SESSION['attendance_message'] ?? null
        ];

        $this->view('home', $data);
    }
    /////////////////
    ////////////////////////////////
    ///////////////////////////////
// attendance, i added this here instead of creating an attendance
//controller bc the tap in and tap out is in the home and not in jsonpayrullo/attendance

    public function tapIn()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['Employee_ID'])) {
                try {
                    $this->attendanceModel->tapIn($_SESSION['Employee_ID']);
                    $_SESSION['attendance_message'] = ['type' => 'success', 'text' => 'Tapped in successfully!'];
                } catch (Exception $e) {
                    if (str_contains($e->getMessage(), '1062')) {
                        $_SESSION['attendance_message'] = ['type' => 'error', 'text' => 'You have already tapped in today.'];
                    } else {
                        $_SESSION['attendance_message'] = ['type' => 'error', 'text' => 'Tap in failed. Please try again.'];
                    }
                }
                header('Location: ' . URLROOT . '/home');
                exit();
            }
        }
    }


    public function tapOut()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->attendanceModel->tapOut($_SESSION['Employee_ID']);
            header('Location: ' . URLROOT . '/home');
        }
    }
}



?>