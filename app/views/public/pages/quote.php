<?php
use App\Core\Security;
use App\Core\BotProtection;
?>
<!-- Quote Request Page -->
<section class="py-8 sm:py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8 lg:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Get Your Free Quote
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">
                No hidden fees, no surprises. Get an upfront, no-obligation estimate for your junk removal needs.
            </p>
        </div>

        <!-- Quote Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
            <div id="quote-form-wrapper">
                <form id="quote-form" 
                      hx-post="/quote" 
                      hx-target="#quote-form-wrapper" 
                      hx-swap="outerHTML"
                      hx-headers='{"X-CSRF-TOKEN": "<?= htmlspecialchars(Security::getCsrfToken()) ?>"}'>
                <?= $csrf() ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            maxlength="100"
                            pattern="[A-Za-z\s\-\.']{2,100}"
                            title="Please enter a valid name (2-100 characters, letters, spaces, and basic punctuation only)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Your full name"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            maxlength="255"
                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Please enter a valid email address"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="your@email.com"
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number *
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            required
                            pattern="\([0-9]{3}\)\s?[0-9]{3}-[0-9]{4}|[0-9]{3}-[0-9]{3}-[0-9]{4}"
                            title="Please enter a valid phone number (e.g., (239) 412-1566 or 239-412-1566)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="(239) 412-1566"
                        >
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Service Type *
                        </label>
                        <select 
                            id="service_type" 
                            name="service_type" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        >
                            <option value="">Select a service</option>
                            <option value="residential">Residential Junk Removal</option>
                            <option value="commercial">Commercial Cleanout</option>
                            <option value="home_cleanout">Home Cleanout</option>
                            <option value="garage_cleanout">Garage Cleanout</option>
                            <option value="storage_unit">Storage Unit Cleanout</option>
                            <option value="basement">Basement Junk Removal</option>
                            <option value="estate_sale">Estate Sale Cleanout</option>
                            <option value="senior_home">Senior Home Cleanout</option>
                            <option value="hot_tub">Hot Tub Removal</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Address *
                    </label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Street address, city, state, zip"
                    >
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description of Items
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        maxlength="2000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Please describe the items you need removed, approximate quantity, and any special considerations..."
                        title="Please describe the items you need removed (maximum 2000 characters)"
                    ></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mt-6">
                    <label for="quote-images" class="block text-sm font-medium text-gray-700 mb-2">Upload Photos (Optional)</label>
                    <div class="relative">
                        <input type="file" id="quote-images" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/webp" 
                            class="hidden" onchange="validateAndDisplayFiles(this, 'quote')" max="5">
                        <label for="quote-images" 
                            class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-colors">
                            <div class="text-center">
                                <i class="icon-[mdi--camera-plus] text-2xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click to upload photos of items</p>
                                <p class="text-xs text-gray-500">Max 5 photos, 5MB each (JPG, PNG, WebP)</p>
                            </div>
                        </label>
                        <div id="quote-file-display" class="mt-2 text-xs"></div>
                        <div id="quote-file-error" class="mt-2 text-xs text-red-600 hidden"></div>
                    </div>
                </div>

                <!-- Preferred Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Date (Optional)
                        </label>
                        <input 
                            type="date" 
                            id="preferred_date" 
                            name="preferred_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        >
                    </div>

                    <div>
                        <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Time (Optional)
                        </label>
                        <select 
                            id="preferred_time" 
                            name="preferred_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        >
                            <option value="">Any time</option>
                            <option value="morning">Morning (8AM - 12PM)</option>
                            <option value="afternoon">Afternoon (12PM - 5PM)</option>
                            <option value="evening">Evening (5PM - 7PM)</option>
                        </select>
                    </div>
                </div>

                <!-- Bot Protection Fields (Hidden) -->
                <input type="text" name="<?php echo BotProtection::getHoneypotFieldName(); ?>" style="display:none !important;" tabindex="-1" autocomplete="off">
                <input type="hidden" name="form_start_time" value="<?php echo BotProtection::getFormStartTime(); ?>">

                <!-- Submit Button -->
                <div class="mt-8">
                    <button 
                        type="submit" 
                        class="w-full btn-primary text-lg py-4"
                    >
                        Get My Free Quote
                    </button>
                </div>

            </form>
        </div>
    </div>

        <!-- Additional Info -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Fast Response</h3>
                <p class="text-gray-600">We'll contact you within 24 hours with your free estimate</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Obligation</h3>
                <p class="text-gray-600">Free estimates with no hidden fees or surprises</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Call Anytime</h3>
                <p class="text-gray-600">Prefer to talk? Call us at <a href="tel:2394121566" class="text-yellow-600 hover:underline">(239) 412-1566</a></p>
            </div>
        </div>
    </div>
</section>