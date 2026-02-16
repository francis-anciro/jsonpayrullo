<nav class="border-b border-zinc-800 bg-zinc-950 px-6">
    <div class="mx-auto flex max-w-7xl items-center justify-between">

        <div class="flex items-center gap-8">

            <div class="flex items-center gap-6 text-sm font-medium">
                <a href="<?php echo URLROOT;?>/home"
                   class="relative py-5 transition <?php echo isActive('home'); ?>">
                    Home
                </a>

                <a href="<?php echo URLROOT;?>/employeeList"
                   class="relative py-5 transition <?php echo isActive('employeeList'); ?>">
                    Employee List
                </a>

                <a href="#" class="relative py-5 transition <?php echo isActive('registration'); ?>">
                    Analytics
                </a>

            </div>
        </div>

    </div>
</nav>