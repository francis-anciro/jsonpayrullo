<?php require APPROOT . '/views/components/head.php'; ?>

    <main class="min-h-screen bg-zinc-950 px-4 py-12 md:px-12">
        <div class="mx-auto max-w-4xl">
            <header class="mb-10 text-center">
                <h1 class="text-4xl font-black uppercase tracking-tighter text-white italic">
                    Employee Registration
                </h1>
                <p class="mt-2 text-zinc-400 uppercase tracking-widest text-xs font-bold">
                    Onboard new personnel to the system
                </p>
            </header>

            <?php if (!empty($data['error'])): ?>
                <div class="mb-8 flex items-center gap-4 rounded-2xl border border-red-500/50 bg-red-500/10 p-5 shadow-lg shadow-red-500/5 animate-pulse">
                    <div class="flex-shrink-0 text-red-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-red-500">Registration Error</h3>
                        <p class="text-sm font-bold text-red-200 mt-1 italic"><?php echo $data['error']; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/AddUser/addUser" method="POST" class="space-y-8">

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 backdrop-blur-sm shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">
                        User Account
                    </h3>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Username</label>
                            <input type="text" name="username" placeholder="Username" required
                                   class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white placeholder-zinc-500 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Email Address</label>
                            <input type="email" name="email" required
                                   class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white placeholder-zinc-500 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Password</label>
                            <input type="password" name="password" required
                                   class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white placeholder-zinc-500 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">System Role</label>
                            <select name="role" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">
                        Employee Details
                    </h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">First Name</label>
                            <input type="text" name="first_name" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Middle Name</label>
                            <input type="text" name="middle_name" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Last Name</label>
                            <input type="text" name="last_name" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Phone Number</label>
                            <input type="text" name="phone" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Address</label>
                            <input type="text" name="address" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Birthdate</label>
                            <input type="date" name="birthdate" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Hire Date</label>
                            <input type="date" name="hire_date" value="<?php echo date('Y-m-d'); ?>" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">
                        Position & Compensation
                    </h3>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Department</label>
                            <select name="Department_id" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="">Select a Department</option>
                                <option value="1">Creative Production (CREAPRO)</option>
                                <option value="2">Content & Social (CONTSOC)</option>
                                <option value="3">Account & Client (ACCCLIE)</option>
                                <option value="4">Operations & Tech (OPETECH)</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Position</label>
                            <select id="position_select" name="position_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="">Select a Department First</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Employment Type</label>
                            <select name="employment_type_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="1">Full-Time</option>
                                <option value="2">Part-Time</option>
                                <option value="3">Freelancer</option>
                                <option value="4">Intern/Trainee</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Basic Salary</label>
                            <input type="number" step="0.01" name="basic_salary" required class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl">
                    <h3 class="mb-6 text-sm font-black uppercase tracking-widest text-indigo-400 border-b border-zinc-800 pb-2">
                        Schedule & Leave
                    </h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Shift</label>
                            <select name="shift_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="1">Day Shift</option>
                                <option value="2">Afternoon Shift</option>
                                <option value="3">Night Shift</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-zinc-300">Leave Type </label>
                            <select name="leave_type_id" class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="1">Vacation</option>
                                <option value="2">Sick Leave</option>
                                <option value="3">Unpaid Leave</option>
                                <option value="4">Emergency</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="pt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="submit"
                            class="w-full rounded-xl bg-indigo-600 py-4 font-black uppercase tracking-widest text-white transition hover:bg-indigo-500 active:scale-[0.98] shadow-lg shadow-indigo-500/20">
                        Register Employee
                    </button>
                    <a href="<?php echo URLROOT;?>/EmployeeList/employeeList"
                       class="flex items-center justify-center w-full rounded-xl bg-zinc-800 py-4 font-black uppercase tracking-widest text-zinc-400 transition hover:bg-zinc-700 hover:text-white active:scale-[0.98] border border-zinc-700 shadow-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.querySelector('select[name="Department_id"]').addEventListener('change', function() {
            const deptId = this.value;
            const positionSelect = document.getElementById('position_select');

            // Clear current options
            positionSelect.innerHTML = '<option value="">Loading...</option>';

            if (deptId) {
                // Fetch positions from your controller
                fetch(`<?php echo URLROOT; ?>/AddUser/getPositions/${deptId}`)
                    .then(response => response.json())
                    .then(data => {
                        positionSelect.innerHTML = '<option value="">Select Position</option>';

                        // Loop through labels and values to create options
                        data.label.forEach((label, index) => {
                            const option = document.createElement('option');
                            option.value = data.values[index];
                            option.textContent = label;
                            positionSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching positions:', error);
                        positionSelect.innerHTML = '<option value="">Error loading positions</option>';
                    });
            }
        });
    </script>
<?php require APPROOT . '/views/components/footer.php'; ?>