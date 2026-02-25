<?php
class Payrolls extends Controller
// THIS IS THE PAYROLL CONTROLLER FOR ALL PAYROLL FUNCTIONALITIESD
{
    public function __construct()
    {
        // CHECK IF LOGGED IN AND ADMIN
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            if ($this->isApiRequest()) {
                return $this->handleResponse(['status' => 'error', 'response' => 'Admin access required'], 403);
            }
            redirect('home');
        }
        $this->payrollModel = $this->model('Payroll');
    }

    // GET: /Payrolls/index
    public function index()
    {
        $data = [
            'title' => 'Payroll Periods',
            'periods' => $this->payrollModel->getPeriods(),
            'role' => $_SESSION['role'] ?? 'guest'
        ];
        return $this->handleResponse($data, 200, 'adminViews/payrollIndex');
    }

    public function details($period_id)
    {
        $period = $this->payrollModel->getPeriodById($period_id);
        if (!$period) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Period not found'], 404);
        }

        $runs = $this->payrollModel->getRunsByPeriod($period_id);

        foreach ($runs as $run) {
            $run->allowances = $this->payrollModel->getAllowancesByRun($run->PayrollRun_ID);
            $run->deductions = $this->payrollModel->getDeductionsByRun($run->PayrollRun_ID);
            $run->payslip = $this->payrollModel->getPayslipByRun($run->PayrollRun_ID);
        }

        $data = [
            'title' => 'Period Detail',
            'period' => $period,
            'runs' => $runs,
            'role' => $_SESSION['role'] ?? 'guest'
        ];
        return $this->handleResponse($data, 200, 'adminViews/payrollView');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true) ?? $_POST;

            $existing = $this->payrollModel->getOpenPeriod();
            if ($existing) {
                return $this->handleResponse(['status' => 'error', 'response' => 'There is already an open payroll period.'], 400);
            }

            $success = $this->payrollModel->createPeriod([
                'period_start' => $inputData['period_start'],
                'period_end' => $inputData['period_end'],
                'pay_date' => $inputData['pay_date']
            ]);

            if ($success) {
                return $this->handleResponse(['status' => 'success', 'response' => 'Created'], 201);
            }

            return $this->handleResponse(['status' => 'error', 'response' => 'Failed to create period'], 500);
        }
        return $this->handleResponse(['status' => 'error', 'response' => 'Invalid Request'], 400);
    }

    public function generate($period_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $period = $this->payrollModel->getPeriodById($period_id);
            if (!$period || $period->status !== 'open') {
                return $this->handleResponse(['status' => 'error', 'response' => 'Period is not open.'], 400);
            }

            // Uses Employee_ID from session for admin tracking
            $this->payrollModel->generate($period_id, $_SESSION['Employee_ID']);
            return $this->handleResponse(['status' => 'success', 'response' => 'Payroll generated successfully.'], 200);
        }
    }

    public function addAllowance()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true);

            $this->payrollModel->addAllowance([
                'run_id' => $inputData['run_id'],
                'name' => $inputData['name'],
                'amount' => $inputData['amount']
            ]);

            // Recalculate Gross/Net in DB
            $this->payrollModel->syncTotals($inputData['run_id']);

            return $this->handleResponse(['status' => 'success', 'response' => 'Allowance added.'], 200);
        }
    }

    public function addDeduction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents("php://input"), true);

            $this->payrollModel->addDeduction([
                'run_id' => $inputData['run_id'],
                'name' => $inputData['name'],
                'amount' => $inputData['amount']
            ]);

            $this->payrollModel->syncTotals($inputData['run_id']);

            return $this->handleResponse(['status' => 'success', 'response' => 'Deduction added.'], 200);
        }
    }
    public function release($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Attempt to update status to 'released'
            if ($this->payrollModel->releasePeriod($id)) {
                return $this->handleResponse([
                    'status' => 'success',
                    'response' => "Payroll Period $id has been released and locked."
                ], 200);
            }

            return $this->handleResponse([
                'status' => 'error',
                'response' => 'Failed to release payroll.'
            ], 500);
        }
    }


    public function getSlip($period_id, $run_id)
    {
        $period = $this->payrollModel->getPeriodById($period_id);
        if (!$period) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Period not found'], 404);
        }

        $run = $this->payrollModel->getRunById($run_id);
        if (!$run || $run->PayrollPeriod_ID != $period_id) {
            return $this->handleResponse(['status' => 'error', 'response' => 'Employee payroll record not found.'], 404);
        }

        $allowances = $this->payrollModel->getAllowancesByRun($run_id);
        $deductions = $this->payrollModel->getDeductionsByRun($run_id);

        return $this->handleResponse([
            'status' => 'success',
            'period' => $period,
            'run' => $run,
            'allowances' => $allowances ?: [],
            'deductions' => $deductions ?: [],
        ], 200);
    }
}