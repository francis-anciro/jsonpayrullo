<?php
class Payroll {
    private $db;

    public function __construct() { $this->db = new Database(); }

    // --- DATA FETCHING METHODS (REQUIRED BY CONTROLLER) ---

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

    // --- LOGIC METHODS (REQUIRED FOR THE FLOW) ---

    public function generate($periodId, $adminId) {
        $this->db->query("CALL generatePayrollRuns(:period_id, :admin_id)");
        $this->db->bind(':period_id', $periodId);
        $this->db->bind(':admin_id', $adminId); // If this is NULL, the SQL error triggers
        return $this->db->execute();
    }

    public function syncTotals($runId) {
        $this->db->query("CALL syncPayrollTotals(:run_id)");
        $this->db->bind(':run_id', $runId);
        return $this->db->execute();
    }

    public function releasePeriod($id) { // Ensure this matches Controller call
        $this->db->query("UPDATE payroll_periods SET status = 'released' WHERE PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function addAllowance($data) {
        $this->db->query("INSERT INTO payroll_allowances (PayrollRun_ID, type, amount) VALUES (:run_id, :type, :amount)");
        $this->db->bind(':run_id', $data['PayrollRun_ID']);
        $this->db->bind(':type', $data['allowance_name']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }

    public function addDeduction($data) {
        $this->db->query("INSERT INTO payroll_deductions (PayrollRun_ID, type, amount) VALUES (:run_id, :type, :amount)");
        $this->db->bind(':run_id', $data['PayrollRun_ID']);
        $this->db->bind(':type', $data['deduction_name']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }
}