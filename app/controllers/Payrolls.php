<?php
class Payrolls extends Controller {
    public function __construct() {
        if ($_SESSION['role'] !== 'admin') redirect('home');
        $this->payrollModel = $this->model('Payroll');
    }

    // PAGE 1: /Payrolls/index
    public function index() {
        $data = [
            'title'   => 'Payroll Periods',
            'periods' => $this->payrollModel->getPeriods(),
            'message' => $_SESSION['payroll_message'] ?? null,
            'role' => $_SESSION['role']
        ];
        unset($_SESSION['payroll_message']);
        $this->view('adminViews/payrollIndex', $data);
    }

    // PAGE 2: /Payrolls/details/2
    public function details($period_id) {
        $period = $this->payrollModel->getPeriodById($period_id);
        if (!$period) {
            redirect('Payrolls');
        }

        $runs = $this->payrollModel->getRunsByPeriod($period_id);

        // Attach allowances, deductions and payslip to each run
        foreach ($runs as $run) {
            $run->allowances = $this->payrollModel->getAllowancesByRun($run->PayrollRun_ID);
            $run->deductions = $this->payrollModel->getDeductionsByRun($run->PayrollRun_ID);
            $run->payslip    = $this->payrollModel->getPayslipByRun($run->PayrollRun_ID);
        }
//        dumpNDie($runs);
        $data = [
            'title'   => 'Period Detail',
            'period'  => $period,
            'runs'    => $runs,
            'message' => $_SESSION['payroll_message'] ?? null,
            'role' => $_SESSION['role'],
        ];
        unset($_SESSION['payroll_message']);
        $this->view('adminViews/payrollView', $data);
    }

    // PAGE 3: /Payrolls/payslip/6
    public function payslip($run_id) {
        $run        = $this->payrollModel->getRunById($run_id);
        $allowances = $this->payrollModel->getAllowancesByRun($run_id);
        $deductions = $this->payrollModel->getDeductionsByRun($run_id);

        $data = [
            'title'      => 'Payslip',
            'run'        => $run,
            'allowances' => $allowances,
            'deductions' => $deductions
        ];
        $this->view('adminViews/payrollSlip', $data);
    }

    // POST: /Payrolls/create
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $existing = $this->payrollModel->getOpenPeriod();
            if ($existing) {
                $_SESSION['payroll_message'] = ['type' => 'error', 'text' => 'There is already an open payroll period.'];
            } else {
                $this->payrollModel->createPeriod([
                    'period_start' => $_POST['period_start'],
                    'period_end'   => $_POST['period_end'],
                    'pay_date'     => $_POST['pay_date']
                ]);
                $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Payroll period created successfully.'];
            }
            redirect('Payrolls');
        }
    }

    // POST: /Payrolls/generate/2
    public function generate($period_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $period = $this->payrollModel->getPeriodById($period_id);
            if (!$period || $period->status !== 'open') {
                $_SESSION['payroll_message'] = ['type' => 'error', 'text' => 'Period is not open.'];
            } else {
                $this->payrollModel->generate($period_id, $_SESSION['Employee_ID']);
                $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Payroll generated successfully.'];
            }
            redirect('Payrolls/details/' . $period_id);
        }
    }

    // POST: /Payrolls/addAllowance
    public function addAllowance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->payrollModel->addAllowance([
                'run_id' => $_POST['run_id'],
                'name'   => $_POST['name'],
                'amount' => $_POST['amount']
            ]);
            $this->payrollModel->syncTotals($_POST['run_id']);
            $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Allowance added.'];
            redirect('Payrolls/details/' . $_POST['period_id']);
        }
    }

    // POST: /Payrolls/removeAllowance
    public function removeAllowance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->payrollModel->removeAllowance($_POST['allowance_id']);
            $this->payrollModel->syncTotals($_POST['run_id']);
            $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Allowance removed.'];
            redirect('Payrolls/details/' . $_POST['period_id']);
        }
    }

    // POST: /Payrolls/addDeduction
    public function addDeduction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->payrollModel->addDeduction([
                'run_id' => $_POST['run_id'],
                'name'   => $_POST['name'],
                'amount' => $_POST['amount']
            ]);
            $this->payrollModel->syncTotals($_POST['run_id']);
            $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Deduction added.'];
            redirect('Payrolls/details/' . $_POST['period_id']);
        }
    }

    // POST: /Payrolls/removeDeduction
    public function removeDeduction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->payrollModel->removeDeduction($_POST['deduction_id']);
            $this->payrollModel->syncTotals($_POST['run_id']);
            $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Deduction removed.'];
            redirect('Payrolls/details/' . $_POST['period_id']);
        }
    }

    // POST: /Payrolls/recordPayslip
    public function recordPayslip() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $run_id   = $_POST['run_id'];
            $existing = $this->payrollModel->getPayslipByRun($run_id);
            if (!$existing) {
                $this->payrollModel->recordPayslip($run_id);
            }
            redirect('Payrolls/payslip/' . $run_id);
        }
    }

    // POST: /Payrolls/release
    public function release($period_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $missing = $this->payrollModel->getMissingPayslips($period_id);
            if ($missing > 0) {
                $_SESSION['payroll_message'] = ['type' => 'error', 'text' => "$missing employee(s) still missing payslips."];
            } else {
                $this->payrollModel->releasePeriod($period_id);
                $_SESSION['payroll_message'] = ['type' => 'success', 'text' => 'Payroll released successfully.', ];
            }
            redirect('Payrolls/details/' . $period_id);
        }
    }
}