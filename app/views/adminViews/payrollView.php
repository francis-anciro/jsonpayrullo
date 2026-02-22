<?php require APPROOT . '/views/components/head.php'; ?>
<?php require APPROOT . '/views/components/header.php'; ?>
<?php require APPROOT . '/views/components/navControl.php'; ?>

<main class="min-h-screen bg-zinc-950 px-6 py-8 text-zinc-300">
    <div class="max-w-7xl mx-auto">

        <div class="flex justify-between items-start mb-8 bg-zinc-900/50 p-6 rounded-2xl border border-zinc-800">
            <div>
                <p class="text-zinc-500 text-sm uppercase tracking-widest mb-1">Payroll Period</p>
                <h1 class="text-2xl font-bold text-white">
                    <?= date('M j', strtotime($data['period']->period_start)) ?> – <?= date('M j, Y', strtotime($data['period']->period_end)) ?>
                </h1>
                <p class="mt-2 text-zinc-400">Pay Date: <span class="text-white"><?= date('M j, Y', strtotime($data['period']->pay_date)) ?></span></p>
            </div>

            <div class="flex gap-4">
                <?php if($data['period']->status === 'open'): ?>
                    <form action="<?= URLROOT ?>/payrolls/generate/<?= $data['period']->PayrollPeriod_ID ?>" method="POST">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold transition">
                            Generate Payroll
                        </button>
                    </form>
                <?php elseif($data['period']->status === 'processed'): ?>
                    <form action="<?= URLROOT ?>/payrolls/release/<?= $data['period']->PayrollPeriod_ID ?>" method="POST">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold transition">
                            Release Payroll
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-zinc-800/50 text-zinc-400 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4 text-right">Basic Pay</th>
                    <th class="px-6 py-4 text-right">OT Pay</th>
                    <th class="px-6 py-4 text-right">Allowances</th>
                    <th class="px-6 py-4 text-right">Deductions</th>
                    <th class="px-6 py-4 text-right text-white">Net Pay</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                <?php foreach ($data['runs'] as $run): ?>
                    <tr class="hover:bg-zinc-800/30">
                        <td class="px-6 py-4">
                            <div class="font-bold text-white"><?= $run->first_name . ' ' . $run->last_name ?></div>
                            <div class="text-xs text-zinc-500"><?= $run->employee_code ?></div>
                        </td>
                        <td class="px-6 py-4 text-right">₱<?= number_format($run->basic_pay, 2) ?></td>
                        <td class="px-6 py-4 text-right text-orange-400">₱<?= number_format($run->overtime_pay, 2) ?></td>
                        <td class="px-6 py-4 text-right text-green-400">₱<?= number_format($run->allowances_total, 2) ?></td>
                        <td class="px-6 py-4 text-right text-red-400">₱<?= number_format($run->deductions_total, 2) ?></td>
                        <td class="px-6 py-4 text-right font-bold text-white bg-white/5">₱<?= number_format($run->net_pay, 2) ?></td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openManageModal(<?= $run->PayrollRun_ID ?>)" class="text-xs bg-zinc-800 px-3 py-1 rounded hover:bg-zinc-700">Manage</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>