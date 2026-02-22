<?php
class Payrolls extends Controller {
    public function __construct() {
        if ($_SESSION['role'] !== 'admin') redirect('home');
        $this->payrollModel = $this->model('Payroll');
    }

    // Page 1: List Periods
    public function index() {
        $data = ['periods' => $this->payrollModel->getPeriods(),
        'role' => $_SESSION['role'],
            'user_id' => $_SESSION['User_id'],
        ];

        $this->view('adminViews/payrollIndex', $data); // Page 1
    }
// Add these back to your Payroll class
    public function getPeriods() {
        $this->db->query("SELECT *, 
        (SELECT COUNT(*) FROM payroll_runs WHERE PayrollPeriod_ID = p.PayrollPeriod_ID) as employee_count 
        FROM payroll_periods p ORDER BY period_start DESC");
        return $this->db->resultSet();
    }

    public function getPeriodById($id) {
        $this->db->query("SELECT * FROM payroll_periods WHERE PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getRunsByPeriod($id) {
        $this->db->query("SELECT pr.*, e.first_name, e.last_name, e.employee_code 
                     FROM payroll_runs pr 
                     JOIN employees e ON pr.Employee_ID = e.Employee_ID 
                     WHERE pr.PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function createPeriod($data) {
        $this->db->query("INSERT INTO payroll_periods (period_start, period_end, pay_date, status) 
                  VALUES (:start, :end, :pay, 'open')");
        $this->db->bind(':start', $data['period_start']);
        $this->db->bind(':end', $data['period_end']);
        $this->db->bind(':pay', $data['pay_date']);
        return $this->db->execute();
    }
    public function details($id) {
        $data = [
            'period' => $this->payrollModel->getPeriodById($id),
            'runs' => $this->payrollModel->getRunsByPeriod($id),
            'role' => $_SESSION['role'],
            'user_id' => $_SESSION['User_id'],

        ];
        $this->view('adminViews/payrollView', $data); // Page 2
    }

    // Action for Page 2: Generate Button
    public function generate($periodId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->payrollModel->generate($periodId, $_SESSION['User_id']);
            dumpNDie($data);
            redirect('Payrolls/details/' . $periodId);
        }
    }

// Example method for the "Manage" modal submission
    public function addAdjustment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $runId = $_POST['PayrollRun_ID'];
            $periodId = $_POST['period_id'];

            if ($_POST['adjustment_type'] == 'allowance') {
                $this->payrollModel->addAllowance($_POST);
            } else {
                $this->payrollModel->addDeduction($_POST);
            }

            // Sync totals so gross/net pay recalculate immediately
            $this->payrollModel->syncTotals($runId);

            redirect('Payrolls/details/' . $periodId);
        }
    }

// Rename this to match the controller call
    public function releasePeriod($id) {
        $this->db->query("UPDATE payroll_periods SET status = 'released' WHERE PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->payrollModel->createPeriod($_POST)) {
                $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'New period created!'];
            } else {
                $_SESSION['payroll_message'] = ['type' => 'error', 'text' => 'Failed to create period.'];
            }
            redirect('payrolls');
        }
    }
    public function release($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Change this to match the model method name above
            $this->payrollModel->releasePeriod($id);
            redirect('Payrolls/index');
        }
    }
}