<?php require 'components/head.php'; ?>
<?php require 'components/header.php'; ?>
<?php require 'components/navControl.php'; ?>

    <main class="min-h-[calc(100vh-160px)] bg-zinc-950 px-6 py-8 md:px-12 flex flex-col items-center gap-12">
        <?php if (!empty($data['message'])): ?>
            <div
                    class="mx-auto max-w-4xl w-full px-4 py-3 rounded-xl font-bold tracking-wide text-center
            <?= $data['message']['type'] === 'success' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' ?>">
                <?= htmlspecialchars($data['message']['text']) ?>
            </div>
        <?php endif; ?>
        <div
                class="mx-auto max-w-4xl w-full bg-zinc-900/50 border border-zinc-800 rounded-3xl p-8 md:p-12 shadow-2xl backdrop-blur-sm">

            <!-- Profile Section -->
            <div class="flex flex-col md:flex-row items-center gap-12 mb-8">
                <div class="relative group">
                    <div
                            class="absolute -inset-1 rounded-full bg-gradient-to-tr from-indigo-500 to-cyan-400 opacity-25 blur transition duration-1000 group-hover:opacity-50">
                    </div>
                    <div
                            class="relative h-48 w-48 md:h-64 md:w-64 overflow-hidden rounded-full border-4 border-zinc-800 bg-zinc-800">
                        <img src="https://ui-avatars.com/api/?name=<?php echo $data['username']; ?>&background=6366f1&color=fff&size=512"
                             alt="User Avatar" class="h-full w-full object-cover">
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter text-white">
                            <?php echo htmlspecialchars($data['username']); ?>
                        </h1>
                        <p class="text-xl font-bold uppercase tracking-widest text-indigo-400">
                            <?php echo $data['role'] ?? 'Employee'; ?>
                        </p>
                        <p class="text-lg font-medium uppercase tracking-wide text-zinc-500">
                            <?php echo $data['dept'] ?? 'General Dept'; ?>
                        </p>
                    </div>

                    <div class="grid gap-4">
                        <div
                                class="w-full rounded-xl bg-zinc-800/80 py-3 px-6 text-center font-bold tracking-widest text-zinc-300 border border-zinc-700">
                            RATE: $<?php echo $data['rate'] ?? '0.00'; ?>
                        </div>

                        <div class="flex gap-4">
                            <form action="<?= URLROOT; ?>/home/tapIn" method="POST" class="contents">
                                <button
                                        class="flex-1 rounded-xl bg-indigo-600 py-4 font-black uppercase tracking-widest text-white transition hover:bg-indigo-500 active:scale-95 shadow-lg shadow-indigo-500/20">
                                    Tap In
                                </button>
                            </form>
                            <form action="<?= URLROOT; ?>/home/tapOut" method="POST" class="contents">
                                <button
                                        class="flex-1 rounded-xl bg-zinc-700 py-4 font-black uppercase tracking-widest text-white transition hover:bg-zinc-600 active:scale-95 border border-zinc-600">
                                    Tap Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance History Table -->
            <div class="overflow-x-auto mt-12">
                <h2 class="text-2xl font-bold text-white mb-4">Attendance History</h2>
                <table class="min-w-full divide-y divide-zinc-700 text-zinc-300">
                    <thead class="bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Time In</th>
                        <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Time Out</th>
                        <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Total hours</th>
                        <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Worked hours</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-700">
                    <?php if (!empty($data['attendanceHistory'])): ?>
                        <?php foreach ($data['attendanceHistory'] as $att): ?>
                            <tr class="bg-zinc-900/50 hover:bg-zinc-800 transition">
                                <td class="px-6 py-4"><?= htmlspecialchars($att->attendance_date) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($att->time_in) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($att->time_out ?? '--:--') ?></td>
                                <td class="px-6 py-4"><?= number_format($att->total_hours, 2) ?></td>
                                <td class="px-6 py-4 text-indigo-400 font-bold">
                                    <?= $att->worked_hours !== null ? number_format($att->worked_hours, 2) : '--' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-zinc-500">No attendance records found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

<?php require 'components/footer.php'; ?>