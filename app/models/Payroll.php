<?php
//THIS IS MODEL FOR PAYROLL
//SEPARATED FROM USER SO USER DOESNT GET TOO CLUTTERED
class Payroll {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPeriods() {
        $this->db->query("
            SELECT *,
                (SELECT COUNT(*) FROM payroll_runs WHERE PayrollPeriod_ID = p.PayrollPeriod_ID) as employee_count
            FROM payroll_periods p
            ORDER BY period_start DESC
        ");
        return $this->db->resultSet();
    }

    public function getPeriodById($id) {
        $this->db->query("SELECT * FROM payroll_periods WHERE PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getOpenPeriod() {
        $this->db->query("SELECT * FROM payroll_periods WHERE status = 'open' LIMIT 1");
        return $this->db->single();
    }

    public function createPeriod($data) {
        $this->db->query("
            INSERT INTO payroll_periods (period_start, period_end, pay_date, status)
            VALUES (:start, :end, :pay, 'open')
        ");
        $this->db->bind(':start', $data['period_start']);
        $this->db->bind(':end', $data['period_end']);
        $this->db->bind(':pay', $data['pay_date']);
        return $this->db->execute();
    }

    public function releasePeriod($id) {
        $this->db->query("UPDATE payroll_periods SET status = 'released' WHERE PayrollPeriod_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getMissingPayslips($period_id) {
        $this->db->query("
            SELECT COUNT(*) AS missing
            FROM payroll_runs pr
            LEFT JOIN payslips ps ON pr.PayrollRun_ID = ps.PayrollRun_ID
            WHERE pr.PayrollPeriod_ID = :period_id
            AND ps.Payslip_ID IS NULL
        ");
        $this->db->bind(':period_id', $period_id);
        $row = $this->db->single();
        return $row->missing;
    }

    // ── RUNS ─────────────────────────────────────────────

    public function generate($periodId, $adminId) {
        $this->db->query("CALL generatePayrollRuns(:period_id, :admin_id)");
        $this->db->bind(':period_id', $periodId);
        $this->db->bind(':admin_id', $adminId);
        return $this->db->execute();
    }

    public function getRunsByPeriod($id) {
        $this->db->query("
            SELECT pr.*,
                CONCAT(e.first_name, ' ', IFNULL(CONCAT(e.middle_name, ' '), ''), e.last_name) AS full_name,
                e.employee_code,
                p.title AS position,
                d.name AS department
            FROM payroll_runs pr
            JOIN employees e ON pr.Employee_ID = e.Employee_ID
            JOIN positions p ON e.Position_ID = p.Position_ID
            JOIN departments d ON e.Department_ID = d.Department_ID
            WHERE pr.PayrollPeriod_ID = :id
            ORDER BY e.last_name ASC
        ");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function getRunById($run_id) {
        $this->db->query("
            SELECT
                pr.*,
                pp.period_start, pp.period_end, pp.pay_date,
                CONCAT(e.first_name, ' ', IFNULL(CONCAT(e.middle_name, ' '), ''), e.last_name) AS full_name,
                e.employee_code,
                p.title AS position,
                d.name AS department,
                s.name AS shift_name,
                s.break_minutes,
                (SELECT COUNT(*) FROM attendance a
                 WHERE a.Employee_ID = e.Employee_ID
                 AND a.attendance_date BETWEEN pp.period_start AND pp.period_end
                 AND a.status = 'present') AS days_present,
                (SELECT COUNT(*) FROM attendance a
                 WHERE a.Employee_ID = e.Employee_ID
                 AND a.attendance_date BETWEEN pp.period_start AND pp.period_end
                 AND a.status = 'late') AS days_late,
                (SELECT COALESCE(SUM(a.worked_hours), 0) FROM attendance a
                 WHERE a.Employee_ID = e.Employee_ID
                 AND a.attendance_date BETWEEN pp.period_start AND pp.period_end) AS total_worked_hours
            FROM payroll_runs pr
            JOIN payroll_periods pp ON pr.PayrollPeriod_ID = pp.PayrollPeriod_ID
            JOIN employees e ON pr.Employee_ID = e.Employee_ID
            JOIN positions p ON e.Position_ID = p.Position_ID
            JOIN departments d ON e.Department_ID = d.Department_ID
            LEFT JOIN employee_shifts es ON e.Employee_ID = es.Employee_ID
            LEFT JOIN shifts s ON es.Shift_ID = s.Shift_ID
            WHERE pr.PayrollRun_ID = :run_id
        ");
        $this->db->bind(':run_id', $run_id);
        return $this->db->single();
    }

    public function syncTotals($runId) {
        $this->db->query("CALL syncPayrollTotals(:run_id)");
        $this->db->bind(':run_id', $runId);
        return $this->db->execute();
    }

    // ── ALLOWANCES ───────────────────────────────────────

    public function getAllowancesByRun($run_id) {
        $this->db->query("SELECT * FROM payroll_allowances WHERE PayrollRun_ID = :run_id");
        $this->db->bind(':run_id', $run_id);
        return $this->db->resultSet();
    }

    public function addAllowance($data) {
        $this->db->query("
            INSERT INTO payroll_allowances (PayrollRun_ID, name, amount)
            VALUES (:run_id, :name, :amount)
        ");
        $this->db->bind(':run_id', $data['run_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }

    public function removeAllowance($id) {
        $this->db->query("DELETE FROM payroll_allowances WHERE PayrollAllowance_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ── DEDUCTIONS ───────────────────────────────────────

    public function getDeductionsByRun($run_id) {
        $this->db->query("SELECT * FROM payroll_deductions WHERE PayrollRun_ID = :run_id");
        $this->db->bind(':run_id', $run_id);
        return $this->db->resultSet();
    }

    public function addDeduction($data) {
        $this->db->query("
            INSERT INTO payroll_deductions (PayrollRun_ID, name, amount)
            VALUES (:run_id, :name, :amount)
        ");
        $this->db->bind(':run_id', $data['run_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':amount', $data['amount']);
        return $this->db->execute();
    }

    public function removeDeduction($id) {
        $this->db->query("DELETE FROM payroll_deductions WHERE PayrollDeduction_ID = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ── PAYSLIPS ─────────────────────────────────────────

    public function getPayslipByRun($run_id) {
        $this->db->query("SELECT * FROM payslips WHERE PayrollRun_ID = :run_id");
        $this->db->bind(':run_id', $run_id);
        return $this->db->single();
    }

    public function recordPayslip($run_id) {
        $this->db->query("INSERT INTO payslips (PayrollRun_ID, pdf_path) VALUES (:run_id, NULL)");
        $this->db->bind(':run_id', $run_id);
        return $this->db->execute();
    }
}