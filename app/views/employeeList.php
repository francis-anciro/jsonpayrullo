<?php
require 'components/head.php';
require 'components/header.php';
require 'components/navControl.php';
?>

    <main class="min-h-[calc(100vh-160px)] bg-zinc-950 px-4 py-8 md:px-12">
        <div class="mx-auto max-w-5xl">

            <div class="mb-6 flex items-center justify-between px-6">
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">Employee Details</span>
                    <span class="text-[10px] text-zinc-600 uppercase tracking-tight">Manage your team directory</span>
                </div>

                <div class="flex items-center gap-8">
                    <a href="<?php echo URLROOT;?>/AddUser/"
                       class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-indigo-500 active:scale-95 shadow-lg shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Employee
                    </a>
                </div>
            </div>

            <div class="space-y-px overflow-hidden rounded-xl border border-zinc-800 bg-zinc-900/50 shadow-2xl">
                <?php foreach ($data['users'] as $user): ?>
                    <div class="group relative flex w-full items-center justify-between border-b border-zinc-800/50 bg-zinc-900/30 px-6 py-4 text-left transition hover:bg-zinc-800/80 last:border-0">

                        <a href="<?php echo URLROOT; ?>/users/edit/<?php echo $user->user_id; ?>" class="absolute inset-0 z-0"></a>

                        <div class="relative z-10 flex items-center gap-4 w-1/3 pointer-events-none">
                            <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-full border border-zinc-700 bg-zinc-800 group-hover:border-indigo-500/50 transition">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user->username); ?>&background=3f3f46&color=fff"
                                     alt="Avatar" class="h-full w-full object-cover">
                            </div>

                            <div class="flex flex-col truncate">
                            <span class="text-sm font-bold tracking-tight text-white uppercase italic group-hover:text-indigo-400 transition">
                                <?php echo htmlspecialchars($user->username); ?>
                            </span>
                                <span class="text-xs text-zinc-500 truncate">
                                <?php echo htmlspecialchars($user->email); ?>
                            </span>
                            </div>
                        </div>

                        <div class="relative z-10 hidden md:flex flex-col w-1/4 pointer-events-none">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-600">Department</span>
                            <span class="text-xs font-semibold text-zinc-300 group-hover:text-zinc-100 transition">
                            <?php echo htmlspecialchars($user->department_name); ?>
                        </span>
                        </div>

                        <div class="relative z-20 flex items-center justify-end gap-6 w-1/3">


                            <div class="flex items-center">
                                <?php echo userBadgeCheck($user->role); ?>
                            </div>
                            <button type="button"
                                    onclick="openDeleteModal('<?php echo $user->employee_code; ?>', '<?php echo URLROOT; ?>/EmployeeList/delete/<?php echo $user->employee_code; ?>')"
                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-600 hover:text-red-500 transition">
                                Delete
                            </button>
                            <a href="<?php echo URLROOT;?>/EditUser/index/<?php echo $user->employee_code; ?>"
                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-600 hover:text-red-500 transition">
                                Edit
                            </a>
<!--                            <div class="text-zinc-600 transition group-hover:text-white">-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">-->
<!--                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />-->
<!--                                </svg>-->
<!--                            </div>-->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if(empty($data['users'])): ?>
                <div class="mt-8 text-center text-zinc-500 italic">No employees found.</div>
            <?php endif; ?>
        </div>
    </main>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-zinc-950/80 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-zinc-800 bg-zinc-900 p-8 shadow-2xl">
            <h3 class="text-xl font-black uppercase italic tracking-tighter text-white">Confirm Deletion</h3>
            <p class="mt-4 text-sm text-zinc-400">
                Are you sure you want to delete <span id="modal-employee-code" class="font-bold text-indigo-400"></span>? This action is permanent.
            </p>

            <div class="mt-8 flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-zinc-800 py-3 text-xs font-bold uppercase tracking-widest text-zinc-400 hover:bg-zinc-700 hover:text-white transition">
                    Cancel
                </button>
                <form id="modal-delete-form" method="POST" class="flex-1">
                    <button type="submit" class="w-full rounded-xl bg-red-600 py-3 text-xs font-bold uppercase tracking-widest text-white hover:bg-red-500 transition shadow-lg shadow-red-500/20">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(code, url) {
            document.getElementById('modal-employee-code').innerText = code;
            document.getElementById('modal-delete-form').action = url;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.getElementById('delete-modal').classList.remove('flex');
        }
    </script>

<?php require 'components/footer.php'; ?>