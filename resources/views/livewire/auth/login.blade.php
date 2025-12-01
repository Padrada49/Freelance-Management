<div>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-4xl w-full bg-white rounded-lg shadow-md overflow-hidden grid grid-cols-1 md:grid-cols-2">
    <!-- Illustration / left column (hidden on small screens) -->
    <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-700 p-8">
      <div class="text-center text-white px-6">
        <h3 class="text-3xl font-bold mb-2">Welcome Back</h3>
        <p class="text-gray-200">Sign in to manage your projects and tasks.</p>
      </div>
    </div>

    <!-- Form column -->
    <div class="p-8 sm:p-10">
      <div class="max-w-md mx-auto">
        <div class="text-center mb-6">
          <h2 class="text-2xl font-extrabold text-gray-900">Sign in to your account</h2>
          <p class="mt-2 text-sm text-gray-600">Enter your details below to continue</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6" novalidate>
          <div>
            <label for="email" class="sr-only">Email</label>
            <input
              id="email"
              type="email"
              wire:model.defer="email"
              autocomplete="username"
              placeholder="you@example.com"
              class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
            />
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              type="password"
              wire:model.defer="password"
              autocomplete="current-password"
              placeholder="Your password"
              class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
            />
            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
              <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 text-black border-gray-300 rounded" />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>

            <div class="text-sm">
              <a href="#" class="font-medium text-black hover:underline">Forgot your password?</a>
            </div>
          </div>

          <div>
            <button
              type="submit"
              wire:loading.attr="disabled"
              class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-black hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-black"
            >
              <span wire:loading.remove>Sign in</span>
              <span wire:loading class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span>Signing in...</span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast / Popup for failed login -->
<div id="login-toast" class="fixed bottom-6 right-6 max-w-xs w-full bg-red-600 text-white rounded-lg shadow-lg transform translate-y-6 opacity-0 pointer-events-none transition-all duration-300" role="alert" aria-hidden="true">
  <div class="p-4">
    <div class="flex items-start">
      <div class="flex-shrink-0">
        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
        </svg>
      </div>
      <div class="ml-3 w-0 flex-1 text-sm">
        <p id="login-toast-message" class="truncate">Login failed</p>
      </div>
      <div class="ml-4 flex-shrink-0 self-start">
        <button id="login-toast-close" class="inline-flex text-white focus:outline-none">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const toast = document.getElementById('login-toast');
    const msg = document.getElementById('login-toast-message');
    const closeBtn = document.getElementById('login-toast-close');
    let hideTimeout = null;

    function showToast(message) {
      if (!toast) return;
      msg.textContent = message || 'Login failed';
      toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-6');
      toast.classList.add('opacity-100');
      toast.setAttribute('aria-hidden', 'false');
      if (hideTimeout) clearTimeout(hideTimeout);
      hideTimeout = setTimeout(hideToast, 4000);
    }

    function hideToast() {
      if (!toast) return;
      toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-6');
      toast.classList.remove('opacity-100');
      toast.setAttribute('aria-hidden', 'true');
    }

    closeBtn && closeBtn.addEventListener('click', function () {
      if (hideTimeout) clearTimeout(hideTimeout);
      hideToast();
    });

    window.addEventListener('login-failed', function (e) {
      const message = (e && e.detail && e.detail.message) ? e.detail.message : 'Login failed';
      showToast(message);
    });
  })();
</script>
</div>
