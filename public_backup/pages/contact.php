<section class="section">
    <div class="site-main">
        <div class="section-header">
            <h1>Contact Us</h1>
            <p>Get in touch for your free junk removal estimate</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Send us a message</h2>
                <form hx-post="/contact" hx-target="#form-response" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1">Name *</label>
                        <input type="text" id="name" name="name" required class="form-input">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" id="email" name="email" required class="form-input">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-1">Phone *</label>
                        <input type="tel" id="phone" name="phone" required class="form-input">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium mb-1">Message *</label>
                        <textarea id="message" name="message" rows="4" required class="form-textarea"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">Send Message</button>
                </form>
                <div id="form-response" class="mt-4"></div>
            </div>

            <!-- Contact Info -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Get in touch</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="icon-[mdi--phone] text-yellow-500 mt-1"></i>
                        <div>
                            <h3 class="font-medium">Phone</h3>
                            <a href="tel:<?= htmlspecialchars($contact_info['phone']) ?>" class="text-gray-600 hover:text-yellow-600">
                                <?= htmlspecialchars($contact_info['phone']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="icon-[mdi--email] text-yellow-500 mt-1"></i>
                        <div>
                            <h3 class="font-medium">Email</h3>
                            <a href="mailto:<?= htmlspecialchars($contact_info['email']) ?>" class="text-gray-600 hover:text-yellow-600">
                                <?= htmlspecialchars($contact_info['email']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="icon-[mdi--clock] text-yellow-500 mt-1"></i>
                        <div>
                            <h3 class="font-medium">Hours</h3>
                            <div class="text-gray-600">
                                <div><?= htmlspecialchars($contact_info['hours']['weekdays']) ?></div>
                                <div><?= htmlspecialchars($contact_info['hours']['saturday']) ?></div>
                                <div><?= htmlspecialchars($contact_info['hours']['sunday']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="icon-[mdi--map-marker] text-yellow-500 mt-1"></i>
                        <div>
                            <h3 class="font-medium">Service Area</h3>
                            <p class="text-gray-600"><?= htmlspecialchars($contact_info['location']) ?> and surrounding areas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>