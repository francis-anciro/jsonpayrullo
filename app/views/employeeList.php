<?php
require 'components/head.php';
require 'components/header.php';
require 'components/navControl.php';
?>
<?php
// Check both data and session for errors
$errorMessage = $data['error'] ?? $_SESSION['flash_error'] ?? null;
if($errorMessage):
    ?>
    <div id="toast-error"
         class="fixed top-0 left-1/2 z-[100] w-full max-w-md -translate-x-1/2 px-4 mt-6 animate-[bounce-in_0.5s_ease-out_forwards]">
        <div class="flex items-center gap-3 rounded-xl border border-red-500/50 bg-zinc-900 p-4 shadow-2xl backdrop-blur-md">
            <div class="flex-shrink-0 text-red-500">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm font-bold uppercase tracking-tight text-white italic">
                <?php echo htmlspecialchars($errorMessage); ?>
            </p>
        </div>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

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
                    <?php
                    if ($user->User_ID === $data['current_user_id']) continue;
                    ?>
                    <div class="group relative flex w-full items-center justify-between border-b border-zinc-800/50 bg-zinc-900/30 px-6 py-4 text-left transition hover:bg-zinc-800/80 last:border-0">
                        <a href="<?php echo URLROOT; ?>/users/edit/<?php echo $user->user_id; ?>" class="absolute inset-0 z-0"></a>

                        <div class="relative z-10 flex items-center gap-4 w-1/4 pointer-events-none">
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

                        <div class="relative z-10 hidden lg:flex items-center justify-center w-1/6 pointer-events-none">
                            <?php if($user->is_active == 1): ?>
                                <span class="rounded-full bg-green-500/10 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-green-500 border border-green-500/20">
                Active
            </span>
                            <?php else: ?>
                                <span class="rounded-full bg-zinc-800 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-zinc-500 border border-zinc-700">
                Resigned
            </span>
                            <?php endif; ?>
                        </div>

                        <div class="relative z-20 flex items-center justify-end gap-6 w-1/4">
                            <div class="flex items-center">
                                <?php echo userBadgeCheck($user->role); ?>
                            </div>

                            <button type="button"
                                    onclick="openActionModal('<?php echo $user->employee_code; ?>', '<?php echo URLROOT; ?>')"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-800 bg-zinc-900/50 text-zinc-500 transition hover:border-zinc-600 hover:text-white active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
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
            <h3 class="text-xl font-black uppercase italic tracking-tighter text-white">Confirm Resignation</h3>

            <p class="mt-4 text-sm text-zinc-400">
                Are you sure you want to mark <span id="modal-employee-code" class="font-bold text-indigo-400"></span> as resigned? This will deactivate their account access.
            </p>

            <div class="mt-8 flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-zinc-800 py-3 text-xs font-bold uppercase tracking-widest text-zinc-400 hover:bg-zinc-700 hover:text-white transition">
                    Cancel
                </button>
                <form id="modal-delete-form" method="POST" class="flex-1">
                    <button type="submit" class="w-full rounded-xl bg-orange-600 py-3 text-xs font-bold uppercase tracking-widest text-white hover:bg-orange-500 transition shadow-lg shadow-orange-500/20">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="action-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-zinc-950/80 backdrop-blur-sm">
        <div class="w-full max-w-xs rounded-2xl border border-zinc-800 bg-zinc-900 p-4 shadow-2xl">
            <div class="mb-4 flex items-center justify-between px-2">
                <h3 class="text-xs font-black uppercase tracking-widest text-zinc-500">Employee Actions</h3>
                <button onclick="closeActionModal()" class="text-zinc-500 hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <div class="space-y-2">
                <a id="modal-edit-link" href="#"
                   class="flex items-center gap-3 w-full rounded-xl bg-zinc-800/50 p-4 text-sm font-bold uppercase tracking-tight text-white transition hover:bg-zinc-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                    </div>
                    Edit Profile
                </a>

                <button onclick="triggerResignFromAction()"
                        class="flex items-center gap-3 w-full rounded-xl bg-zinc-800/50 p-4 text-sm font-bold uppercase tracking-tight text-white transition hover:bg-red-500/10 hover:text-red-500">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500/10 text-red-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" /></svg>
                    </div>
                    Mark Resigned
                </button>
            </div>
        </div>
    </div>
    <script src="<?php echo URLROOT; ?>/js/employeeList/toast.js"></script>
    <script src="<?php echo URLROOT; ?>/js/employeeList/employeeActions.js"></script>


<?php require 'components/footer.php'; ?>