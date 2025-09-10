<?php
use App\Core\Security;
use App\Core\BotProtection;
?>
<section id="contact" class="section">
    <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1 gap-12 p-6">
        <!-- Contact Form -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Send us a message</h2>
            <div id="contact-form-wrapper">
                <form hx-post="/contact" 
                        hx-target="#contact-form-wrapper" 
                        hx-swap="outerHTML"
                        hx-headers='{"X-CSRF-TOKEN": "<?= htmlspecialchars(Security::getCsrfToken()) ?>"}'
                        class="space-y-4">
                <?= $csrf() ?>
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Name *</label>
                    <input type="text" id="name" name="name" required 
                            maxlength="100"
                            pattern="[A-Za-z\s\-\.']{2,100}"
                            title="Please enter a valid name (2-100 characters, letters, spaces, and basic punctuation only)"
                            class="form-input">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" 
                            id="email" 
                            name="email" 
                            required 
                            maxlength="255"
                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                            title="Please enter a valid email address"
                            class="form-input">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium mb-1">Phone *</label>
                    <input type="tel" 
                            id="phone" 
                            name="phone" 
                            required 
                            pattern="\([0-9]{3}\)\s?[0-9]{3}-[0-9]{4}|[0-9]{3}-[0-9]{3}-[0-9]{4}"
                            title="Please enter a valid phone number (e.g., (239) 412-1566 or 239-412-1566)"
                            class="form-input">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium mb-1">How can we help? *</label>
                    <select id="subject" name="subject" required class="form-input">
                        <option value="">Select a service...</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Free Estimate">Free Estimate</option>
                        <option value="Home Cleanout">Home Cleanout</option>
                        <option value="Commercial Service">Commercial Service</option>
                        <option value="Garage Cleanout">Garage Cleanout</option>
                        <option value="Storage Unit Cleanout">Storage Unit Cleanout</option>
                        <option value="Estate Sale Cleanout">Estate Sale Cleanout</option>
                        <option value="Hot Tub Removal">Hot Tub Removal</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium mb-1">Message *</label>
                    <textarea id="message" 
                                name="message" 
                                rows="4" 
                                required
                                maxlength="2000"
                                class="form-textarea"
                                title="Please enter your message (maximum 2000 characters)"></textarea>
                </div>
                <!-- Bot Protection Fields (Hidden) -->
                <input type="text" name="<?php echo BotProtection::getHoneypotFieldName(); ?>" style="display:none !important;" tabindex="-1" autocomplete="off">
                <input type="hidden" name="form_start_time" value="<?php echo BotProtection::getFormStartTime(); ?>">
                
                <button type="submit" class="btn-primary w-full">Send Message</button>
            </form>
            </div>
        </div>

        <!-- Contact Info -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Get in touch</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="icon-[mdi--phone] text-yellow-500 mt-1"></i>
                    <div>
                        <h3 class="font-medium">Phone</h3>
                        <a href="tel:<?= htmlspecialchars($settings->business->phone) ?>" class="text-gray-600 hover:text-yellow-600">
                            <?= htmlspecialchars($settings->business->phone) ?>
                        </a>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="icon-[mdi--email] text-yellow-500 mt-1"></i>
                    <div>
                        <h3 class="font-medium">Email</h3>
                        <a href="mailto:<?= htmlspecialchars($settings->business->email) ?>" class="text-gray-600 hover:text-yellow-600">
                            <?= htmlspecialchars($settings->business->email) ?>
                        </a>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="icon-[mdi--clock] text-yellow-500 mt-1"></i>
                    <div>
                        <h3 class="font-medium">Hours</h3>
                        <div class="text-gray-600">
                            <div><?= htmlspecialchars($settings->business->hours_weekdays) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
