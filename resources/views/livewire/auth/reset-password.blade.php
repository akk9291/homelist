<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-8 space-y-6">
        <div>
            <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900">Reset Password</h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Create a new secure password for your account.
            </p>
        </div>

        <form wire:submit.prevent="resetPassword" class="space-y-4">
            <input type="hidden" wire:model="token">

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email address</label>
                <input wire:model="email" id="email" type="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors @error('email') border-red-500 @enderror">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                <input wire:model="password" id="password" type="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors @error('password') border-red-500 @enderror">
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Confirm New Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
