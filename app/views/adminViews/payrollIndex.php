<?php require APPROOT . '/views/components/head.php'; ?>
<?php require APPROOT . '/views/components/header.php'; ?>
<?php require APPROOT . '/views/components/navControl.php'; ?>

    <main class="min-h-screen bg-zinc-950 px-6 py-8 text-zinc-300">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-white">Payroll Rollout</h1>
                <button onclick="document.getElementById('newPeriodModal').classList.remove('hidden')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-bold transition">
                    + New Period
                </button>
            </div>

            <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl overflow-hidden shadow-xl">
                <table class="w-full text-left">
                    <thead class="bg-zinc-800/50 text-zinc-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Period</th>
                        <th class="px-6 py-4">Pay Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                    <?php foreach($data['periods'] as $p): ?>
                        <tr class="hover:bg-zinc-800/30 transition">
                            <td class="px-6 py-4 text-white">
                                <?= date('M j', strtotime($p->period_start)) ?> – <?= date('M j, Y', strtotime($p->period_end)) ?>
                            </td>
                            <td class="px-6 py-4"><?= date('M j, Y', strtotime($p->pay_date)) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= URLROOT ?>/Payrolls/details/<?= $p->PayrollPeriod_ID ?>"
                                   class="text-indigo-400 font-bold hover:text-white transition">
                                    View Details →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<?php require APPROOT . '/views/components/footer.php'; ?>