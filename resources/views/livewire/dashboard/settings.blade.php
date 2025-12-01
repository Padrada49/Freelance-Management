<div>
    <h3 class="text-2xl font-bold mb-6">Settings</h3>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Image Upload -->
        <div class="bg-slate-50 p-4 rounded border border-slate-200">
            <h4 class="font-semibold text-lg mb-4">Profile Picture</h4>
            <div class="mb-4 text-center">
                @if ($previewUrl)
                    <img src="{{ $previewUrl }}" alt="Profile" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover" />
                @else
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                @endif
            </div>
            <form wire:submit.prevent="uploadProfileImage" class="space-y-3">
                <input type="file" wire:model="profileImage" accept="image/*" class="w-full" />
                @error('profileImage') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                <button type="submit" wire:loading.attr="disabled" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <span wire:loading.remove>Upload Image</span>
                    <span wire:loading>Uploading...</span>
                </button>
            </form>
        </div>

        <!-- Privacy & Security -->
        <div class="bg-slate-50 p-4 rounded border border-slate-200">
            <h4 class="font-semibold text-lg mb-4">Privacy & Security</h4>
            <p class="text-sm text-slate-600 mb-4">Change your password and security settings.</p>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Manage Security
            </button>
        </div>

        <!-- Notifications -->
        <div class="bg-slate-50 p-4 rounded border border-slate-200">
            <h4 class="font-semibold text-lg mb-4">Notifications</h4>
            <p class="text-sm text-slate-600 mb-4">Configure notification preferences.</p>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Notification Settings
            </button>
        </div>

        <!-- General Settings -->
        <div class="bg-slate-50 p-4 rounded border border-slate-200">
            <h4 class="font-semibold text-lg mb-4">General Settings</h4>
            <p class="text-sm text-slate-600 mb-4">Adjust general application settings.</p>
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                General Settings
            </button>
        </div>
    </div>
</div>
