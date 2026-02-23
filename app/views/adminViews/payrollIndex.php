<?php require APPROOT . '/views/components/head.php'; ?>
<?php require APPROOT . '/views/components/header.php'; ?>
<?php require APPROOT . '/views/components/navControl.php'; ?>
<?php if (!isset($_SESSION['username'])){redirect("login");} ?>


    <div class="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">

        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50"
             style="background-image: radial-gradient(#666666 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0"></div>
        <div class="fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-violet-500/20 to-transparent blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full max-w-5xl flex flex-col gap-6">

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
                <div class="flex flex-col">
                    <h1 class="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
                        Payroll
                    </h1>
                    <p class="text-violet-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
                        Total Periods: <?= count($data['periods']) ?>
                    </p>
                </div>
                <button
                        onclick="openModal()"
                        class="flex items-center gap-2 bg-violet-600 hover:bg-violet-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm tracking-wider uppercase transition-colors shadow-lg shadow-violet-600/20 border border-violet-500">
                    <i data-lucide="calendar-plus" class="w-[18px] h-[18px]"></i> New Period
                </button>
            </div>

            <!-- Periods Table -->
            <div class="bg-[#0a0a0a]/80 backdrop-blur-sm border border-zinc-700 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">
                <div class="flex flex-col w-full overflow-x-auto">
                    <div class="min-w-[700px]">

                        <!-- Table Header -->
                        <div class="grid grid-cols-6 gap-4 pb-4 border-b border-zinc-800 text-xs font-bold text-zinc-500 uppercase tracking-wider mb-4 px-4">
                            <span class="col-span-2">Payroll Period</span>
                            <span>Pay Date</span>
                            <span>Employees</span>
                            <span>Status</span>
                            <span class="text-right">Action</span>
                        </div>

                        <!-- Table Rows -->
                        <div class="flex flex-col gap-3">
                            <?php if (empty($data['periods'])): ?>
                                <div class="text-center py-10 text-zinc-500 font-bold uppercase tracking-widest">
                                    No payroll periods found.
                                </div>
                            <?php else: ?>
                                <?php foreach ($data['periods'] as $p): ?>
                                    <?php
                                    $statusStyle = match($p->status) {
                                        'open'      => 'border-blue-500 text-blue-500 bg-blue-500/10',
                                        'processed' => 'border-yellow-500 text-yellow-500 bg-yellow-500/10',
                                        'released'  => 'border-green-500 text-green-500 bg-green-500/10',
                                        default     => 'border-zinc-500 text-zinc-500 bg-zinc-500/10'
                                    };
                                    ?>
                                    <div class="grid grid-cols-6 gap-4 items-center bg-[#121212] border border-zinc-800 hover:border-violet-500/50 transition-colors rounded-xl p-4">

                                        <!-- Period -->
                                        <div class="col-span-2 flex items-center gap-3">
                                            <i data-lucide="calendar" class="text-violet-500 w-5 h-5"></i>
                                            <span class="font-bold text-white tracking-wide">
                                            <?= date('M j', strtotime($p->period_start)) ?> –
                                            <?= date('M j, Y', strtotime($p->period_end)) ?>
                                        </span>
                                        </div>

                                        <!-- Pay Date -->
                                        <span class="text-zinc-300 font-medium">
                                        <?= date('M j, Y', strtotime($p->pay_date)) ?>
                                    </span>

                                        <!-- Employee Count -->
                                        <span class="text-zinc-300 font-bold">
                                        <?= $p->employee_count ?>
                                        <span class="text-zinc-600 text-xs font-normal">PROCESSED</span>
                                    </span>

                                        <!-- Status Badge -->
                                        <div>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase border <?= $statusStyle ?>">
                                            <?= htmlspecialchars($p->status) ?>
                                        </span>
                                        </div>

                                        <!-- Action -->
                                        <div class="flex justify-end">
                                            <a href="<?= URLROOT ?>/Payrolls/details/<?= $p->PayrollPeriod_ID ?>"
                                               class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors flex items-center gap-2">
                                                <span class="text-xs font-bold uppercase tracking-widest">View</span>
                                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                            </a>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: New Period -->
        <div id="new-period-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>

            <div class="relative w-full max-w-md bg-[#121212] border border-zinc-800 rounded-[2rem] p-8 shadow-2xl">
                <button onclick="closeModal()" class="absolute top-6 right-6 text-zinc-500 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <h2 class="text-xl font-black text-white uppercase tracking-wider mb-6 flex items-center gap-3">
                    <i data-lucide="calendar-plus" class="text-violet-500 w-6 h-6"></i> Create Period
                </h2>

                <!-- Real form posting to Payrolls/create -->
                <form action="<?= URLROOT ?>/Payrolls/create" method="POST" class="flex flex-col gap-5">

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-zinc-400 tracking-wider uppercase">Period Start</label>
                        <input type="date" name="period_start" required
                               class="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white transition-colors"/>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-zinc-400 tracking-wider uppercase">Period End</label>
                        <input type="date" name="period_end" required
                               class="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white transition-colors"/>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-bold text-zinc-400 tracking-wider uppercase">Pay Date</label>
                        <input type="date" name="pay_date" required
                               class="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-violet-500 outline-none text-white transition-colors"/>
                    </div>

                    <div class="flex gap-3 mt-4">
                        <button type="button" onclick="closeModal()"
                                class="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-zinc-800 text-zinc-300 hover:bg-zinc-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 py-3.5 rounded-xl font-bold tracking-widest text-xs uppercase bg-violet-600 text-white hover:bg-violet-500 transition-colors">
                            Create
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();

        function openModal() {
            const modal = document.getElementById('new-period-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('new-period-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

<?php require APPROOT . '/views/components/footer.php'; ?>