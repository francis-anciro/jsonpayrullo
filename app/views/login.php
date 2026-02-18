<?php require 'components/head.php';?>

<div class="flex min-h-[80vh] items-center justify-center px-6 py-12">
    <form action="<?php echo URLROOT;?>/login/auth" method="post"
          class="w-full max-w-md rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 shadow-2xl backdrop-blur-sm">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white">Welcome Back</h1>
            <p class="mt-2 text-sm text-zinc-400">Please enter your details to login</p>
        </div>

        <?php if($data['status'] === 'failed'): ?>
            <div class="mb-6 rounded-lg border border-red-500/50 bg-red-500/10 p-3 text-sm text-red-400">
                <?php echo htmlspecialchars($data['response'])?>
            </div>
        <?php endif; ?>

        <div class="space-y-5">
            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Email Address</label>
                <input type="email" name="email" required
                       class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-3 text-white placeholder-zinc-500 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="name@company.com">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-zinc-300">Password</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-3 text-white placeholder-zinc-500 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                       placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-semibold text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
                Sign In
            </button>
        </div>

        <p class="mt-8 text-center text-sm text-zinc-400">
            Don't have an account?
            <a href="<?php echo URLROOT; ?>/register" class="font-medium text-indigo-400 hover:text-indigo-300 transition">
                Register here
            </a>
        </p>
    </form>
</div>
