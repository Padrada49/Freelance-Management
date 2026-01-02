<div>
    <h3 class="text-2xl font-bold mb-6">User Approvals</h3>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="flex space-x-2 border-b border-slate-200">
            <button wire:click="$set('filterStatus', 'pending')"
                    class="px-4 py-2 {{ $filterStatus === 'pending' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-slate-600' }}">
                Pending
            </button>
            <button wire:click="$set('filterStatus', 'approved')"
                    class="px-4 py-2 {{ $filterStatus === 'approved' ? 'border-b-2 border-green-500 text-green-600 font-semibold' : 'text-slate-600' }}">
                Approved
            </button>
            <button wire:click="$set('filterStatus', 'rejected')"
                    class="px-4 py-2 {{ $filterStatus === 'rejected' ? 'border-b-2 border-red-500 text-red-600 font-semibold' : 'text-slate-600' }}">
                Rejected
            </button>
            <button wire:click="$set('filterStatus', 'all')"
                    class="px-4 py-2 {{ $filterStatus === 'all' ? 'border-b-2 border-slate-500 text-slate-600 font-semibold' : 'text-slate-600' }}">
                All
            </button>
        </div>
    </div>

    <!-- Pending Users List -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-200">
            <h4 class="font-semibold text-lg">Pending Approvals ({{ $pendingUsers->count() }})</h4>
        </div>

        @if($pendingUsers->isEmpty())
            <div class="p-8 text-center text-slate-500">
                <p>No pending approvals</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Subscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($pendingUsers as $user)
                        @php
                            $proof = $user->paymentProofs->first();
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}"
                                             class="w-10 h-10 rounded-full mr-3" alt="{{ $user->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $user->role === 'freelance' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($proof)
                                    <span class="text-sm">Lifetime Access</span>
                                @else
                                    <span class="text-sm text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($proof)
                                    <span class="font-semibold">฿{{ number_format($proof->amount, 2) }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($proof)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $proof->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                           ($proof->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($proof->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="viewUser({{ $user->id }})"
                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Recently Approved Users -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h4 class="font-semibold text-lg">Recently Approved (Last 10)</h4>
        </div>

        @if($approvedUsers->isEmpty())
            <div class="p-8 text-center text-slate-500">
                <p>No approved users yet</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Subscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Approved At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-700 uppercase">Approved By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($approvedUsers as $user)
                        @php
                            $proof = $user->paymentProofs->first();
                        @endphp
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}"
                                             class="w-8 h-8 rounded-full mr-2" alt="{{ $user->name }}">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-semibold mr-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-sm">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $user->role === 'freelance' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($proof)
                                    Lifetime Access
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                @if($proof)
                                    ฿{{ number_format($proof->amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $user->approved_at?->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $user->approver?->name ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- User Detail Modal -->
    @if($showUserDetail && $selectedUser)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeUserDetail">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-auto m-4" wire:click.stop>
                <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-xl font-bold">Review Registration</h3>
                    <button wire:click="closeUserDetail" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <!-- User Info -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            @if($selectedUser->profile_image)
                                <img src="{{ asset('storage/' . $selectedUser->profile_image) }}"
                                     class="w-20 h-20 rounded-full mr-4" alt="{{ $selectedUser->name }}">
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    {{ substr($selectedUser->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="text-2xl font-bold">{{ $selectedUser->name }}</h4>
                                <p class="text-slate-600">{{ $selectedUser->email }}</p>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full mt-2 inline-block
                                    {{ $selectedUser->role === 'freelance' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($selectedUser->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-600">Registered:</span>
                                <span class="font-medium">{{ $selectedUser->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($selectedProof)
                                <div>
                                    <span class="text-slate-600">Plan:</span>
                                    <span class="font-medium">Lifetime Access</span>
                                </div>
                                <div>
                                    <span class="text-slate-600">Amount:</span>
                                    <span class="font-medium text-lg text-green-600">฿{{ number_format($selectedProof->amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-600">Status:</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $selectedProof->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                           ($selectedProof->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($selectedProof->status) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Slip -->
                    @if($selectedProof && $selectedProof->proof_file)
                        <div class="mb-6">
                            <h5 class="font-semibold mb-3 text-lg">Payment Slip</h5>
                            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                                <img src="{{ $selectedProof->proof_file_url }}"
                                     alt="Payment Slip"
                                     class="max-w-full h-auto rounded shadow-lg">
                            </div>
                        </div>
                    @endif

                    <!-- Admin Note (if already reviewed) -->
                    @if($selectedProof && $selectedProof->admin_note)
                        <div class="mb-6">
                            <h5 class="font-semibold mb-2">Previous Admin Note</h5>
                            <div class="bg-slate-100 border border-slate-300 rounded p-3 text-sm">
                                {{ $selectedProof->admin_note }}
                            </div>
                        </div>
                    @endif

                    <!-- Admin Note Input (for pending only) -->
                    @if($selectedProof && $selectedProof->status === 'pending')
                        <div class="mb-6">
                            <label class="block font-semibold mb-2">Admin Note (Optional)</label>
                            <textarea wire:model="adminNote"
                                      rows="3"
                                      class="w-full border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Add a note for this approval/rejection..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button wire:click="closeUserDetail"
                                    class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium">
                                Cancel
                            </button>
                            <button wire:click="rejectUser"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                                Reject
                            </button>
                            <button wire:click="approveUser"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                                Approve
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
