<div class="max-w-2xl">
    <h3 class="text-lg font-semibold">Account</h3>
    <p class="text-sm text-slate-600 mb-4">Manage your account settings here.</p>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="updateProfile" class="space-y-4">
        <div class="flex items-center gap-4">
            <div>
                @if ($profile_image)
                    <img src="{{ $profile_image->temporaryUrl() }}" class="w-20 h-20 rounded-full object-cover border" alt="preview">
                @else
                    <img src="{{ auth()->user()->profile_image_url }}" class="w-20 h-20 rounded-full object-cover border" alt="avatar">
                @endif
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" wire:model="profile_image" accept="image/*" class="mt-2" />
                @error('profile_image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Full name</label>
            <input type="text" wire:model.defer="name" class="mt-1 block w-full border px-3 py-2 rounded" />
            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" wire:model.defer="email" class="mt-1 block w-full border px-3 py-2 rounded" />
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">New password <span class="text-xs text-slate-500">(leave blank to keep current)</span></label>
            <input type="password" wire:model.defer="password" class="mt-1 block w-full border px-3 py-2 rounded" />
            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm new password</label>
            <input type="password" wire:model.defer="password_confirmation" class="mt-1 block w-full border px-3 py-2 rounded" />
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-black text-white rounded">Save changes</button>
        </div>
    </form>

    {{-- account deletion removed from modal --}}

</div>
