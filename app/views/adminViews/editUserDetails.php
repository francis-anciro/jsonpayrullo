<?php require APPROOT . '/views/components/head.php'; ?>

    <main class="min-h-screen bg-zinc-950 px-4 py-12 md:px-12">
        <div class="mx-auto max-w-4xl">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-black uppercase tracking-tighter text-white italic">
                    Edit Employee
                </h1>
                <p class="mt-2 text-zinc-400 uppercase tracking-widest text-xs font-bold">
                    Updating details for: <span class="text-indigo-400"><?php echo $data['user']->employee_code; ?></span>
                </p>
            </header>

            <form action="<?php echo URLROOT; ?>/EditUser/update" method="POST" class="space-y-8">
                <input type="hidden" name="employee_code" value="<?php echo $data['user']->employee_code; ?>">

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">User Account</h3>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Email Address</label>
                            <input type="email" name="email" value="<?php echo $data['user']->email; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">System Role</label>
                            <select name="role" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                                <option value="employee" <?php echo ($data['user']->role == 'employee') ? 'selected' : ''; ?>>Employee</option>
                                <option value="manager" <?php echo ($data['user']->role == 'manager') ? 'selected' : ''; ?>>Manager</option>
                                <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">Personal Details</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">First Name</label>
                            <input type="text" name="first_name" value="<?php echo $data['user']->first_name; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Middle Name</label>
                            <input type="text" name="middle_name" value="<?php echo $data['user']->middle_name; ?>" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo $data['user']->last_name; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                    </div>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Phone</label>
                            <input type="text" name="phone" value="<?php echo $data['user']->phone; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Address</label>
                            <input type="text" name="address" value="<?php echo $data['user']->address; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">Position & Pay</h3>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Department</label>
                            <select name="Department_id" id="dept_select" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                                <option value="1" <?php echo ($data['user']->Department_ID == 1) ? 'selected' : ''; ?>>Creative Production</option>
                                <option value="2" <?php echo ($data['user']->Department_ID == 2) ? 'selected' : ''; ?>>Content & Social</option>
                                <option value="3" <?php echo ($data['user']->Department_ID == 3) ? 'selected' : ''; ?>>Account & Client</option>
                                <option value="4" <?php echo ($data['user']->Department_ID == 4) ? 'selected' : ''; ?>>Operations & Tech</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Position</label>
                            <select id="position_select" name="position_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Basic Salary</label>
                            <input type="number" step="0.01" name="basic_salary" value="<?php echo $data['user']->basic_salary; ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-zinc-300">Shift</label>
                            <select name="shift_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white">
                                <option value="1" <?php echo ($data['user']->Shift_ID == 1) ? 'selected' : ''; ?>>Day Shift</option>
                                <option value="2" <?php echo ($data['user']->Shift_ID == 2) ? 'selected' : ''; ?>>Afternoon Shift</option>
                                <option value="3" <?php echo ($data['user']->Shift_ID == 3) ? 'selected' : ''; ?>>Night Shift</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 py-4 font-black uppercase text-white hover:bg-indigo-500 shadow-lg shadow-indigo-500/20">
                        Save Changes
                    </button>
                    <a href="<?php echo URLROOT;?>/EmployeeList" class="flex items-center justify-center w-full rounded-xl bg-zinc-800 py-4 font-black uppercase text-zinc-400 hover:text-white border border-zinc-700">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Reuse your AJAX logic to populate positions based on pre-selected department
        const deptSelect = document.getElementById('dept_select');
        const posSelect = document.getElementById('position_select');
        const currentPosId = "<?php echo $data['user']->Position_ID; ?>";

        function loadPositions(deptId) {
            fetch(`<?php echo URLROOT; ?>/AddUser/getPositions/${deptId}`)
                .then(res => res.json())
                .then(data => {
                    posSelect.innerHTML = '';
                    data.label.forEach((label, i) => {
                        const opt = document.createElement('option');
                        opt.value = data.values[i];
                        opt.textContent = label;
                        if(opt.value == currentPosId) opt.selected = true; // Auto-select current position
                        posSelect.appendChild(opt);
                    });
                });
        }

        // Load on page load
        loadPositions(deptSelect.value);

        // Listen for changes
        deptSelect.addEventListener('change', (e) => loadPositions(e.target.value));
    </script>

<?php require APPROOT . '/views/components/footer.php'; ?>