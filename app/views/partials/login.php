<div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div id="login-form" class="bg-white rounded-lg shadow-xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Restore Removal</h1>
                <p class="mt-2 text-gray-600">Admin Access</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                    <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <p class="text-sm"><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login/request" class="space-y-6" 
                  hx-post="/login/request" 
                  hx-target="#login-form" 
                  hx-swap="outerHTML"
                  hx-indicator="#login-spinner">
                <?= $csrf() ?>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address:
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        autocomplete="email" 
                        placeholder="Enter your email address"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 px-4 rounded-md transition-colors duration-200 relative"
                >
                    <span class="htmx-indicator absolute inset-0 flex items-center justify-center" id="login-spinner">
                        <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span class="htmx-indicator:not(.htmx-request) block">Send Login Link</span>
                </button>

                <p class="mt-2 text-sm text-gray-600 text-center">
                    We'll email you a magic link for secure, password-free access.
                </p>
            </form>

            <div class="text-center mt-6">
                <a href="/" class="text-blue-600 hover:text-blue-800 text-sm">
                    ← Back to Website
                </a>
            </div>
        </div>
    </div>
</div>