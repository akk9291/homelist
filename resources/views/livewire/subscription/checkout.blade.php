<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black text-slate-900 mb-2 text-center">Upgrade Your Subscription</h1>
    <p class="text-slate-500 text-center mb-12 max-w-xl mx-auto">Choose a plan that fits your business needs. Upgrade now to publish more properties and verify your profile.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Plans List Column -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-7 h-7 bg-amber-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                Select Subscription Plan
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plans as $plan)
                    <div wire:click="$set('selectedPlanId', {{ $plan->id }})" class="border-2 rounded-2xl p-6 cursor-pointer transition-all shadow-sm flex flex-col justify-between h-72 {{ $selectedPlanId === $plan->id ? 'border-amber-500 bg-amber-50/20 shadow-md scale-[1.02]' : 'border-slate-100 bg-white hover:border-slate-200' }}">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Agent Plan</span>
                            <h3 class="font-extrabold text-slate-950 text-xl">{{ $plan->name }}</h3>
                            <div class="my-4">
                                <span class="text-2xl font-black text-slate-950">₹{{ number_format($plan->price) }}</span>
                                <span class="text-slate-500 text-sm">/ {{ $plan->billing_period }}</span>
                            </div>
                            
                            <ul class="text-slate-600 text-xs space-y-1.5 font-medium">
                                <li>✓ Limit: {{ $plan->property_limit ?: 'Unlimited' }} Properties</li>
                                <li>✓ Featured Limit: {{ $plan->featured_limit ?: '0' }} Properties</li>
                                <li>✓ Images Limit: {{ $plan->image_limit ?: '0' }} / Property</li>
                                <li>✓ Video Limit: {{ $plan->video_limit ?: '0' }} / Property</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Checkout Summary Column -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-8 space-y-6 h-fit">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2 mb-2">
                <span class="w-7 h-7 bg-amber-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                Billing Details
            </h2>

            @php $selPlan = $plans->where('id', $selectedPlanId)->first(); @endphp

            @if($selPlan)
                <div class="bg-slate-50 rounded-2xl p-5 space-y-3 text-sm">
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Plan Price:</span>
                        <span>₹{{ number_format($selPlan->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>GST (18%):</span>
                        <span>₹{{ number_format($selPlan->price * 0.18, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-black text-slate-950 border-t border-slate-200 pt-3 text-base">
                        <span>Total Payable:</span>
                        <span>₹{{ number_format($selPlan->price * 1.18, 2) }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-slate-700">Select Payment Method</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="border rounded-xl p-3.5 flex items-center justify-center gap-2 cursor-pointer font-bold text-xs capitalize transition-colors {{ $gateway === 'razorpay' ? 'border-amber-500 bg-amber-50/20 text-amber-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                            <input type="radio" wire:model.live="gateway" value="razorpay" class="hidden">
                            Razorpay
                        </label>
                        <label class="border rounded-xl p-3.5 flex items-center justify-center gap-2 cursor-pointer font-bold text-xs capitalize transition-colors {{ $gateway === 'phonepe' ? 'border-amber-500 bg-amber-50/20 text-amber-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                            <input type="radio" wire:model.live="gateway" value="phonepe" class="hidden">
                            PhonePe
                        </label>
                        <label class="border rounded-xl p-3.5 flex items-center justify-center gap-2 cursor-pointer font-bold text-xs capitalize transition-colors {{ $gateway === 'bank_transfer' ? 'border-amber-500 bg-amber-50/20 text-amber-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }} col-span-2">
                            <input type="radio" wire:model.live="gateway" value="bank_transfer" class="hidden">
                            Bank Transfer / UTR Upload
                        </label>
                    </div>
                </div>

                @if($gateway === 'bank_transfer')
                    <div class="space-y-4 border-t border-slate-100 pt-6">
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-900 space-y-1">
                            <span class="font-bold block">🏦 Bank Account Details:</span>
                            <span>Bank Name: HDFC Bank Ltd</span>
                            <span class="block">Account Name: Homelist Technologies Pvt Ltd</span>
                            <span>A/C Number: 50200085433221</span>
                            <span class="block">IFSC Code: HDFC0000103</span>
                            <span class="block">Branch: Vijay Nagar, Indore</span>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Enter UTR / Txn Reference Number</label>
                            <input type="text" wire:model="utrNumber" placeholder="e.g. 982654321032" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                            @error('utrNumber') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Payment Notes (Optional)</label>
                            <textarea wire:model="notes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors"></textarea>
                        </div>
                    </div>
                @endif

                <div class="pt-4">
                    <button wire:click="processCheckout" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-xl shadow-lg transition-colors flex items-center justify-center gap-2 text-base">
                        Pay & Subscribe Now
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
