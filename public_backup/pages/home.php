<!-- Benefits Section -->
    <section class="section">
        <div class="section-header">
            <h2>Why Choose My Restore Pro?</h2>
            <p>Professional, reliable, and environmentally responsible junk removal services</p>
        </div>
        
        <div class="benifits-grid">
            <?php foreach ($benefits as $benefit): ?>
            <div class="benifits-item">
                <img src="<?= htmlspecialchars($benefit['image']) ?>" alt="<?= htmlspecialchars($benefit['title']) ?>">
                <h3><?= htmlspecialchars($benefit['title']) ?></h3>
                <p><?= htmlspecialchars($benefit['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- How we can help Section -->
    <section id="services" class="section">
        <div class="section-header">
            <h2>
                What can we help you with?
            </h2>
            <p>
                Choose the service that fits your needs - we handle everything from residential cleanouts to commercial projects.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-6 items-center mb-8 px-6">

            <div class="service-card hover-lift">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-20 flex justify-center">
                        <i class="icon-[mdi--truck] text-6xl"></i>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3>Junk Removal / Hualing</h3>
                        <ul>
                            <li>• Home cleanouts (attic to basement)</li>
                            <li>• Garage cleanouts</li>
                            <li>• Estate sale cleanouts</li>
                            <li>• Senior home cleanouts</li>
                            <li>• Storage unit cleanouts</li>
                        </ul>
                        <a
                            class="btn-primary"
                            hx-get="/partials/junk-removal" 
                            hx-target="#tab-content" 
                            hx-swap="innerHTML"
                            onclick="setActiveTab(this)">
                            Read More
                        </a>
                    </div>
                </div>
            </div>

            <div class="service-card hover-lift">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-20 flex justify-center">
                        <i class="icon-[mdi--home] text-6xl"></i>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3>Surface Coatings</h3>
                        <ul>
                            <li>• Home cleanouts (attic to basement)</li>
                            <li>• Garage cleanouts</li>
                            <li>• Estate sale cleanouts</li>
                            <li>• Senior home cleanouts</li>
                            <li>• Storage unit cleanouts</li>
                        </ul>
                        <a
                            class="btn-primary"
                            hx-get="/partials/surface-coatings" 
                            hx-target="#tab-content" 
                            hx-swap="innerHTML"
                            onclick="setActiveTab(this)">
                            Read More
                        </a>
                    </div>
                </div>
            </div>

            <div class="service-card hover-lift">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-20 flex justify-center">
                        <i class="icon-[mdi--home] text-6xl"></i>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3>LandScaping</h3>
                        <ul>
                            <li>• Home cleanouts (attic to basement)</li>
                            <li>• Garage cleanouts</li>
                            <li>• Estate sale cleanouts</li>
                            <li>• Senior home cleanouts</li>
                            <li>• Storage unit cleanouts</li>
                        </ul>
                        <a
                            class="btn-primary"
                            hx-get="/partials/landscaping" 
                            hx-target="#tab-content" 
                            hx-swap="innerHTML"
                            onclick="setActiveTab(this)">
                            Read More
                        </a>
                    </div>
                </div>
            </div>

            <div class="service-card hover-lift">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0 w-20 flex justify-center">
                        <i class="icon-[mdi--home] text-6xl"></i>
                    </div>
                    <div class="flex-1 pt-2">
                        <h3>Exterior Cleaning</h3>
                        <ul>
                            <li>• Home cleanouts (attic to basement)</li>
                            <li>• Garage cleanouts</li>
                            <li>• Estate sale cleanouts</li>
                            <li>• Senior home cleanouts</li>
                            <li>• Storage unit cleanouts</li>
                        </ul>
                        <a
                            class="btn-primary"
                            hx-get="/partials/cleaning" 
                            hx-target="#tab-content" 
                            hx-swap="innerHTML"
                            onclick="setActiveTab(this)">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div id="tab-content" class=" p-8">
            <!-- Default content (Home pickup) -->
           
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta">
        <div class="text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Ready to Clear Your Space?</h2>
            <p class="text-xl mb-8">Get your free estimate today and let us handle the heavy lifting</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="tel:+12394121566" class="btn-primary text-lg px-8 py-4">
                    <i class="icon-[mdi--phone] mr-2"></i>
                    Call Now: (239) 412-1566
                </a>
                <a href="/quote" class="btn-secondary text-lg px-8 py-4" hx-get="/quote" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                    Get Free Quote
                </a>
            </div>
        </div>
    </section>