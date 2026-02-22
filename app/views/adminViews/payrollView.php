<?php require APPROOT . '/views/components/head.php'; ?>
<?php require APPROOT . '/views/components/header.php'; ?>
<?php require APPROOT . '/views/components/navControl.php'; ?>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div id="main-container" class="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none opacity-50"
             style="background-image: radial-gradient(#666666 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full max-w-7xl flex flex-col gap-6">

            <!-- Session Message -->
            <?php if (!empty($data['message'])): ?>
                <div class="px-4 py-3 rounded-xl font-bold tracking-wide text-center
                <?= $data['message']['type'] === 'success'
                        ? 'bg-green-500/20 text-green-400 border border-green-500/30'
                        : 'bg-red-500/20 text-red-400 border border-red-500/30' ?>">
                    <?= htmlspecialchars($data['message']['text']) ?>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                <div class="flex items-center gap-4">
                    <a href="<?= URLROOT ?>/Payrolls"
                       class="p-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors bg-[#121212] border border-zinc-800 shadow-lg">
                        <i data-lucide="arrow-left" class="w-6 h-6"></i>
                    </a>
                    <div class="flex flex-col">
                        <h1 class="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
                            Period Detail
                        </h1>
                        <p class="text-violet-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
                            <?= date('M j', strtotime($data['period']->period_start)) ?> –
                            <?= date('M j, Y', strtotime($data['period']->period_end)) ?> |
                            Pay Date: <?= date('M j, Y', strtotime($data['period']->pay_date)) ?>
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <!-- Status Badge -->
                    <?php
                    $statusStyle = match($data['period']->status) {
                        'open'      => 'border-blue-500 text-blue-500 bg-blue-500/10',
                        'processed' => 'border-yellow-500 text-yellow-500 bg-yellow-500/10',
                        'released'  => 'border-green-500 text-green-500 bg-green-500/10',
                        default     => 'border-zinc-500 text-zinc-500 bg-zinc-500/10'
                    };
                    ?>
                    <span class="px-4 py-2 rounded-xl text-xs font-bold tracking-widest uppercase border flex items-center <?= $statusStyle ?>">
                    STATUS: <?= strtoupper($data['period']->status) ?>
                </span>

                    <!-- Generate Button (only when open) -->
                    <?php if ($data['period']->status === 'open'): ?>
                        <form action="<?= URLROOT ?>/Payrolls/generate/<?= $data['period']->PayrollPeriod_ID ?>" method="POST">
                            <button class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-lg shadow-violet-600/20">
                                <i data-lucide="play" class="w-4 h-4"></i> Generate Payroll
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Runs Table -->
            <div class="bg-[#0a0a0a]/80 backdrop-blur-sm border border-zinc-700 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">

                <?php if (empty($data['runs'])): ?>
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center flex-1 gap-4 py-20 text-zinc-500">
                        <i data-lucide="file-text" class="w-12 h-12 opacity-50"></i>
                        <p class="font-bold tracking-widest uppercase text-sm">
                            Click Generate to process employee payrolls
                        </p>
                    </div>
                <?php else: ?>
                    <!-- Table -->
                    <div class="w-full overflow-x-auto scrollbar-hide">
                        <div class="min-w-[1000px]">
                            <!-- Table Header -->
                            <div class="grid grid-cols-9 gap-4 pb-4 border-b border-zinc-800 text-[10px] font-bold text-zinc-500 uppercase tracking-wider px-2">
                                <span class="col-span-2">Employee</span>
                                <span class="col-span-2">Department / Role</span>
                                <span>Basic Pay</span>
                                <span>OT Pay</span>
                                <span>Net Pay</span>
                                <span class="col-span-2 text-right">Actions</span>
                            </div>

                            <!-- Table Rows -->
                            <div class="flex flex-col mt-4 gap-3">
                                <?php foreach ($data['runs'] as $run): ?>
                                    <div class="grid grid-cols-9 gap-4 items-center bg-[#121212] border border-zinc-800 p-4 rounded-xl hover:border-violet-500/30 transition-all">

                                        <!-- Employee -->
                                        <div class="col-span-2 flex flex-col">
                                            <span class="font-bold text-white truncate"><?= htmlspecialchars($run->full_name) ?></span>
                                            <span class="text-xs text-zinc-500 uppercase"><?= htmlspecialchars($run->employee_code) ?></span>
                                        </div>

                                        <!-- Department / Role -->
                                        <div class="col-span-2 flex flex-col">
                                            <span class="text-sm text-zinc-300 truncate"><?= htmlspecialchars($run->department) ?></span>
                                            <span class="text-xs text-violet-400 uppercase truncate"><?= htmlspecialchars($run->position) ?></span>
                                        </div>

                                        <!-- Basic Pay -->
                                        <span class="text-zinc-300 font-medium">₱<?= number_format($run->basic_pay, 2) ?></span>

                                        <!-- OT Pay -->
                                        <span class="text-orange-400 font-medium">₱<?= number_format($run->overtime_pay, 2) ?></span>

                                        <!-- Net Pay -->
                                        <span class="text-green-400 font-bold">₱<?= number_format($run->net_pay, 2) ?></span>

                                        <!-- Actions -->
                                        <div class="col-span-2 flex justify-end gap-2">
<!--                                            --><?php //dumpNDie($run);?>
                                                <button
                                                        onclick="openManageModal(<?= $run->PayrollRun_ID ?>)"
                                                        class="flex items-center gap-2 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                                    <i data-lucide="settings" class="w-3.5 h-3.5"></i> Manage
                                                </button>

                                            <?php if ($run->payslip): ?>
                                                <a href="<?= URLROOT ?>/Payrolls/payslip/<?= $run->PayrollRun_ID ?>"
                                                   class="flex items-center gap-2 px-4 py-2 bg-violet-600/20 border border-violet-500/50 hover:bg-violet-600 text-violet-400 hover:text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> View Payslip
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                    </div>

                                    <!-- Hidden data for JS modal -->
                                    <div class="hidden"
                                         id="run-data-<?= $run->PayrollRun_ID ?>"
                                         data-run-id="<?= $run->PayrollRun_ID ?>"
                                         data-period-id="<?= $data['period']->PayrollPeriod_ID ?>"
                                         data-name="<?= htmlspecialchars($run->full_name) ?>"
                                         data-code="<?= htmlspecialchars($run->employee_code) ?>"
                                         data-role="<?= htmlspecialchars($run->position) ?>"
                                         data-basic="<?= $run->basic_pay ?>"
                                         data-ot="<?= $run->overtime_pay ?>"
                                         data-gross="<?= $run->gross_pay ?>"
                                         data-net="<?= $run->net_pay ?>"
                                         data-allowances='<?= json_encode(array_map(fn($a) => ['id' => $a->PayrollAllowance_ID, 'name' => $a->name, 'amount' => $a->amount], $run->allowances)) ?>'
                                         data-deductions='<?= json_encode(array_map(fn($d) => ['id' => $d->PayrollDeduction_ID, 'name' => $d->name, 'amount' => $d->amount], $run->deductions)) ?>'>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Release Button (only when processed) -->
            <?php if ($data['period']->status === 'processed'): ?>
                <div class="flex justify-end mt-4">
                    <form action="<?= URLROOT ?>/Payrolls/release/<?= $data['period']->PayrollPeriod_ID ?>" method="POST">
                        <button class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-8 py-4 rounded-xl font-black text-sm tracking-[0.2em] uppercase transition-colors shadow-[0_0_20px_rgba(22,163,74,0.3)]">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i> Release Payroll
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>

        <!-- Manage Modal -->
        <div id="manage-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeManageModal()"></div>
            <div class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-[#0a0a0a] border border-zinc-800 rounded-[2rem] shadow-2xl scrollbar-hide flex flex-col">

                <!-- Modal Header -->
                <div class="p-6 md:p-8 border-b border-zinc-800 flex justify-between items-center bg-[#121212] sticky top-0 z-10">
                    <div class="flex flex-col">
                        <h2 id="modal-emp-name" class="text-2xl font-black text-white uppercase tracking-wider"></h2>
                        <p id="modal-emp-role" class="text-violet-400 font-bold text-xs tracking-widest uppercase"></p>
                    </div>
                    <button onclick="closeManageModal()" class="text-zinc-500 hover:text-white transition-colors p-2 bg-zinc-900 rounded-full">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Allowances & Deductions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">

                    <!-- Allowances -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest border-b border-zinc-800 pb-2">Allowances</h3>
                        <form action="<?= URLROOT ?>/Payrolls/addAllowance" method="POST" class="flex gap-2">
                            <input type="hidden" name="run_id" id="allowance-run-id">
                            <input type="hidden" name="period_id" id="allowance-period-id">
                            <select name="name" required class="flex-1 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none focus:border-violet-500">
                                <option value="">Select...</option>
                                <option value="Rice Subsidy">Rice Subsidy</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Performance Bonus">Performance Bonus</option>
                                <option value="Meal">Meal Allowance</option>
                                <option value="Housing">Housing</option>
                            </select>
                            <input type="number" name="amount" placeholder="Amt" required
                                   class="w-24 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none"/>
                            <button type="submit" class="bg-violet-600 hover:bg-violet-500 text-white p-2 rounded-lg">
                                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            </button>
                        </form>
                        <div id="allowances-list" class="flex flex-col gap-2 mt-2"></div>
                    </div>

                    <!-- Deductions -->
                    <div class="flex flex-col gap-4">
                        <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest border-b border-zinc-800 pb-2">Deductions</h3>
                        <form action="<?= URLROOT ?>/Payrolls/addDeduction" method="POST" class="flex gap-2">
                            <input type="hidden" name="run_id" id="deduction-run-id">
                            <input type="hidden" name="period_id" id="deduction-period-id">
                            <select name="name" required class="flex-1 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none focus:border-red-500">
                                <option value="">Select...</option>
                                <option value="Tax">Withholding Tax</option>
                                <option value="SSS">SSS Contribution</option>
                                <option value="PhilHealth">PhilHealth</option>
                                <option value="Pag-IBIG">Pag-IBIG</option>
                            </select>
                            <input type="number" name="amount" placeholder="Amt" required
                                   class="w-24 bg-[#121212] border border-zinc-800 rounded-lg px-3 text-sm text-white outline-none"/>
                            <button type="submit" class="bg-red-600 hover:bg-red-500 text-white p-2 rounded-lg">
                                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            </button>
                        </form>
                        <div id="deductions-list" class="flex flex-col gap-2 mt-2"></div>
                    </div>

                </div>

                <!-- Modal Footer: Pay Summary -->
                <div class="mt-auto p-6 md:p-8 bg-[#121212] border-t border-zinc-800 grid grid-cols-1 md:grid-cols-2 items-end gap-6">
                    <div class="flex flex-col gap-2 bg-[#0a0a0a] p-4 rounded-xl border border-zinc-800">
                        <div class="flex justify-between text-xs font-bold text-zinc-500 uppercase tracking-widest">
                            <span>Gross Pay</span>
                            <span id="modal-gross" class="text-white">₱0</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-red-500 uppercase tracking-widest">
                            <span>Total Deductions</span>
                            <span id="modal-total-ded">₱0</span>
                        </div>
                        <div class="h-px w-full bg-zinc-800 my-1"></div>
                        <div class="flex justify-between text-sm font-black text-green-400 uppercase tracking-widest">
                            <span>Net Pay</span>
                            <span id="modal-net" class="text-lg">₱0</span>
                        </div>
                    </div>

                    <div class="flex justify-end h-full">
                        <form action="<?= URLROOT ?>/Payrolls/recordPayslip" method="POST" class="w-full md:w-auto h-full">
                            <input type="hidden" name="run_id" id="payslip-run-id">
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 bg-violet-600/10 border border-violet-500/50 hover:bg-violet-600 hover:text-white text-violet-400 px-6 py-4 rounded-xl font-bold tracking-widest text-xs uppercase transition-all w-full h-full">
                                <i data-lucide="file-text" class="w-4 h-4"></i> Generate Payslip
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        // lucide.createIcons();

        const formatMoney = (amount) => '₱' + parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });

        function openManageModal(runId) {
            const el = document.getElementById('run-data-' + runId);

            // Populate modal header
            document.getElementById('modal-emp-name').innerText = el.dataset.name;
            document.getElementById('modal-emp-role').innerText = el.dataset.code + ' | ' + el.dataset.role;

            // Set hidden inputs for forms
            document.getElementById('allowance-run-id').value    = runId;
            document.getElementById('allowance-period-id').value = el.dataset.periodId;
            document.getElementById('deduction-run-id').value    = runId;
            document.getElementById('deduction-period-id').value = el.dataset.periodId;
            document.getElementById('payslip-run-id').value      = runId;

            // Render allowances
            const allowances = JSON.parse(el.dataset.allowances);
            const allowList  = document.getElementById('allowances-list');
            allowList.innerHTML = allowances.length === 0
                ? '<p class="text-xs text-zinc-600 uppercase tracking-widest">No allowances yet.</p>'
                : allowances.map(a => `
                <div class="flex justify-between items-center bg-[#121212] p-3 rounded-lg border border-zinc-800/50">
                    <span class="text-sm text-zinc-300">${a.name}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-white">+ ${formatMoney(a.amount)}</span>
                        <form action="<?= URLROOT ?>/Payrolls/removeAllowance" method="POST">
                            <input type="hidden" name="allowance_id" value="${a.id}">
                            <input type="hidden" name="run_id" value="${runId}">
                            <input type="hidden" name="period_id" value="${el.dataset.periodId}">
                            <button type="submit" class="text-zinc-600 hover:text-red-400 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            `).join('');

            // Render deductions
            const deductions = JSON.parse(el.dataset.deductions);
            const dedList    = document.getElementById('deductions-list');
            dedList.innerHTML = deductions.length === 0
                ? '<p class="text-xs text-zinc-600 uppercase tracking-widest">No deductions yet.</p>'
                : deductions.map(d => `
                <div class="flex justify-between items-center bg-[#121212] p-3 rounded-lg border border-red-900/20">
                    <span class="text-sm text-zinc-300">${d.name}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-red-400">- ${formatMoney(d.amount)}</span>
                        <form action="<?= URLROOT ?>/Payrolls/removeDeduction" method="POST">
                            <input type="hidden" name="deduction_id" value="${d.id}">
                            <input type="hidden" name="run_id" value="${runId}">
                            <input type="hidden" name="period_id" value="${el.dataset.periodId}">
                            <button type="submit" class="text-zinc-600 hover:text-red-400 transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            `).join('');

            // Pay summary
            document.getElementById('modal-gross').innerText    = formatMoney(el.dataset.gross);
            document.getElementById('modal-total-ded').innerText = '- ' + formatMoney(
                deductions.reduce((acc, d) => acc + parseFloat(d.amount), 0)
            );
            document.getElementById('modal-net').innerText = formatMoney(el.dataset.net);

            // Show modal
            const modal = document.getElementById('manage-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            lucide.createIcons();
        }

        function closeManageModal() {
            const modal = document.getElementById('manage-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

<?php require APPROOT . '/views/components/footer.php'; ?>