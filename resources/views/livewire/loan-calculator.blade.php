<div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 11h.01M12 7h.01M15 11h.01M15 7h.01M12 14h.01M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Home Loan & EMI Calculator
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Inputs Column -->
        <div class="space-y-6">
            <!-- Property Price -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-semibold text-slate-700">Property Price (₹)</label>
                    <span class="text-sm font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">₹{{ number_format($propertyPrice) }}</span>
                </div>
                <input type="range" wire:model.live="propertyPrice" min="100000" max="50000000" step="50000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
            </div>

            <!-- Down Payment -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-semibold text-slate-700">Down Payment (₹)</label>
                    <span class="text-sm font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">₹{{ number_format($downPayment) }}</span>
                </div>
                <input type="range" wire:model.live="downPayment" min="0" max="{{ $propertyPrice }}" step="10000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
            </div>

            <!-- Interest Rate -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-semibold text-slate-700">Interest Rate (%)</label>
                    <span class="text-sm font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">{{ $interestRate }}%</span>
                </div>
                <input type="range" wire:model.live="interestRate" min="1" max="20" step="0.1" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
            </div>

            <!-- Loan Term -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-semibold text-slate-700">Loan Duration (Years)</label>
                    <span class="text-sm font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">{{ $loanTerm }} Years</span>
                </div>
                <input type="range" wire:model.live="loanTerm" min="1" max="30" step="1" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
            </div>
        </div>

        <!-- Output Column -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <span class="text-sm font-medium text-slate-500 uppercase tracking-wider block mb-1">Monthly EMI</span>
                    <span class="text-3xl font-black text-amber-500">₹{{ number_format($monthlyEmi, 2) }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase block">Principal Amount</span>
                        <span class="text-sm font-bold text-slate-800">₹{{ number_format($propertyPrice - $downPayment) }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-500 uppercase block">Total Interest Payable</span>
                        <span class="text-sm font-bold text-slate-800">₹{{ number_format($totalInterest, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-4 mt-6">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-700">Total Amount Payable</span>
                    <span class="text-lg font-extrabold text-slate-900">₹{{ number_format($totalPayment, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
