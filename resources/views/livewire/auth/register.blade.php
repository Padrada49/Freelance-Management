<div>
    <!-- Notification Toast -->
    <div x-data="{ show: false, type: 'success', message: '' }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @show-notification.window="
            show = true;
            type = $event.detail.type;
            message = $event.detail.message;
            setTimeout(() => show = false, 5000);
         "
         class="fixed top-4 right-4 z-50 max-w-md"
         style="display: none;">
        <div :class="{
            'bg-green-50 border-green-500 text-green-800': type === 'success',
            'bg-red-50 border-red-500 text-red-800': type === 'error',
            'bg-yellow-50 border-yellow-500 text-yellow-800': type === 'warning'
        }" class="border-l-4 p-4 rounded shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg x-show="type === 'success'" class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg x-show="type === 'error'" class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium" x-text="message"></p>
                </div>
                <button @click="show = false" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-400 rounded-xl flex items-center justify-center py-12 px-12 sm:px-6 lg:px-8">
        <div class="max-w-6xl w-full bg-white rounded-lg shadow-md overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <!-- Illustration / left column (hidden on small screens) -->
            <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-700 p-8">
                <div class="text-center text-white px-6">
                    <h3 class="text-3xl font-bold mb-2">Join Us</h3>
                    <p class="text-gray-200">Create an account to start managing your projects and tasks.</p>
                </div>
            </div>

            <!-- Form column -->
            <div class="p-8 sm:p-10">
                <div class="max-w-2xl mx-auto">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-extrabold text-gray-900">Create your account</h2>
                        <p class="mt-2 text-sm text-gray-600">Fill in the details below to get started</p>
                    </div>

                    <form wire:submit.prevent="register" class="space-y-6" novalidate>
                        <div>
                            <label for="name" class="sr-only">Full Name</label>
                            <input
                                id="name"
                                type="text"
                                wire:model.defer="name"
                                autocomplete="name"
                                placeholder="Full name"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent" />
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="sr-only">Email</label>
                            <input
                                id="email"
                                type="email"
                                wire:model.defer="email"
                                autocomplete="username"
                                placeholder="you@example.com"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent" />
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input
                                id="password"
                                type="password"
                                wire:model.defer="password"
                                autocomplete="new-password"
                                placeholder="Create a password (min. 6 characters)"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent" />
                            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="sr-only">Confirm Password</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                wire:model.defer="password_confirmation"
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent" />
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                            <select
                                id="role"
                                wire:model.live="role"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="customer">Customer</option>
                                <option value="freelance">Freelancer</option>
                            </select>
                            @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Pricing Display -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Plan</label>
                            <div class="p-4 border-2 border-black bg-slate-50 rounded-lg">
                                <div class="font-semibold text-gray-900">One-time Payment - Lifetime Access</div>
                                <div class="text-3xl font-bold text-black mt-2">฿{{ number_format($this->amount, 0) }}</div>
                                <div class="text-sm text-gray-600 mt-1">Pay once, use forever</div>
                            </div>
                        </div>

                        <!-- Payment Slip Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Slip <span class="text-red-600">*</span>
                            </label>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Payment Required:</strong> Please transfer ฿{{ number_format($this->amount, 0) }} and upload your payment slip below. Your account will be activated after admin approval.
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <input
                                    id="payment_slip"
                                    type="file"
                                    wire:model.live="payment_slip"
                                    accept="image/*"
                                    class="hidden" />
                                <button type="button" onclick="document.getElementById('payment_slip').click()" class="flex-1 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg shadow-sm text-gray-700 hover:border-black hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">
                                    {{ $payment_slip ? 'Change Slip' : 'Upload Payment Slip' }}
                                </button>
                            </div>
                            @error('payment_slip') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            @if ($payment_slip)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-600 mb-2">Payment Slip Preview:</p>
                                    <img src="{{ $payment_slip->temporaryUrl() }}" alt="Slip Preview" class="max-w-xs mx-auto rounded-lg border-2 border-gray-200 shadow">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture (Optional)</label>
                            <div class="flex gap-2">
                                <input
                                    id="profile_image"
                                    type="file"
                                    wire:model.live="profile_image"
                                    accept="image/*"
                                    class="hidden" />
                                <button type="button" onclick="document.getElementById('profile_image').click()" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent">Choose file</button>
                            </div>
                            @error('profile_image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            @if ($profile_image)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                    <img src="{{ $profile_image->temporaryUrl() }}" alt="Profile Preview" class="w-24 h-24 rounded-lg object-cover mx-auto border border-gray-200">
                                </div>
                            @endif
                        </div>

                        <div>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full cursor-pointer flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-black hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-black">
                                <span wire:loading.remove>Create Account</span>
                                <div wire:loading class="inline-flex flex-row items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 text-white inline-flex items-center justify-center" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <div>Creating account...</div>
                                </div>
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Already have an account?
                                <a href="{{ route('login') }}" class="font-medium text-black hover:underline">Sign in here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
