<div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-xl">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/50">
                <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 dark:text-white">Verify your email</h2>
            <p class="mt-2 text-center text-sm text-slate-500 dark:text-slate-400">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-xl text-sm border border-green-100 dark:border-green-800 text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-8 space-y-4">
            <button wire:click="resend" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 dark:bg-amber-600 dark:hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                Resend Verification Email
            </button>

            <div class="flex justify-between items-center text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span>Wrong email address? Register again or logout.</span>
                <a href="{{ route('logout') }}" class="text-amber-500 hover:text-amber-600 dark:text-amber-400 transition-colors">Logout</a>
            </div>
        </div>
    </div>
</div>
