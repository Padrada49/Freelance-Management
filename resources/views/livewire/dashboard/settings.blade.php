<div x-data="{ activeTab: 'profile' }">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-slate-900">Settings</h2>
            <p class="text-slate-600 mt-1">Manage your account settings and preferences</p>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="flex overflow-x-auto">
                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-4 font-medium whitespace-nowrap transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile</span>
                    </div>
                </button>

                <button @click="activeTab = 'password'" 
                        :class="activeTab === 'password' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-4 font-medium whitespace-nowrap transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span>Security</span>
                    </div>
                </button>

                @if($paymentSlipUrl)
                <button @click="activeTab = 'payment'" 
                        :class="activeTab === 'payment' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-4 font-medium whitespace-nowrap transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Payment Proof</span>
                    </div>
                </button>
                @endif

                @if(auth()->user()->role === 'admin')
                <button @click="activeTab = 'pricing'" 
                        :class="activeTab === 'pricing' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-600 hover:text-slate-900'"
                        class="px-6 py-4 font-medium whitespace-nowrap transition-colors">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Pricing</span>
                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">Admin</span>
                    </div>
                </button>
                @endif
            </div>
        </div>

        <!-- Tab Content -->
        <div class="space-y-6">
            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-cloak>
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-slate-900 mb-6">Profile Information</h3>
                    
                    <form wire:submit.prevent="updateProfile">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Profile Image Column -->
                            <div class="md:col-span-1">
                                <label class="block text-sm font-medium text-slate-700 mb-3">Profile Picture</label>
                                <div class="flex flex-col items-center">
                                    @if ($previewUrl)
                                        <img src="{{ $previewUrl }}" alt="Profile" class="w-40 h-40 rounded-full object-cover border-4 border-blue-100 shadow-lg mb-4" />
                                    @else
                                        <div class="w-40 h-40 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg mb-4">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    <div class="w-full">
                                        <input type="file" wire:model.live="profileImage" accept="image/*" 
                                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                        @error('profileImage')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        <div wire:loading wire:target="profileImage" class="text-xs text-blue-600 mt-1">
                                            Uploading image...
                                        </div>
                                        <p class="text-xs text-slate-500 mt-2">JPG, PNG or GIF (MAX. 2MB)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Fields Column -->
                            <div class="md:col-span-2 space-y-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                                    <input type="text" wire:model="name" 
                                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                           placeholder="Enter your full name" />
                                    @error('name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                                    <div class="relative">
                                        <input type="email" value="{{ $email }}" disabled 
                                               class="w-full px-4 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 cursor-not-allowed" />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        Email cannot be changed for security reasons
                                    </p>
                                </div>

                                <!-- Role Badge -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Account Type</label>
                                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                        <span class="font-semibold text-blue-900 capitalize">{{ auth()->user()->role }}</span>
                                    </div>
                                </div>

                                <!-- Save Button -->
                                <div class="pt-4">
                                    <button type="submit" 
                                            wire:loading.attr="disabled"
                                            wire:target="updateProfile, profileImage"
                                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="updateProfile, profileImage">Save Changes</span>
                                        <span wire:loading wire:target="updateProfile, profileImage" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Tab -->
            <div x-show="activeTab === 'password'" x-cloak>
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">Change Password</h3>
                    <p class="text-slate-600 text-sm mb-6">Update your password to keep your account secure</p>
                    
                    <form wire:submit.prevent="updatePassword" class="max-w-lg">
                        <div class="space-y-4">
                            <!-- Current Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                                <input type="password" wire:model="current_password" 
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="Enter current password" />
                                @error('current_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                                <input type="password" wire:model="new_password" 
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="Enter new password (min. 8 characters)" />
                                @error('new_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                                <input type="password" wire:model="new_password_confirmation" 
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="Confirm new password" />
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium mb-1">Password Requirements:</p>
                                        <ul class="list-disc list-inside space-y-1 text-blue-700">
                                            <li>Minimum 8 characters</li>
                                            <li>Include uppercase and lowercase letters</li>
                                            <li>Include at least one number</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm disabled:opacity-50">
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword">Updating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Proof Tab -->
            @if($paymentSlipUrl)
            <div x-show="activeTab === 'payment'" x-cloak>
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">Payment Proof</h3>
                    <p class="text-slate-600 text-sm mb-6">Payment slip submitted during registration</p>
                    
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="flex-shrink-0">
                            <img src="{{ $paymentSlipUrl }}" alt="Payment Slip" 
                                 class="w-64 h-auto rounded-lg border-2 border-slate-200 shadow-md hover:shadow-xl transition-shadow cursor-pointer"
                                 onclick="window.open('{{ $paymentSlipUrl }}', '_blank')" />
                        </div>
                        <div class="flex-1">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-green-900">Payment Verified</p>
                                        <p class="text-sm text-green-700 mt-1">Your payment has been confirmed and your account is active</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <a href="{{ $paymentSlipUrl }}" target="_blank" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    View Full Size
                                </a>
                                
                                <a href="{{ $paymentSlipUrl }}" download 
                                   class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors ml-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pricing Tab (Admin Only) -->
            @if(auth()->user()->role === 'admin')
            <div x-show="activeTab === 'pricing'" x-cloak>
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">Pricing Settings</h3>
                            <p class="text-slate-600 text-sm mt-1">Manage lifetime pricing for different account types</p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">Admin Only</span>
                    </div>
                    
                    <form wire:submit.prevent="savePricing">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Freelance Price -->
                            <div class="p-5 border-2 border-purple-200 rounded-lg bg-gradient-to-br from-purple-50 to-white">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Freelance</h4>
                                        <p class="text-xs text-slate-600">Lifetime Access</p>
                                    </div>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-medium">฿</span>
                                    <input type="number" wire:model="freelance_price" step="0.01" min="0"
                                        class="w-full pl-8 pr-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg font-semibold"
                                        placeholder="2990" />
                                </div>
                                @error('freelance_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Customer Price -->
                            <div class="p-5 border-2 border-blue-200 rounded-lg bg-gradient-to-br from-blue-50 to-white">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Customer</h4>
                                        <p class="text-xs text-slate-600">Lifetime Access</p>
                                    </div>
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-medium">฿</span>
                                    <input type="number" wire:model="customer_price" step="0.01" min="0"
                                        class="w-full pl-8 pr-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-semibold"
                                        placeholder="1990" />
                                </div>
                                @error('customer_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium">Important Notice:</p>
                                    <p class="mt-1">Price changes will take effect immediately. New registrations will see the updated pricing.</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm disabled:opacity-50">
                            <span wire:loading.remove wire:target="savePricing">Save Pricing Settings</span>
                            <span wire:loading wire:target="savePricing">Saving...</span>
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
