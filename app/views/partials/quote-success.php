<div class="text-center p-8 bg-green-50 rounded-lg border border-green-100">
    <div class="mb-4">
        <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-4">Quote Request Received!</h3>
    <p class="text-gray-600 mb-3">
        Thank you for requesting a quote. Our team will review your requirements and get back to you within 24 hours.
    </p>
    <p class="text-gray-500 mb-6">
        Quote Reference: #<span class="font-mono"><?= str_pad($quote_id, 6, '0', STR_PAD_LEFT) ?></span>
    </p>
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 mx-auto max-w-md">
        <h4 class="font-semibold text-gray-900 mb-2">What happens next?</h4>
        <ul class="text-left text-gray-600 space-y-2">
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                We'll review your request details
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                Prepare a detailed price estimate
            </li>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                </svg>
                Contact you to discuss the quote
            </li>
        </ul>
    </div>
    <div class="flex justify-center space-x-4">
        <a href="/" class="text-yellow-600 hover:text-yellow-700 font-medium">
            ← Back to Home
        </a>
        <a href="/services" class="text-yellow-600 hover:text-yellow-700 font-medium">
            View Our Services →
        </a>
    </div>
</div>