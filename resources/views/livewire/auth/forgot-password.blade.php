<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-8 space-y-6">
        <div>
            <h2 class="text-center text-3xl font-extrabold tracking-tight text-slate-900">Forgot Password</h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email address</label>
                <input wire:model="email" id="email" type="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors @error('email') border-red-500 @enderror">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2">
                    Send Reset Link
                </button>
            </div>
        </form>

        <div class="text-center text-sm">
            <a href="{{ route('login') }}" class="font-semibold text-amber-500 hover:text-amber-600">Back to login</a>
        </div>
    </div>
</div>
